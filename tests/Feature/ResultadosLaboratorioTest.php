<?php

namespace Tests\Feature;

use App\Models\ResultadoLaboratorioImportado;
use App\Models\User;
use App\Services\ImportarResultadosLaboratorio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ResultadosLaboratorioTest extends TestCase
{
    use RefreshDatabase;

    public function test_las_rutas_del_modulo_requieren_autenticacion(): void
    {
        $this->get('/resultados-laboratorio')->assertRedirect('/login');
        $this->get('/resultados-laboratorio/importar')->assertRedirect('/login');
    }

    public function test_un_usuario_autenticado_puede_ver_el_listado_sin_consultar_pacientes(): void
    {
        $resultado = ResultadoLaboratorioImportado::create($this->datos());

        $this->actingAs(User::factory()->create())
            ->get('/resultados-laboratorio?dni=00123456')
            ->assertOk()
            ->assertSee('00123456')
            ->assertSee($resultado->nombres_apellidos);
    }

    public function test_la_clave_unica_impide_duplicar_fecha_procedencia_y_dni(): void
    {
        ResultadoLaboratorioImportado::create($this->datos());

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        ResultadoLaboratorioImportado::create(array_merge($this->datos(), ['lote_uuid' => '22222222-2222-4222-8222-222222222222']));
    }

    public function test_la_vista_pdf_contiene_el_nombre_completo_de_rpr_y_no_agrega_hoja_final(): void
    {
        $resultado = ResultadoLaboratorioImportado::create($this->datos());
        $analisis = collect(config('laboratorios.analisis'))->sortBy('orden')->groupBy('area');

        $html = view('resultados-laboratorio.pdf', ['resultados' => collect([$resultado]), 'analisis' => $analisis])->render();

        $this->assertStringContainsString('Prueba de Sífilis – Anticuerpo No Treponémico (RPR), cualitativo', $html);
        $this->assertStringContainsString('.resultado-paciente:last-child { page-break-after: auto; }', $html);
        $this->assertStringContainsString('&gt; 2000', $html);
    }

    public function test_la_importacion_explica_como_instalar_el_lector_si_no_esta_disponible(): void
    {
        if (class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            $this->markTestSkipped('PhpSpreadsheet está instalado en este entorno.');
        }

        try {
            app(ImportarResultadosLaboratorio::class)->importar(
                UploadedFile::fake()->create('resultados.xls', 1),
                '2026-08-27',
                'HEDIAL'
            );
            $this->fail('La importación debía informar que falta PhpSpreadsheet.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString(
                'composer require phpoffice/phpspreadsheet',
                $exception->errors()['archivo'][0]
            );
        }
    }

    private function datos(): array
    {
        $datos = [
            'lote_uuid' => '11111111-1111-4111-8111-111111111111',
            'archivo_nombre' => 'laboratorio.xls', 'fecha_resultado' => '2026-08-27',
            'procedencia' => 'HEDIAL', 'dni' => '00123456', 'nombres_apellidos' => 'PACIENTE PRUEBA',
        ];
        foreach (array_filter(array_column(config('laboratorios.analisis'), 'campo')) as $campo) {
            $datos[$campo] = $campo === 'upre' ? '> 2000' : 'NO REACTIVO';
        }

        return $datos;
    }
}

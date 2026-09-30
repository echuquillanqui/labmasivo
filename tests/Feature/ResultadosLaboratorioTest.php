<?php

namespace Tests\Feature;

use App\Models\ResultadoLaboratorioImportado;
use App\Models\User;
use App\Services\ImportarResultadosLaboratorio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $this->assertStringContainsString('@page { size: A4 portrait; margin: 12mm 10mm 10mm; }', $html);
        $this->assertStringContainsString('.resultado-paciente { width: 100%; page-break-after: always; page-break-inside: avoid; }', $html);
        $this->assertStringContainsString('font-size: 6.2pt;', $html);
        $this->assertStringNotContainsString('Firma y sello', $html);
        $this->assertStringNotContainsString('position: absolute', $html);
        $this->assertStringContainsString('VARONES: 42.0 - 54.0 / MUJERES: 37.0 - 48.0', $html);
        $this->assertStringContainsString('Menor 0.90: negativo / Mayor 1: positivo / 0.90 - 0.99: indeterminado', $html);
        $this->assertNull($resultado->selloDigitalDataUri());
        $this->assertStringNotContainsString('alt="Sello digital"', $html);
    }

    public function test_la_impresion_en_bloque_conserva_una_pagina_con_margenes_por_resultado(): void
    {
        $primero = ResultadoLaboratorioImportado::create($this->datos());
        $segundo = ResultadoLaboratorioImportado::create(array_merge($this->datos(), [
            'dni' => '00999999',
            'nombres_apellidos' => 'SEGUNDO PACIENTE',
        ]));

        $html = view('resultados-laboratorio.pdf', [
            'resultados' => collect([$primero, $segundo]),
            'analisis' => collect(config('laboratorios.analisis'))->sortBy('orden')->groupBy('area'),
        ])->render();

        $this->assertStringContainsString('@page { size: A4 portrait; margin: 12mm 10mm 10mm; }', $html);
        $this->assertStringContainsString('page-break-inside: avoid;', $html);
        $this->assertSame(2, substr_count($html, '<div class="resultado-paciente">'));
    }

    public function test_la_vista_pdf_solo_muestra_los_examenes_que_tienen_resultado(): void
    {
        $resultado = ResultadoLaboratorioImportado::create(array_merge($this->datos(), [
            'hto' => null,
            'hb' => '   ',
            'upre' => '0',
        ]));

        $html = view('resultados-laboratorio.pdf', [
            'resultados' => collect([$resultado]),
            'analisis' => collect(config('laboratorios.analisis'))->sortBy('orden')->groupBy('area'),
        ])->render();

        $this->assertStringNotContainsString('HEMATOCRITO', $html);
        $this->assertStringNotContainsString('HEMOGLOBINA', $html);
        $this->assertStringContainsString('UREA PRE', $html);
        $this->assertMatchesRegularExpression('/UREA PRE<\/td><td class="valor">0<\/td>/', $html);
    }

    public function test_la_vista_pdf_incrusta_el_sello_digital_del_resultado(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('sellos-digitales/sello.png', 'imagen-de-prueba');
        $resultado = ResultadoLaboratorioImportado::create(array_merge($this->datos(), [
            'sello_digital' => 'sellos-digitales/sello.png',
        ]));

        $html = view('resultados-laboratorio.pdf', [
            'resultados' => collect([$resultado]),
            'analisis' => collect(config('laboratorios.analisis'))->sortBy('orden')->groupBy('area'),
        ])->render();

        $this->assertStringContainsString('data:', $html);
        $this->assertStringContainsString(base64_encode('imagen-de-prueba'), $html);
        $this->assertStringContainsString('alt="Sello digital"', $html);
        $this->assertStringContainsString('.sello { margin-top: 1mm;', $html);
        $this->assertStringNotContainsString('Firma y sello', $html);
    }

    public function test_la_vista_pdf_usa_el_sello_del_usuario_si_el_resultado_no_tiene_uno(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('sellos-usuarios/sello.png', 'sello-del-usuario');
        $usuario = User::factory()->create(['sello_digital' => 'sellos-usuarios/sello.png']);
        $resultado = ResultadoLaboratorioImportado::create($this->datos());

        $html = view('resultados-laboratorio.pdf', [
            'resultados' => collect([$resultado]),
            'analisis' => collect(config('laboratorios.analisis'))->sortBy('orden')->groupBy('area'),
            'selloUsuario' => $usuario->selloDigitalDataUri(),
        ])->render();

        $this->assertStringContainsString(base64_encode('sello-del-usuario'), $html);
        $this->assertStringContainsString('alt="Sello digital"', $html);
    }

    public function test_el_formulario_de_importacion_acepta_un_sello_digital(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/resultados-laboratorio/importar')
            ->assertOk()
            ->assertSee('Sello digital')
            ->assertSee('Opcional. Se admite PNG, JPG o WEBP, máximo 2 MB.')
            ->assertSee('image/png,image/jpeg,image/webp', false);
    }

    public function test_se_puede_subir_un_sello_para_todos_los_resultados_de_un_lote(): void
    {
        Storage::fake('public');
        $primero = ResultadoLaboratorioImportado::create($this->datos());
        $segundo = ResultadoLaboratorioImportado::create(array_merge($this->datos(), [
            'dni' => '00999999',
        ]));
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');

        $this->actingAs(User::factory()->create())->post(
            route('resultados-laboratorio.lote.sello', $primero->lote_uuid),
            ['sello_digital' => UploadedFile::fake()->createWithContent('sello.png', $png)]
        )->assertRedirect()->assertSessionHas('success');

        $this->assertNotNull($primero->fresh()->sello_digital);
        $this->assertSame($primero->fresh()->sello_digital, $segundo->fresh()->sello_digital);
        Storage::disk('public')->assertExists($primero->fresh()->sello_digital);
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

<?php

namespace App\Services;

use App\Models\ResultadoLaboratorioImportado;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportarResultadosLaboratorio
{
    public const HOJA = 'PARA LABORATORIO';

    public const MAPEO = [
        'DNI' => 'dni',
        'Nombres y apellidos' => 'nombres_apellidos',
        'HTO' => 'hto', 'HB' => 'hb', 'UPRE' => 'upre', 'UPOST' => 'upost',
        'Cl' => 'cloro', 'Na' => 'sodio', 'K' => 'potasio',
        'FosforoSerico' => 'fosforo_serico', 'CalcioSerico' => 'calcio_serico',
        'TGO' => 'tgo', 'TGP' => 'tgp', 'AlbuminaSerica' => 'albumina_serica',
        'FOSFATASA' => 'fosfatasa', 'HIERROSERICO' => 'hierro_serico',
        'FERRITINA' => 'ferritina', 'TRANSFERRINA' => 'transferrina', 'PTH' => 'pth',
        'ANTICUERPOS PARA VIH 1 Y 2' => 'vih_1_2', 'RPR' => 'rpr', 'HBSAG' => 'hbsag',
        'ANTICUERPOS CONTRA EL ANTIGENO DE SUPERFICIE HEP. B (Anti- HBsAb)' => 'anti_hbs',
        'ANTICUERPOS CONTRA EL ANTIGENO DE LA NUCLEOCAPSIDE DE HEP. B (Anti - HBc) TOTAL' => 'anti_hbc_total',
        'HVC' => 'hcv', 'ANTICUERPOS HTLV 1 Y 2' => 'htlv_1_2',
    ];

    public function importar(UploadedFile $archivo, string $fechaResultado, string $procedencia, UploadedFile|string|null $selloDigital = null): array
    {
        if (! class_exists(IOFactory::class)) {
            throw ValidationException::withMessages([
                'archivo' => 'No está instalada la librería para leer Excel. En la carpeta del proyecto ejecute: composer require phpoffice/phpspreadsheet:^2.1 --with-all-dependencies',
            ]);
        }

        $libro = IOFactory::load($archivo->getRealPath());
        if (! in_array(self::HOJA, $libro->getSheetNames(), true)) {
            throw ValidationException::withMessages(['archivo' => 'El archivo no contiene la hoja «'.self::HOJA.'».']);
        }

        $hoja = $libro->getSheetByName(self::HOJA);
        [$columnas, $faltantes] = $this->leerEncabezados($hoja);
        if ($faltantes !== []) {
            throw ValidationException::withMessages(['archivo' => 'Faltan encabezados obligatorios: '.implode(', ', $faltantes).'.']);
        }

        $filas = $this->leerFilas($hoja, $columnas);
        $errores = [];
        foreach ($filas as $numero => $fila) {
            if (blank($fila['dni'])) {
                $errores[] = "Fila {$numero}: el DNI está vacío.";
            }
            if (blank($fila['nombres_apellidos'])) {
                $errores[] = "Fila {$numero}: los nombres y apellidos están vacíos.";
            }
        }
        if ($errores !== []) {
            throw ValidationException::withMessages(['archivo' => $errores]);
        }

        $loteUuid = (string) Str::uuid();
        $nombreArchivo = $archivo->getClientOriginalName();
        $rutaSello = $selloDigital instanceof UploadedFile
            ? $selloDigital->store('sellos-digitales', 'public')
            : $this->copiarSelloDelUsuario($selloDigital);
        $creadas = 0;
        $actualizadas = 0;

        DB::transaction(function () use ($filas, $fechaResultado, $procedencia, $loteUuid, $nombreArchivo, $rutaSello, &$creadas, &$actualizadas) {
            foreach ($filas as $fila) {
                $registro = ResultadoLaboratorioImportado::firstOrNew([
                    'fecha_resultado' => $fechaResultado,
                    'procedencia' => $procedencia,
                    'dni' => $fila['dni'],
                ]);
                $datosRegistro = array_merge($fila, [
                    'lote_uuid' => $loteUuid,
                    'archivo_nombre' => $nombreArchivo,
                ]);
                if ($rutaSello !== null) {
                    $datosRegistro['sello_digital'] = $rutaSello;
                }
                $registro->fill($datosRegistro);
                $registro->exists ? $actualizadas++ : $creadas++;
                $registro->save();
            }
        });

        return [
            'archivo_nombre' => $nombreArchivo, 'lote_uuid' => $loteUuid,
            'fecha_resultado' => $fechaResultado, 'procedencia' => $procedencia,
            'filas_leidas' => count($filas), 'creadas' => $creadas,
            'actualizadas' => $actualizadas, 'errores' => 0, 'mensajes_error' => [],
        ];
    }

    private function copiarSelloDelUsuario(?string $rutaOriginal): ?string
    {
        if (! $rutaOriginal || ! Storage::disk('public')->exists($rutaOriginal)) {
            return null;
        }

        $extension = pathinfo($rutaOriginal, PATHINFO_EXTENSION);
        $rutaCopia = 'sellos-digitales/'.Str::uuid().($extension ? '.'.$extension : '');
        Storage::disk('public')->copy($rutaOriginal, $rutaCopia);

        return $rutaCopia;
    }

    private function leerEncabezados(Worksheet $hoja): array
    {
        $columnas = [];
        for ($columna = 1; $columna <= 26; $columna++) {
            $encabezado = trim((string) $hoja->getCell([$columna, 1])->getFormattedValue());
            if ($encabezado !== '') {
                $columnas[$encabezado] = $columna;
            }
        }

        return [$columnas, array_values(array_diff(array_keys(self::MAPEO), array_keys($columnas)))];
    }

    private function leerFilas(Worksheet $hoja, array $columnas): array
    {
        $filas = [];
        for ($numero = 2; $numero <= $hoja->getHighestDataRow(); $numero++) {
            $fila = [];
            $tieneContenido = false;
            foreach (self::MAPEO as $encabezado => $campo) {
                $valor = (string) $hoja->getCell([$columnas[$encabezado], $numero])->getFormattedValue();
                $fila[$campo] = $valor === '' ? null : $valor;
                $tieneContenido = $tieneContenido || $valor !== '';
            }
            if ($tieneContenido) {
                $fila['dni'] = trim((string) $fila['dni']);
                $fila['nombres_apellidos'] = trim((string) $fila['nombres_apellidos']);
                $filas[$numero] = $fila;
            }
        }

        return $filas;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ResultadoLaboratorioImportado;
use App\Services\ImportarResultadosLaboratorio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class ResultadoLaboratorioImportadoController extends Controller
{
    public function index(Request $request)
    {
        $resultados = ResultadoLaboratorioImportado::query()
            ->when($request->lote_uuid, fn ($q, $v) => $q->where('lote_uuid', $v))
            ->when($request->fecha, fn ($q, $v) => $q->whereDate('fecha_resultado', $v))
            ->when($request->procedencia, fn ($q, $v) => $q->where('procedencia', 'like', "%{$v}%"))
            ->when($request->dni, fn ($q, $v) => $q->where('dni', 'like', "%{$v}%"))
            ->when($request->nombres, fn ($q, $v) => $q->where('nombres_apellidos', 'like', "%{$v}%"))
            ->orderBy('nombres_apellidos')->paginate(150)->withQueryString();

        return view('resultados-laboratorio.index', compact('resultados'));
    }

    public function crearImportacion()
    {
        return view('resultados-laboratorio.importar');
    }

    public function importar(Request $request, ImportarResultadosLaboratorio $importador)
    {
        $datos = $request->validate([
            'archivo' => ['required', 'file', 'mimes:xls,xlsx', 'max:20480'],
            'fecha_resultado' => ['required', 'date'],
            'procedencia' => ['required', 'string', 'max:150'],
        ]);
        $resumen = $importador->importar($request->file('archivo'), $datos['fecha_resultado'], trim($datos['procedencia']));

        return redirect()->route('resultados-laboratorio.index', ['lote_uuid' => $resumen['lote_uuid']])
            ->with('resumen_importacion', $resumen)->with('success', 'La importación terminó correctamente.');
    }

    public function show(ResultadoLaboratorioImportado $resultado)
    {
        return view('resultados-laboratorio.show', compact('resultado'));
    }

    public function pdf(ResultadoLaboratorioImportado $resultado)
    {
        return $this->crearPdf(collect([$resultado]), 'resultado-'.$resultado->dni.'.pdf');
    }

    public function pdfSeleccionados(Request $request)
    {
        $datos = $request->validate(['resultados' => ['required', 'array', 'min:1'], 'resultados.*' => ['integer']]);
        $resultados = ResultadoLaboratorioImportado::whereIn('id', $datos['resultados'])->orderBy('nombres_apellidos')->get();
        abort_if($resultados->isEmpty(), 404);

        return $this->crearPdf($resultados, 'resultados-seleccionados.pdf');
    }

    public function pdfLote(string $loteUuid)
    {
        $resultados = ResultadoLaboratorioImportado::where('lote_uuid', $loteUuid)->orderBy('nombres_apellidos')->get();
        abort_if($resultados->isEmpty(), 404);

        return $this->crearPdf($resultados, 'resultados-lote-'.$loteUuid.'.pdf');
    }

    private function crearPdf($resultados, string $nombre)
    {
        if (! class_exists(Pdf::class)) {
            throw new ServiceUnavailableHttpException(null, 'No está instalada la librería PDF. Ejecute: composer require barryvdh/laravel-dompdf:^2.2 --with-all-dependencies');
        }

        return Pdf::loadView('resultados-laboratorio.pdf', [
            'resultados' => $resultados,
            'analisis' => collect(config('laboratorios.analisis'))->sortBy('orden')->groupBy('area'),
        ])->setPaper('a4', 'portrait')->stream($nombre);
    }
}

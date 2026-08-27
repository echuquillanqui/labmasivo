<?php

use App\Http\Controllers\ResultadoLaboratorioImportadoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->resource('usuarios', UserController::class)->except('show');

Route::middleware('auth')->prefix('resultados-laboratorio')->name('resultados-laboratorio.')->group(function () {
    Route::get('/', [ResultadoLaboratorioImportadoController::class, 'index'])->name('index');
    Route::get('/importar', [ResultadoLaboratorioImportadoController::class, 'crearImportacion'])->name('importar.create');
    Route::post('/importar', [ResultadoLaboratorioImportadoController::class, 'importar'])->name('importar.store');
    Route::post('/pdf-seleccionados', [ResultadoLaboratorioImportadoController::class, 'pdfSeleccionados'])->name('pdf-seleccionados');
    Route::post('/lote/{loteUuid}/sello', [ResultadoLaboratorioImportadoController::class, 'actualizarSelloLote'])->name('lote.sello');
    Route::get('/lote/{loteUuid}/pdf', [ResultadoLaboratorioImportadoController::class, 'pdfLote'])->name('lote.pdf');
    Route::get('/{resultado}/pdf', [ResultadoLaboratorioImportadoController::class, 'pdf'])->name('pdf');
    Route::get('/{resultado}', [ResultadoLaboratorioImportadoController::class, 'show'])->name('show');
});

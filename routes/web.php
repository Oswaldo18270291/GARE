<?php

use App\Http\Controllers\InformePdf;
use App\Http\Controllers\Portada as ControllersPortada;
use App\Livewire\Admin\Estructura;
use App\Livewire\Admin\TodosReportes;
use App\Livewire\CReports\Index as CReportsIndex;
use App\Livewire\History\Index as HistoryIndex;
use App\Livewire\Reports\Index;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Livewire\Plantillas\portada;
use App\Livewire\Reports\Addcontenido;
use App\Livewire\Reports\Editestructura;
use App\Livewire\Reports\BotonesAdd\Addc;
use App\Livewire\Reports\BotonesAdd\Editc;
use App\Livewire\Admin\Users;
use App\Http\Controllers\Dashboard;
use App\Livewire\Reports\BotonesAdd\AddcExtends;
use App\Livewire\Reports\BotonesAdd\EditExtends;

/*Route::get('/', function () {
    return view('welcome');
})->name('home');*/

Route::get('dashboard', [Dashboard::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

/*Route::get('/portada_pdf', function () {
    $pdf = Pdf::loadView('plantillas.portada_pdf');
    return $pdf->stream();
});*/

Route::get('c_report', CReportsIndex::class)->name('creacion_reports.index')
->middleware('permission:crear informes');
Route::get('my_reports', Index::class)->name('my_reports.index')
->middleware('permission:mis informes');
Route::get('history', HistoryIndex::class)->name('history.index')
->middleware('permission:historico de informes');
Route::get('my_reports/addcontent/{id}', Addcontenido::class)->name('my_reports.addcontenido')
->middleware('permission:agregar contenido');
Route::get('my_reports/addcontent/addc/{id}/{boton}/{rp}', Addc::class)->name('my_reports.addcontenido.Addc')
->middleware('permission:agregar contenido');
Route::get('my_reports/addcontent/editc/{id}/{boton}/{rp}', Editc::class)->name('my_reports.addcontenido.Editc')
->middleware('permission:editar contenido');

Route::get('my_reports/addcontent/addc_extends/{id}/{boton}/{rp}', AddcExtends::class)->name('my_reports.addcontenido.Addc_extends')
->middleware('permission:agregar contenido');
Route::get('my_reports/addcontent/editc_extends/{id}/{boton}/{rp}', EditExtends::class)->name('my_reports.addcontenido.Edit_extends')
->middleware('permission:editar contenido');


Route::get('my_reports/editstructure/{id}', Editestructura::class)->name('my_reports.editestructura')
->middleware('permission:editar informes');
//Route::get('/portada', portada::class)->name('plantillas.portada');


//Route::get('/reporte/generar-grafica/{id}', [InformePdf::class, 'generarGrafica'])->name('reporte.generarGrafica');
//Route::post('/reporte/guardar-imagen/{id}', [InformePdf::class, 'guardarImagenGrafica'])->name('guardar.imagen.grafica');

// genera AMBAS gráficas (32 y 16) en una sola vista
Route::get('/reporte/generar-graficas/{id}', [InformePdf::class, 'generarGraficas'])
    ->name('reporte.generarGraficas');

// guardar imagen (reutiliza tu actual, solo agregamos subtitleId en el body)
Route::post('/reporte/guardar-grafica/{id}', [InformePdf::class, 'guardarImagenGrafica'])
    ->name('guardar.imagen.grafica');


//Route::get('/reporte/pdf/{id}', [InformePdf::class, 'generar'])->name('reporte.pdf');
Route::get('reportes/{id}/generar-mapa', [InformePdf::class, 'generarMapa'])->name('reporte.generarMapa');
Route::post('reportes/{id}/guardar-mapa', [InformePdf::class, 'guardarImagenMapa'])->name('reporte.guardarMapa');


Route::get('/portada', [ControllersPortada::class, 'index']);
Route::get('/report/pdf/{id}', [InformePdf::class, 'generar'])
    ->name('reporte.pdf')
    ->middleware('permission:ver informe pdf');



Route::get('/admin/editstructure', Estructura::class)->name('admin.editarestrutura')
->middleware('permission:editar estructura');

Route::get('/admin/allreports', TodosReportes::class)->name('admin.todosreportes')
->middleware('permission:ver todos los informes');
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', Users::class)->name('admin.users');
});

Route::get('/reportes/{id}/descargar', [InformePdf::class, 'descargar'])->name('reportes.descargar');

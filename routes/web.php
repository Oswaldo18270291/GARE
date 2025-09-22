<?php

use App\Http\Controllers\Portada as ControllersPortada;
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
use App\Livewire\Reports\BotonesAdd\AddC;
use App\Livewire\Reports\BotonesAdd\Editc;


/*Route::get('/', function () {
    return view('welcome');
})->name('home');*/

Route::view('dashboard', 'dashboard')
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

Route::get('c_report', CReportsIndex::class)->name('creacion_reports.index');
Route::get('my_reports', Index::class)->name('my_reports.index');
Route::get('history', HistoryIndex::class)->name('history.index');
Route::get('my_reports/addcontent/{id}', Addcontenido::class)->name('my_reports.addcontenido');
Route::get('my_reports/addcontent/addc/{id}/{boton}/{rp}', Addc::class)->name('my_reports.addcontenido.Addc');
Route::get('my_reports/addcontent/editc/{id}/{boton}', Editc::class)->name('my_reports.addcontenido.Editc');
Route::get('my_reports/editstructure/{id}', Editestructura::class)->name('my_reports.editestructura');
//Route::get('/portada', portada::class)->name('plantillas.portada');


Route::get('/portada', [ControllersPortada::class, 'index']);
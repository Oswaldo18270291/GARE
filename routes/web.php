<?php

use App\Livewire\CReports\Index as CReportsIndex;
use App\Livewire\History\Index as HistoryIndex;
use App\Livewire\Reports\Index;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Livewire\Plantillas\portada_pdf;


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
Route::get('/portada_pdf', portada_pdf::class)->name('plantillas.portada_pdf');
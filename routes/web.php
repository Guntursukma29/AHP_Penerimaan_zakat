<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\PerangkinganController;
use App\Http\Controllers\PenerimaanZakatController;
use App\Http\Controllers\PerbandinganKriteriaController;
use App\Http\Controllers\PerbandinganPekerjaanController;
use App\Http\Controllers\PerbandinganPenghasilanController;
use App\Http\Controllers\PerbandinganTempatTinggalController;
use App\Http\Controllers\PerbandinganKondisiKesehatanController;
use App\Http\Controllers\PerbandinganTanggunganKeluargaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('penerimaan-zakat', PenerimaanZakatController::class);
    Route::resource('kriteria', KriteriaController::class);
    Route::resource('alternatif', AlternatifController::class);
    Route::resource('subkriteria', SubKriteriaController::class)->except(['store', 'update', 'destroy']);
    Route::post('subkriteria/{kriteria}', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
    Route::put('subkriteria/{kriteria}', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
    Route::delete('subkriteria/{subKriteria}', [SubKriteriaController::class, 'destroy'])->name('subkriteria.destroy');
    Route::resource('perbandingankriteria', PerbandinganKriteriaController::class)->except(['store', 'update', 'destroy']);
    Route::post('/submit-perbandingan', [PerbandinganKriteriaController::class, 'store']);
    Route::get('/lanjut', [PerbandinganKriteriaController::class, 'result'])->name('lanjut');
    Route::get('/perbandingan-kriteria/result', [PerbandinganKriteriaController::class, 'result'])->name('perbandingan-kriteria.result');
    Route::get('/perbandingan-pekerjaan', [PerbandinganPekerjaanController::class, 'index'])->name('perbandinganpekerjaan');
    Route::post('/submit-perbandinganpekerjaan', [PerbandinganPekerjaanController::class, 'store']);
    Route::get('/perbandingan-penghasilan', [PerbandinganPenghasilanController::class, 'index'])->name('perbandinganpenghasilan');
    Route::post('/submit-perbandinganpenghasilan', [PerbandinganPenghasilanController::class, 'store']);
    Route::get('/perbandingan-tempattinggal', [PerbandinganTempatTinggalController::class, 'index'])->name('perbandingantempattinggal');
    Route::post('/submit-perbandingantempattinggal', [PerbandinganTempatTinggalController::class, 'store']);
    Route::get('/perbandingan-tanggungankeluarga', [PerbandinganTanggunganKeluargaController::class, 'index'])->name('perbandingantanggungankeluarga');
    Route::post('/submit-perbandingantanggungankeluarga', [PerbandinganTanggunganKeluargaController::class, 'store']);
    Route::get('/perbandingan-kondisikesehatan', [PerbandinganKondisiKesehatanController::class, 'index'])->name('perbandingankondisikesehatan');
    Route::post('/submit-perbandingankondisikesehatan', [PerbandinganKondisiKesehatanController::class, 'store']);
    Route::get('/hasil-perangkingan', [PerangkinganController::class, 'index'])->name('perangkingan');
    Route::get('/cetak-pdf', [PerangkinganController::class, 'generatePDF'])->name('cetak.pdf');

});

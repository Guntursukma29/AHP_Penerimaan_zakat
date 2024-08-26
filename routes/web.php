<?php

use App\Http\Controllers\AlternatifController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenerimaanZakatController;
use App\Http\Controllers\PerbandinganKriteriaController;
use App\Http\Controllers\SubKriteriaController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('penerimaan-zakat', PenerimaanZakatController::class);
Route::resource('kriteria', KriteriaController::class);
Route::resource('alternatif', AlternatifController::class);
Route::resource('subkriteria', SubKriteriaController::class)->except(['store','update','destroy']);
Route::post('subkriteria/{kriteria}', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
Route::put('subkriteria/{kriteria}', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
Route::delete('subkriteria/{subKriteria}', [SubKriteriaController::class, 'destroy'])->name('subkriteria.destroy');
Route::resource('perbandingankriteria', PerbandinganKriteriaController::class)->except(['store','update','destroy']);
Route::post('/submit-perbandingan', [PerbandinganKriteriaController::class, 'store']);
Route::get('/lanjut', [PerbandinganKriteriaController::class, 'result'])->name('lanjut');
Route::get('/perbandingan-kriteria/result', [PerbandinganKriteriaController::class, 'result'])->name('perbandingan-kriteria.result');


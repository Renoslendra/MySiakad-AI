<?php

use App\Http\Controllers\Mahasiswa\TagihanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan/bayar', [TagihanController::class, 'bayar'])->name('tagihan.bayar');
    Route::get('/tagihan/callback', [TagihanController::class, 'callback'])->name('tagihan.callback');
});

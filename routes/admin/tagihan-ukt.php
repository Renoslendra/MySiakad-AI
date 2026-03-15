<?php

use App\Http\Controllers\Admin\TagihanUktController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin', 'fakultas.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tagihan-ukt', [TagihanUktController::class, 'index'])->name('tagihan-ukt.index');
    Route::get('/tagihan-ukt/create', [TagihanUktController::class, 'create'])->name('tagihan-ukt.create');
    Route::post('/tagihan-ukt', [TagihanUktController::class, 'store'])->name('tagihan-ukt.store');
    Route::delete('/tagihan-ukt/{tagihan}', [TagihanUktController::class, 'destroy'])->name('tagihan-ukt.destroy');
});

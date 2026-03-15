<?php

use App\Http\Controllers\Admin\TagihanUktController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin', 'fakultas.scope'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tagihan-ukt', [TagihanUktController::class, 'index'])->name('tagihan-ukt.index');
});

<?php

use App\Http\Controllers\MayarWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/mayar', [MayarWebhookController::class, 'handle'])
    ->name('webhooks.mayar');

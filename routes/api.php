<?php

use App\Http\Controllers\FallbackController;
use App\Http\Controllers\PaymentMethodController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;


Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
Route::apiResource('transactions',TransactionController::class)
->only(['index','store','show','update']);

Route::fallback(FallbackController::class);
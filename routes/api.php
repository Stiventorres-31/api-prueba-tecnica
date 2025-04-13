<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;

Route::get('/getPaymentsMethods', [TransactionController::class, 'getPaymentsMethods']);
Route::post('/generatePayment', [TransactionController::class, 'generatePayment']);
Route::post('/createPayment', [TransactionController::class, 'createPayment']);
Route::get('/getTransaction', [TransactionController::class, 'getTransaction']);
Route::get('/getTransactions', [TransactionController::class, 'getTransactions']);

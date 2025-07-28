<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::get('/download-pakbon/{filename}', [OrderController::class, 'downloadPakbon'])
    ->name('orders.download-pakbon')
    ->where('filename', '[A-Za-z0-9\-_\.]+');

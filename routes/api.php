<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;

Route::get('/prices', [PriceController::class, 'index']);
Route::post('/create-booking', [BookingController::class, 'create']);
Route::delete('/delete-booking/{id}', [BookingController::class, 'destroy']);
Route::put('/update-booking/{id}', [BookingController::class, 'update']);

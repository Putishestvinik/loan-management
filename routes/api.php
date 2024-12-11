<?php

use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// User authentication (tokens)
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

// Loans CRUD
Route::group(['prefix' => 'loans'], function () {
    Route::get('/', [LoanController::class, 'index']);
    Route::post('/', [LoanController::class, 'store'])->middleware('auth:api');

    Route::get('{loan}', [LoanController::class, 'show']);
    Route::put('{loan}', [LoanController::class, 'update'])->middleware('auth:api');
    Route::delete('{loan}', [LoanController::class, 'destroy'])->middleware('auth:api');
});
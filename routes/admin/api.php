<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;



Route::resource('/products', \App\Http\Controllers\admin\ProductController::class);
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::get('/ttttt', function(){
return response(['sdfsqdf'=> hash::make('12345678')]);     ;
});

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);

Route::get('/affect', [\App\Http\Controllers\CategoryController::class, 'affect']);
Route::get('/product_category/{category}', [\App\Http\Controllers\CategoryController::class, 'product_category']);
Route::resource('/cart', \App\Http\Controllers\CartController::class);
Route::post('/cart/sync ', [\App\Http\Controllers\CartController::class, 'sync_cart']);
Route::resource('/categories', \App\Http\Controllers\CategoryController::class);
Route::get('/products/search', [\App\Http\Controllers\ProductController::class, 'search']);
Route::resource('/products', \App\Http\Controllers\ProductController::class);
Route::get('/brands/getRoles', [\App\Http\Controllers\BrandController::class, 'getRoles']);

Route::get('/test', function () {
    // $response = file_get_contents('https://fakestoreapi.com/products');
    return response()->json(['message' => auth()->user()->roles_slug()]);
});

Route::middleware(['auth:api', 'role:admin,vendor'])
    ->group(function () {

    });

    
Route::middleware('auth:api')->group(function () {
    Route::resource('/home', \App\Http\Controllers\HomeController::class);
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
    // Route::resource('/products', \App\Http\Controllers\CategoryController::class);
    Route::post('/valide_payement', [\App\Http\Controllers\CheckoutController::class, "valide_payement"]);
});

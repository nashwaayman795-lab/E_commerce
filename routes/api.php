<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('products')->group(function(){
    Route::post('add',[ProductController::class,'store']);
    Route::delete('/delete/{id}',[ProductController::class,'destroy']);
    Route::post('/update/{id}',[ProductController::class,'update']);
    Route::post('/cart/add',[CartController::class,'add']);
    Route::get('/cart',[CartController::class,'index']);
});

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::get('/products/search',[ProductController::class,'search']);

Route::get('/products',[ProductController::class,'index']);
Route::get('/products/{id}',[ProductController::class,'show']);

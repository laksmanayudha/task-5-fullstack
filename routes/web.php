<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group([
    'middleware' => 'auth'
], function(){
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/category/create', [CategoryController::class, 'create']);
    Route::post('/category/delete/{category:id}', [CategoryController::class, 'delete']);
    Route::post('/category/update/{category:id}', [CategoryController::class, 'update']);

    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blog/form', [BlogController::class, 'form']);
    Route::post('/blog/create', [BlogController::class, 'create']);
    Route::post('/blog/delete/{post:id}', [BlogController::class, 'delete']);
});

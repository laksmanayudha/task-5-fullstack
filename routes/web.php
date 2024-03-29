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


Route::group([
    'middleware' => 'auth'
], function(){
    // home route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // category route
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/category/create', [CategoryController::class, 'create']);
    Route::post('/category/delete/{category:id}', [CategoryController::class, 'delete']);
    Route::post('/category/update/{category:id}', [CategoryController::class, 'update']);

    // blog route
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blog/form', [BlogController::class, 'form']);
    Route::get('/blog/formUpdate/{post:id}', [BlogController::class, 'formUpdate']);
    Route::post('/blog/create', [BlogController::class, 'create']);
    Route::post('/blog/delete/{post:id}', [BlogController::class, 'delete']);
    Route::post('/blog/update/{post:id}', [BlogController::class, 'update']);
});

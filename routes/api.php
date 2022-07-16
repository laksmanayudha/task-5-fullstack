<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'namespace' => 'App\Http\Controllers\api\v1',
    'prefix' => 'v1'
], function(){

    // auth api
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');

    // posts api
    Route::group([
        'prefix' => 'post',
        'middleware' => ['auth:api']
    ],
    function(){
        Route::get('all', 'PostController@index');
        Route::post('create', 'PostController@store');
        Route::get('detail/{post:id}', 'PostController@show');
        Route::post('delete/{post:id}', 'PostController@destroy');
        Route::post('update/{post:id}', 'PostController@update');
    });
});
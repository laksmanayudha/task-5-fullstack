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
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    Route::get('test', function(){
        return response()->json([
            'message' => 'you are in'
        ]);
    })->middleware('auth:api');
});
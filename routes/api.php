<?php

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

Route::get('/getToken/{email}/{password}', 'Auth\AuthController@getToken');

Route::get('/getListProduct', 'CRUDController@getListProduct');

Route::prefix('{token}', function () {
    //
});

Route::group(['middleware' => ['api_accessible'], 'prefix' => '{token}'], function () {

    Route::get('/test', function () {
        return 1;
    });

});

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

//Example: example.com/api/...

//For all users
Route::get('/getToken/{email?}/{password?}', 'Auth\AuthController@getToken');
Route::get('/getListProduct', 'CRUDController@getListProduct');
Route::get('/getListProductInCategory/{category_id?}', 'CRUDController@getListProductInCategory');
Route::get('/getProduct/{product_id?}', 'CRUDController@getProduct');
Route::get('/getCategories', 'CRUDController@getCategories');

//Only for registered users
Route::group(['middleware' => 'api_accessible', 'prefix' => '{token}'], function () {

    Route::post('/createProduct', 'CRUDController@createProduct');
    Route::post('/deleteProduct', 'CRUDController@deleteProduct');
    Route::post('/addCategory', 'CRUDController@addCategory');
    Route::post('/editCategory', 'CRUDController@editCategory');
    Route::post('/deleteCategory', 'CRUDController@deleteCategory');

});

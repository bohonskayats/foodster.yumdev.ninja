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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'App\Http\Controllers\UserController@register');
Route::post('/login', 'App\Http\Controllers\UserController@login');
Route::get('/user', 'App\Http\Controllers\UserController@getCurrentUser');
Route::post('/update', 'App\Http\Controllers\UserController@update');
Route::get('/logout', 'App\Http\Controllers\UserController@logout');

Route::get('/user_list/', [App\Http\Controllers\UserController::class,'user_list']);
Route::get('/cities_list/', [App\Http\Controllers\CityController::class,'cities_list']);

Route::get('/user_address_list/', [App\Http\Controllers\AddressController::class,'user_address_list']);
Route::get('/user_address_list/{user_id}', [App\Http\Controllers\AddressController::class,'user_address_list']);
Route::get('/user_address_list_by/', [App\Http\Controllers\AddressController::class,'user_address_list_by']);

//user orders
Route::get('/orders/', [App\Http\Controllers\OrderController::class,'orders']);
Route::get('/orders/{order_id}', [App\Http\Controllers\OrderController::class,'order_by_id']);
Route::get('/categories/', [App\Http\Controllers\CategoryController::class,'categories']);
Route::get('/scategories/', [App\Http\Controllers\CategoryController::class,'categories_for_select']);


Route::get('/top_dishes/', [App\Http\Controllers\DishController::class,'top_dishes_list']);
Route::get('/recommended_dishes/', [App\Http\Controllers\DishController::class,'recommended_dishes']);
Route::get('/dishes_by_category/', [App\Http\Controllers\DishController::class,'dishes_by_category']);
Route::get('/dishes/', [App\Http\Controllers\DishController::class,'all_dishes']);
Route::get('/dishes_full/', [App\Http\Controllers\DishController::class,'all_dishes_full']);

Route::get('/dishes/{dish_id}', [App\Http\Controllers\DishController::class,'dish']);



//get type_ie=3 articles where are welcome info
//Route::get('/user_list/', 'App\Http\Controllers\UserController@user_list');

//Route::get('/user_list', function () {})->name('profile');

//$url = route('profile', ['id' => 1, 'photos' => 'yes']);

 

<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('users', UsersController::class);
    $router->resource('api/users', UsersController::class);

    $router->resource('categories', CategoryController::class);
    $router->resource('dishes', DishController::class);
	$router->resource('parameters', ParameterController::class);
	$router->resource('cities', CityController::class);
	$router->resource('addresses', AddressController::class);
	$router->resource('payment-methods', PaymentMethodController::class);
	$router->resource('orders', OrderController::class);
	$router->resource('api', ApiController::class);
	$router->resource('user-login-codes', UserLoginCodeController::class);
	//Route::get('/tmp', 						[App\Admin\Controllers\ApiController::class,'user_address_list_by']);
	Route::get('/api_user_address_list_by', [App\Admin\Controllers\ApiController::class,'user_address_list_by']);
	Route::get('/api_user_list', [App\Admin\Controllers\ApiController::class,'user_list_by']);

	
	//Route::get('/user2/{id}', function ($id) {
	//    return 'User '.$id;
	//});
	
	// $router->resource('dish-orders', DishOrderController::class);

	
});


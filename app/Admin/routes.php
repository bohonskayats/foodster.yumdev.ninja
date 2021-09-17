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

	
});


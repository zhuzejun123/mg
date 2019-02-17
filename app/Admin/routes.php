<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HouseController@index');
    $router->resource('house', HouseController::class);
    $router->get('/house/generator/{id}', 'HouseController@generatorText');

    $router->resource('transfer/{house_id}/intent', IntentController::class);
});

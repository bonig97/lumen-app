<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->get('profiles', 'ProfileController@index');
    $router->post('profiles', 'ProfileController@store');
    $router->get('profiles/{id}', 'ProfileController@show');
    $router->put('profiles/{id}', 'ProfileController@update');
    $router->patch('profiles/{id}', 'ProfileController@updatePartial');
    $router->delete('profiles/{id}', 'ProfileController@destroy');
});

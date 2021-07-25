<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', ['as' => 'register', 'uses' => 'Api\UserController@register']);
    $router->post('login',    ['as' => 'login',    'uses' => 'Api\UserController@login']);

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('logout',   ['as' => 'logout',   'uses' => 'Api\UserController@logout']);
        $router->post('refresh',  ['as' => 'refresh',  'uses' => 'Api\UserController@refresh']);
        $router->get('profile', 'Api\UserController@show');
    });
});

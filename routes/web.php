<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('v1/customers', 'CustomerController@index');
$router->get('v1/customers/{customerId}', 'CustomerController@show');
$router->get('v1/importCustomer', 'CustomerController@importCustomer');
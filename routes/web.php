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

$router->get('employees', 'EmployeesController@index');
$router->get('raffles', 'RafflesController@index');
$router->get('categories', 'EmployeesController@categories');

$router->get('getRandomWinners', 'RaffleWinnersController@getRandomWinners');
$router->get('getCurrentWinners', 'RaffleWinnersController@getCurrentWinners');
$router->get('getFinalWinners', 'RaffleWinnersController@getFinalWinners');
$router->get('getAllWinners', 'RaffleWinnersController@getAllWinners');
$router->get('clearWinners', 'AdminController@clearWinners');

$router->post('setWinners', 'RaffleWinnersController@setWinners');
$router->post('saveEmployees', 'EmployeesController@saveEmployees');
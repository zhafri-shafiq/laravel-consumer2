<?php

// use Illuminate\Http\Request;
use Dingo\Api\Routing\Router;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middlware' => 'api', 'namespace' => 'App\Http\Controllers'], function (Router $api) {

    $api->version('v1', ['prefix' => 'auth'], function (Router $api) {

        $api->post('login', 'AuthController@login');
        $api->get('user', 'AuthController@me')->middleware('api.auth');

    });
});

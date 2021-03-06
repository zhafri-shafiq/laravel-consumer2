<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/passport', function () {
    $query = http_build_query([
        'client_id'     => env('OAUTH_CLIENT_ID'),
        'redirect_uri'  => env('APP_URL') . 'callback',
        'response_type' => 'code',
        'scope'         => 'profile private',
    ]);

    return redirect(env('OAUTH_SERVER') . 'oauth/authorize?' . $query);
})->name('login.passport');

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post(env('OAUTH_SERVER') . 'oauth/token', [
        'form_params' => [
            'grant_type'    => 'authorization_code',
            'client_id'     => env('OAUTH_CLIENT_ID'),
            'client_secret' => env('OAUTH_CLIENT_SECRET'),
            'redirect_uri'  => env('APP_URL') . 'callback',
            'code'          => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

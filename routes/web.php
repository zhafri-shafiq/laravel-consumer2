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
        'client_id'     => '5',
        'redirect_uri'  => 'http://merry.test/callback',
        'response_type' => 'code',
        'scope'         => 'profile private',
    ]);

    return redirect('http://passport.test/oauth/authorize?' . $query);
})->name('login.passport');

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://passport.test/oauth/token', [
        'form_params' => [
            'grant_type'    => 'authorization_code',
            'client_id'     => '5',
            'client_secret' => 'yAsm0A4lkQTamabBBLjAWQoZh05IBCgLHEQRE7Dc',
            'redirect_uri'  => 'http://merry.test/callback',
            'code'          => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

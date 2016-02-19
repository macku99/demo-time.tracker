<?php
use Tymon\JWTAuth\Facades\JWTAuth;

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::resource('users', 'ApiUsersController', [
        'except' => ['create', 'edit'],
    ]);
    Route::resource('users.timesheets', 'ApiUserTimeSheetController', [
        'except' => ['create', 'edit'],
    ]);
});
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', function () {
        return Redirect::to('home');
    });
    Route::get('home', 'PageController@home');
    Route::get('users', 'PageController@users');
    Route::get('users/{users}/timesheets', 'PageController@timesheets');
    Route::get('account', 'PageController@account');

    Route::get('token', function () {
        //$token = JWTAuth::parseToken();
        //return $token->authenticate();
        $loggedInUser = request()->user();
        $token = JWTAuth::fromUser($loggedInUser);
        return $token->refresh();
    });
});
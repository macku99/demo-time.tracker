<?php
use Tymon\JWTAuth\Facades\JWTAuth;

Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::resource('users', 'ApiUsersController', [
        'except' => ['create', 'edit'],
    ]);
    Route::resource('users.timesheets', 'ApiUserTimeSheetController', [
        'except' => ['create', 'edit'],
    ]);
    Route::put('users/preferences/{users}', 'ApiUserPreferencesController@update')
         ->name('api.users.preferences.update');
});
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', function () {
        return Redirect::to('home');
    });
    Route::get('home', 'PageController@home')->name('home');
    Route::get('users', 'PageController@users')->name('users');
    Route::get('users/{users}/timesheets', 'PageController@timesheets')->name('my-timesheets');
    Route::get('users/{users}/timesheets/export/{dateRange?}', 'PageController@exportTimesheets')->name('export-timesheets');

    Route::get('token', function () {
        //$token = JWTAuth::parseToken();
        //return $token->authenticate();
        $loggedInUser = request()->user();
        $token = JWTAuth::fromUser($loggedInUser);

        return $token->refresh();
    });
});
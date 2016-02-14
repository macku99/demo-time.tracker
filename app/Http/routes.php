<?php
Route::group(['prefix' => 'api', 'middleware' => ['api']], function () {
    Route::resource('users', 'ApiUsersController', [
        'except' => ['create', 'edit'],
    ]);
    Route::resource('users.timesheets', 'ApiUserTimeSheetController', [
        'except' => ['create', 'edit'],
    ]);
});
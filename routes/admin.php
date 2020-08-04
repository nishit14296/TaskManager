<?php

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function ()
{

    /*============================================= login ================================================*/
    Route::get('admin', 'LoginController@index')->name('admin.index');

    Route::group(['middleware' => 'auth'], function () {

    });
});
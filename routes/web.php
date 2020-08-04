<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('login.index');
})->name('admin.login');

Route::get('/logout', 'Admin\LoginController@Logout')->name('Logout');
Route::post('/ValidLogin', 'Admin\LoginController@ValidLogin')->name('valid_login');

Auth::routes();

Route::get('login/facebook', 'Admin\LoginController@redirectToProvider')->name('facebook.login');
Route::get('login/facebook/callback', 'Admin\LoginController@handleProviderCallback');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/task_details', 'TaskController@index')->name('task_details');
    Route::post('/task_list', 'TaskController@GetTaskList')->name('task_detail_list');
    Route::get('/add_task', 'TaskController@create')->name('add_task');
    Route::post('/create_task_details', 'TaskController@store')->name('add_task_details');
    Route::get('/edit_task/{id}', 'TaskController@edit')->name('edit_task');
    Route::post('/update_task_details', 'TaskController@update')->name('update_task_details');
    Route::post('/deleteTask', 'TaskController@destroy')->name('deleteTask');
});



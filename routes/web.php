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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', "Admin\LoginController@showLoginForm")->name('admin-login');
    Route::post('/login', "Admin\LoginController@authenticate");
    Route::get('/', 'AdminController@dashboard');
    Route::get('/students/{class}', 'AdminController@getClassStudents');

    Route::group(['prefix' => 'subjects'], function() {
        Route::get('/{subject}/questions', 'AdminController@getAllQuestions');
        Route::post('/{subject}/questions', 'AdminController@addNewQuestion');
        Route::put('/{subject}/questions/{id}', 'AdminController@updateQuestion');
    });

});

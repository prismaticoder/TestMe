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

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/exam/{subject}','StudentController@getExamQuestions')->name('exam');

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/getQuestions','StudentController@getAjaxQuestions')->name('get-questions');
Route::get('/findQuestion/{id}', 'StudentController@findOneQuestion');
// Route::post('/exam/{subject}','StudentController@getAjaxQuestions');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', "Admin\LoginController@showLoginForm")->name('admin-login');
    Route::post('/login', "Admin\LoginController@authenticate");
    Route::get('/logout', "Admin\LoginController@logout")->name('admin-logout');
    Route::get('/', 'AdminController@dashboard')->name('dashboard');
    Route::get('/students/{class}', 'AdminController@getClassStudents')->name('class-students');

    Route::group(['prefix' => 'subjects'], function() {
        Route::get('/{subject}/{class_id}/questions', 'AdminController@getAllQuestions')->name('questions');
        Route::post('/{subject}/{class_id}/questions', 'AdminController@addQuestion');
        Route::post('/{subject}/{class_id}/questions/{id}', 'AdminController@updateQuestion');
    });

});

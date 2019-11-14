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

Route::get('/singleresult', function() {
    return view('admin.main-result');
});

Route::get('/hostexam', function() {
    return view('admin.host-exam');
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/exam/{subject}','StudentController@getExamQuestions')->name('exam');

// Auth::routes();

Route::get('/home', 'StudentController@index')->name('home');

//Ajax Routes
Route::get('/getQuestions','StudentController@getAjaxQuestions')->name('get-questions');
Route::get('/admin/findQuestion/{id}', 'AdminController@findOneQuestion');
Route::post('/updateQuestion/{id}','AdminController@updateQuestion');
Route::post('/addQuestion', 'AdminController@addQuestion');
Route::post('/deleteQuestion/{id}','AdminController@deleteQuestion');
Route::post('/calculateScore', 'StudentController@calculateScore');
Route::post('/updateStudent/{id}','AdminController@updateStudent');
Route::post('/deleteStudent/{id}','AdminController@deleteStudent');
Route::post('/restoreStudent/{id}','AdminController@restoreStudent');
Route::post('/addStudent', 'AdminController@addStudent');
Route::get('/submitQuestion','StudentController@submitQuestion');
Route::post('/setSubjectMark','AdminController@setMark');
Route::post('/updateSubjectMark','AdminController@updateMark');
Route::get('/checkMark/{id}','AdminController@checkMark');
// Route::post('/exam/{subject}','StudentController@getAjaxQuestions');

Route::get('/success','StudentController@submitSuccess')->name('success');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', "Admin\LoginController@showLoginForm")->name('admin-login');
    Route::post('/login', "Admin\LoginController@authenticate");
    Route::get('/logout', "Admin\LoginController@logout")->name('admin-logout');
    Route::get('/', 'AdminController@dashboard')->name('dashboard');
    Route::get('/students/{class}', 'AdminController@getClassStudents')->name('class-students');
    Route::get('/results','AdminController@getResults')->name('results');
    Route::get('/{subject}/hostexam','AdminController@hostExam')->name('host-exam');
    Route::get('/{subject}/endexam','AdminController@endExam')->name('end-exam');

    Route::group(['prefix' => 'subjects'], function() {
        Route::get('/{subject}/{class_id}/questions', 'AdminController@getAllQuestions')->name('questions');
        Route::get('/{subject}/{class_id}/results', 'AdminController@getSingleResult')->name('singleresult');
        // Route::post('/{subject}/{class_id}/questions', 'AdminController@addQuestion');
        // Route::post('/{subject}/{class_id}/questions/{id}', 'AdminController@updateQuestion');
    });

});

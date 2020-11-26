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

Route::get('/downloadSampleExcelFile', function () {
    return response()->download(public_path('upload_format.xlsx'),'upload-students-sample.xlsx');
});

Route::post('register/institution', function(){
    return view('register.institution');
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

Route::group(['prefix' => 'api'], function () {
    Route::get('/getQuestions','StudentController@getAjaxQuestions')->name('get-questions');
    Route::post('/updateQuestion/{id}','AdminController@updateQuestion');
    Route::post('/addQuestion', 'AdminController@addQuestion')->middleware('check-exam-status');
    Route::post('/deleteQuestion/{id}','AdminController@deleteQuestion')->middleware('check-exam-status');
    Route::post('/submitExam', 'StudentController@submitExam');
    Route::post('/exams','AdminController@createExam')->middleware('check-exam-status');
    Route::put('/exams/{id}','AdminController@updateExam')->middleware('check-exam-status');
    Route::patch('/start-exam','AdminController@startExam');
    Route::patch('/end-exam/{id}','AdminController@endExam');
    Route::post('/useTemplate/{template_id}','AdminController@createExamFromTemplate');
    Route::post('/students/single', 'AdminController@addStudent');
    Route::post('/students/multiple', 'AdminController@addMultipleStudents');
    Route::put('/students/{id}','AdminController@updateStudent');
    Route::delete('/students/{id}','AdminController@deleteStudent');
    Route::get('/disableStudent/{id}','AdminController@disableStudent');
    Route::get('/restoreStudent/{id}','AdminController@restoreStudent');

    Route::group(['prefix' => 'admins', 'middleware' => ['auth:admins']], function() {
        Route::post('/','Admin\AdminSectionController@createAdmin')->middleware('can:superAdminGate');
        Route::get('/teachers','Admin\AdminSectionController@getAllTeachers')->middleware('can:superAdminGate');
        Route::post('/teachers','Admin\AdminSectionController@createTeacher')->middleware('can:superAdminGate');
        Route::put('/teachers/{id}','Admin\AdminSectionController@updateTeacher')->middleware('can:superAdminGate');
        Route::delete('/teachers/{id}','Admin\AdminSectionController@deleteTeacher')->middleware('can:superAdminGate');
        Route::get('/subjects','Admin\AdminSectionController@getAllSubjects')->middleware('can:superAdminGate');
        Route::post('/subjects','Admin\AdminSectionController@createSubject')->middleware('can:superAdminGate');
        Route::put('/subjects/{id}','Admin\AdminSectionController@updateSubject')->middleware('can:superAdminGate');
        Route::post('/confirmPassword', 'AdminController@confirmPassword');
        Route::put('/updatePassword', 'AdminController@updatePassword');

    });

    Route::get('/generateNumber', function() {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';

        $code = mt_rand(5111, 9999) . $characters[rand(0, strlen($characters) - 1)] . $characters[rand(0, strlen($characters) - 1)];

        dd($code);

    });
});

Route::get('/success','StudentController@submitSuccess')->name('success');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', "Admin\LoginController@showLoginForm")->name('admin-login');
    Route::post('/login', "Admin\LoginController@authenticate");
    Route::get('/logout', "Admin\LoginController@logout")->name('admin-logout');
    Route::get('/', 'AdminController@dashboard')->name('dashboard');
    Route::get('/students', 'AdminController@getAllStudents')->name('students');
    Route::get('/account', 'AdminController@getAccountPage')->name('account');
    Route::get('/{subject}/hostexam','AdminController@hostExam')->name('host-exam');
    Route::get('/{subject}/endexam','AdminController@endExam')->name('end-exam');
    Route::get('/teachers', 'Admin\AdminSectionController@teachersPage')->name('teachers');
    Route::get('/subjects', 'Admin\AdminSectionController@subjectsPage')->name('subjects');
    Route::get('/{subject}/{class_id}/questions', 'AdminController@getAllQuestions')->name('questions');
    Route::get('/{subject}/{class_id}/results', 'AdminController@getSingleResult')->name('results');
    Route::get('/{subject}/{class_id}/results/download/{exam_id}', 'AdminController@downloadResult')->name('download-result');

});

//function to add new teacher (username, password and subject, c'est fini)
//function to add new admin (like a superadmin) - username and password only. Confirm that the user wants to add the person as an admin

//Function to update admin details. Only the details of the admin that is logged in (like his/her username and password), the person's username and password
//function to delete a teacher, that is to revoke their access.

//can't delete an admin, can only revoke the person's access. Admins have equal rights so it should be very minimal
//teacher can also change password but not subject
//function to add the new teacher to the database on the place that the user is located
//admin cannot change teacher's password, he can only change his own password and add a new teacher

//why does a teacher's password have to be renewed everyday?
//Teacher can change password but not subject

//username should have no spaces in between. An example is aremu.physics or aremu_physics or olaosebikan-chemistry
//main admin username can be anything.
//so main admin can only add and delete teachers

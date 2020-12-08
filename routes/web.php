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

use App\Http\Controllers\Teacher\Admin\RestrictedStudentsController;
use App\Http\Controllers\Teacher\Admin\StudentsController;
use App\Http\Controllers\Teacher\Admin\TeachersController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ExamsController as TeacherExamsController;
use App\Http\Controllers\Teacher\QuestionsController;
use App\Http\Controllers\Teacher\StartedExamsController;
use App\Http\Controllers\Teacher\SubjectResultsController;
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
    Route::post('/submitExam', 'StudentController@submitExam');
    Route::post('/useTemplate/{template_id}','AdminController@createExamFromTemplate');
    Route::post('/students/single', 'AdminController@addStudent');
    Route::post('/students/multiple', 'AdminController@addMultipleStudents');
    Route::put('/students/{id}','AdminController@updateStudent');
    Route::delete('/students/{id}','AdminController@deleteStudent');
    Route::get('/disableStudent/{id}','AdminController@disableStudent');
    Route::get('/restoreStudent/{id}','AdminController@restoreStudent');

    Route::group(['middleware' => ['auth:admins']], function() {
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

        Route::group(['prefix' => 'exams', 'middleware' => ['check-exam-status']], function() {
            Route::post('/', [TeacherExamsController::class, 'store']);
            Route::put('/{id}',[TeacherExamsController::class, 'update']);
        });

        Route::group(['prefix' => 'started-exams'], function() {
            Route::post('/', [StartedExamsController::class, 'store']);
            Route::delete('/{id}',[StartedExamsController::class, 'destroy']);
        });

        Route::group(['prefix' => 'questions', 'middleware' => ['check-exam-status']], function() {
            Route::post('/', [QuestionsController::class, 'store']);
            Route::put('/{id}', [QuestionsController::class, 'update']);
            Route::delete('/{id}', [QuestionsController::class, 'destroy']);
        });

        Route::group(['middleware' => ['can:superAdminGate']], function() {
            Route::group(['prefix' => 'teachers'], function() {
                Route::get('/', [TeachersController::class, 'index']);
                Route::post('/', [TeachersController::class, 'store']);
                Route::put('/{id}', [TeachersController::class, 'update']);
                Route::delete('/{id}', [TeachersController::class, 'destroy']);
            });

            Route::group(['prefix' => 'students'], function() {
                Route::post('/', [StudentsController::class, 'store']);
                Route::put('/{id}', [StudentsController::class, 'update']);
                Route::delete('/{id}', [StudentsController::class, 'destroy']);
                Route::post('/students/multiple', 'AdminController@addMultipleStudents');
            });

            Route::group(['prefix' => 'restricted-students'], function() {
                Route::post('/', [RestrictedStudentsController::class, 'store']);
                Route::delete('/{id}', [RestrictedStudentsController::class, 'destroy']);
            });
        });
    });
});

Route::get('/success','StudentController@submitSuccess')->name('success');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', "Admin\LoginController@showLoginForm")->name('admin-login');
    Route::post('/login', "Admin\LoginController@authenticate");
    Route::get('/logout', "Admin\LoginController@logout")->name('admin-logout');


    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/students', 'AdminController@getAllStudents')->name('students');
    Route::get('/account', 'AdminController@getAccountPage')->name('account');
    Route::get('/{subject}/hostexam','AdminController@hostExam')->name('host-exam');
    Route::get('/{subject}/endexam','AdminController@endExam')->name('end-exam');
    Route::get('/teachers', 'Admin\AdminSectionController@teachersPage')->name('teachers');
    Route::get('/subjects', 'Admin\AdminSectionController@subjectsPage')->name('subjects');
    Route::get('/{subject}/{class_id}/questions', [QuestionsController::class, 'index'])->name('questions');

    Route::get('/{subject}/{class_id}/results', [SubjectResultsController::class, 'index'])->name('results');
    Route::get('/{subject}/{class_id}/results/{exam_id}/download', [SubjectResultsController::class, 'download'])->name('download-result');

});

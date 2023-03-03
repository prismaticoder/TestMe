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

use App\Http\Controllers\Student\ExamsController as StudentExamsController;
use App\Http\Controllers\Student\LoginController as StudentLoginController;
use App\Http\Controllers\Student\SubmissionsController;
use App\Http\Controllers\Teacher\AccountPasswordController;
use App\Http\Controllers\Teacher\Admin\RestrictedStudentsController;
use App\Http\Controllers\Teacher\Admin\StudentsController;
use App\Http\Controllers\Teacher\Admin\SubjectsController;
use App\Http\Controllers\Teacher\Admin\TeachersController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ExamsController as TeacherExamsController;
use App\Http\Controllers\Teacher\LoginController as TeacherLoginController;
use App\Http\Controllers\Teacher\ManageAccountController;
use App\Http\Controllers\Teacher\QuestionsController;
use App\Http\Controllers\Teacher\StartedExamsController;
use App\Http\Controllers\Teacher\SubjectResultsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/success', function () {
    return view('student.submit-success');
})->name('success');

Route::get('/download-sample-excel-file', function () {
    return response()->download(public_path('upload_format.xlsx'), 'upload-students-sample.xlsx');
});

//Student Authentication routes
Route::get('/login', [StudentLoginController::class, 'index'])->name('login');
Route::post('/login', [StudentLoginController::class, 'login']);
Route::get('/logout', [StudentLoginController::class, 'logout'])->name('logout');

//Teacher Authentication routes
Route::get('/admin/login', [TeacherLoginController::class, 'show'])->name('admin-login');
Route::post('/admin/login', [TeacherLoginController::class, 'authenticate']);
Route::get('/admin/logout', [TeacherLoginController::class, 'logout'])->name('admin-logout');

Route::get('/exams', [StudentExamsController::class, 'index'])->middleware('auth')->name('home');
Route::get('/exams/{subject}', [StudentExamsController::class, 'show'])->middleware('auth')->name('exam');

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admins'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/manage-account', [ManageAccountController::class, 'index'])->name('account');
    Route::get('/students', [StudentsController::class, 'index'])->name('students');
    Route::get('/subjects', [SubjectsController::class, 'index'])->name('subjects');
    Route::get('/teachers', [TeachersController::class, 'index'])->name('teachers');
    Route::get('/{subject}/{class}/questions', [QuestionsController::class, 'index'])->name('questions');
    Route::get('/{subject}/{class}/results', [SubjectResultsController::class, 'index'])->name('results');
    Route::get('/{subject}/{class}/results/{exam}/download', [SubjectResultsController::class, 'download'])->name('download-result');
});

Route::group(['prefix' => 'api'], function () {
    Route::get('/network-status', function () {
        return response()->json([
            'status' => 'connected',
        ]);
    });
    Route::post('/submissions', [SubmissionsController::class, 'store']);

    Route::group(['middleware' => ['auth:admins']], function () {
        Route::group(['prefix' => 'exams', 'middleware' => ['check-exam-status']], function () {
            Route::post('/', [TeacherExamsController::class, 'store'])->name('exams.create');
            Route::put('/{id}', [TeacherExamsController::class, 'update']);
        });

        Route::group(['prefix' => 'duplicated-exams'], function () {
            Route::post('/', [TeacherExamsController::class, 'duplicate']);
        });

        Route::group(['prefix' => 'started-exams'], function () {
            Route::post('/', [StartedExamsController::class, 'store']);
            Route::delete('/{id}', [StartedExamsController::class, 'destroy']);
        });

        Route::group(['prefix' => 'update-password'], function () {
            Route::post('/', [AccountPasswordController::class, 'update']);
            Route::post('/verify', [AccountPasswordController::class, 'verify']);
        });

        Route::group(['prefix' => 'questions', 'middleware' => ['check-exam-status']], function () {
            Route::post('/', [QuestionsController::class, 'store']);
            Route::put('/{question}', [QuestionsController::class, 'update']);
            Route::delete('/{question}', [QuestionsController::class, 'destroy']);
        });

        Route::group(['middleware' => ['can:superAdminGate']], function () {
            Route::group(['prefix' => 'teachers'], function () {
                Route::post('/', [TeachersController::class, 'store']);
                Route::put('/{teacher}', [TeachersController::class, 'update']);
                Route::delete('/{teacher}', [TeachersController::class, 'destroy']);
            });

            Route::group(['prefix' => 'subjects'], function () {
                Route::post('/', [SubjectsController::class, 'store']);
                Route::put('/{subject:id}', [SubjectsController::class, 'update']);
                Route::delete('/{subject:id}', [SubjectsController::class, 'destroy']);
            });

            Route::group(['prefix' => 'students'], function () {
                Route::post('/', [StudentsController::class, 'store']);
                Route::post('/multiple', [StudentsController::class, 'storeMany']);
                Route::put('/{student}', [StudentsController::class, 'update']);
                Route::delete('/{id}', [StudentsController::class, 'destroy']);
            });

            Route::group(['prefix' => 'restricted-students'], function () {
                Route::post('/', [RestrictedStudentsController::class, 'store']);
                Route::delete('/{id}', [RestrictedStudentsController::class, 'destroy']);
            });
        });
    });
});

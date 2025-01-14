<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HeadStaffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/errors/permission', function () {
    return view('errors.permission');
})->name('errors.permission');

Route::middleware(['isGuest'])->group(function () {
    Route::get('/', function () {
        return view('landing-page');
    })->name('landing');


    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');

});

Route::middleware(['isLogin'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::middleware(['isLogin', 'isUser'])->group(function () {
        Route::prefix('/guest')->name('guest.')->group(function () {
            Route::get('/index', [ReportController::class, 'index'])->name('index');
            Route::get('/create', [ReportController::class, 'create'])->name('create');
            Route::post('/store', [ReportController::class, 'store'])->name('store');
            Route::get('/reports/search', [ReportController::class, 'searchByProvince'])->name('search');
            Route::get('/show/{id}', [ReportController::class, 'show'])->name('show');
            Route::post('/vote/{id}', [ReportController::class, 'vote'])->name('vote');
            Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
            Route::delete('/delete/{id}', [ReportController::class, 'destroy'])->name('delete');
            Route::post('/views/{id}', [ReportController::class, 'views'])->name('views');
            Route::prefix('/comments')->name('comments.')->group(function () {
                Route::post('/store/{id}', [CommentController::class, 'store'])->name('store');
                Route::delete('/delete/{id}', [CommentController::class, 'destroy'])->name('delete');
            });
        });
    });
});

Route::middleware(['isLogin', 'isStaff'])->group(function () {
    Route::prefix('/staff')->name('staff.')->group(function () {
        Route::get('/pengaduan', [ResponseController::class, 'index'])->name('index');
        Route::post('/pengaduan/store/{id}', [ResponseController::class, 'store'])->name('store');
        Route::get('/pengaduan/show/{id}', [ResponseController::class, 'show'])->name('show');
        Route::post('/pengaduan/store_progress/{id}', [ResponseController::class, 'storeProgress'])->name('storeProgress');
        Route::get('/download', [ReportController::class, 'exportExcel'])->name('download');
        Route::put('/pengaduan/update_status/{id}', [ResponseController::class, 'updateStatus'])->name('update');
    });
});

Route::middleware(['isLogin', 'isHeadStaff'])->group(function () {
    Route::prefix('/head_staff')->name('head_staff.')->group(function () {
        Route::get('/home', [HeadStaffController::class, 'index'])->name('index');
        Route::get('/user', [HeadStaffController::class, 'create'])->name('create');
        Route::post('/user', [HeadStaffController::class, 'store'])->name('store');
        Route::get('/reportsbyprovince', [HeadStaffController::class, 'getReportsByProvince'])->name('reports.by.province');
        Route::delete('/user/{id}', [HeadStaffController::class, 'destroy'])->name('destroy');
        Route::post('/user/{id}/reset-password', [HeadStaffController::class, 'resetPassword'])->name('reset.password');
    });
});

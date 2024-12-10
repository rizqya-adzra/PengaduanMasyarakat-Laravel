<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

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
Route::middleware(['isGuest'])->group(function() {
    Route::get('/', function () {
        return view('landing-page');
    })->name('landing');
    
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    
    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
    
    Route::get('/register', function () {
        return view('register');
    })->name('register');
});

Route::middleware(['isLogin'])->group(function() {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::prefix('/guest')->name('guest.')->group(function() {
        Route::get('/home', [ReportController::class, 'index'])->name('index');
    });

    Route::prefix('/head_staff')->name('head_staff.')->group(function() {
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/store', [ReportController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ReportController::class, 'show'])->name('show');
    });

});

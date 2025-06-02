<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminController as AdminControllerMain;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'index'])->name('login');
Route::get('/register', [AdminController::class, 'register'])->name('register');
Route::post('/store', [AdminController::class, 'store'])->name('store');
Route::post('/dashboard', [AdminController::class, 'dashbaord'])
    ->name('dashbaord');

Route::post('/checkadmin', [AdminController::class, 'checkadmin'])->name('checkadmin');
Route::post('/delete', [AdminController::class, 'delete'])->name('delete');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');


Route::get('/error', function () {
    abort(500);
});


Route::get('/admin', [AdminControllerMain::class, 'index'])->name('admin.login');
Route::get('/admin/register', [AdminControllerMain::class, 'register'])->name('admin.register');
Route::post('/admin/store', [AdminControllerMain::class, 'store'])->name('admin.store');
Route::post('/admin/checkadmin', [AdminControllerMain::class, 'checkadmin'])->name('admin.checkadmin');
Route::get('/admin/dashboard', [AdminControllerMain::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/delete', [AdminControllerMain::class, 'delete'])->name('admin.delete');
Route::get('/admin/logout', [AdminControllerMain::class, 'logout'])->name('admin.logout');
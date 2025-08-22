<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
Route::get('/admin/{id}', [AdminController::class, 'show'])->name('admin.show');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth')->name('admin.index');
Route::get('/user', [UserController::class, 'index'])->middleware('auth')->name('user.index');

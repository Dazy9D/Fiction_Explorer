<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/admin', [AdminController::class, 'index'])->middleware('auth')->name('admin.index');
Route::get('/user', [UserController::class, 'index'])->middleware('auth')->name('user.index');
Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create')->middleware('auth');
Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit')->middleware('auth');
Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update')->middleware('auth');
Route::post('/admin', [AdminController::class, 'store'])->name('admin.store')->middleware('auth');
Route::get('/admin/{id}', [AdminController::class, 'show'])->name('admin.show')->middleware('auth');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show')->middleware('auth');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/watchlist/add/{id}', [UserController::class, 'addToWatchlist'])->name('watchlist.add')->middleware('auth');
Route::post('/watchlist/remove/{id}', [UserController::class, 'removeFromWatchlist'])->name('watchlist.remove')->middleware('auth');
Route::get('/watchlist', [UserController::class, 'showWatchlist'])->name('watchlist.show');
Route::post('/watched/add/{id}', [UserController::class, 'markAsWatched'])->name('watched.add')->middleware('auth');
Route::post('/watched/remove/{id}', [UserController::class, 'unmarkAsWatched'])->name('watched.remove')->middleware('auth');
Route::get('/watched', [UserController::class, 'showWatched'])->name('watched.show')->middleware('auth');


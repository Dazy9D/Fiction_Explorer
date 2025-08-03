<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;

Route::get('/contents', [ContentController::class, 'index'])->name('contents.index');
Route::get('/contents/{id}', [ContentController::class, 'show'])->name('contents.show');

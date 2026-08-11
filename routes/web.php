<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CloudStorageController;

Route::get('/', function () {
    return redirect()->route('students.index');
});

Route::get('/students', [CloudStorageController::class, 'index'])->name('students.index');
Route::get('/students/create', [CloudStorageController::class, 'create'])->name('students.create');
Route::post('/students', [CloudStorageController::class, 'store'])->name('students.store');

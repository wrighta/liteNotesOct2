<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// This line creates a route every function in NoteController
Route::resource('/notes', NoteController::class)->middleware(['auth']);

require __DIR__.'/auth.php';

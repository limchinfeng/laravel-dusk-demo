<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class)->except('show');
});

Route::get('/send-email', [EmailController::class, 'show'])->name('send-email.show');
Route::post('/send-email', [EmailController::class, 'send'])->name('send-email.send');

require __DIR__.'/auth.php';

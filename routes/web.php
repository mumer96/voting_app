<?php

use App\Http\Controllers\PostsController;
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

    Route::resource('posts', PostsController::class);

    Route::get('my_posts/{userId?}', [PostsController::class, 'index'])->name('my.posts');
    Route::get('top_posts/', [PostsController::class, 'topPosts'])->name('top.posts');
    Route::post('/votes/{post}', [PostsController::class, 'addVote'])->name('votes.store');

    
});

require __DIR__.'/auth.php';

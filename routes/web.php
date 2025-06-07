<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/posts');
});

// Login page
Route::get('/login', function () {
    return view('login');
});
// Register page
Route::get('/register', function () {
    return view('register');
});

// Posts list page
Route::get('/posts', function () {
    $posts = Post::with('user')->get();
    return view('posts', compact('posts'));
});

// Add links to all web routes from the posts page
// (This is done by editing the Blade file: resources/views/posts.blade.php)

// Comments for a post
Route::get('/posts/{post}/comments', function (Post $post) {
    $comments = $post->comments()->with('user')->get();
    return view('comments', compact('post', 'comments'));
});

// Auth actions
Route::post('/register', [WebController::class, 'register']);
Route::post('/login', [WebController::class, 'login']);
Route::post('/logout', [WebController::class, 'logout']);

// Post actions
Route::post('/posts', [WebController::class, 'createPost']);
Route::post('/posts/{post}/edit', [WebController::class, 'editPost']);
Route::delete('/posts/{post}', [WebController::class, 'deletePost']);

// Comment actions
Route::post('/posts/{post}/comments', [WebController::class, 'addComment']);
Route::post('/comments/{comment}/edit', [WebController::class, 'editComment']);
Route::delete('/comments/{comment}', [WebController::class, 'deleteComment']);

// Show create post form
Route::get('/posts/create', function () {
    return view('create_post');
});

// Show edit post form

Route::get('/posts/{post}/edit', function (App\Models\Post $post) {
    // Only allow the owner to edit
    $user = Auth::user();
    if ($user === null || $user->id !== $post->user_id) {
        abort(403);
    }
    return view('edit_post', compact('post'));
});

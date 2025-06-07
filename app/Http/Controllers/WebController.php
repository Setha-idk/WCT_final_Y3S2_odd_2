<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Auth::login($user);
        return redirect('/posts');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/posts');
        }
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/posts');
    }

    // Create post
    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);
        return redirect('/posts');
    }

    // Edit post
    public function editPost(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $post->update($request->only('title', 'body'));
        return redirect('/posts');
    }

    // Delete post
    public function deletePost(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        $post->delete();
        return redirect('/posts');
    }

    // Add comment
    public function addComment(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required',
        ]);
        Comment::create([
            'body' => $request->body,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);
        return redirect("/posts/{$post->id}/comments");
    }

    // Edit comment
    public function editComment(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'required',
        ]);
        $comment->update($request->only('body'));
        return redirect("/posts/{$comment->post_id}/comments");
    }

    // Delete comment
    public function deleteComment(Comment $comment)
    {
        $postId = $comment->post_id;
        $comment->delete();
        return redirect("/posts/{$postId}/comments");
    }
}

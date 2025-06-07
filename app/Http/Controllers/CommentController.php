<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments for a post.
     */
    public function index($postId)
    {
        return \App\Models\Comment::with('user:id,name')
            ->where('post_id', $postId)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created comment for a post.
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string',
        ]);
        $comment = \App\Models\Comment::create([
            'post_id' => $postId,
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);
        return response()->json($comment, 201);
    }

    /**
     * Display the specified comment.
     */
    public function show($id)
    {
        return \App\Models\Comment::with('user:id,name')->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, $id)
    {
        $comment = \App\Models\Comment::findOrFail($id);
        if ($request->user()->id !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $request->validate([
            'body' => 'required|string',
        ]);
        $comment->update($request->only('body'));
        return response()->json($comment);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Request $request, $id)
    {
        $comment = \App\Models\Comment::findOrFail($id);
        if ($request->user()->id !== $comment->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $comment->delete();
        return response()->json(null, 204);
    }
}

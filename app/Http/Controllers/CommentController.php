<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return response()->json(Comment::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'comment_date' => 'nullable|date',
            'likes' => 'integer|default:0'
        ]);

        $comment = Comment::create($validated);
        return response()->json($comment, 201);
    }

    public function show($id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not found'], 404);
        return response()->json($comment, 200);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not found'], 404);

        $comment->update($request->all());
        return response()->json($comment, 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not found'], 404);

        $comment->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

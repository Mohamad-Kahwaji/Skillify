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
        $user = auth('users')->user();
        $validated = $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
            'comment_date' => 'nullable|date',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'user_id' => auth('users')->id(),
            'post_id' => $validated['post_id'],
            'comment_date' => $request->comment_date ?? now(),
            'likes' => 0
        ]);
        return redirect()->route('posts.show', $comment->post_id)->with('success', 'Comment added.');
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

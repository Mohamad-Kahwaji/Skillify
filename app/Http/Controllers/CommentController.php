<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostCommentedNotification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return response()->json(Comment::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content'   => 'required|string|max:1000',
            'post_id'   => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $authId = auth('users')->id();

        Comment::create([
            'content'      => $request->content,
            'user_id'      => $authId,
            'post_id'      => $request->post_id,
            'parent_id'    => $request->parent_id ?? null,
            'comment_date' => now(),
            'likes'        => 0,
        ]);

        // Only notify for top-level comments (not replies)
        if (!$request->parent_id) {
            $post = Post::with('user')->find($request->post_id);
            if ($post && $post->user_id !== $authId) {
                $post->user->notify(new PostCommentedNotification(auth('users')->user(), $post));
            }
        }

        return back()->with('commented_post', $request->post_id);
    }

    public function show(int $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not found'], 404);
        return response()->json($comment, 200);
    }

    public function update(Request $request, int $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not found'], 404);

        $comment->update($request->only(['content']));
        return response()->json($comment, 200);
    }

    public function destroy(int $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth('users')->id()) {
            abort(403);
        }

        $comment->delete();

        return back();
    }
}

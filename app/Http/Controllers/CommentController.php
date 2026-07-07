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
        // Accept post_id from URL param or request body
        $postId = $request->route('post') ?? $request->post_id;

        $request->validate([
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $authId = auth('users')->id();
        $user   = auth('users')->user();

        $comment = Comment::create([
            'content'      => $request->input('content'),
            'user_id'      => $authId,
            'post_id'      => $postId,
            'parent_id'    => $request->input('parent_id'),
            'comment_date' => now(),
            'likes'        => 0,
        ]);

        // Only notify for top-level comments
        if (!$request->input('parent_id')) {
            $post = Post::with('user')->find($postId);
            if ($post && $post->user_id !== $authId) {
                $post->user->notify(new PostCommentedNotification($user, $post));
            }
        }

        if ($request->wantsJson()) {
            $user->load('businesses');
            return response()->json([
                'id'         => $comment->id,
                'content'    => $comment->content,
                'user'       => [
                    'id'            => $user->id,
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'profile_photo' => $user->profile_photo,
                    'businesses'    => $user->businesses ? ['image' => $user->businesses->image] : null,
                ],
                'created_at' => $comment->created_at,
            ], 201);
        }

        return back()->with('commented_post', $postId);
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

    // حذف أي تعليق من قبل الأدمن أو السوبر أدمن (بدون شرط الملكية)
    public function adminDestroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'تم حذف التعليق.');
    }
}

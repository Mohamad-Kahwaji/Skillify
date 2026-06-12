<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Notifications\PostLikedNotification;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function toggle(Request $request, int $postId)
    {
        $userId   = auth('users')->id();
        $existing = PostLike::where('user_id', $userId)->where('post_id', $postId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            PostLike::create(['user_id' => $userId, 'post_id' => $postId]);
            $liked = true;

            $post = Post::with('user')->find($postId);
            if ($post && $post->user_id !== $userId) {
                $post->user->notify(new PostLikedNotification(auth('users')->user(), $post));
            }
        }

        $count = PostLike::where('post_id', $postId)->count();

        return response()->json(['liked' => $liked, 'count' => $count]);
    }
}

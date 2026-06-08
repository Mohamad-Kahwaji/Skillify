<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')
        ->where('user_id','!=',auth('users')->id())
        ->latest()->get();
        return view('admin.posts.index', compact('posts'));
    }

    public function showmypost()
    {
        $posts = Post::where('user_id', auth('users')->id())->latest()->get();
        return view('user.posts', compact('posts'));
    }

    public function communityPosts()
    {
        $posts = Post::with('user')
            ->where('user_id', '!=', auth('users')->id())
            ->latest()
            ->get();
        return view('user.community-posts', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
            'image'       => 'nullable|string',
            'user_id'     => 'required|exists:users,id',
            'post_date'   => 'nullable|date',
            'views'       => 'nullable|integer',
            'status'      => 'nullable|string',
        ]);

        $post = Post::updateOrCreate(['id' => $request->id], $validated);
        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }



    public function destroy(int $id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }
}

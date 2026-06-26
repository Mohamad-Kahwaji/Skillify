<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->get();
        return Inertia::render('Admin/Posts', ['posts' => $posts]);
    }

    public function allUserPosts()
    {
        $authId = auth('users')->id();
        $posts  = Post::with(['user', 'comments.user', 'likes'])
            ->where('user_id', '!=', $authId)
            ->latest()
            ->get();
        return Inertia::render('User/AllPosts', ['posts' => $posts, 'authId' => $authId]);
    }

    public function showmypost()
    {
        $posts = Post::where('user_id', auth('users')->id())->latest()->get();
        return Inertia::render('User/Posts', ['posts' => $posts]);
    }

    public function communityPosts()
    {
        $authId = auth('users')->id();
        $posts  = Post::with(['user', 'comments.user', 'likes'])
            ->where('user_id', '!=', $authId)
            ->latest()
            ->get();
        return Inertia::render('User/CommunityPosts', ['posts' => $posts, 'authId' => $authId]);
    }

    public function storeUserPost(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255', 'description' => 'required|string']);
        Post::create([
            'title'       => $request->title,
            'description' => $request->description,
            'user_id'     => auth('users')->id(),
        ]);
        return back()->with('success', 'Post published.');
    }

    public function destroyUserPost(int $id)
    {
        Post::where('id', $id)->where('user_id', auth('users')->id())->firstOrFail()->delete();
        return back()->with('success', 'Post deleted.');
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

        Post::updateOrCreate(['id' => $request->id], $validated);
        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }



    public function destroy(int $id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }
}

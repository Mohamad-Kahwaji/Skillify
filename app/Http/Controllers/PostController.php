<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'views' => 'integer|default:0',
            'status' => 'string|default:published'
        ]);

        $post = Post::create($validated);
        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) return response()->json(['message' => 'Not found'], 404);
        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) return response()->json(['message' => 'Not found'], 404);

        $post->update($request->all());
        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) return response()->json(['message' => 'Not found'], 404);

        $post->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        return response()->json(Conversation::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id_1' => 'required|exists:users,id',
            'user_id_2' => 'required|exists:users,id',
            'last_message' => 'nullable|string',
            'last_message_at' => 'nullable|datetime'
        ]);

        $conversation = Conversation::create($validated);
        return response()->json($conversation, 201);
    }

    public function show($id)
    {
        $conversation = Conversation::find($id);
        if (!$conversation) return response()->json(['message' => 'Not found'], 404);
        return response()->json($conversation, 200);
    }

    public function update(Request $request, $id)
    {
        $conversation = Conversation::find($id);
        if (!$conversation) return response()->json(['message' => 'Not found'], 404);

        $conversation->update($request->all());
        return response()->json($conversation, 200);
    }

    public function destroy($id)
    {
        $conversation = Conversation::find($id);
        if (!$conversation) return response()->json(['message' => 'Not found'], 404);

        $conversation->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

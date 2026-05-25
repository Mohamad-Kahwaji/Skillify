<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return response()->json(Message::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'user_id' => 'required|exists:users,id',
            'message_text' => 'required|string',
            'send_date' => 'nullable|date'
        ]);

        $message = Message::create($validated);
        return response()->json($message, 201);
    }

    public function show($id)
    {
        $message = Message::find($id);
        if (!$message) return response()->json(['message' => 'Not found'], 404);
        return response()->json($message, 200);
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);
        if (!$message) return response()->json(['message' => 'Not found'], 404);

        $message->update($request->all());
        return response()->json($message, 200);
    }

    public function destroy($id)
    {
        $message = Message::find($id);
        if (!$message) return response()->json(['message' => 'Not found'], 404);

        $message->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}

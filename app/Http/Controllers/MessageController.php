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
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'is_read' => 'boolean|default:false'
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

<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\NewMessageNotification;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    // جلب رسائل محادثة معينة
    public function index($conversationId)
    {
        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($q) {
                $uid = Auth::guard('users')->id();
                $q->where('user_id_1', $uid)->orWhere('user_id_2', $uid);
            })->firstOrFail();

        $messages = Message::where('conversation_id', $conversationId)
            ->with('user:id,first_name,last_name')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages, 200);
    }

    // إرسال رسالة نص أو ملف
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message_text'    => 'nullable|string',
            'file'            => 'nullable|file|max:10240', // 10MB
        ]);

        // تأكد أن المستخدم عضو في المحادثة
        $conversation = Conversation::where('id', $request->conversation_id)
            ->where(function ($q) {
                $uid = Auth::guard('users')->id();
                $q->where('user_id_1', $uid)->orWhere('user_id_2', $uid);
            })->firstOrFail();

        $filePath = null;
        $fileName = null;
        $fileType = null;

        // رفع الملف إذا موجود
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileType = $this->getFileType($file->getMimeType());
            $filePath = $file->store("chats/{$conversation->id}", 'public');
        }

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'user_id'         => Auth::guard('users')->id(),
            'message_text'    => $request->message_text,
            'send_date'       => now(),
            'file_path'       => $filePath,
            'file_name'       => $fileName,
            'file_type'       => $fileType,
        ]);

        // تحديث آخر رسالة في المحادثة
        $conversation->update([
            'last_message'    => $request->message_text ?? "📎 {$fileName}",
            'last_message_at' => now(),
        ]);

        // إرسال Real-time للطرف الثاني
        broadcast(new MessageSent($message))->toOthers();
        // إرسال إشعار للطرف الثاني
        broadcast(new NewMessageNotification($message));
        return response()->json($message->load('user'), 201);
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

    private function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if ($mimeType === 'application/pdf')        return 'pdf';
        return 'file';
    }



    public function unreadCount()
    {
        $uid = Auth::guard('users')->id();

        $count = Message::whereHas('conversation', function ($q) use ($uid) {
            $q->where('user_id_1', $uid)->orWhere('user_id_2', $uid);
        })
        ->where('user_id', '!=', $uid)
        ->where('is_read', false)
        ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead(int $conversationId)
    {
        $uid = Auth::guard('users')->id();

        Message::where('conversation_id', $conversationId)
            ->where('user_id', '!=', $uid)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Notifications\UserBlockedNotification;
use Illuminate\Http\JsonResponse;
use App\Notifications\AdminBlockedNotification;
use App\Notifications\SystemAnnouncementNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function userPage(Request $request)
    {
        $user          = auth('users')->user();
        $notifications = $user->notifications()->latest()->paginate(30);
        $unreadCount   = $user->unreadNotifications()->count();

        return Inertia::render('User/Notifications', [
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }
    public function unread(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'unread_notifications' => $user->unreadNotifications()->latest()->get(),
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }
    public function markAsread(Request $request,$id): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notifications->markAsRead();


        return response()->json([
            'message' => 'Notification marked as read',
        ]);

        }
        public function makeAllread(Request $request): JsonResponse{
            $request->user()->unreadNotifications->markAsRead();
            return response()->json([
                'message'=>'All notifications marked as read',
            ]);
        }
    public function destroy(Request $request, $id): JsonResponse
    {
        $request->user()
            ->notifications()
            ->findOrFail($id)
            ->delete();

        return response()->json(['message' => 'Notification deleted']);
    }
//user to admin
    public function notifyUser(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string',
        ]);
        $user = User::findOrFail($request->user_id);
        $user->notify(new UserBlockedNotification($request->reason));
        return response()->json(['message' => 'User notified successfully']);
    }

//admin to super admin
    public function notifyAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'accepted' => 'required|boolean',
            'reason' => 'required|string',
        ]);
        $admin = Admin::findOrFail($request->admin_id);
        $admin->notify(new AdminBlockedNotification($request->reason));
        return response()->json(['message' => 'Admin notified successfully']);
    }


//for All system 
    public function announceToAll(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
        ]);
        $notification = new SystemAnnouncementNotification(
            $request->title,
            $request->message);
        User::all()->each->notify($notification);
        Admin::all()->each->notify($notification);
        return response()->json(['message' => 'Announcement sent to all users and admins']);
    }

}

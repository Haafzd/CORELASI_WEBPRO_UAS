<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Support for Mobile (passing user_id) or Web (Auth session)
        $user = request('user_id') ? \App\Models\User::find(request('user_id')) : Auth::user();
        
        if(!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Filtering
        $type = request('type', 'all');
        $query = $user->notifications();

        if ($type === 'tugas') {
            // Filter notifications related to assignments (grade_released, assignment_posted, etc)
            $query->where(function($q) {
                // JSON filtering in MySQL/MariaDB using '->' operator or JSON_EXTRACT
                // Note: The 'type' key is inside the 'data' JSON column.
                // Depending on DB version, simplified where string matching might be safer if JSON support varies.
                // But for standard Laravel/MySQL setup:
                $q->where('data->type', 'grade_released')
                  ->orWhere('data->type', 'assignment_new');
            });
        } elseif ($type === 'presensi') {
             $query->where('data->type', 'attendance');
        }

        $notifications = $query->paginate(10);

        if(request()->wantsJson()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $user->unreadNotifications->count()
            ]);
        }

        return view('pages.notifications.index', compact('notifications', 'type'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        if($notification) {
            $notification->markAsRead();
        }

        return back();
    }
    
    public function markAllRead()
    {
        $user = request('user_id') ? \App\Models\User::find(request('user_id')) : Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        return back();
    }
}

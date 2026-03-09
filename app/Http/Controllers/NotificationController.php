<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->when($request->filter, function($query, $filter) {
                if ($filter === 'unread') {
                    $query->where('read', false);
                } elseif ($filter === 'read') {
                    $query->where('read', true);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function getLatest()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);
        
        $notification->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca');
    }
}
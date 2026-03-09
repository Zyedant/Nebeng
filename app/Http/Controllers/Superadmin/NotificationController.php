<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $notifications = Notification::where('user_id', $userId)
            ->when($request->filter, function ($query, $filter) {
                if ($filter === 'unread') {
                    $query->where('read', 0);
                } elseif ($filter === 'read') {
                    $query->where('read', 1);
                }
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        $unreadCount = Notification::where('user_id', $userId)
            ->where('read', 0)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function getLatest()
    {
        $userId = auth()->id();

        $notifications = Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->take(5)
            ->get(['id', 'user_id', 'type', 'title', 'description', 'read', 'created_at'])
            ->map(function ($n) {
                $n->created_at_human = Carbon::parse($n->created_at)->diffForHumans();
                return $n;
            });

        $unreadCount = Notification::where('user_id', $userId)
            ->where('read', 0)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['read' => 1]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('read', 0)
            ->update(['read' => 1]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca');
    }
}
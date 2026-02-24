<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $rows = DB::table('notifications')
            ->where('user_id', auth()->id())
            ->orderByDesc('id')
            ->paginate(20);

        $unreadCount = DB::table('notifications')
            ->where('user_id', auth()->id())
            ->where('read', 0)
            ->count();

        return view('superadmin.pages.notifications', compact('rows', 'unreadCount'));
    }

    public function markRead($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['read' => 1]);

        return back();
    }

    public function markAllRead()
    {
        DB::table('notifications')
            ->where('user_id', auth()->id())
            ->where('read', 0)
            ->update(['read' => 1]);

        return back();
    }
}

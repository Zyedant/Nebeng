<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;              // ✅ WAJIB
use App\Observers\UserObserver;   // ✅ observer kamu

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ✅ 1) Daftarkan observer supaya otomatis bikin notifikasi saat pengajuan
        User::observe(UserObserver::class);

        // ✅ 2) Kirim data notifikasi ke semua view superadmin (topbar, layout, dll)
        View::composer('superadmin.*', function ($view) {
            $userId = Auth::id();

            // kalau belum login, jangan query
            if (!$userId) {
                $view->with('topbarNotifications', collect());
                $view->with('topbarUnreadCount', 0);
                return;
            }

            // Ambil notifikasi terbaru (misal 20)
            $notifs = DB::table('notifications')
                ->where('user_id', $userId)
                ->orderByDesc('id')
                ->limit(20)
                ->get();

            $unread = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('read', 0)
                ->count();

            $view->with('topbarNotifications', $notifs);
            $view->with('topbarUnreadCount', $unread);
        });
    }
}

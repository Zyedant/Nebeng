<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('notify_admin')) {
    /**
     * Kirim notifikasi ke superadmin.
     * Default superadmin user_id = 1 (ubah kalau id superadmin kamu bukan 1).
     */
    function notify_admin(string $title, string $description, int $adminUserId = 1): void
    {
        // Pastikan tabel notifications ada
        if (!DB::getSchemaBuilder()->hasTable('notifications')) {
            return;
        }

        DB::table('notifications')->insert([
            'user_id'     => $adminUserId,
            'title'       => $title,
            'description' => $description,
            'read'        => 0, // kolom kamu namanya 'read' (tinyint)
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
function notify_admin(string $title, string $description, ?string $type = null): void
{
    if (!DB::getSchemaBuilder()->hasTable('notifications')) return;

    $adminIds = DB::table('users')
        ->whereRaw("LOWER(TRIM(COALESCE(role,''))) IN ('admin','superadmin')")
        ->where('is_banned', 0)
        ->pluck('id');

    foreach ($adminIds as $adminId) {
        DB::table('notifications')->insert([
            'user_id' => $adminId,
            'type' => $type, // ✅ ini yang baru
            'title' => $title,
            'description' => $description,
            'read' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}


if (!function_exists('notify_user')) {
    /**
     * Kirim notifikasi ke user tertentu.
     */
    function notify_user(int $userId, string $title, string $description): void
    {
        if (!DB::getSchemaBuilder()->hasTable('notifications')) {
            return;
        }

        DB::table('notifications')->insert([
            'user_id'     => $userId,
            'title'       => $title,
            'description' => $description,
            'read'        => 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}

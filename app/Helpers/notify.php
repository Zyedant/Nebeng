<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('notify_admin')) {
    /**
     * Kirim notifikasi ke semua admin/superadmin (role = admin/superadmin).
     * - type opsional (refund/laporan/verifikasi_customer/verifikasi_mitra/dll)
     * - hanya kirim ke user yang tidak dibanned
     */
    function notify_admin(string $title, string $description, ?string $type = null): void
    {
        // Pastikan tabel notifications ada
        if (!DB::getSchemaBuilder()->hasTable('notifications')) {
            return;
        }

        // Ambil semua admin/superadmin yang tidak dibanned
        $adminIds = DB::table('users')
            ->whereRaw("LOWER(TRIM(COALESCE(role,''))) IN ('admin','superadmin')")
            ->where('is_banned', 0)
            ->pluck('id');

        foreach ($adminIds as $adminId) {
            DB::table('notifications')->insert([
                'user_id'     => $adminId,
                'type'        => $type,
                'title'       => $title,
                'description' => $description,
                'read'        => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}

if (!function_exists('notify_user')) {
    /**
     * Kirim notifikasi ke user tertentu.
     * - type opsional
     */
    function notify_user(int $userId, string $title, string $description, ?string $type = null): void
    {
        if (!DB::getSchemaBuilder()->hasTable('notifications')) {
            return;
        }

        DB::table('notifications')->insert([
            'user_id'     => $userId,
            'type'        => $type,
            'title'       => $title,
            'description' => $description,
            'read'        => 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}

/**
 * =========================
 * WRAPPER AKTIVITAS ADMIN
 * =========================
 */

if (!function_exists('notify_verifikasi_mitra')) {
    function notify_verifikasi_mitra(string $namaMitra = 'Mitra'): void
    {
        notify_admin(
            'Verifikasi Mitra',
            "{$namaMitra} mengajukan verifikasi mitra.",
            'verifikasi_mitra'
        );
    }
}

if (!function_exists('notify_verifikasi_customer')) {
    function notify_verifikasi_customer(string $namaCustomer = 'Customer'): void
    {
        notify_admin(
            'Verifikasi Customer',
            "{$namaCustomer} mengajukan verifikasi customer.",
            'verifikasi_customer'
        );
    }
}

if (!function_exists('notify_refund_baru')) {
    function notify_refund_baru(string $orderNo = '-'): void
    {
        notify_admin(
            'Refund Baru',
            "Ada pengajuan refund baru untuk order {$orderNo}.",
            'refund'
        );
    }
}

if (!function_exists('notify_laporan_baru')) {
    function notify_laporan_baru(string $orderNo = '-'): void
    {
        notify_admin(
            'Laporan Baru',
            "Ada laporan baru untuk order {$orderNo}.",
            'laporan'
        );
    }
}

if (!function_exists('notify_update_akun')) {
    function notify_update_akun(string $namaUser = 'User'): void
    {
        notify_admin(
            'Update Akun',
            "{$namaUser} melakukan perubahan data akun.",
            'akun_update'
        );
    }
}
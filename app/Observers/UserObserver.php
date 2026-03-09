<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function updated(User $user): void
    {
        // hanya kalau verified_status berubah dan jadi 'pengajuan'
        if ($user->wasChanged('verified_status')) {
            $new = strtolower(trim((string) $user->verified_status));
            $role = strtolower(trim((string) $user->role));

            if ($new === 'pengajuan' && in_array($role, ['mitra', 'customer'])) {
                notify_admin(
                    'Pengajuan Verifikasi Baru',
                    strtoupper($role) . " {$user->name} melakukan pengajuan verifikasi."
                );
            }
        }
    }

    public function created(User $user): void
    {
        // opsional: kalau ada user baru daftar dan kamu mau notif
        // notify_admin('User Baru', "User {$user->name} baru saja membuat akun.");
    }
}

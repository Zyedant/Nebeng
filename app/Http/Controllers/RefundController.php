<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RefundController extends Controller
{
    // (4) Customer minta refund
    public function store(Request $request)
    {
        // TODO: simpan refund
        // $refund = Refund::create([...]);
        // $refundId = $refund->id;

        $refundId = 0; // sementara kalau belum ada tabel refund

        log_activity(
            'refund',
            auth()->user()->name.' mengajukan refund',
            'Menunggu konfirmasi admin',
            'refund',
            'Refund',
            (int) $refundId
        );

        return back()->with('success', 'Refund berhasil diajukan.');
    }
}

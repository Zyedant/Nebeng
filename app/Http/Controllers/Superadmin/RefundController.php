<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $date   = $request->query('date');

        $query = Refund::query()
            ->with(['order.customer', 'order.mitra'])
            ->orderByDesc('id');

        // Search
        if (!empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->whereHas('order', function ($o) use ($q) {
                        $o->where('order_no', 'like', "%{$q}%")
                          ->orWhere('layanan', 'like', "%{$q}%");
                    })
                  ->orWhereHas('order.customer', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%");
                    })
                  ->orWhereHas('order.mitra', function ($m) use ($q) {
                        $m->where('name', 'like', "%{$q}%");
                    });
            });
        }

        // Status
        if (!empty($status)) {
            $query->whereRaw("LOWER(TRIM(COALESCE(status,''))) = ?", [strtolower($status)]);
        }

        // Date (pakai created_at refund)
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }

        $rows = $query->get();

        return view('superadmin.pages.refund', compact('rows', 'q', 'status', 'date'));
    }

    public function downloadPdf(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $date   = $request->query('date');

        $query = Refund::query()
            ->with(['order.customer', 'order.mitra'])
            ->orderByDesc('id');

        if (!empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->whereHas('order', function ($o) use ($q) {
                        $o->where('order_no', 'like', "%{$q}%")
                          ->orWhere('layanan', 'like', "%{$q}%");
                    })
                  ->orWhereHas('order.customer', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%");
                    })
                  ->orWhereHas('order.mitra', function ($m) use ($q) {
                        $m->where('name', 'like', "%{$q}%");
                    });
            });
        }

        if (!empty($status)) {
            $query->whereRaw("LOWER(TRIM(COALESCE(status,''))) = ?", [strtolower($status)]);
        }

        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }

        $rows = $query->get();

        $pdf = Pdf::loadView('superadmin.pages.pdf.refund_pdf', compact('rows', 'q', 'status', 'date'))
            ->setPaper('A4', 'portrait');

        $filename = 'refund'
            . ($status ? "-{$status}" : '')
            . ($date ? "-{$date}" : '')
            . '.pdf';

        return $pdf->download($filename);
    }

    public function detail($id)
    {
        $refund = Refund::with(['order.customer', 'order.mitra'])->findOrFail($id);
        return view('superadmin.pages.refund_detail', compact('refund'));
    }

    /**
     * ✅ TERIMA refund:
     * - set status = 'Diterima'
     * - set amount = harga pesanan
     * - kirim notif ke customer
     */
    public function terima($id)
    {
        DB::transaction(function () use ($id) {

            $refund = Refund::lockForUpdate()->findOrFail($id);

            // Ambil pesanan terkait
            $pesanan = DB::table('pesanan')->where('id', $refund->order_id)->first();

            if (!$pesanan) {
                abort(404, 'Pesanan tidak ditemukan untuk refund ini.');
            }

            // kalau sudah diterima, jangan dobel proses
            if (strtolower(trim((string)$refund->status)) === 'diterima') {
                return;
            }

            // Update refund -> Diterima + amount dari harga pesanan
            $refund->status = 'Diterima';
            $refund->amount = (int) ($pesanan->harga ?? 0);
            $refund->save();

            // ✅ NOTIF KE CUSTOMER setelah refund berhasil diterima
            if (!empty($pesanan->customer_id)) {
                notify_user(
                    (int) $pesanan->customer_id,
                    'Refund Diterima',
                    'Pengajuan refund untuk order ' . ($pesanan->order_no ?? '-') . ' telah diterima.',
                    'refund'
                );
            }
        });

        return redirect()->back()->with('success', 'Refund berhasil diterima.');
    }

    /**
     * ✅ TOLAK refund
     * - status = 'Ditolak'
     * - amount = 0
     * - kirim notif ke customer
     */
    public function tolak($id)
    {
        return DB::transaction(function () use ($id) {

            $refund = Refund::lockForUpdate()->findOrFail($id);

            $pesanan = DB::table('pesanan')->where('id', $refund->order_id)->first();

            if (strtolower(trim((string)$refund->status)) === 'ditolak') {
                return redirect()->back()->with('success', 'Refund sudah berstatus Ditolak.');
            }

            $refund->update([
                'status' => 'Ditolak',
                'amount' => 0,
            ]);

            // ✅ NOTIF KE CUSTOMER setelah refund ditolak
            if ($pesanan && !empty($pesanan->customer_id)) {
                notify_user(
                    (int) $pesanan->customer_id,
                    'Refund Ditolak',
                    'Pengajuan refund untuk order ' . ($pesanan->order_no ?? '-') . ' ditolak.',
                    'refund'
                );
            }

            return redirect()->back()->with('success', 'Refund berhasil ditolak.');
        });
    }
}
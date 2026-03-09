<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $q    = $request->q;
        $date = $request->date; // YYYY-MM-DD

        // ambil dari reports (laporan terpisah)
        $query = DB::table('reports as r')
            ->leftJoin('pesanan as p', 'p.id', '=', 'r.pesanan_id')
            ->leftJoin('users as ur', 'ur.id', '=', 'r.reporter_user_id')
            ->leftJoin('users as ud', 'ud.id', '=', 'r.reported_user_id')
            ->select([
                'r.id as report_id',
                'r.pesanan_id',
                'r.reporter_role',
                'r.reported_role',
                'r.reason',
                'r.description',
                'r.status',
                DB::raw("COALESCE(p.tanggal, p.created_at, r.created_at) as tanggal_tampil"),
                'p.order_no',
                'p.layanan',
                'ur.name as reporter_name',
                'ud.name as reported_name',
            ]);

        if (!empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('p.order_no', 'like', "%{$q}%")
                  ->orWhere('p.layanan', 'like', "%{$q}%")
                  ->orWhere('ur.name', 'like', "%{$q}%")
                  ->orWhere('ud.name', 'like', "%{$q}%")
                  ->orWhere('r.reason', 'like', "%{$q}%")
                  ->orWhere('r.description', 'like', "%{$q}%");
            });
        }

        if (!empty($date)) {
            $query->whereDate(DB::raw("COALESCE(p.tanggal, p.created_at, r.created_at)"), $date);
        }

        // kalau kamu mau paging, pake paginate
        $rows = $query->orderByDesc('r.id')->paginate(10)->withQueryString();

        return view('superadmin.pages.laporan', compact('rows'));
    }

    public function detail($id)
    {
        $report = DB::table('reports as r')
            ->leftJoin('pesanan as p', 'p.id', '=', 'r.pesanan_id')
            ->leftJoin('users as ur', 'ur.id', '=', 'r.reporter_user_id')
            ->leftJoin('users as ud', 'ud.id', '=', 'r.reported_user_id')
            ->select([
                'r.id as report_id',
                'r.pesanan_id',
                'r.reporter_user_id',
                'r.reported_user_id',
                'r.reporter_role',
                'r.reported_role',
                'r.reason',
                'r.description',
                'r.status',
                'r.created_at as report_created_at',

                DB::raw("COALESCE(p.tanggal, p.created_at, r.created_at) as tanggal_tampil"),
                'p.order_no',
                'p.layanan',
                'p.catatan',

                'ur.name as reporter_name',
                'ud.name as reported_name',

                // ✅ data terlapor untuk panel tanggapi
                'ud.email as reported_email',
                'ud.phone_number as reported_phone',
                'ud.gender as reported_gender',
                'ud.birth_place as reported_birth_place',
                'ud.birth_date as reported_birth_date',
                'ud.image as reported_image',
                'ud.is_banned as reported_is_banned',
            ])
            ->where('r.id', $id)
            ->first();

        abort_if(!$report, 404);

        return view('superadmin.pages.laporan_detail', compact('report'));
    }

    public function updateReported(Request $request, $id)
    {
        $report = DB::table('reports')->select('id', 'reported_user_id')->where('id', $id)->first();
        abort_if(!$report, 404);

        // validasi ringan (silakan ketatkan sesuai kebutuhan)
        $data = $request->validate([
            'name'         => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:30',
            'gender'       => 'nullable|in:Laki-laki,Perempuan',
            'birth_place'  => 'nullable|string|max:255',
            'birth_date'   => 'nullable|date',
        ]);

        DB::table('users')
            ->where('id', $report->reported_user_id)
            ->update($data);

        return redirect()->route('sa.laporan.detail', ['id' => $id])
            ->with('saved_success', true); // ini buat popup gambar 3
    }

    // ✅ PERBAIKAN DI SINI (blockReported)
    public function blockReported($id)
    {
        $report = DB::table('reports')->select('id', 'reported_user_id')->where('id', $id)->first();
        abort_if(!$report, 404);

        DB::table('users')
            ->where('id', $report->reported_user_id)
            ->update(['is_banned' => 1]);

        // pakai session khusus block, supaya tidak bentrok dengan 'success' lain
        return redirect()->route('sa.laporan.detail', ['id' => $id])
            ->with('blocked_success', true)
            ->with('blocked_message', 'Akun terlapor berhasil diblokir.');
    }

    public function download(Request $request)
    {
        $q    = $request->q;
        $date = $request->date;

        $query = DB::table('reports as r')
            ->leftJoin('pesanan as p', 'p.id', '=', 'r.pesanan_id')
            ->leftJoin('users as ur', 'ur.id', '=', 'r.reporter_user_id')
            ->leftJoin('users as ud', 'ud.id', '=', 'r.reported_user_id')
            ->select([
                'p.order_no',
                'p.layanan',
                DB::raw("COALESCE(p.tanggal, p.created_at, r.created_at) as tanggal_tampil"),
                'ur.name as reporter_name',
                'ud.name as reported_name',
                'r.reporter_role',
                'r.reported_role',
                'r.reason',
                'r.description',
                'r.status',
            ]);

        if (!empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('p.order_no', 'like', "%{$q}%")
                  ->orWhere('p.layanan', 'like', "%{$q}%")
                  ->orWhere('ur.name', 'like', "%{$q}%")
                  ->orWhere('ud.name', 'like', "%{$q}%")
                  ->orWhere('r.reason', 'like', "%{$q}%")
                  ->orWhere('r.description', 'like', "%{$q}%");
            });
        }

        if (!empty($date)) {
            $query->whereDate(DB::raw("COALESCE(p.tanggal, p.created_at, r.created_at)"), $date);
        }

        $rows = $query->orderByDesc('tanggal_tampil')->get();

        // pastikan view pdf ada: resources/views/superadmin/pages/pdf/laporan_pdf.blade.php
        $pdf = Pdf::loadView('superadmin.pages.pdf.laporan_pdf', compact('rows', 'q', 'date'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('laporan.pdf');
    }
}
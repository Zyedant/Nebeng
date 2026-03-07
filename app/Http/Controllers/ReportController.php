<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    // (5) Customer melaporkan mitra
    public function store(Request $request)
    {
        // TODO: simpan laporan
        // $report = Report::create([...]);
        // $reportId = $report->id;

        $reportId = 0; // sementara kalau belum ada tabel report

        log_activity(
            'report',
            auth()->user()->name.' melaporkan mitra',
            'Laporan masuk dari customer',
            'laporan',
            'Report',
            (int) $reportId
        );

        return back()->with('success', 'Laporan berhasil dikirim.');
    }
}

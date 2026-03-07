<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    private function normStatusExpr(string $col): string
    {
        return "LOWER(TRIM(COALESCE($col,'')))";
    }

    private function detectFirstColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $col) {
            if (Schema::hasColumn($table, $col)) return $col;
        }
        return null;
    }

    /**
     * Date expression yang dipakai modul pesanan + top destinations
     * Priority: completed_at -> tanggal -> created_at
     */
    private function resolveDateExpr(string $table): ?string
    {
        if (!Schema::hasTable($table)) return null;

        $hasCompleted = Schema::hasColumn($table, 'completed_at');
        $hasTanggal   = Schema::hasColumn($table, 'tanggal');
        $hasCreated   = Schema::hasColumn($table, 'created_at');

        if ($hasCompleted && $hasTanggal && $hasCreated) {
            return "COALESCE($table.completed_at, $table.tanggal, $table.created_at)";
        }
        if ($hasCompleted && $hasTanggal) {
            return "COALESCE($table.completed_at, $table.tanggal)";
        }
        if ($hasCompleted) return "$table.completed_at";
        if ($hasTanggal)   return "$table.tanggal";
        if ($hasCreated)   return "$table.created_at";

        return null;
    }

    public function index()
    {
        Carbon::setLocale('id');

        $now = now();

        // =====================================================
        // DETEKSI TABEL PESANAN
        // =====================================================
        $orderTable = null;
        if (Schema::hasTable('pesanan')) {
            $orderTable = 'pesanan';
        } elseif (Schema::hasTable('orders')) {
            $orderTable = 'orders';
        }

        // kalau tidak ada tabel pesanan, tetap render dashboard minimal
        if (!$orderTable) {
            $periodeLabel = $now->translatedFormat('M Y');

            $selectedYm      = $now->format('Y-m');
            $monthOptions    = collect([['ym' => 'all', 'label' => 'Semua Periode']]);

            $totalPesanan    = 0;
            $pesananChart    = [0,0,0,0];
            $topDestinations = collect();

            $totalMitra      = 0;
            $totalPelanggan  = 0;
            $verifMitra      = 0;
            $verifPelanggan  = 0;

            $pendapatanRaw = (float) ($pendapatanTotal ?? 0);
            $pendapatanBulanLalu = 0.0;
            $pendapatanSeries    = array_fill(0, 12, 0);
            $financeNoteLeft     = 'Belum ada pendapatan';
            $financeNoteRight    = 'Rp 0';
            $financeMonthLabel   = $periodeLabel;
            $rekeningLabel       = '798102839877897';

            return view('superadmin.pages.dashboard', compact(
                'periodeLabel',
                'selectedYm',
                'monthOptions',

                'totalPesanan',
                'pesananChart',
                'topDestinations',

                'totalMitra',
                'totalPelanggan',
                'verifMitra',
                'verifPelanggan',

                'pendapatanBulanIni',
                'pendapatanSeries',
                'pendapatanBulanLalu',
                'financeNoteLeft',
                'financeNoteRight',
                'financeMonthLabel',
                'rekeningLabel',
            ));
        }

        // =====================================================
        // DETEKSI KOLOM LAYANAN
        // =====================================================
        $layananCol = $this->detectFirstColumn($orderTable, ['layanan', 'service', 'type']);

        // =====================================================
        // DETEKSI KOLOM TANGGAL (dipakai: pesanan + tujuan)
        // =====================================================
        $dateExpr = $this->resolveDateExpr($orderTable);

        // =====================================================
        // FILTER BULAN DARI DROPDOWN
        // ?ym=YYYY-MM atau ym=all
        // =====================================================
        $selectedYm = request('ym');

        if (!$selectedYm) {
            $selectedYm = $now->format('Y-m');
        }

        if ($selectedYm !== 'all' && !preg_match('/^\d{4}-\d{2}$/', $selectedYm)) {
            $selectedYm = $now->format('Y-m');
        }

        if ($selectedYm !== 'all') {
            try {
                $selectedDate = Carbon::createFromFormat('Y-m', $selectedYm)->startOfMonth();
            } catch (\Throwable $e) {
                $selectedDate = $now->copy()->startOfMonth();
                $selectedYm   = $selectedDate->format('Y-m');
            }

            $year  = (int) $selectedDate->year;
            $month = (int) $selectedDate->month;
            $periodeLabel = $selectedDate->translatedFormat('M Y');
        } else {
            // semua periode
            $selectedDate = null;
            $year = null;
            $month = null;
            $periodeLabel = 'Semua Periode';
        }

        // =====================================================
        // OPTIONS DROPDOWN BULAN (YM unik dari data)
        // =====================================================
        $monthOptions = collect();

        if ($dateExpr) {
            $monthOptions = DB::table($orderTable)
                ->selectRaw("DATE_FORMAT($dateExpr, '%Y-%m') as ym")
                ->whereNotNull(DB::raw($dateExpr))
                ->groupBy('ym')
                ->orderByDesc('ym')
                ->limit(24)
                ->pluck('ym')
                ->map(function ($ym) {
                    try {
                        $d = Carbon::createFromFormat('Y-m', $ym);
                        return ['ym' => $ym, 'label' => $d->translatedFormat('M Y')];
                    } catch (\Throwable $e) {
                        return ['ym' => $ym, 'label' => $ym];
                    }
                })
                ->values();
        }

        // tambah opsi "all" di paling atas
        $monthOptions = collect([['ym' => 'all', 'label' => 'Semua Periode']])
            ->concat($monthOptions)
            ->values();

        // =====================================================
        // TOTAL PESANAN + CHART PESANAN (bulan terpilih / all)
        // =====================================================
        $totalPesanan = 0;
        $pesananChart = [0, 0, 0, 0];

        if ($dateExpr) {
            $qTotal = DB::table($orderTable);

            if ($selectedYm !== 'all') {
                $qTotal->whereYear(DB::raw($dateExpr), $year)
                       ->whereMonth(DB::raw($dateExpr), $month);
            }

            $totalPesanan = $qTotal->count();
        }

        if ($dateExpr && $layananCol) {
            $qChart = DB::table($orderTable)
                ->selectRaw("LOWER(TRIM(COALESCE($layananCol,''))) as layanan_key, COUNT(*) as total")
                ->groupBy('layanan_key');

            if ($selectedYm !== 'all') {
                $qChart->whereYear(DB::raw($dateExpr), $year)
                       ->whereMonth(DB::raw($dateExpr), $month);
            }

            $raw = $qChart->pluck('total', 'layanan_key');

            $pesananChart = [
                (int)($raw['nebeng mobil'] ?? 0),
                (int)($raw['nebeng motor'] ?? 0),
                (int)($raw['nebeng barang'] ?? 0),
                (int)($raw['titip barang'] ?? 0),
            ];
        }

        // =====================================================
        // TOTAL MITRA & CUSTOMER
        // =====================================================
        $totalMitra = 0;
        $totalPelanggan = 0;

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            $totalMitra = DB::table('users')
                ->whereRaw($this->normStatusExpr('role') . ' = ?', ['mitra'])
                ->count();

            $totalPelanggan = DB::table('users')
                ->whereRaw($this->normStatusExpr('role') . ' = ?', ['customer'])
                ->count();
        }

        // =====================================================
        // VERIFIKASI PENDING
        // =====================================================
        $verifMitra = 0;
        $verifPelanggan = 0;

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'verified_status')) {
            $verifMitra = DB::table('users')
                ->whereRaw($this->normStatusExpr('role') . ' = ?', ['mitra'])
                ->whereRaw($this->normStatusExpr('verified_status') . ' = ?', ['pengajuan'])
                ->where('is_banned', 0)
                ->count();

            $verifPelanggan = DB::table('users')
                ->whereRaw($this->normStatusExpr('role') . ' = ?', ['customer'])
                ->whereRaw($this->normStatusExpr('verified_status') . ' = ?', ['pengajuan'])
                ->where('is_banned', 0)
                ->count();
        }

        // =====================================================
        // TOP DESTINATIONS (Tujuan Terbanyak) - bulan terpilih / all
        // =====================================================
        $topDestinations = collect();

        if ($dateExpr) {
            $jemputExpr = Schema::hasColumn($orderTable, 'titik_jemput')
                ? "COALESCE(NULLIF(TRIM($orderTable.titik_jemput),''), NULLIF(TRIM($orderTable.pickup_address),''), '-')"
                : "COALESCE(NULLIF(TRIM($orderTable.pickup_address),''), '-')";

            $tujuanExpr = Schema::hasColumn($orderTable, 'tujuan')
                ? "COALESCE(NULLIF(TRIM($orderTable.tujuan),''), NULLIF(TRIM($orderTable.dropoff_address),''), '-')"
                : "COALESCE(NULLIF(TRIM($orderTable.dropoff_address),''), '-')";

            $qTop = DB::table($orderTable)
                ->selectRaw("$jemputExpr as kota_asal, $tujuanExpr as kota_tujuan, COUNT(*) as total_perjalanan")
                ->groupBy('kota_asal', 'kota_tujuan')
                ->orderByDesc('total_perjalanan')
                ->limit(7);

            if ($selectedYm !== 'all') {
                $qTop->whereYear(DB::raw($dateExpr), $year)
                     ->whereMonth(DB::raw($dateExpr), $month);
            }

            $topDestinations = $qTop->get();
        }
// ==========================================================
// FINANCE (NET)
// - Card utama: TOTAL BERSIH semua periode (tidak ikut dropdown)
// - Note kanan: pendapatan bersih bulan terpilih / all
// - Perbandingan: bulan terpilih vs bulan sebelumnya
// - Chart: 12 bulan (tahun yg dipilih) - bersih
// - Tambahan: tampilkan refund diterima (memotong pendapatan)
// ==========================================================

$pendapatanTotal     = 0.0; // NET all period
$pendapatanBulanIni  = 0.0; // NET month selected
$pendapatanBulanLalu = 0.0; // NET last month from selected
$pendapatanSeries    = array_fill(0, 12, 0);

$totalRefundDiterima    = 0.0; // refund diterima all period
$refundDiterimaBulanIni = 0.0; // refund diterima month selected

$financeNoteLeft   = 'Belum ada pendapatan';
$financeNoteRight  = 'Rp 0';
$financeMonthLabel = $periodeLabel ?? now()->locale('id')->translatedFormat('M Y');
$rekeningLabel     = '798102839877897';

// ym=YYYY-MM atau ym=all
$selectedYm  = request()->query('ym');
$isAllPeriod = ($selectedYm === 'all');

// selectedDate aman (dipakai untuk mode bulan)
if (!$isAllPeriod && is_string($selectedYm) && preg_match('/^\d{4}-\d{2}$/', $selectedYm)) {
    $selectedDate = \Carbon\Carbon::createFromFormat('Y-m', $selectedYm)->startOfMonth();
} else {
    $selectedDate = now()->startOfMonth();
}

$financeMonthLabel = $isAllPeriod
    ? 'Semua Periode'
    : $selectedDate->locale('id')->translatedFormat('M Y');

$year  = (int) $selectedDate->year;   // untuk chart (kalau all period, pakai tahun sekarang)
$month = (int) $selectedDate->month;

if ($orderTable === 'pesanan'
    && Schema::hasTable('pesanan')
    && Schema::hasColumn('pesanan', 'harga')
    && Schema::hasColumn('pesanan', 'status')
    && Schema::hasTable('refunds')
    && Schema::hasColumn('refunds', 'order_id')
    && Schema::hasColumn('refunds', 'status')
) {
    // ======================================================
    // PILIH 1 KOLOM TANGGAL UNTUK FINANCE (biar bisa pakai index)
    // Priority: completed_at -> tanggal -> created_at
    // ======================================================
    if (Schema::hasColumn('pesanan', 'completed_at')) {
        $financeDateCol = 'p.completed_at';
    } elseif (Schema::hasColumn('pesanan', 'tanggal')) {
        $financeDateCol = 'p.tanggal';
    } else {
        $financeDateCol = 'p.created_at';
    }

    // ======================================================
    // REFUND AGG
    // - kalau ada kolom amount: pakai SUM(amount)
    // - kalau TIDAK ada kolom amount: anggap refund FULL = harga pesanan
    //   -> cukup flag per order_id agar tidak double count
    // ======================================================
    $refundHasAmount = Schema::hasColumn('refunds', 'amount');

    if ($refundHasAmount) {
        $refundAgg = DB::table('refunds')
            ->selectRaw("order_id, SUM(amount) as refund_amount")
            ->whereRaw($this->normStatusExpr('status') . " = ?", ['diterima'])
            ->groupBy('order_id');

        $refundSumExpr = "COALESCE(SUM(r.refund_amount),0)";
    } else {
        $refundAgg = DB::table('refunds')
            ->selectRaw("order_id")
            ->whereRaw($this->normStatusExpr('status') . " = ?", ['diterima'])
            ->groupBy('order_id');

        $refundSumExpr = "COALESCE(SUM(CASE WHEN r.order_id IS NULL THEN 0 ELSE p.harga END),0)";
    }

    // ======================================================
    // BASE QUERY (pesanan selesai + join refundAgg)
    // ======================================================
    $base = DB::table('pesanan as p')
        ->leftJoinSub($refundAgg, 'r', 'r.order_id', '=', 'p.id')
        ->whereRaw($this->normStatusExpr('p.status') . " = ?", ['selesai']);

    // ==========================
    // TOTAL NET all period (CARD)
    // ==========================
    $rowAll = (clone $base)
        ->selectRaw("
            COALESCE(SUM(p.harga),0) as total_harga,
            $refundSumExpr as total_refund
        ")
        ->first();

    $totalHargaAll       = (float) ($rowAll->total_harga ?? 0);
    $totalRefundDiterima = (float) ($rowAll->total_refund ?? 0);
    $pendapatanTotal     = $totalHargaAll - $totalRefundDiterima;

    // ==========================
    // MODE: ALL PERIOD
    // ==========================
    if ($isAllPeriod) {

        $pendapatanBulanIni  = $pendapatanTotal;
        $pendapatanBulanLalu = 0.0;

        $financeNoteLeft  = 'Total pendapatan bersih seluruh periode';
        $financeNoteRight = 'Rp ' . number_format($pendapatanTotal, 0, ',', '.');

        // chart tetap 12 bulan untuk tahun $year (tahun sekarang)
        $rows = (clone $base)
            ->whereYear(DB::raw($financeDateCol), $year)
            ->selectRaw("
                MONTH($financeDateCol) as m,
                (COALESCE(SUM(p.harga),0) - $refundSumExpr) as total
            ")
            ->groupBy('m')
            ->orderBy('m')
            ->get();

        foreach ($rows as $r) {
            $idx = (int) $r->m - 1;
            if ($idx >= 0 && $idx < 12) $pendapatanSeries[$idx] = (float) $r->total;
        }

        // pada mode all period, kamu bisa tampilkan totalRefundDiterima kalau mau
        $refundDiterimaBulanIni = 0.0;

    } else {

        // ==========================
        // MODE: BULAN TERPILIH (NET)
        // ==========================
        $start = $selectedDate->copy()->startOfMonth();
        $end   = $selectedDate->copy()->endOfMonth();

        $rowMonth = (clone $base)
            ->whereBetween(DB::raw($financeDateCol), [$start, $end])
            ->selectRaw("
                COALESCE(SUM(p.harga),0) as total_harga,
                $refundSumExpr as total_refund
            ")
            ->first();

        $totalHargaBulanIni     = (float) ($rowMonth->total_harga ?? 0);
        $refundDiterimaBulanIni = (float) ($rowMonth->total_refund ?? 0);
        $pendapatanBulanIni     = $totalHargaBulanIni - $refundDiterimaBulanIni;

        // bulan lalu
        $last = $selectedDate->copy()->subMonthNoOverflow();
        $lastStart = $last->copy()->startOfMonth();
        $lastEnd   = $last->copy()->endOfMonth();

        $rowLast = (clone $base)
            ->whereBetween(DB::raw($financeDateCol), [$lastStart, $lastEnd])
            ->selectRaw("
                COALESCE(SUM(p.harga),0) as total_harga,
                $refundSumExpr as total_refund
            ")
            ->first();

        $pendapatanBulanLalu = (float) ($rowLast->total_harga ?? 0) - (float) ($rowLast->total_refund ?? 0);

        // chart 12 bulan (NET) untuk tahun terpilih
        $rows = (clone $base)
            ->whereYear(DB::raw($financeDateCol), $year)
            ->selectRaw("
                MONTH($financeDateCol) as m,
                (COALESCE(SUM(p.harga),0) - $refundSumExpr) as total
            ")
            ->groupBy('m')
            ->orderBy('m')
            ->get();

        foreach ($rows as $r) {
            $idx = (int) $r->m - 1;
            if ($idx >= 0 && $idx < 12) $pendapatanSeries[$idx] = (float) $r->total;
        }

        // note kiri (perbandingan NET)
        if ($pendapatanBulanIni <= 0) {
            $financeNoteLeft = 'Belum ada pendapatan bulan ini';
        } elseif ($pendapatanBulanLalu <= 0) {
            $financeNoteLeft = 'Naik (bulan lalu 0)';
        } else {
            $diff = $pendapatanBulanIni - $pendapatanBulanLalu;
            $pct  = ($diff / $pendapatanBulanLalu) * 100;

            $financeNoteLeft = ($pct >= 0)
                ? 'Naik ' . number_format($pct, 1, ',', '.') . '% dari bulan lalu'
                : 'Turun ' . number_format(abs($pct), 1, ',', '.') . '% dari bulan lalu';
        }

        $financeNoteRight = 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.');
    }
}
        return view('superadmin.pages.dashboard', compact(
            'periodeLabel',
            'selectedYm',
            'monthOptions',
            'totalPesanan',
            'pesananChart',
            'topDestinations',
            'totalMitra',
            'totalPelanggan',
            'verifMitra',
            'verifPelanggan',
            'pendapatanBulanIni',
            'pendapatanTotal',
            'pendapatanSeries',
            'pendapatanBulanLalu',
            'financeNoteLeft',
            'financeNoteRight',
            'financeMonthLabel',
            'rekeningLabel',
            'totalRefundDiterima',
            'refundDiterimaBulanIni',
        ));
    }
}
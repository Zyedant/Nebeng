<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Normalisasi string kolom untuk perbandingan (lower + trim + null-safe).
     */
    private function normStatusExpr(string $col): string
    {
        return "LOWER(TRIM(COALESCE($col,'')))";
    }

    public function index()
    {
        Carbon::setLocale('id');

        $now          = now();
        $periodeLabel = $now->translatedFormat('M Y');

        // =========================
        // Tentukan tabel pesanan
        // =========================
        $orderTable = null;
        if (Schema::hasTable('pesanan')) {
            $orderTable = 'pesanan';
        } elseif (Schema::hasTable('orders')) {
            $orderTable = 'orders';
        }

        // Kolom layanan (untuk chart)
        $layananCol = null;
        if ($orderTable) {
            if (Schema::hasColumn($orderTable, 'layanan')) {
                $layananCol = 'layanan';
            } elseif (Schema::hasColumn($orderTable, 'service')) {
                $layananCol = 'service';
            } elseif (Schema::hasColumn($orderTable, 'type')) {
                $layananCol = 'type';
            }
        }

        // Tanggal efektif: COALESCE(tanggal, created_at)
        $dateExpr = null;
        if ($orderTable) {
            $hasTanggal = Schema::hasColumn($orderTable, 'tanggal');
            $hasCreated = Schema::hasColumn($orderTable, 'created_at');

            if ($hasTanggal && $hasCreated) {
                $dateExpr = "COALESCE($orderTable.tanggal, $orderTable.created_at)";
            } elseif ($hasTanggal) {
                $dateExpr = "$orderTable.tanggal";
            } elseif ($hasCreated) {
                $dateExpr = "$orderTable.created_at";
            }
        }

        // =========================
        // PESANAN: total bulan ini + chart per layanan
        // =========================
        $totalPesanan  = 0;
        $pesananChart  = [0, 0, 0, 0]; // [Nebeng Mobil, Nebeng Motor, Nebeng Barang, Titip Barang]
        $year          = $now->year;
        $month         = $now->month;

        if ($orderTable && $dateExpr) {
            $totalPesanan = DB::table($orderTable)
                ->whereYear(DB::raw($dateExpr), $year)
                ->whereMonth(DB::raw($dateExpr), $month)
                ->count();
        }

        if ($orderTable && $dateExpr && $layananCol) {
            $raw = DB::table($orderTable)
                ->selectRaw("LOWER(TRIM(COALESCE($layananCol,''))) as layanan_key, COUNT(*) as total")
                ->whereYear(DB::raw($dateExpr), $year)
                ->whereMonth(DB::raw($dateExpr), $month)
                ->groupBy('layanan_key')
                ->pluck('total', 'layanan_key');

            $pesananChart = [
                (int) ($raw['nebeng mobil'] ?? 0),
                (int) ($raw['nebeng motor'] ?? 0),
                (int) ($raw['nebeng barang'] ?? 0),
                (int) ($raw['titip barang'] ?? 0),
            ];
        }

        // =========================
        // TOTAL MITRA / CUSTOMER
        // =========================
        $totalMitra     = 0;
        $totalPelanggan = 0;

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            $totalMitra = DB::table('users')
                ->whereRaw($this->normStatusExpr('role') . ' = ?', ['mitra'])
                ->count();

            $totalPelanggan = DB::table('users')
                ->whereRaw($this->normStatusExpr('role') . ' = ?', ['customer'])
                ->count();
        }

        // =========================
        // VERIFIKASI (pending)
        // =========================
        $verifMitra     = 0;
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

        // =========================
        // LIST MITRA TERBARU (join partners -> users)
        // =========================
        $dataMitra = collect();

        if (Schema::hasTable('partners') && Schema::hasTable('users')) {
            $dataMitra = DB::table('partners')
                ->join('users', 'users.id', '=', 'partners.user_id')
                ->select([
                    'users.id as id',
                    'users.name as name',
                    'users.email as email',
                    'users.phone_number as phone_number',
                    'users.verified_status as verified_status',
                    'users.is_banned as is_banned',
                ])
                ->where('users.is_banned', 0)
                ->orderByDesc('partners.id')
                ->limit(5)
                ->get();
        }

        // =========================
        // TOP DESTINATIONS (bulan ini)
        // =========================
        $topDestinations = collect();

        if ($orderTable && $dateExpr) {
            $jemputExpr = Schema::hasColumn($orderTable, 'titik_jemput')
                ? "COALESCE(NULLIF(TRIM($orderTable.titik_jemput),''), NULLIF(TRIM($orderTable.pickup_address),''), '-')"
                : "COALESCE(NULLIF(TRIM($orderTable.pickup_address),''), '-')";

            $tujuanExpr = Schema::hasColumn($orderTable, 'tujuan')
                ? "COALESCE(NULLIF(TRIM($orderTable.tujuan),''), NULLIF(TRIM($orderTable.dropoff_address),''), '-')"
                : "COALESCE(NULLIF(TRIM($orderTable.dropoff_address),''), '-')";

            $topDestinations = DB::table($orderTable)
                ->selectRaw("$jemputExpr as kota_asal, $tujuanExpr as kota_tujuan, COUNT(*) as total_perjalanan")
                ->whereYear(DB::raw($dateExpr), $year)
                ->whereMonth(DB::raw($dateExpr), $month)
                ->whereRaw("TRIM(COALESCE($orderTable.titik_jemput, $orderTable.pickup_address, '')) <> ''")
                ->whereRaw("TRIM(COALESCE($orderTable.tujuan, $orderTable.dropoff_address, '')) <> ''")
                ->groupBy('kota_asal', 'kota_tujuan')
                ->orderByDesc('total_perjalanan')
                ->limit(7)
                ->get();
        }

        // =========================
        // LATEST USERS
        // =========================
        $latestUsers = collect();

        if (Schema::hasTable('users')) {
            $latestUsers = DB::table('users')
                ->select('id', 'name', 'email', 'phone_number', 'role', 'created_at', 'is_banned')
                ->orderByDesc('id')
                ->limit(5)
                ->get();
        }

        return view('superadmin.pages.dashboard', compact(
            'periodeLabel',
            'totalPesanan',
            'pesananChart',
            'totalMitra',
            'totalPelanggan',
            'verifMitra',
            'verifPelanggan',
            'topDestinations',
            'dataMitra',
            'latestUsers'
        ));
    }
}
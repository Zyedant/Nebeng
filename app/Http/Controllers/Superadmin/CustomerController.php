<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Pesanan;

class CustomerController extends Controller
{
    private function normStatusExpr(string $col)
    {
        return "LOWER(TRIM(COALESCE($col,'')))";
    }

    // =========================
    // VERIFIKASI CUSTOMER (LIST PENGAJUAN)
    // =========================
    public function customer()
    {
        $rows = DB::table('users')
            ->leftJoin('customers', 'customers.user_id', '=', 'users.id')
            ->select([
                'users.id as id',
                'users.name as name',
                'users.email as email',
                'users.phone_number as phone_number',
                'users.verified_status as verified_status',
                'users.is_banned as is_banned',
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['customer'])
            ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['pengajuan'])
            ->where('users.is_banned', 0)
            ->orderByDesc('users.id')
            ->get();

        return view('superadmin.pages.verifikasi_customer', compact('rows'));
    }

    // =========================
    // CUSTOMER SIDEBAR (LIST ALL)
    // =========================
    public function daftarCustomer()
{
    $q      = request('q');
    $status = request('status'); // pengajuan|terverifikasi|ditolak|belum|diblok|null
    $date   = request('date');

    $query = DB::table('users')
        ->leftJoin('customers', 'customers.user_id', '=', 'users.id')
        ->select([
            'users.id as id',
            'users.name as name',
            'users.email as email',
            'users.phone_number as phone_number',
            'users.verified_status as verified_status',
            'users.is_banned as is_banned',
            DB::raw('COALESCE(customers.created_at, users.created_at) as created_at'),
        ])
        ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['customer']);

    // SEARCH
    if (!empty($q)) {
        $query->where(function ($w) use ($q) {
            $w->where('users.name', 'like', "%{$q}%")
              ->orWhere('users.email', 'like', "%{$q}%")
              ->orWhere('users.phone_number', 'like', "%{$q}%")
              ->orWhere('users.id', 'like', "%{$q}%");
        });
    }

    // FILTER STATUS (✅ FIX)
    if ($status !== null && $status !== '') {

        $status = strtolower(trim($status));

        if ($status === 'diblok') {
            // hanya yang diblokir
            $query->where('users.is_banned', 1);

        } else {
            // ✅ selain diblok, pastikan tidak dibanned
            $query->where('users.is_banned', 0);

            if ($status === 'belum') {
                $query->whereNull('users.verified_status');

            } else {
                // pengajuan / terverifikasi / ditolak
                $query->whereRaw(
                    $this->normStatusExpr('users.verified_status') . " = ?",
                    [$status]
                );
            }
        }
    }

    // FILTER DATE
    if (!empty($date)) {
        $query->whereDate(DB::raw('COALESCE(customers.created_at, users.created_at)'), $date);
    }

    $rows = $query->orderByDesc('users.id')->get();

    return view('superadmin.pages.customer', compact('rows', 'q', 'status', 'date'));
}
    // =========================
    // DOWNLOAD PDF - VERIFIKASI CUSTOMER
    // =========================
    public function downloadCustomerPdf(Request $request)
    {
        $date = $request->query('date'); // YYYY-MM

        $query = DB::table('users')
            ->leftJoin('customers', 'customers.user_id', '=', 'users.id')
            ->select([
                'users.name',
                'users.email',
                'users.phone_number',
                'users.verified_status',
                DB::raw('COALESCE(customers.created_at, users.created_at) as created_at'),
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['customer'])
            ->where(function ($w) {
                $w->whereNull('users.verified_status')
                    ->orWhereRaw("LOWER(TRIM(users.verified_status)) = 'pengajuan'");
            });

        if ($date && preg_match('/^\d{4}-\d{2}$/', $date)) {
            $year  = (int) substr($date, 0, 4);
            $month = (int) substr($date, 5, 2);

            $query->whereYear(DB::raw('COALESCE(customers.created_at, users.created_at)'), $year)
                ->whereMonth(DB::raw('COALESCE(customers.created_at, users.created_at)'), $month);
        }

        $rows = $query->orderByDesc('users.id')->get();

        $pdf = Pdf::loadView('superadmin.pages.pdf.verifikasi_customer_pdf', compact('rows', 'date'));
        $filename = 'verifikasi-customer' . ($date ? "-{$date}" : '') . '.pdf';

        return $pdf->download($filename);
    }

    // =========================
    // DOWNLOAD PDF - DAFTAR CUSTOMER
    // =========================
    public function downloadDaftarCustomerPdf(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $date   = $request->query('date');

        $query = DB::table('users')
            ->leftJoin('customers', 'customers.user_id', '=', 'users.id')
            ->select([
                'users.id as id',
                'users.name as name',
                'users.email as email',
                'users.phone_number as phone_number',
                'users.verified_status as verified_status',
                'users.is_banned as is_banned',
                DB::raw('COALESCE(customers.created_at, users.created_at) as created_at'),
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['customer']);

        if (!empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%")
                    ->orWhere('users.phone_number', 'like', "%{$q}%")
                    ->orWhere('users.id', 'like', "%{$q}%");
            });
        }

        if ($status !== null && $status !== '') {
            if ($status === 'diblok') {
                $query->where('users.is_banned', 1);
            } elseif ($status === 'belum') {
                $query->whereNull('users.verified_status');
            } else {
                $query->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", [strtolower($status)]);
            }
        }

        if (!empty($date)) {
            $query->whereDate(DB::raw('COALESCE(customers.created_at, users.created_at)'), $date);
        }

        $rows = $query->orderByDesc('users.id')->get();

        $pdf = Pdf::loadView('superadmin.pages.pdf.daftar_customer_pdf', compact('rows', 'q', 'status', 'date'))
            ->setPaper('A4', 'portrait');

        $filename = 'daftar-customer'
            . ($status ? "-{$status}" : '')
            . ($date ? "-{$date}" : '')
            . '.pdf';

        return $pdf->download($filename);
    }

    // =========================
    // DETAIL CUSTOMER (VERIFIKASI)
    // =========================
 public function customerDetail(int $id)
{
    $customer = DB::table('users')
        ->leftJoin('customers', 'customers.user_id', '=', 'users.id')
        ->select([
            'users.id',
            'users.name',
            'users.email',
            'users.phone_number',
            'users.gender',
            'users.birth_place',
            'users.birth_date',

            // ✅ ALIAS yang dipakai di blade
            'users.verified_status as user_verified_status',
            'users.is_banned as user_is_banned',
            'users.image as user_image',

            // customers
            'customers.id_fullname',
            'customers.id_number',
            'customers.id_birth_date',
            'customers.id_image',
            'customers.verified_status_message',
        ])
        ->where('users.id', $id)
        ->first();

    abort_if(!$customer, 404);

    return view('superadmin.pages.detail_customer', compact('customer'));
}

    // =========================
    // EDIT CUSTOMER (SIDEBAR CUSTOMER)
    // =========================
    public function edit(int $id)
{
    $customer = DB::table('users')
        ->leftJoin('customers', 'customers.user_id', '=', 'users.id')
        ->select([
            'users.id',
            'users.name',
            'users.email',
            'users.phone_number',
            'users.gender',
            'users.birth_place',
            'users.birth_date',
            'users.image as user_image',
            'users.verified_status',
            'users.is_banned',

            'customers.id_fullname',
            'customers.id_number',
            'customers.id_image',
            'customers.verified_status_message',
        ])
        ->where('users.id', $id)
        ->whereRaw("LOWER(TRIM(COALESCE(users.role,''))) = 'customer'")
        ->first();

    abort_if(!$customer, 404);

 return view('superadmin.pages.customer_edit', compact('customer'));

}


    public function update(Request $request, int $id)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'nullable|string|max:30',
        'gender'       => 'nullable|string|max:30',
        'birth_place'  => 'nullable|string|max:100',
        'birth_date'   => 'nullable|date',

        // ✅ tambah "diblok"
        'verified_status' => 'nullable|in:pengajuan,terverifikasi,ditolak,diblok',
        'verified_status_message' => 'nullable|string|max:255',
    ]);

    abort_if(!DB::table('users')->where('id', $id)->exists(), 404);

    $data = [
        'name'         => $request->name,
        'email'        => $request->email,
        'phone_number' => $request->phone_number,
        'gender'       => $request->gender,
        'birth_place'  => $request->birth_place,
        'birth_date'   => $request->birth_date,
        'updated_at'   => now(),
    ];

    // ✅ LOGIKA STATUS
    if ($request->filled('verified_status')) {
        if ($request->verified_status === 'diblok') {
            // JANGAN set verified_status ke 'diblok' (enum users kamu biasanya tidak punya ini)
            $data['is_banned'] = 1;
        } else {
            $data['verified_status'] = $request->verified_status;
            $data['is_banned'] = 0; // kalau bukan diblok, pastikan tidak keblokir
        }
    }

    DB::table('users')->where('id', $id)->update($data);

    DB::table('customers')->where('user_id', $id)->update([
        'verified_status_message' => $request->verified_status_message,
        'updated_at'              => now(),
    ]);

    return redirect()
        ->route('sa.customer.detail', ['id' => $id])
        ->with('success', 'Customer berhasil diupdate.');
}


    // =========================
    // ACTION: REJECT
    // =========================
    public function customerReject(Request $request, int $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $userExists = DB::table('users')->where('id', $id)->exists();
        abort_if(!$userExists, 404);

        DB::table('users')->where('id', $id)->update([
            'verified_status' => 'ditolak',
            'role'            => 'Customer',
            'is_banned'       => 0,
            'updated_at'      => now(),
        ]);

        DB::table('customers')->where('user_id', $id)->update([
            'verified_status_message' => $request->reason,
            'updated_at'              => now(),
        ]);

        return redirect()
            ->route('sa.verifikasi.customer.detail', ['id' => $id])
            ->with('customer_rejected_success', true);
    }

    // =========================
    // ACTION: VERIFY
    // =========================
   public function customerVerify(int $id)
{
    $user = DB::table('users')->where('id', $id)->first();
    if (!$user) {
        return redirect()->route('sa.verifikasi.customer')
            ->with('error', 'User tidak ditemukan.');
    }

    // ✅ update status di USERS aja
    DB::table('users')->where('id', $id)->update([
        'verified_status' => 'terverifikasi',
        'is_banned'       => 0,
        'updated_at'      => now(),
    ]);

    // opsional: kalau row customers ada, bersihkan pesan reject
    DB::table('customers')->where('user_id', $id)->update([
        'verified_status_message' => null,
        'updated_at'              => now(),
    ]);

    return redirect()
        ->route('sa.verifikasi.customer.detail', ['id' => $id])
        ->with('customer_verified_success', true);
}



    // =========================
    // ACTION: BLOCK / UNBLOCK
    // =========================
    public function customerBlock(Request $request, int $id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        abort_if(!$user, 404);

        $new = $user->is_banned ? 0 : 1;
        DB::table('users')->where('id', $id)->update([
            'is_banned'  => $new,
            'updated_at' => now(),
        ]);

        return back()->with('block_success', true);
    }
// =========================
// PESANAN (LIST)
// =========================
 public function transaksi(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');  // proses|selesai|batal
        $date   = $request->query('date');    // YYYY-MM-DD

        $rows = Pesanan::with(['customer', 'mitra'])
            ->when($q, function ($query) use ($q) {
                // ✅ biar OR tidak "lepas" dari query utama, bungkus dalam where(function(){})
                $query->where(function ($w) use ($q) {
                    $w->where('order_no', 'like', "%{$q}%")
                      ->orWhereHas('customer', fn ($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('mitra', fn ($qq) => $qq->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($date, fn ($query) => $query->whereDate('tanggal', $date))
            ->latest('id')
            ->get();

        // ✅ karena view kamu namanya pesanan.blade.php
        return view('superadmin.pages.pesanan', compact('rows', 'q', 'status', 'date'));
    }

    // =========================
    // PESANAN DETAIL
    // =========================
    public function transaksiDetail($id)
    {
        $order = Pesanan::with(['customer', 'mitra'])->findOrFail($id);

        /**
         * ✅ Ambil kendaraan paling aman:
         * - Pesanan menyimpan mitra_id (users.id)
         * - partners menyimpan user_id (users.id)
         * - partner_vehicles menyimpan partner_id (partners.id)
         *
         * Jadi: partners.user_id = pesanan.mitra_id
         */
        $vehicle = DB::table('partner_vehicles as pv')
            ->join('partners as pr', 'pr.id', '=', 'pv.partner_id')
            ->where('pr.user_id', $order->mitra_id)   // <--- ini kunci
            ->orderByDesc('pv.id')
            ->select([
                'pv.id',
                'pv.partner_id',
                'pv.vehicle_type',
                'pv.vehicle_brand',
                'pv.plate_number',
                'pv.color',
                'pv.created_at',
                'pv.updated_at',
            ])
            ->first();

        return view('superadmin.pages.pesanan_detail', compact('order', 'vehicle'));
    }

    // =========================
    // DOWNLOAD PDF - PESANAN
    // =========================
    public function downloadPesananPdf(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $date   = $request->query('date');

        $rows = Pesanan::with(['customer', 'mitra'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('order_no', 'like', "%{$q}%")
                      ->orWhereHas('customer', fn ($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('mitra', fn ($qq) => $qq->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($date, fn ($query) => $query->whereDate('tanggal', $date))
            ->latest('id')
            ->get();

        $pdf = Pdf::loadView('superadmin.pages.pdf.pesanan_pdf', compact('rows', 'q', 'status', 'date'))
            ->setPaper('A4', 'portrait');

        $filename = 'pesanan'
            . ($status ? "-{$status}" : '')
            . ($date ? "-{$date}" : '')
            . '.pdf';

        return $pdf->download($filename);
    }
}
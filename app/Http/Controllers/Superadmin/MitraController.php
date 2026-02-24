<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MitraController extends Controller
{
    private function normStatusExpr(string $col): string
    {
        return "LOWER(TRIM(COALESCE($col,'')))";
    }

    /*
    |--------------------------------------------------------------------------
    | MITRA KENDARAAN - LIST
    |--------------------------------------------------------------------------
    */
    public function mitraKendaraan(Request $request)
    {
        $q = $request->query('q');

        $hasPartnerVehicles = DB::getSchemaBuilder()->hasTable('partner_vehicles');
        $hasVehicles        = DB::getSchemaBuilder()->hasTable('vehicles');
        $hasKendaraans      = DB::getSchemaBuilder()->hasTable('kendaraans');

        $rows = collect();

        if ($hasPartnerVehicles) {
            $query = DB::table('partner_vehicles')
                ->leftJoin('partners', 'partners.id', '=', 'partner_vehicles.partner_id')
                ->leftJoin('users', 'users.id', '=', 'partners.user_id')
                ->select([
                    'partner_vehicles.id as vehicle_id',
                    DB::raw('COALESCE(users.name, partners.id_fullname, "-") as name'),
                    'partner_vehicles.vehicle_type as kendaraan',
                    'partner_vehicles.vehicle_brand as merk',
                    'partner_vehicles.plate_number as plat',
                    'partner_vehicles.color as warna',
                    'partner_vehicles.vehicle_image as image',
                ])
                // ✅ Tambahan FILTER: hanya mitra terverifikasi & tidak dibanned
                ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
                ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['terverifikasi'])
                ->where('users.is_banned', 0);

            if ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('partners.id_fullname', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.plate_number', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.vehicle_brand', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.vehicle_type', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.color', 'like', "%{$q}%");
                });
            }

            $rows = $query->orderByDesc('partner_vehicles.id')->get();

        } elseif ($hasVehicles) {
            $query = DB::table('vehicles')
                ->leftJoin('users', 'users.id', '=', 'vehicles.user_id')
                ->select([
                    'vehicles.id as vehicle_id',
                    'users.name as name',
                    'vehicles.type as kendaraan',
                    'vehicles.brand as merk',
                    'vehicles.plate_number as plat',
                    'vehicles.color as warna',
                    'vehicles.image as image',
                ])
                // ✅ Tambahan FILTER: hanya mitra terverifikasi & tidak dibanned
                ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
                ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['terverifikasi'])
                ->where('users.is_banned', 0);

            if ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('vehicles.plate_number', 'like', "%{$q}%")
                        ->orWhere('vehicles.brand', 'like', "%{$q}%")
                        ->orWhere('vehicles.type', 'like', "%{$q}%")
                        ->orWhere('vehicles.color', 'like', "%{$q}%");
                });
            }

            $rows = $query->orderByDesc('vehicles.id')->get();

        } elseif ($hasKendaraans) {
            $query = DB::table('kendaraans')
                ->leftJoin('users', 'users.id', '=', 'kendaraans.user_id')
                ->select([
                    'kendaraans.id as vehicle_id',
                    'users.name as name',
                    'kendaraans.jenis as kendaraan',
                    'kendaraans.merk as merk',
                    'kendaraans.plat as plat',
                    'kendaraans.warna as warna',
                    'kendaraans.image as image',
                ])
                // ✅ Tambahan FILTER: hanya mitra terverifikasi & tidak dibanned
                ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
                ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['terverifikasi'])
                ->where('users.is_banned', 0);

            if ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('kendaraans.plat', 'like', "%{$q}%")
                        ->orWhere('kendaraans.merk', 'like', "%{$q}%")
                        ->orWhere('kendaraans.jenis', 'like', "%{$q}%")
                        ->orWhere('kendaraans.warna', 'like', "%{$q}%");
                });
            }

            $rows = $query->orderByDesc('kendaraans.id')->get();
        }

        $rows = collect($rows)->map(function ($r) {
            $img = $r->image ?: 'https://via.placeholder.com/40';

            return [
                'img' => str_starts_with($img, 'http') ? $img : asset($img),
                'nama' => $r->name ?? '-',
                'kendaraan' => $r->kendaraan ?? '-',
                'merk' => $r->merk ?? '-',
                'plat' => $r->plat ?? '-',
                'warna' => $r->warna ?? '-',
                'vehicle_id' => $r->vehicle_id ?? null,
            ];
        })->toArray();

        return view('superadmin.pages.mitra_kendaraan', compact('rows', 'q'));
    }

    public function mitraKendaraanDetail(int $id)
{
    $hasPartnerVehicles = DB::getSchemaBuilder()->hasTable('partner_vehicles');
    $hasVehicles        = DB::getSchemaBuilder()->hasTable('vehicles');
    $hasKendaraans      = DB::getSchemaBuilder()->hasTable('kendaraans');

    $data = null;
    $source = null;

    if ($hasPartnerVehicles) {
        $data = DB::table('partner_vehicles')
            ->leftJoin('partners', 'partners.id', '=', 'partner_vehicles.partner_id')
            ->leftJoin('users', 'users.id', '=', 'partners.user_id')
            ->select([
                'partner_vehicles.id as vehicle_id',
                DB::raw('COALESCE(users.name, partners.id_fullname, "-") as name'),

                // kendaraan
                'partner_vehicles.vehicle_type as kendaraan',
                'partner_vehicles.vehicle_brand as merk',
                'partner_vehicles.plate_number as plat',
                'partner_vehicles.color as warna',
                'partner_vehicles.vehicle_image as image',

                // ✅ STNK dari partners
                'partners.stnk_number as stnk_number',
                'partners.stnk_expired_at as stnk_expired_at',
                'partners.stnk_image as stnk_image',
            ])
            ->where('partner_vehicles.id', $id)
            ->first();

        $source = 'partner_vehicles';

    } elseif ($hasVehicles) {
        $data = DB::table('vehicles')
            ->leftJoin('users', 'users.id', '=', 'vehicles.user_id')
            // optional: kalau tabel vehicles punya relasi ke partners via user_id
            ->leftJoin('partners', 'partners.user_id', '=', 'vehicles.user_id')
            ->select([
                'vehicles.id as vehicle_id',
                'users.name as name',

                // kendaraan
                'vehicles.type as kendaraan',
                'vehicles.brand as merk',
                'vehicles.plate_number as plat',
                'vehicles.color as warna',
                'vehicles.image as image',

                // ✅ STNK dari partners (kalau ada)
                'partners.stnk_number as stnk_number',
                'partners.stnk_expired_at as stnk_expired_at',
                'partners.stnk_image as stnk_image',
            ])
            ->where('vehicles.id', $id)
            ->first();

        $source = 'vehicles';

    } elseif ($hasKendaraans) {
        $data = DB::table('kendaraans')
            ->leftJoin('users', 'users.id', '=', 'kendaraans.user_id')
            ->leftJoin('partners', 'partners.user_id', '=', 'kendaraans.user_id')
            ->select([
                'kendaraans.id as vehicle_id',
                'users.name as name',

                // kendaraan
                'kendaraans.jenis as kendaraan',
                'kendaraans.merk as merk',
                'kendaraans.plat as plat',
                'kendaraans.warna as warna',
                'kendaraans.image as image',

                // ✅ STNK dari partners (kalau ada)
                'partners.stnk_number as stnk_number',
                'partners.stnk_expired_at as stnk_expired_at',
                'partners.stnk_image as stnk_image',
            ])
            ->where('kendaraans.id', $id)
            ->first();

        $source = 'kendaraans';
    }

    abort_if(!$data, 404);

    // foto kendaraan (punyamu)
    $img = $data->image ?: 'https://via.placeholder.com/400x260';
    if (str_starts_with($img, 'http')) {
        $data->image = $img;
    } else {
        $img = ltrim($img, '/');
        if (!str_starts_with($img, 'storage/')) {
            $img = 'storage/' . preg_replace('#^(public/)#', '', $img);
        }
        $data->image = asset($img);
    }

    // ✅ rapikan stnk_image jadi URL yang valid juga (biar bisa tampil)
    if (!empty($data->stnk_image)) {
        $stnkImg = $data->stnk_image;
        if (str_starts_with($stnkImg, 'http')) {
            $data->stnk_image = $stnkImg;
        } else {
            $stnkImg = ltrim($stnkImg, '/');
            if (!str_starts_with($stnkImg, 'storage/')) {
                $stnkImg = 'storage/' . preg_replace('#^(public/)#', '', $stnkImg);
            }
            $data->stnk_image = asset($stnkImg);
        }
    }

    return view('superadmin.pages.mitra_kendaraan_detail', compact('data', 'source'));
}

    public function mitraKendaraanUpdate(Request $request, int $id)
    {
        $validated = $request->validate([
            'kendaraan' => 'required|string|max:50',
            'merk'      => 'required|string|max:50',
            'plat'      => 'required|string|max:20',
            'warna'     => 'required|string|max:30',
        ]);

        $hasPartnerVehicles = DB::getSchemaBuilder()->hasTable('partner_vehicles');
        $hasVehicles        = DB::getSchemaBuilder()->hasTable('vehicles');
        $hasKendaraans      = DB::getSchemaBuilder()->hasTable('kendaraans');

        $updated = 0;

        if ($hasPartnerVehicles) {
            $updated = DB::table('partner_vehicles')->where('id', $id)->update([
                'vehicle_type'  => $validated['kendaraan'],
                'vehicle_brand' => $validated['merk'],
                'plate_number'  => $validated['plat'],
                'color'         => $validated['warna'],
            ]);
        } elseif ($hasVehicles) {
            $updated = DB::table('vehicles')->where('id', $id)->update([
                'type'         => $validated['kendaraan'],
                'brand'        => $validated['merk'],
                'plate_number' => $validated['plat'],
                'color'        => $validated['warna'],
            ]);
        } elseif ($hasKendaraans) {
            $updated = DB::table('kendaraans')->where('id', $id)->update([
                'jenis' => $validated['kendaraan'],
                'merk'  => $validated['merk'],
                'plat'  => $validated['plat'],
                'warna' => $validated['warna'],
            ]);
        }

        abort_if($updated === 0, 404, 'Data kendaraan tidak ditemukan / tidak berubah');

        return redirect()
            ->route('sa.mitra.kendaraan.detail', ['id' => $id])
            ->with('success', 'Data kendaraan berhasil disimpan.');
    }

    public function downloadDaftarKendaraanMitraPdf(Request $request)
    {
        $q = $request->query('q');

        $hasPartnerVehicles = DB::getSchemaBuilder()->hasTable('partner_vehicles');
        $hasVehicles        = DB::getSchemaBuilder()->hasTable('vehicles');
        $hasKendaraans      = DB::getSchemaBuilder()->hasTable('kendaraans');

        if ($hasPartnerVehicles) {
            $query = DB::table('partner_vehicles')
                ->leftJoin('partners', 'partners.id', '=', 'partner_vehicles.partner_id')
                ->leftJoin('users', 'users.id', '=', 'partners.user_id')
                ->select([
                    DB::raw('COALESCE(users.name, partners.id_fullname, "-") as name'),
                    'partner_vehicles.vehicle_type as kendaraan',
                    'partner_vehicles.vehicle_brand as merk',
                    'partner_vehicles.plate_number as plat',
                    'partner_vehicles.color as warna',
                ])
                // ✅ Tambahan FILTER: hanya mitra terverifikasi & tidak dibanned
                ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
                ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['terverifikasi'])
                ->where('users.is_banned', 0);

            if (!empty($q)) {
                $query->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('partners.id_fullname', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.plate_number', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.vehicle_brand', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.vehicle_type', 'like', "%{$q}%")
                        ->orWhere('partner_vehicles.color', 'like', "%{$q}%");
                });
            }

            $rows = $query->orderByDesc('partner_vehicles.id')->get();

        } elseif ($hasVehicles) {
            $query = DB::table('vehicles')
                ->leftJoin('users', 'users.id', '=', 'vehicles.user_id')
                ->select([
                    DB::raw('COALESCE(users.name, "-") as name'),
                    'vehicles.type as kendaraan',
                    'vehicles.brand as merk',
                    'vehicles.plate_number as plat',
                    'vehicles.color as warna',
                ])
                // ✅ Tambahan FILTER: hanya mitra terverifikasi & tidak dibanned
                ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
                ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['terverifikasi'])
                ->where('users.is_banned', 0);

            if (!empty($q)) {
                $query->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('vehicles.plate_number', 'like', "%{$q}%")
                        ->orWhere('vehicles.brand', 'like', "%{$q}%")
                        ->orWhere('vehicles.type', 'like', "%{$q}%")
                        ->orWhere('vehicles.color', 'like', "%{$q}%");
                });
            }

            $rows = $query->orderByDesc('vehicles.id')->get();

        } elseif ($hasKendaraans) {
            $query = DB::table('kendaraans')
                ->leftJoin('users', 'users.id', '=', 'kendaraans.user_id')
                ->select([
                    DB::raw('COALESCE(users.name, "-") as name'),
                    'kendaraans.jenis as kendaraan',
                    'kendaraans.merk as merk',
                    'kendaraans.plat as plat',
                    'kendaraans.warna as warna',
                ])
                // ✅ Tambahan FILTER: hanya mitra terverifikasi & tidak dibanned
                ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
                ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['terverifikasi'])
                ->where('users.is_banned', 0);

            if (!empty($q)) {
                $query->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('kendaraans.plat', 'like', "%{$q}%")
                        ->orWhere('kendaraans.merk', 'like', "%{$q}%")
                        ->orWhere('kendaraans.jenis', 'like', "%{$q}%")
                        ->orWhere('kendaraans.warna', 'like', "%{$q}%");
                });
            }

            $rows = $query->orderByDesc('kendaraans.id')->get();

        } else {
            $rows = collect();
        }

        $pdf = Pdf::loadView(
            'superadmin.pages.pdf.daftar_kendaraan_mitra_pdf',
            compact('rows', 'q')
        )->setPaper('A4', 'portrait');

        return $pdf->download('daftar-kendaraan-mitra.pdf');
    }

    // =======================
    // VERIFIKASI MITRA (LIST)
    // =======================
    public function mitra()
    {
        $rows = DB::table('users')
            ->leftJoin('partners', 'partners.user_id', '=', 'users.id')
            ->select([
                DB::raw('COALESCE(partners.id, 0) as partner_id'),
                'users.id as id',
                'users.name as name',
                'users.email as email',
                'users.phone_number as phone_number',
                'users.verified_status as verified_status',
                DB::raw("COALESCE(partners.layanan,'-') as layanan"),
                'users.is_banned as is_banned',
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
            ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['pengajuan'])
            ->where('users.is_banned', 0)
            ->orderByDesc('users.id')
            ->get();

        return view('superadmin.pages.verifikasi_mitra', compact('rows'));
    }

    // =======================
    // DETAIL VERIFIKASI MITRA
    // =======================
    public function mitraDetail(int $id)
    {
        $mitra = DB::table('users')
            ->leftJoin('partners', 'partners.user_id', '=', 'users.id')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'users.role',
                'users.gender',
                'users.birth_place',
                'users.birth_date',
                'users.is_banned',
                'users.image',
                'users.verified_status',
                DB::raw("COALESCE(partners.layanan,'-') as layanan"),
                'partners.id_fullname',
                'partners.id_number',
                'partners.id_birth_date',
                'partners.id_image',
                'partners.dl_fullname',
                'partners.dl_number',
                'partners.dl_birth_date',
                'partners.dl_image',
                'partners.stnk_number',
                'partners.stnk_expired_at',
                'partners.stnk_image',
                'partners.skck_number',
                'partners.skck_expired_at',
                'partners.skck_image',
                'partners.bank_account_name',
                'partners.bank_account_number',
                'partners.bank_name',
                'partners.bank_book_image',
                'partners.verified_status_message as partner_verified_status_message',
                'partners.id as partner_id',
            ])
            ->where('users.id', $id)
            ->first();

        abort_if(!$mitra, 404);

        $vehicle = null;

        $hasPartnerVehicles = DB::getSchemaBuilder()->hasTable('partner_vehicles');
        $hasVehicles        = DB::getSchemaBuilder()->hasTable('vehicles');
        $hasKendaraans      = DB::getSchemaBuilder()->hasTable('kendaraans');

        if ($hasPartnerVehicles) {
            $vehicle = DB::table('partner_vehicles')
                ->leftJoin('partners', 'partners.id', '=', 'partner_vehicles.partner_id')
                ->where('partners.user_id', $mitra->id)
                ->select([
                    'partner_vehicles.*',
                    'partner_vehicles.plate_number as plate_number',
                    'partner_vehicles.vehicle_brand as vehicle_brand',
                    'partner_vehicles.vehicle_type as vehicle_type',
                    'partner_vehicles.vehicle_image as vehicle_image',
                ])
                ->orderByDesc('partner_vehicles.id')
                ->first();
        } elseif ($hasVehicles) {
            $vehicle = DB::table('vehicles')
                ->where('user_id', $mitra->id)
                ->orderByDesc('id')
                ->first();
        } elseif ($hasKendaraans) {
            $vehicle = DB::table('kendaraans')
                ->where('user_id', $mitra->id)
                ->orderByDesc('id')
                ->first();
        }

        return view('superadmin.pages.mitra_detail', compact('mitra', 'vehicle'));
    }

    public function downloadMitraPdf(Request $request)
    {
        $date = $request->query('date');

        $query = DB::table('users')
            ->leftJoin('partners', 'partners.user_id', '=', 'users.id')
            ->select([
                'users.id as id',
                'users.name as name',
                'users.email as email',
                'users.phone_number as phone_number',
                'users.verified_status as verified_status',
                'users.is_banned as is_banned',
                DB::raw('COALESCE(partners.created_at, users.created_at) as created_at'),
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
            ->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", ['pengajuan'])
            ->where('users.is_banned', 0)
            ->orderByDesc('users.id');

        if ($date) {
            $query->whereDate(DB::raw('COALESCE(partners.created_at, users.created_at)'), $date);
        }

        $rows = $query->get();
        $periode = $date ?: now()->format('Y-m-d');

        $pdf = Pdf::loadView('superadmin.pages.pdf.verifikasi_mitra_pdf', [
            'rows' => $rows,
            'periode' => $periode,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('verifikasi-mitra-' . str_replace('-', '', $periode) . '.pdf');
    }

    public function edit(int $id)
    {
        $mitra = DB::table('users')
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
                'users.verified_status_message',
                'users.is_banned',
            ])
            ->where('users.id', $id)
            ->whereRaw("LOWER(TRIM(COALESCE(users.role,''))) = 'mitra'")
            ->first();

        abort_if(!$mitra, 404);

        return view('superadmin.pages.mitra_edit', compact('mitra'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:30',
            'gender' => 'nullable|string|max:30',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'verified_status' => 'nullable|in:pengajuan,terverifikasi,ditolak,diblok',
            'verified_status_message' => 'nullable|string|max:255',
        ]);

        $exists = DB::table('users')
            ->where('id', $id)
            ->whereRaw("LOWER(TRIM(COALESCE(role,''))) = 'mitra'")
            ->exists();

        abort_if(!$exists, 404);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'verified_status_message' => $request->verified_status_message,
            'updated_at' => now(),
        ];

        if ($request->filled('verified_status')) {
            if ($request->verified_status === 'diblok') {
                $data['is_banned'] = 1;
            } else {
                $data['verified_status'] = $request->verified_status;
                $data['is_banned'] = 0;
            }
        }

        DB::table('users')->where('id', $id)->update($data);

        return redirect()
            ->route('sa.mitra.detail', ['id' => $id])
            ->with('success', 'Mitra berhasil diupdate.');
    }

    public function mitraVerify(int $id)
    {
        $affected = DB::table('users')
            ->where('id', $id)
            ->update([
                'verified_status' => 'terverifikasi',
                'is_banned' => 0,
                'updated_at' => now(),
            ]);

        abort_if($affected === 0, 400, 'Update gagal');

        return redirect()
            ->route('sa.verifikasi.mitra.detail', ['id' => $id])
            ->with('mitra_verified_success', true);
    }

    public function mitraReject(Request $request, int $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        DB::table('users')->where('id', $id)->update([
            'verified_status' => 'ditolak',
            'is_banned' => 0,
        ]);

        DB::table('partners')->where('user_id', $id)->update([
            'verified_status_message' => $request->input('reason'),
        ]);

        return redirect()
            ->route('sa.verifikasi.mitra.detail', ['id' => $id])
            ->with('mitra_rejected_success', true);
    }

    public function mitraBlock(Request $request, int $id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        abort_if(!$user, 404);

        $isBannedNow = (int) $user->is_banned;
        $newIsBanned = $isBannedNow === 1 ? 0 : 1;

        DB::table('users')->where('id', $id)->update([
            'is_banned'  => $newIsBanned,
            'updated_at' => now(),
        ]);

        return back()->with('block_success', true);
    }

    public function daftarMitra(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $date   = $request->query('date');

        $query = DB::table('users')
            ->leftJoin('partners', 'partners.user_id', '=', 'users.id')
            ->select([
                'users.id as id',
                DB::raw('COALESCE(users.name, "-") as name'),
                DB::raw('COALESCE(users.email, "-") as email'),
                DB::raw('COALESCE(users.phone_number, "-") as phone_number'),
                'users.verified_status as verified_status',
                'users.is_banned as is_banned',
                DB::raw("COALESCE(partners.layanan,'-') as layanan"),
                DB::raw('COALESCE(partners.created_at, users.created_at) as created_at'),
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra']);

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
            } else {
                $query->where('users.is_banned', 0);
                $query->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", [strtolower($status)]);
            }
        }

        if (!empty($date)) {
            $query->whereDate(DB::raw('COALESCE(partners.created_at, users.created_at)'), $date);
        }

        $rows = $query->orderByDesc('users.id')->get();

        return view('superadmin.pages.daftar_mitra', compact('rows', 'q', 'status', 'date'));
    }

    public function downloadDaftarMitraPdf(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');
        $date   = $request->query('date');

        $query = DB::table('users')
            ->leftJoin('partners', 'partners.user_id', '=', 'users.id')
            ->select([
                'users.id as id',
                DB::raw('COALESCE(users.name, "-") as name'),
                DB::raw('COALESCE(users.email, "-") as email'),
                DB::raw('COALESCE(users.phone_number, "-") as phone_number'),
                'users.verified_status as verified_status',
                'users.is_banned as is_banned',
                DB::raw('COALESCE(partners.created_at, users.created_at) as created_at'),
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra']);

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
            } else {
                $query->where('users.is_banned', 0);
                $query->whereRaw($this->normStatusExpr('users.verified_status') . " = ?", [strtolower($status)]);
            }
        }

        if (!empty($date)) {
            $query->whereDate(DB::raw('COALESCE(partners.created_at, users.created_at)'), $date);
        }

        $rows = $query->orderByDesc('users.id')->get();

        $pdf = Pdf::loadView(
            'superadmin.pages.pdf.daftar_mitra_pdf',
            compact('rows', 'q', 'status', 'date')
        )->setPaper('A4', 'portrait');

        $filename = 'daftar-mitra'
            . ($status ? "-{$status}" : '')
            . ($date ? "-{$date}" : '')
            . '.pdf';

        return $pdf->download($filename);
    }

    public function mitraBlokir(Request $request)
    {
        $q = $request->query('q');

        $query = DB::table('users')
            ->leftJoin('partners', 'partners.user_id', '=', 'users.id')
            ->select([
                'users.id as id',
                'users.name as name',
                'users.email as email',
                'users.phone_number as phone_number',
                DB::raw("COALESCE(partners.layanan,'-') as layanan"),
                'users.is_banned as is_banned',
                'users.verified_status as verified_status',
            ])
            ->whereRaw($this->normStatusExpr('users.role') . " = ?", ['mitra'])
            ->where('users.is_banned', 1);

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%")
                    ->orWhere('users.phone_number', 'like', "%{$q}%")
                    ->orWhere('users.id', 'like', "%{$q}%");
            });
        }

        $rows = $query->orderByDesc('users.id')->get();

        return view('superadmin.pages.mitra_blokir', compact('rows', 'q'));
    }
}
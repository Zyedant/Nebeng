@extends('superadmin.layouts.app')

@section('content')
@php
    $avatar = !empty($mitra->user_image ?? null) ? asset($mitra->user_image) : null;
    $displayId = str_pad((int)($mitra->id ?? 0), 6, '0', STR_PAD_LEFT);
    $s = $mitra->is_banned ? 'diblok' : strtolower($mitra->verified_status ?? '');

    /*
    |--------------------------------------------------------------------------
    | TAMBAHAN: Data STNK / SKCK / R.Bank (sesuaikan nama field DB kalau beda)
    |--------------------------------------------------------------------------
    */

    // ===== STNK =====
    $stnk_plate   = $mitra->stnk_plate ?? null;          // contoh: B 9090 AM
    $stnk_merk    = $mitra->stnk_merk ?? null;           // contoh: SKYLINE GT-R
    $stnk_type    = $mitra->stnk_type ?? null;           // contoh: R34
    $stnk_expired = $mitra->stnk_expired_date ?? null;   // YYYY-MM-DD
    $stnk_image   = !empty($mitra->stnk_image ?? null) ? asset($mitra->stnk_image) : null;

    // ===== SKCK =====
    $skck_number  = $mitra->skck_number ?? null;
    $skck_expired = $mitra->skck_expired_date ?? null;   // YYYY-MM-DD
    $skck_image   = !empty($mitra->skck_image ?? null) ? asset($mitra->skck_image) : null;

    // ===== R. BANK =====
    $bank_name    = $mitra->bank_name ?? null;           // contoh: BNI
    $bank_number  = $mitra->bank_account_number ?? null; // contoh: 1234567890
    $bank_holder  = $mitra->bank_account_holder ?? ($mitra->name ?? null);
    $bank_expired = $mitra->bank_expired_date ?? null;   // kalau tidak ada, bisa hapus inputnya
    $bank_image   = !empty($mitra->bankbook_image ?? null) ? asset($mitra->bankbook_image) : null;
@endphp

<div class="space-y-6 font-['Urbanist']">

    <div class="flex items-center gap-3">
        <a href="{{ route('sa.mitra.detail', ['id' => $mitra->id]) }}" class="text-slate-600 hover:text-slate-900">←</a>
        <div class="text-[22px] font-semibold text-slate-900">Edit Data Mitra</div>
    </div>

    <div class="bg-[#EAF3FF] rounded-2xl border border-slate-100 p-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full overflow-hidden bg-white flex items-center justify-center">
                @if($avatar)
                    <img src="{{ $avatar }}" class="w-full h-full object-cover" alt="avatar">
                @else
                    <svg class="w-10 h-10 text-slate-300" viewBox="0 0 24 24" fill="none">
                        <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M20 21a8 8 0 1 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                @endif
            </div>

            <div class="space-y-0.5">
                <div class="text-[14px] font-semibold text-slate-900">{{ $mitra->name ?? '—' }}</div>
                <div class="text-[12px] text-blue-700 font-semibold">{{ $displayId }}</div>
            </div>
        </div>
    </div>

    {{-- FORM EDIT --}}
    <form method="POST" action="{{ route('sa.mitra.update', ['id' => $mitra->id]) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi User</div>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Nama</div>
                    <input name="name" value="{{ old('name', $mitra->name) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Email</div>
                    <input name="email" type="email" value="{{ old('email', $mitra->email) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">No. Tlp</div>
                    <input name="phone_number" value="{{ old('phone_number', $mitra->phone_number) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Jenis Kelamin</div>
                    <select name="gender"
                            class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300">
                        @php $g = strtolower((string) old('gender', $mitra->gender ?? '')); @endphp
                        <option value="">-</option>
                        <option value="Laki-laki" {{ $g === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $g === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Tempat Lahir</div>
                    <input name="birth_place" value="{{ old('birth_place', $mitra->birth_place) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Tanggal Lahir</div>
                    <input name="birth_date" type="date" value="{{ old('birth_date', $mitra->birth_date) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="text-[14px] font-semibold text-slate-900 mb-4">Status Verifikasi</div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Status</div>
                    <select name="verified_status"
                            class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300">
                        @php $sv = strtolower((string) old('verified_status', $mitra->verified_status)); @endphp
                        <option value="">(Tidak diubah)</option>
                        <option value="pengajuan" {{ $sv === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                        <option value="terverifikasi" {{ $sv === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="ditolak" {{ $sv === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="diblok" {{ $sv === 'diblok' ? 'selected' : '' }}>Diblok</option>
                    </select>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Catatan</div>
                    <input name="verified_status_message" value="{{ old('verified_status_message', $mitra->verified_status_message) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>
            </div>
        </div>

        {{-- ===================== TAMBAHAN FORM DOKUMEN ===================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- kiri: form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- STNK --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi STNK</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Nomor Plat Kendaraan</div>
                            <input name="stnk_plate" value="{{ old('stnk_plate', $stnk_plate) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">MERK</div>
                            <input name="stnk_merk" value="{{ old('stnk_merk', $stnk_merk) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">TYPE</div>
                            <input name="stnk_type" value="{{ old('stnk_type', $stnk_type) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Masa Berlaku</div>
                            <input name="stnk_expired_date" type="date" value="{{ old('stnk_expired_date', $stnk_expired) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>
                    </div>
                </div>

                {{-- SKCK --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi SKCK</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Nomor SKCK</div>
                            <input name="skck_number" value="{{ old('skck_number', $skck_number) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Masa Berlaku</div>
                            <input name="skck_expired_date" type="date" value="{{ old('skck_expired_date', $skck_expired) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>
                    </div>
                </div>

                {{-- R. BANK --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                    <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi R. Bank</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Nama Bank</div>
                            <input name="bank_name" value="{{ old('bank_name', $bank_name) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Nomor Rekening</div>
                            <input name="bank_account_number" value="{{ old('bank_account_number', $bank_number) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Nama Pemilik Rekening</div>
                            <input name="bank_account_holder" value="{{ old('bank_account_holder', $bank_holder) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Masa Berlaku (Opsional)</div>
                            <input name="bank_expired_date" type="date" value="{{ old('bank_expired_date', $bank_expired) }}"
                                   class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                        </div>
                    </div>
                </div>

            </div>

            {{-- kanan: preview dokumen --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                    <div class="text-[14px] font-semibold text-slate-900">Foto STNK</div>
                    <div class="mt-4 flex items-center justify-center">
                        @if($stnk_image)
                            <img src="{{ $stnk_image }}" class="w-[220px] h-[140px] object-cover rounded-xl border border-slate-200" alt="STNK">
                        @else
                            <div class="text-slate-400 text-sm">Belum ada foto STNK.</div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                    <div class="text-[14px] font-semibold text-slate-900">Foto SKCK</div>
                    <div class="mt-4 flex items-center justify-center">
                        @if($skck_image)
                            <img src="{{ $skck_image }}" class="w-[220px] h-[140px] object-cover rounded-xl border border-slate-200" alt="SKCK">
                        @else
                            <div class="text-slate-400 text-sm">Belum ada foto SKCK.</div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                    <div class="text-[14px] font-semibold text-slate-900">Foto Buku Rekening</div>
                    <div class="mt-4 flex items-center justify-center">
                        @if($bank_image)
                            <img src="{{ $bank_image }}" class="w-[220px] h-[140px] object-cover rounded-xl border border-slate-200" alt="Buku Rekening">
                        @else
                            <div class="text-slate-400 text-sm">Belum ada foto buku rekening.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- BUTTONS (tetap) --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('sa.mitra.detail', ['id' => $mitra->id]) }}"
               class="h-10 px-5 rounded-lg border border-slate-200 bg-white text-slate-700 text-[12px]
                      flex items-center justify-center hover:bg-slate-50 transition">
                Batal
            </a>

            <button type="submit"
                    class="h-10 px-6 rounded-lg bg-[#0B3A82] text-white text-[12px] font-semibold hover:opacity-95 transition">
                Simpan
            </button>
        </div>

    </form>
</div>
@endsection

@extends('superadmin.layouts.app')

@section('content')
@php
    $isBanned = (int) ($mitra->is_banned ?? 0) === 1;
    $verified = strtolower(trim((string) ($mitra->verified_status ?? '')));

    // tombol aksi hanya kalau masih pengajuan dan belum diblokir
    $showActions = (!$isBanned) && ($verified === 'pengajuan');

    // status badge
    if ($isBanned || $verified === 'diblok') {
        $statusText  = 'DIBLOKIR';
        $statusClass = 'bg-red-100 text-red-700';
    } elseif ($verified === 'terverifikasi') {
        $statusText  = 'TERVERIFIKASI';
        $statusClass = 'bg-emerald-100 text-emerald-700';
    } elseif ($verified === 'ditolak') {
        $statusText  = 'DITOLAK';
        $statusClass = 'bg-red-100 text-red-700';
    } else {
        $statusText  = 'PENGAJUAN';
        $statusClass = 'bg-amber-400 text-white';
    }

    // avatar
    $avatar = !empty($mitra->image) ? asset($mitra->image) : null;

    // layanan
    $layanan = $mitra->layanan ?? '-';

    $displayId = str_pad((int)($mitra->id ?? 0), 6, '0', STR_PAD_LEFT);

    // Data pribadi
    $namaLengkap = $mitra->name ?? '—';
    $email       = $mitra->email ?? '—';
    $tempatLahir = $mitra->birth_place ?? '—';
    $tglLahir    = $mitra->birth_date ?? '—';
    $gender      = $mitra->gender ?? '—';
    $noTlp       = $mitra->phone_number ?? '—';

    // KTP
    $ktpNama = $mitra->id_fullname ?? $mitra->name ?? '—';
    $ktpNik  = $mitra->id_number ?? '—';
    $ktpTgl  = $mitra->id_birth_date ?? ($mitra->birth_date ?? '—');

    // SIM
    $simNama = $mitra->dl_fullname ?? $mitra->name ?? '—';
    $simNo   = $mitra->dl_number ?? '—';
    $simTgl  = $mitra->dl_birth_date ?? ($mitra->birth_date ?? '—');

    // Foto KTP/SIM
    $ktpImg = !empty($mitra->id_image) ? asset($mitra->id_image) : null;
    $simImg = !empty($mitra->dl_image) ? asset($mitra->dl_image) : null;

    // Kendaraan (support berbagai schema)
    $plate = $vehicle->plate_number ?? $vehicle->plat ?? '—';
    $merk  = $vehicle->vehicle_brand ?? $vehicle->brand ?? $vehicle->merk ?? '—';
    $type  = $vehicle->vehicle_type ?? $vehicle->type ?? $vehicle->jenis ?? '—';
    $vehImg = $vehicle->vehicle_image ?? $vehicle->image ?? null;

    // STNK
    $stnkNumber = $mitra->stnk_number ?? '—';
    $stnkExpiry = $mitra->stnk_expired_at ?? '—';
    $stnkImg    = !empty($mitra->stnk_image) ? asset($mitra->stnk_image) : null;

    // SKCK
    $skckNumber = $mitra->skck_number ?? '—';
    $skckExpiry = $mitra->skck_expired_at ?? '—';
    $skckImg    = !empty($mitra->skck_image) ? asset($mitra->skck_image) : null;

    // BANK
    $bankName   = $mitra->bank_name ?? '—';
    $bankAccNo  = $mitra->bank_account_number ?? '—';
    $bankAccNm  = $mitra->bank_account_name ?? '—';
    $bankImg    = !empty($mitra->bank_book_image) ? asset($mitra->bank_book_image) : null;

    $reasons = $reasons ?? [
        'Tidak Memenuhi Persyaratan Kendaraan',
        'Ketidaksesuaian Data Pengemudi',
        'Dokumen Kendaraan Tidak Valid',
        'Riwayat Pengemudi Tidak Memenuhi Kriteria',
        'Kendaraan Tidak Layak Operasi',
        'Penolakan Terhadap Aturan dan Kebijakan Aplikasi',
        'Indikasi Penipuan atau Kecurangan',
        'Lainnya',
    ];

    $backToListUrl = route('sa.verifikasi.mitra');
@endphp

<div class="space-y-6 font-['Urbanist']">

    <div class="flex items-center gap-3">
        <a href="{{ route('sa.verifikasi.mitra') }}" class="text-slate-600 hover:text-slate-900">←</a>
        <div class="text-[22px] font-semibold text-slate-900">Detail Data Mitra</div>
    </div>

    {{-- Header --}}
    <div class="bg-[#EAF3FF] rounded-2xl border border-slate-100 p-5">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full overflow-hidden bg-white flex items-center justify-center">
                    @if($avatar)
                        <img src="{{ $avatar }}" class="w-full h-full object-cover" alt="avatar">
                    @else
                        <svg class="w-10 h-10 text-slate-300" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M20 21a8 8 0 1 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    @endif
                </div>

                <div class="space-y-0.5">
                    <div class="text-[14px] font-semibold text-slate-900">{{ $mitra->name ?? '—' }}</div>
                    <div class="text-[12px] text-slate-500">{{ $layanan }}</div>
                    <div class="text-[12px] text-blue-700 font-semibold">{{ $displayId }}</div>

                    <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col items-end gap-2">
                <a href="{{ route('sa.mitra.edit', ['id' => $mitra->id]) }}"
                   class="h-9 px-4 rounded-full bg-white/80 border border-slate-200 text-slate-700 text-[12px]
                          flex items-center gap-2 hover:bg-white transition">
                    Edit
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5Z"
                              stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KIRI (Form info) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Informasi Pribadi --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi Pribadi</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nama Lengkap</div>
                        <input disabled value="{{ $namaLengkap }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Email</div>
                        <input disabled value="{{ $email }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Tempat Lahir</div>
                        <input disabled value="{{ $tempatLahir }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Tanggal Lahir</div>
                        <input disabled value="{{ $tglLahir }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Jenis Kelamin</div>
                        <input disabled value="{{ $gender }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">No. Tlp</div>
                        <input disabled value="{{ $noTlp }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                </div>
            </div>

            {{-- KTP --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi KTP</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nama Lengkap</div>
                        <input disabled value="{{ $ktpNama }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">NIK</div>
                        <input disabled value="{{ $ktpNik }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Tanggal Lahir</div>
                        <input disabled value="{{ $ktpTgl }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Jenis Kelamin</div>
                        <input disabled value="{{ $gender }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                </div>
            </div>

            {{-- SIM --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi SIM</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nama Lengkap</div>
                        <input disabled value="{{ $simNama }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nomor SIM</div>
                        <input disabled value="{{ $simNo }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Tanggal Lahir</div>
                        <input disabled value="{{ $simTgl }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Jenis Kelamin</div>
                        <input disabled value="{{ $gender }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                </div>
            </div>

            {{-- STNK (kendaraan) --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi STNK</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nomor Plat Kendaraan</div>
                        <input disabled value="{{ $plate }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Merk</div>
                        <input disabled value="{{ $merk }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Type</div>
                        <input disabled value="{{ $type }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Masa Berlaku</div>
                        <input disabled value="{{ $stnkExpiry }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>

                    <div class="md:col-span-2">
                        <div class="text-[12px] text-slate-600 mb-1">Nomor STNK</div>
                        <input disabled value="{{ $stnkNumber }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                </div>
            </div>

            {{-- SKCK --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi SKCK</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nomor SKCK</div>
                        <input disabled value="{{ $skckNumber }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Masa Berlaku</div>
                        <input disabled value="{{ $skckExpiry }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                </div>
            </div>

            {{-- BANK --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi R. Bank</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nama Bank</div>
                        <input disabled value="{{ $bankName }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">Nomor Rekening</div>
                        <input disabled value="{{ $bankAccNo }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-[12px] text-slate-600 mb-1">Nama Pemilik Rekening</div>
                        <input disabled value="{{ $bankAccNm }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                </div>
            </div>

        </div>

        {{-- KANAN (Foto dokumen) --}}
        <div class="space-y-6">

            {{-- Foto KTP --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900">Foto KTP</div>
                <div class="mt-4 flex items-center justify-center">
                    @if($ktpImg)
                        <img src="{{ $ktpImg }}" class="w-full max-w-[360px] h-[170px] object-cover rounded-xl border border-slate-200" alt="KTP">
                    @else
                        <div class="text-slate-400 text-sm">Belum ada foto KTP.</div>
                    @endif
                </div>
            </div>

            {{-- Foto SIM --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900">Foto SIM</div>
                <div class="mt-4 flex items-center justify-center">
                    @if($simImg)
                        <img src="{{ $simImg }}" class="w-full max-w-[360px] h-[170px] object-cover rounded-xl border border-slate-200" alt="SIM">
                    @else
                        <div class="text-slate-400 text-sm">Belum ada foto SIM.</div>
                    @endif
                </div>
            </div>

            {{-- Foto STNK --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900">Foto STNK</div>
                <div class="mt-4 flex items-center justify-center">
                    @if($stnkImg)
                        <img src="{{ $stnkImg }}" class="w-full max-w-[360px] h-[170px] object-cover rounded-xl border border-slate-200" alt="STNK">
                    @else
                        <div class="text-slate-400 text-sm">Belum ada foto STNK.</div>
                    @endif
                </div>
            </div>

            {{-- Foto SKCK --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900">Foto SKCK</div>
                <div class="mt-4 flex items-center justify-center">
                    @if($skckImg)
                        <img src="{{ $skckImg }}" class="w-full max-w-[360px] h-[170px] object-cover rounded-xl border border-slate-200" alt="SKCK">
                    @else
                        <div class="text-slate-400 text-sm">Belum ada foto SKCK.</div>
                    @endif
                </div>
            </div>

            {{-- Foto Buku Rekening --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900">Foto Buku Rekening</div>
                <div class="mt-4 flex items-center justify-center">
                    @if($bankImg)
                        <img src="{{ $bankImg }}" class="w-full max-w-[360px] h-[170px] object-cover rounded-xl border border-slate-200" alt="Buku Rekening">
                    @else
                        <div class="text-slate-400 text-sm">Belum ada foto buku rekening.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- =========================
        BOTTOM ACTION BAR
        (tombol verifikasi & tolak)
    ========================== --}}
    @if($showActions)
        <form id="verifyForm" method="POST"
              action="{{ route('sa.verifikasi.mitra.verify', ['id' => $mitra->id]) }}"
              class="hidden">
            @csrf
        </form>

        <div class="sticky bottom-0 z-40">
    <div class="max-w-[1200px] mx-auto px-4 md:px-6 pb-4">

            {{-- BATAL (outline putih seperti gambar) --}}
            <button type="button"
                    onclick="openRejectStep1()"
                    class="h-[44px] px-8 rounded-xl bg-white border border-slate-200 text-slate-700
                           font-semibold hover:bg-slate-50 active:scale-[0.99] transition">
                Tolak
            </button>

            {{-- SIMPAN / VERIFIKASI (biru seperti gambar) --}}
            <button type="button"
                    onclick="openVerifyConfirm()"
                    class="h-[44px] px-8 rounded-xl bg-[#0B3A82] text-white font-semibold
                           hover:opacity-95 active:scale-[0.99] transition">
                Verifikassi
            </button>

        </div>
    </div>
</div>
    @endif

</div>

{{-- ===================== MODAL VERIFY CONFIRM ===================== --}}
<div id="modalVerifyConfirm" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalVerifyConfirm" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12">
            <h2 class="text-center text-3xl md:text-4xl font-extrabold text-slate-900 leading-snug">
                Anda akan memverifikasi mitra ini.<br>Apakah Anda yakin?
            </h2>

            <div class="flex justify-center gap-10 mt-12">
                <button type="button"
                        onclick="hideModal('modalVerifyConfirm')"
                        class="h-12 w-[160px] rounded-xl bg-[#4B5563] text-white font-semibold hover:opacity-95">
                    Kembali
                </button>

                <button type="submit"
                        form="verifyForm"
                        class="h-12 w-[160px] rounded-xl bg-emerald-600 text-white font-semibold hover:opacity-95">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL SUKSES VERIFY ===================== --}}
<div id="modalVerifiedSuccess" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalVerifiedSuccess" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                Anda telah berhasil memverifikasi mitra.
            </h2>

            <div class="flex justify-center mt-12">
                <button type="button"
                        onclick="closeSuccess('modalVerifiedSuccess')"
                        class="h-12 w-[200px] rounded-xl bg-[#0B3A82] text-white font-semibold hover:opacity-95">
                    Oke
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL SUKSES REJECT ===================== --}}
<div id="modalRejectedSuccess" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalRejectedSuccess" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                Anda telah menolak verifikasi mitra.
            </h2>

            <div class="flex justify-center mt-12">
                <button type="button"
                        onclick="closeSuccess('modalRejectedSuccess')"
                        class="h-12 w-[200px] rounded-xl bg-[#0B3A82] text-white font-semibold hover:opacity-95">
                    Oke
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== SCRIPT (verify + success) ===================== --}}
<script>
    function lockScroll(lock) {
        document.documentElement.classList.toggle('overflow-hidden', !!lock);
        document.body.classList.toggle('overflow-hidden', !!lock);
    }

    function showModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        lockScroll(true);
    }

    function hideModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');

        const anyOpen = Array.from(document.querySelectorAll('[id^="modal"]'))
            .some(m => !m.classList.contains('hidden'));
        if (!anyOpen) lockScroll(false);
    }

    function openVerifyConfirm() { showModal('modalVerifyConfirm'); }

    function closeSuccess(id) {
        hideModal(id);
        window.location.href = @json($backToListUrl);
    }

    document.addEventListener('click', (e) => {
        const t = e.target;
        if (!(t instanceof HTMLElement)) return;
        const id = t.getAttribute('data-backdrop');
        if (!id) return;

        if (id === 'modalVerifiedSuccess' || id === 'modalRejectedSuccess') {
            hideModal(id);
            return;
        }
        hideModal(id);
    });

    document.addEventListener('DOMContentLoaded', () => {
        @if(session()->has('mitra_verified_success'))
            showModal('modalVerifiedSuccess');
        @endif

        @if(session()->has('mitra_rejected_success'))
            showModal('modalRejectedSuccess');
        @endif
    });
</script>

@endsection

@push('modals')
{{-- MODAL REJECT (STEP 1 & STEP 2) — punyamu tetap, saya rapikan sedikit saja --}}
<div id="modalRejectStep1" class="fixed inset-0 z-[2147483647] hidden isolate">
    <div class="absolute inset-0 bg-[#5B5E88]/80" onclick="closeAllRejectModals()"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl px-10 py-12 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-snug">
                Anda akan Menolak verifikasi mitra ini.<br>
                Apakah Anda yakin?
            </h2>

            <div class="flex justify-center gap-6 mt-12">
                <button type="button"
                        onclick="closeAllRejectModals()"
                        class="h-12 w-[160px] rounded-xl bg-[#6B7280] text-white font-semibold hover:opacity-95">
                    Kembali
                </button>

                <button type="button"
                        onclick="openRejectStep2()"
                        class="h-12 w-[160px] rounded-xl bg-[#FF0000] text-white font-semibold hover:opacity-95">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modalRejectStep2" class="fixed inset-0 z-[2147483647] hidden isolate">
    <div class="absolute inset-0 bg-[#5B5E88]/80" onclick="closeAllRejectModals()"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl px-8 py-8">

            <div class="flex items-center gap-3 mb-4">
                <button type="button" onclick="backToRejectStep1()" class="text-slate-700">←</button>
                <h3 class="text-[18px] font-semibold text-slate-900">Penolakan Verifikasi Mitra</h3>
            </div>

            <p class="text-slate-600 mb-6">Berikan alasan penolakan verifikasi mitra!</p>

            <form id="rejectFinalForm" method="POST"
                  action="{{ route('sa.verifikasi.mitra.reject', ['id' => $mitra->id]) }}">
                @csrf

                <div class="space-y-3">
                    @foreach($reasons as $r)
                        <label class="flex items-center gap-3 text-slate-700">
                            <input type="radio" name="reason" value="{{ $r }}" class="h-4 w-4" required>
                            <span>{{ $r }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="submit"
                        class="mt-8 w-full h-12 rounded-xl bg-[#FF3B3B] text-white font-semibold hover:opacity-95">
                    Tolak verifikasi
                </button>

                <button type="button"
                        onclick="closeAllRejectModals()"
                        class="mt-4 w-full h-12 rounded-xl border border-[#1D4ED8] text-[#1D4ED8] font-semibold hover:bg-blue-50">
                    Kembali
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const m1 = document.getElementById('modalRejectStep1');
    const m2 = document.getElementById('modalRejectStep2');

    function lockScroll(lock) {
        document.documentElement.classList.toggle('overflow-hidden', !!lock);
        document.body.classList.toggle('overflow-hidden', !!lock);
    }
    function hide(el){ el?.classList.add('hidden'); }
    function show(el){ el?.classList.remove('hidden'); }

    function closeAllRejectModals() {
        hide(m1); hide(m2);
        lockScroll(false);
    }

    function openRejectStep1() {
        hide(m2);
        show(m1);
        lockScroll(true);
    }

    function openRejectStep2() {
        hide(m1);
        show(m2);
        lockScroll(true);
    }

    function backToRejectStep1() {
        openRejectStep1();
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAllRejectModals();
    });
</script>
@endpush
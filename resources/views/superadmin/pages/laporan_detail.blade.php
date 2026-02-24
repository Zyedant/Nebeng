@extends('superadmin.layouts.app')

@section('content')
@php
    $tglText = !empty($report->tanggal_tampil)
        ? \Carbon\Carbon::parse($report->tanggal_tampil)->format('d/m/Y H:i')
        : '—';

    // tentukan siapa customer & siapa mitra berdasarkan role di report
    $customerName = '—';
    $mitraName    = '—';

    if (($report->reporter_role ?? null) === 'customer') $customerName = $report->reporter_name ?? '—';
    if (($report->reported_role ?? null) === 'customer') $customerName = $report->reported_name ?? '—';

    if (($report->reporter_role ?? null) === 'partner') $mitraName = $report->reporter_name ?? '—';
    if (($report->reported_role ?? null) === 'partner') $mitraName = $report->reported_name ?? '—';

    $laporanText = $report->description ?: ($report->reason ?: '—');
    $pelaporText = trim(($report->reporter_name ?? '—') . ' (' . strtoupper($report->reporter_role ?? '-') . ')');

    // terlapor
    $terlaporName  = $report->reported_name ?? '—';
    $terlaporRole  = strtoupper($report->reported_role ?? '-');
    $isBanned      = !empty($report->reported_is_banned);

    // value edit
    $reportedEmail      = $report->reported_email ?? '';
    $reportedPhone      = $report->reported_phone ?? '';
    $reportedGender     = $report->reported_gender ?? '';
    $reportedBirthPlace = $report->reported_birth_place ?? '';
    $reportedBirthDate  = $report->reported_birth_date ?? '';
@endphp

<div class="font-['Urbanist']">

    {{-- TOP: back + title --}}
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('sa.laporan') }}"
           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <div class="text-[20px] md:text-[22px] font-semibold text-slate-900">
            Detail Laporan
        </div>
    </div>

    {{-- MAIN WRAPPER --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

        {{-- Header line: ID Pesanan + tanggal --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div class="text-[13px] text-slate-500">
                ID Pesanan :
                <span class="text-slate-900 font-semibold">{{ $report->order_no ?? '—' }}</span>
            </div>

            <div class="text-[13px] text-slate-500">
                Tanggal:
                <span class="text-slate-900 font-semibold">{{ $tglText }}</span>
            </div>
        </div>

        {{-- Cards Customer & Mitra --}}
        <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- CUSTOMER CARD --}}
            <div class="rounded-2xl bg-[#F3FAFF] border border-[#E7F3FF] p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-400" viewBox="0 0 24 24" fill="none">
                                <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M4 20c1.5-4 14.5-4 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <div>
                            <div class="text-[15px] font-semibold text-slate-900 leading-tight">
                                {{ $customerName }}
                            </div>
                            <div class="text-[12px] text-slate-500">Customer</div>
                        </div>
                    </div>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-white border border-slate-200 text-slate-700">
                        CUSTOMER
                    </span>
                </div>

                <div class="mt-4 text-[13px] font-semibold text-slate-900">Informasi Customer</div>

                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <div class="text-[12px] text-slate-500 mb-1">Nama Lengkap</div>
                        <div class="h-10 px-3 rounded-xl bg-white border border-slate-200 text-[13px] text-slate-700 flex items-center">
                            {{ $customerName }}
                        </div>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-500 mb-1">No. Tlp</div>
                        <div class="h-10 px-3 rounded-xl bg-white border border-slate-200 text-[13px] text-slate-700 flex items-center">
                            —
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-[12px] text-slate-500 mb-1">Catatan Untuk Driver</div>
                    <div class="min-h-[78px] px-3 py-2 rounded-xl bg-white border border-slate-200 text-[13px] text-slate-700">
                        {{ $report->catatan ?? '—' }}
                    </div>
                </div>
            </div>

            {{-- MITRA CARD --}}
            <div class="rounded-2xl bg-[#F3FAFF] border border-[#E7F3FF] p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-400" viewBox="0 0 24 24" fill="none">
                                <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M4 20c1.5-4 14.5-4 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>

                        <div>
                            <div class="text-[15px] font-semibold text-slate-900 leading-tight">
                                {{ $mitraName }}
                            </div>
                            <div class="text-[12px] text-slate-500">Mitra</div>
                        </div>
                    </div>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-white border border-slate-200 text-slate-700">
                        MITRA
                    </span>
                </div>

                <div class="mt-4 text-[13px] font-semibold text-slate-900">Informasi Mitra</div>

                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <div class="text-[12px] text-slate-500 mb-1">Nama Lengkap</div>
                        <div class="h-10 px-3 rounded-xl bg-white border border-slate-200 text-[13px] text-slate-700 flex items-center">
                            {{ $mitraName }}
                        </div>
                    </div>
                    <div>
                        <div class="text-[12px] text-slate-500 mb-1">No. Tlp</div>
                        <div class="h-10 px-3 rounded-xl bg-white border border-slate-200 text-[13px] text-slate-700 flex items-center">
                            —
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-[12px] text-slate-500 mb-1">Ringkasan</div>
                    <div class="h-10 px-3 rounded-xl bg-white border border-slate-200 text-[13px] text-slate-700 flex items-center">
                        Layanan: {{ $report->layanan ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL LAPORAN --}}
        <div class="mt-6 rounded-2xl border border-slate-100 p-5">
            <div class="flex items-center justify-between gap-3">
                <div class="text-[14px] font-semibold text-slate-900">Laporan</div>

                <button type="button" id="btnTanggapi"
                        class="text-[12px] font-semibold text-blue-600 hover:text-blue-700 transition">
                    Tanggapi
                </button>
            </div>

            <div class="mt-3 rounded-xl bg-slate-50 border border-slate-200 p-4 text-[13px] text-slate-700 leading-relaxed">
                {{ $laporanText }}
            </div>

            <div class="mt-3 text-[12px] text-slate-500 text-right">
                Pelapor:
                <span class="text-slate-700 font-semibold">{{ $pelaporText }}</span>
            </div>
        </div>

        {{-- PANEL TANGGAPI --}}
        <div id="panelTanggapi" class="mt-5 hidden rounded-2xl border border-slate-100 overflow-hidden">

            {{-- header --}}
            <div class="bg-[#F3FAFF] border-b border-[#E7F3FF] p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                        @if(!empty($report->reported_image))
                            <img src="{{ asset($report->reported_image) }}" class="w-full h-full object-cover" alt="avatar">
                        @else
                            <svg class="w-10 h-10 text-slate-400" viewBox="0 0 24 24" fill="none">
                                <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M4 20c1.5-4 14.5-4 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <div class="text-[15px] font-semibold text-slate-900 leading-tight truncate">
                            {{ $terlaporName }}
                        </div>
                        <div class="text-[12px] text-slate-500 truncate">
                            {{ $report->layanan ?? '—' }}
                        </div>

                        <div class="mt-1 flex items-center gap-2 text-[12px] text-blue-600 font-semibold">
                            <span>{{ $terlaporRole }}</span>
                        </div>
                    </div>
                </div>

                {{-- buttons --}}
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" id="btnEdit"
                            class="h-9 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[13px] font-semibold transition">
                        Edit
                    </button>

                    <button type="submit" form="formUpdateTerlapor" id="btnSave"
                            class="hidden h-9 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[13px] font-semibold transition">
                        Simpan
                    </button>

                    <button type="button" id="btnCancelEdit"
                            class="hidden h-9 px-4 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-800 text-[13px] font-semibold transition">
                        Batal
                    </button>

                    <button type="button" id="btnOpenBlock"
                            class="h-9 px-4 rounded-lg text-white text-[13px] font-semibold transition
                                   {{ $isBanned ? 'bg-slate-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600' }}"
                            {{ $isBanned ? 'disabled' : '' }}>
                        {{ $isBanned ? 'Sudah Diblokir' : 'Block Akun' }}
                    </button>
                </div>
            </div>

            {{-- body --}}
            <div class="bg-white p-5">
                <form id="formUpdateTerlapor" method="POST"
                      action="{{ route('sa.laporan.updateTerlapor', ['id' => $report->report_id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="text-[16px] font-semibold text-slate-900 mb-3">Informasi Pribadi</div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Nama Lengkap</div>
                            <input name="name" value="{{ $terlaporName }}"
                                   class="form-editable w-full h-10 px-3 rounded-lg bg-slate-100 border border-slate-200 text-[13px]"
                                   disabled>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Email</div>
                            <input name="email" value="{{ $reportedEmail }}"
                                   class="form-editable w-full h-10 px-3 rounded-lg bg-slate-100 border border-slate-200 text-[13px]"
                                   disabled>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Tempat Lahir</div>
                            <input name="birth_place" value="{{ $reportedBirthPlace }}"
                                   class="form-editable w-full h-10 px-3 rounded-lg bg-slate-100 border border-slate-200 text-[13px]"
                                   disabled>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Tanggal Lahir</div>
                            <input type="date" name="birth_date" value="{{ $reportedBirthDate }}"
                                   class="form-editable w-full h-10 px-3 rounded-lg bg-slate-100 border border-slate-200 text-[13px]"
                                   disabled>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">Jenis Kelamin</div>
                            <select name="gender"
                                    class="form-editable w-full h-10 px-3 rounded-lg bg-slate-100 border border-slate-200 text-[13px]"
                                    disabled>
                                <option value="">—</option>
                                <option value="Laki-laki" {{ $reportedGender === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $reportedGender === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <div class="text-[12px] text-slate-600 mb-1">No. Tlp</div>
                            <input name="phone_number" value="{{ $reportedPhone }}"
                                   class="form-editable w-full h-10 px-3 rounded-lg bg-slate-100 border border-slate-200 text-[13px]"
                                   disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- ================= MODAL SIMPAN SUKSES (gambar 3) ================= --}}
<div id="modalSaved" class="hidden fixed inset-0 z-[999]">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2
                w-[620px] max-w-[92vw] bg-white rounded-2xl shadow-xl p-10 text-center">
        <div class="text-[28px] font-semibold text-slate-900">Data terbaru berhasil disimpan</div>

        <div class="mt-7 flex justify-center">
            <div class="w-20 h-20 rounded-2xl bg-emerald-100 flex items-center justify-center">
                <span class="text-4xl">✅</span>
            </div>
        </div>

        <button type="button"
                class="mt-8 h-10 px-10 rounded-lg bg-[#0B4A8B] hover:opacity-90 text-white font-semibold"
                onclick="document.getElementById('modalSaved').classList.add('hidden')">
            Oke
        </button>
    </div>
</div>

{{-- ================= MODAL KONFIRMASI BLOCK (gambar 4) ================= --}}
<div id="modalBlockConfirm" class="hidden fixed inset-0 z-[999]">
    <div class="absolute inset-0 bg-black/40"
         onclick="document.getElementById('modalBlockConfirm').classList.add('hidden')"></div>

    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2
                w-[520px] max-w-[92vw] bg-white rounded-2xl shadow-xl p-10 text-center">
        <div class="text-[16px] font-semibold text-slate-900">Apakah Anda Yakin Ingin Block Akun Ini</div>

        <div class="mt-7 flex justify-center">
            <div class="w-24 h-24 rounded-2xl bg-orange-50 flex items-center justify-center">
                <span class="text-5xl">🚫</span>
            </div>
        </div>

        <div class="mt-9 flex items-center justify-center gap-4">
            <button type="button"
                    class="h-10 px-10 rounded-lg bg-slate-300 hover:bg-slate-400 text-slate-800 font-semibold"
                    onclick="document.getElementById('modalBlockConfirm').classList.add('hidden')">
                Kembali
            </button>

            <form method="POST" action="{{ route('sa.laporan.block', ['id' => $report->report_id]) }}">
                @csrf
                <button type="submit"
                        class="h-10 px-10 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold">
                    Block
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ================= MODAL SUKSES BANNED (gambar 5) ================= --}}
<div id="modalBanned" class="hidden fixed inset-0 z-[999]">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2
                w-[520px] max-w-[92vw] bg-white rounded-2xl shadow-xl p-10 text-center">
        <div class="text-[22px] font-semibold text-slate-900">Akun Ini Berhasil Di Banned</div>

        <div class="mt-7 flex justify-center">
            <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center">
                <span class="text-5xl">👮‍♀️✅</span>
            </div>
        </div>

        <button type="button"
                class="mt-9 h-10 px-10 rounded-lg bg-slate-300 hover:bg-slate-400 text-slate-800 font-semibold"
                onclick="document.getElementById('modalBanned').classList.add('hidden')">
            Kembali
        </button>
    </div>
</div>

{{-- ================= SCRIPTS ================= --}}
<script>
    // toggle panel tanggapi
    const btnTanggapi = document.getElementById('btnTanggapi');
    const panelTanggapi = document.getElementById('panelTanggapi');
    if (btnTanggapi && panelTanggapi) {
        btnTanggapi.addEventListener('click', () => {
            panelTanggapi.classList.toggle('hidden');
        });
    }

    // edit mode
    const btnEdit = document.getElementById('btnEdit');
    const btnSave = document.getElementById('btnSave');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const editables = document.querySelectorAll('.form-editable');

    const initialValues = [];
    editables.forEach((el, i) => initialValues[i] = el.value);

    function setEditMode(isEdit) {
        editables.forEach((el) => {
            el.disabled = !isEdit;
            if (isEdit) {
                el.classList.remove('bg-slate-100');
                el.classList.add('bg-white');
            } else {
                el.classList.add('bg-slate-100');
                el.classList.remove('bg-white');
            }
        });

        if (btnEdit) btnEdit.classList.toggle('hidden', isEdit);
        if (btnSave) btnSave.classList.toggle('hidden', !isEdit);
        if (btnCancelEdit) btnCancelEdit.classList.toggle('hidden', !isEdit);
    }

    if (btnEdit) btnEdit.addEventListener('click', () => setEditMode(true));
    if (btnCancelEdit) {
        btnCancelEdit.addEventListener('click', () => {
            editables.forEach((el, i) => el.value = initialValues[i]);
            setEditMode(false);
        });
    }

    // modal block
    const btnOpenBlock = document.getElementById('btnOpenBlock');
    if (btnOpenBlock && !btnOpenBlock.disabled) {
        btnOpenBlock.addEventListener('click', () => {
            document.getElementById('modalBlockConfirm')?.classList.remove('hidden');
        });
    }

    // show modal simpan sukses
    @if(session('saved_success'))
        document.getElementById('modalSaved')?.classList.remove('hidden');
        // kalau mau auto balik view mode setelah simpan:
        setEditMode(false);
        // optional: buka panel tanggapi otomatis setelah redirect
        panelTanggapi?.classList.remove('hidden');
    @endif

    // show modal banned sukses (controller kamu pakai session('success'))
    @if(session('success'))
        document.getElementById('modalBanned')?.classList.remove('hidden');
        // optional: buka panel tanggapi otomatis setelah redirect
        panelTanggapi?.classList.remove('hidden');
    @endif
</script>
@endsection
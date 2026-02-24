@extends('superadmin.layouts.app')

@section('content')
@php
    // =========================
    // ALASAN REJECT
    // =========================
    $reasonsCustomer = [
        'Data tidak sesuai',
        'Foto KTP tidak jelas',
        'NIK tidak valid',
        'Identitas tidak lengkap',
        'Indikasi penipuan atau kecurangan',
        'Lainnya',
    ];

    // =========================
    // STATUS (SAMA SEPERTI detail_mitra: PAKAI FIELD ASLI DARI CONTROLLER)
    // =========================
   $statusRaw  = (string)($customer->user_verified_status ?? '');
    $statusNorm = strtolower(trim($statusRaw));

    if ((int)($customer->user_is_banned ?? 0) === 1) {
    $statusNorm = 'diblok';
    $statusRaw  = 'diblok';
}


    $isPengajuan = ($statusNorm === '' || $statusNorm === 'pengajuan');

    $statusText  = strtoupper($statusRaw ?: 'PENGAJUAN');

    $statusClass = 'bg-slate-100 text-slate-700';
    if ($statusNorm === 'terverifikasi') $statusClass = 'bg-emerald-100 text-emerald-700';
    elseif ($statusNorm === 'ditolak')   $statusClass = 'bg-red-100 text-red-700';
    elseif ($statusNorm === 'diblok')    $statusClass = 'bg-slate-900 text-white';
    else                                 $statusClass = 'bg-amber-400 text-white';

    // =========================
    // AVATAR / LAYANAN / ID
    // =========================
   $avatar = !empty($customer->user_image) ? asset($customer->user_image) : null;
    $layanan   = $customer->layanan ?? 'Nebeng Customer';
    $displayId = str_pad((int)($customer->id ?? 0), 6, '0', STR_PAD_LEFT);

    // FOTO KTP
    $ktpImg = !empty($customer->id_image) ? asset($customer->id_image) : null;

    // INFO PRIBADI
    $namaLengkap = $customer->id_fullname ?? $customer->name ?? '—';
    $email       = $customer->email ?? '—';
    $tempatLahir = $customer->birth_place ?? '—';
    $tglLahir    = $customer->birth_date ?? '—';
    $gender      = $customer->gender ?? '—';
    $noTlp       = $customer->phone_number ?? '—';

    // INFO KTP
    $ktpNama = $customer->id_fullname ?? '—';
    $ktpNik  = $customer->id_number ?? '—';
    $ktpTgl  = $customer->id_birth_date ?? ($customer->birth_date ?? '—');

    $backToListUrl = route('sa.verifikasi.customer');
@endphp

<div class="space-y-6 font-['Urbanist']">

    <div class="flex items-center gap-3">
        <a href="{{ $backToListUrl }}" class="text-slate-600 hover:text-slate-900">←</a>
        <div class="text-[22px] font-semibold text-slate-900">Detail Data Customer</div>
    </div>

    {{-- Header card --}}
    <div class="bg-[#EAF3FF] rounded-2xl border border-slate-100 p-5">
        <div class="flex items-start justify-between gap-4">

            {{-- kiri --}}
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
                    <div class="text-[14px] font-semibold text-slate-900">{{ $customer->name ?? '—' }}</div>
                    <div class="text-[12px] text-slate-500">{{ $layanan }}</div>
                    <div class="text-[12px] text-blue-700 font-semibold">{{ $displayId }}</div>

                    <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>
            </div>

            {{-- kanan --}}
            <div class="flex flex-col items-end gap-2">

                @if(\Illuminate\Support\Facades\Route::has('sa.customer.edit'))
                    <a href="{{ route('sa.customer.edit', ['id' => $customer->id]) }}"
                       class="h-9 px-4 rounded-full bg-white/80 border border-slate-200 text-slate-700 text-[12px]
                              flex items-center gap-2 hover:bg-white transition">
                        Edit
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5Z"
                                  stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @endif

                {{-- =========================
                   ✅ FORM GLOBAL (ANTI 404)
                   - JANGAN taruh form di modal
                   ========================= --}}
                <form id="verifyForm" method="POST"
                      action="{{ route('sa.verifikasi.customer.verify', ['id' => $customer->id]) }}"
                      class="hidden">
                    @csrf
                </form>

                <form id="rejectForm" method="POST"
                      action="{{ route('sa.verifikasi.customer.reject', ['id' => $customer->id]) }}"
                      class="hidden">
                    @csrf
                    <input type="hidden" id="reasonFinal" name="reason_final" value="">
                </form>

                {{-- aksi --}}
                @if($isPengajuan)
                    <div class="flex items-center gap-2">

                        <button type="button"
                                onclick="openVerifyConfirm()"
                                class="h-9 w-9 rounded-full bg-emerald-600 text-white flex items-center justify-center hover:bg-emerald-700 transition"
                                title="Verifikasi">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <button type="button"
                                onclick="openRejectStep1()"
                                class="h-9 w-9 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700 transition"
                                title="Tolak">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                                <path d="M6 6l12 12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                            </svg>
                        </button>

                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- 2 kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- kiri --}}
        <div class="lg:col-span-2 space-y-6">

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
                        <select disabled class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700">
                            <option>{{ $gender }}</option>
                        </select>
                    </div>

                    <div>
                        <div class="text-[12px] text-slate-600 mb-1">No. Tlp</div>
                        <input disabled value="{{ $noTlp }}"
                               class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700"/>
                    </div>
                </div>
            </div>

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
                        <select disabled class="w-full h-10 rounded-lg bg-slate-100 border border-slate-200 px-3 text-[12px] text-slate-700">
                            <option>{{ $gender }}</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        {{-- kanan --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                <div class="text-[14px] font-semibold text-slate-900">Foto KTP</div>
                <div class="mt-4 flex items-center justify-center">
                    @if($ktpImg)
                        <img src="{{ $ktpImg }}" class="w-[220px] h-[140px] object-cover rounded-xl border border-slate-200" alt="KTP">
                    @else
                        <div class="text-slate-400 text-sm">Belum ada foto KTP.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- =======================================================
   MODAL: KONFIRMASI VERIFY
======================================================= --}}
<div id="modalVerifyConfirm" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalVerifyConfirm" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12"
             onclick="event.stopPropagation()">
            <h2 class="text-center text-3xl md:text-4xl font-extrabold text-slate-900 leading-snug">
                Anda akan memverifikasi customer ini.<br>Apakah Anda yakin?
            </h2>

            <div class="flex justify-center gap-10 mt-12">
                <button type="button"
                        onclick="hideModal('modalVerifyConfirm')"
                        class="h-12 w-[160px] rounded-xl bg-[#4B5563] text-white font-semibold hover:opacity-95">
                    Kembali
                </button>

                {{-- ✅ SUBMIT GLOBAL FORM --}}
                <button type="submit"
                        form="verifyForm"
                        class="h-12 w-[160px] rounded-xl bg-emerald-600 text-white font-semibold hover:opacity-95">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =======================================================
   MODAL: STEP 1 REJECT (KONFIRMASI)
======================================================= --}}
<div id="modalRejectStep1" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalRejectStep1" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12"
             onclick="event.stopPropagation()">
            <h2 class="text-center text-3xl md:text-4xl font-extrabold text-slate-900 leading-snug">
                Anda akan menolak verifikasi customer ini.<br>Apakah Anda yakin?
            </h2>

            <div class="flex justify-center gap-10 mt-12">
                <button type="button"
                        onclick="hideModal('modalRejectStep1')"
                        class="h-12 w-[160px] rounded-xl bg-[#4B5563] text-white font-semibold hover:opacity-95">
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

{{-- =======================================================
   MODAL: STEP 2 REJECT (ALASAN)
======================================================= --}}
<div id="modalRejectStep2" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalRejectStep2" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12"
             onclick="event.stopPropagation()">
            <div class="flex items-center gap-3 mb-4">
                <button type="button" onclick="backRejectStep1()" class="text-2xl leading-none">←</button>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-snug">
                    Pembatalan Verifikasi Customer
                </h2>
            </div>

            <div class="mt-6 w-full max-w-4xl mx-auto">
                <div class="text-[14px] text-slate-700 mb-4">Berikan alasan pembatalan verifikasi customer!</div>

                {{-- ✅ TIDAK ADA FORM DI SINI (ANTI 404) --}}
                <div class="space-y-3">
                    @foreach($reasonsCustomer as $r)
                        <label class="flex items-center gap-3 text-slate-700">
                            <input type="radio"
                                   name="reason"
                                   value="{{ $r }}"
                                   class="h-4 w-4"
                                   required
                                   form="rejectForm">
                            <span class="text-[14px]">{{ $r }}</span>
                        </label>
                    @endforeach
                </div>

                <div id="reasonOtherWrap" class="mt-6 hidden">
                    <div class="text-[14px] text-slate-700 mb-2">Tulis alasan lainnya</div>
                    <textarea id="reasonOtherInput"
                              class="w-full min-h-[150px] rounded-xl border-2 border-[#0057FF] bg-slate-50
                                     px-6 py-5 text-[14px] text-slate-700 placeholder:text-slate-400
                                     focus:outline-none focus:ring-0"
                              placeholder="Contoh: Data tidak sesuai, foto KTP tidak jelas, dsb."></textarea>
                    <div class="mt-4 text-[12px] text-slate-500">
                        Jika memilih "Lainnya", isian textarea akan dikirim sebagai alasan final.
                    </div>
                </div>

                <div class="flex justify-center gap-10 mt-12">
                    <button type="button"
                            onclick="hideModal('modalRejectStep2')"
                            class="h-12 w-[160px] rounded-xl bg-[#4B5563] text-white font-semibold hover:opacity-95">
                        Kembali
                    </button>

                    {{-- ✅ SUBMIT GLOBAL FORM (POST PASTI) --}}
                    <button type="submit"
                            form="rejectForm"
                            class="h-12 w-[220px] rounded-xl bg-[#FF0000] text-white font-semibold hover:opacity-95">
                        Batalkan Pengajuan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =======================================================
   MODAL: SUKSES VERIFY
======================================================= --}}
<div id="modalVerifiedSuccess" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalVerifiedSuccess" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12 text-center"
             onclick="event.stopPropagation()">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                Anda telah berhasil memverifikasi customer.
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

{{-- =======================================================
   MODAL: SUKSES REJECT
======================================================= --}}
<div id="modalRejectedSuccess" class="fixed inset-0 z-[999999] hidden">
    <div data-backdrop="modalRejectedSuccess" class="absolute inset-0 bg-[#5B5E88]/80"></div>

    <div class="relative min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl px-10 py-12 text-center"
             onclick="event.stopPropagation()">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                Anda telah menolak verifikasi customer.
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

    function openRejectStep1() { showModal('modalRejectStep1'); }
    function openRejectStep2() {
        hideModal('modalRejectStep1');
        showModal('modalRejectStep2');
    }
    function backRejectStep1() {
        hideModal('modalRejectStep2');
        showModal('modalRejectStep1');
    }

    // backdrop click close
    document.addEventListener('click', (e) => {
        const t = e.target;
        if (!(t instanceof HTMLElement)) return;
        const id = t.getAttribute('data-backdrop');
        if (!id) return;

        hideModal(id);
    });

    // ESC close
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;

        const ids = [
            'modalRejectStep2',
            'modalRejectStep1',
            'modalVerifyConfirm',
            'modalVerifiedSuccess',
            'modalRejectedSuccess'
        ];
        for (const id of ids) {
            const el = document.getElementById(id);
            if (el && !el.classList.contains('hidden')) {
                hideModal(id);
                break;
            }
        }
    });

    function closeSuccess(id) {
        hideModal(id);
        window.location.href = @json($backToListUrl);
    }

    // Toggle textarea kalau reason = "Lainnya"
    document.addEventListener('change', (e) => {
        const t = e.target;
        if (!(t instanceof HTMLInputElement)) return;
        if (t.name !== 'reason') return;

        const wrap = document.getElementById('reasonOtherWrap');
        const input = document.getElementById('reasonOtherInput');
        const selected = document.querySelector('input[name="reason"]:checked');

        if (!wrap || !input) return;

        if (selected && selected.value === 'Lainnya') {
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
            input.value = '';
            const final = document.getElementById('reasonFinal');
            if (final) final.value = '';
        }
    });

    // Set reason_final sebelum submit rejectForm
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (form.id !== 'rejectForm') return;

        const selected = document.querySelector('input[name="reason"]:checked');
        const input = document.getElementById('reasonOtherInput');
        const final = document.getElementById('reasonFinal');

        if (!selected) return;

        if (selected.value === 'Lainnya') {
            final.value = (input?.value || '').trim();
        } else {
            final.value = '';
        }
    });

    // auto open success modal dari session
    document.addEventListener('DOMContentLoaded', () => {
        @if(session()->has('customer_verified_success'))
            showModal('modalVerifiedSuccess');
        @endif

        @if(session()->has('customer_rejected_success'))
            showModal('modalRejectedSuccess');
        @endif
    });
</script>
@endsection

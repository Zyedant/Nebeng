@extends('superadmin.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl p-8 shadow border border-slate-100">

    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('sa.verifikasi.mitra.detail', ['id' => $mitra->id]) }}" class="text-slate-600">←</a>
        <h1 class="text-xl font-semibold">Penolakan Verifikasi Mitra</h1>
    </div>

    <p class="text-slate-600 mb-6">Berikan alasan penolakan verifikasi mitra!</p>

    <form method="POST" action="{{ route('sa.verifikasi.mitra.reject', ['id' => $mitra->id]) }}">
        @csrf

        @php
            $reasons = [
                'Tidak Memenuhi Persyaratan Kendaraan',
                'Ketidaksesuaian Data Pengemudi',
                'Dokumen Kendaraan Tidak Valid',
                'Riwayat Pengemudi Tidak Memenuhi Kriteria',
                'Kendaraan Tidak Layak Operasi',
                'Penolakan Terhadap Aturan dan Kebijakan Aplikasi',
                'Indikasi Penipuan atau Kecurangan',
                'Lainnya',
            ];
        @endphp

        <div class="space-y-3">
            @foreach($reasons as $r)
                <label class="flex items-center gap-3 text-slate-700">
                    <input type="radio" name="reason" value="{{ $r }}" class="h-4 w-4" required>
                    <span>{{ $r }}</span>
                </label>
            @endforeach
        </div>

        <button type="submit"
                class="mt-8 w-full h-12 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700">
            Tolak verifikasi
        </button>

        <a href="{{ route('sa.verifikasi.mitra.detail', ['id' => $mitra->id]) }}"
           class="mt-4 w-full h-12 rounded-xl border border-slate-300 text-slate-700 font-semibold
                  inline-flex items-center justify-center hover:bg-slate-50">
            Kembali
        </a>
    </form>
</div>
@endsection

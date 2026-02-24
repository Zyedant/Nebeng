@extends('superadmin.layouts.app')

@section('content')
@php
    $avatar = !empty($customer->user_image ?? null) ? asset($customer->user_image) : null;
    $displayId = str_pad((int)($customer->id ?? 0), 6, '0', STR_PAD_LEFT);
@endphp

<div class="space-y-6 font-['Urbanist']">

    <div class="flex items-center gap-3">
        <a href="{{ route('sa.customer.detail', ['id' => $customer->id]) }}" class="text-slate-600 hover:text-slate-900">←</a>
        <div class="text-[22px] font-semibold text-slate-900">Edit Data Customer</div>
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
                <div class="text-[14px] font-semibold text-slate-900">{{ $customer->name ?? '—' }}</div>
                <div class="text-[12px] text-blue-700 font-semibold">{{ $displayId }}</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('sa.customer.update', ['id' => $customer->id]) }}" class="space-y-6">
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
                    <input name="name" value="{{ old('name', $customer->name) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Email</div>
                    <input name="email" type="email" value="{{ old('email', $customer->email) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">No. Tlp</div>
                    <input name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Jenis Kelamin</div>
                    <select name="gender"
                            class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                   focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300">
                        @php $g = strtolower((string) old('gender', $customer->gender)); @endphp
                        <option value="">-</option>
                        <option value="Laki-laki" {{ $g === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $g === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Tempat Lahir</div>
                    <input name="birth_place" value="{{ old('birth_place', $customer->birth_place) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Tanggal Lahir</div>
                    <input name="birth_date" type="date" value="{{ old('birth_date', $customer->birth_date) }}"
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
                        @php $s = strtolower((string) old('verified_status', $customer->verified_status)); @endphp
                        <option value="">(Tidak diubah)</option>
                        <option value="pengajuan" {{ $s === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                        <option value="terverifikasi" {{ $s === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="ditolak" {{ $s === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="diblok">diblok</option>
                    </select>
                </div>

                <div>
                    <div class="text-[12px] text-slate-600 mb-1">Catatan</div>
                    <input name="verified_status_message" value="{{ old('verified_status_message', $customer->verified_status_message) }}"
                           class="w-full h-10 rounded-lg bg-white border border-slate-200 px-3 text-[12px] text-slate-700
                                  focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"/>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('sa.customer.detail', ['id' => $customer->id]) }}"
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

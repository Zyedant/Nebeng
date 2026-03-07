@extends('layouts.app')

@section('title', 'Detail Mitra')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8 bg-white rounded-xl shadow">

    <div class="flex items-center justify-between bg-blue-50 p-6 rounded-xl">
        <div class="flex items-center gap-4">
            <img src="{{ $partner->user->image ?? asset('images/avatar.png') }}"
                 class="w-16 h-16 rounded-full object-cover"
                 alt="Avatar">
            <div>
                <h2 class="font-semibold text-lg">{{ $partner->user->name ?? '-' }}</h2>
                <p class="text-sm text-gray-500">{{ $partner->nama_usaha ?? '-' }}</p>
            </div>
        </div>

        <div class="text-right">
            <p class="text-xs text-gray-500">ID MITRA</p>
            <p class="font-semibold text-blue-600 text-center">{{ $partner->user_id ?? '-' }}</p>
        </div>
    </div>

    <section>
        <h3 class="font-semibold mb-4">Informasi Pribadi</h3>

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Nama Lengkap</label>
                <input type="text" value="{{ $partner->user->name ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Email</label>
                <input type="text" value="{{ $partner->user->email ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Tempat Lahir</label>
                <input type="text" value="{{ $partner->user->birth_place ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Tanggal Lahir</label>
                <input type="text" value="{{ $partner->id_birth_date ? \Carbon\Carbon::parse($partner->id_birth_date)->format('d/m/Y') : '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Jenis Kelamin</label>
                <input type="text" value="{{ $partner->user->gender ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">No. Telp</label>
                <input type="text" value="{{ $partner->user->phone_number ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>
        </div>
    </section>

    <section class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-4">
            <h3 class="font-semibold">Informasi KTP</h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nama KTP</label>
                    <input type="text" value="{{ $partner->id_fullname ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">NIK</label>
                    <input type="text" value="{{ $partner->id_number ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Jenis Kelamin</label>
                    <input type="text" value="{{ $partner->ktp_jenis_kelamin ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Tanggal Lahir</label>
                    <input type="text" value="{{ $partner->id_birth_date ? \Carbon\Carbon::parse($partner->id_birth_date)->format('d/m/Y') : '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-600 font-medium mb-2">Foto KTP</label>
            @if($partner->id_image)
                <img src="{{ asset('storage/' . $partner->id_image) }}" 
                     class="w-full rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition"
                     onclick="window.open('{{ asset('storage/' . $partner->id_image) }}', '_blank')"
                     alt="Foto KTP">
            @else
                <div class="w-full h-40 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-4">
            <h3 class="font-semibold">Informasi SIM</h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nama SIM</label>
                    <input type="text" value="{{ $partner->dl_fullname ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nomor SIM</label>
                    <input type="text" value="{{ $partner->dl_number ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Jenis SIM</label>
                    <input type="text" value="{{ $partner->jenis_sim ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Masa Berlaku</label>
                    <input type="text" value="{{ $partner->dl_birth_date ? \Carbon\Carbon::parse($partner->dl_birth_date)->format('d/m/Y') : '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-600 font-medium mb-2">Foto SIM</label>
            @if($partner->dl_image)
                <img src="{{ asset('storage/' . $partner->dl_image) }}" 
                     class="w-full rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition"
                     onclick="window.open('{{ asset('storage/' . $partner->dl_image) }}', '_blank')"
                     alt="Foto SIM">
            @else
                <div class="w-full h-40 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
        </div>
    </section>

    @if($partner->vihecles && $partner->vihecles->count() > 0)
        @foreach($partner->vihecles as $vehicle)
            <section class="grid grid-cols-3 gap-6">
                <div class="col-span-2 space-y-4">
                    <h3 class="font-semibold">Informasi Kendaraan {{ $loop->iteration }}</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">Plat Nomor</label>
                            <input type="text" value="{{ $vehicle->vehivle_plate_number ?? $vehicle->vihecle_plate_number ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">Merk</label>
                            <input type="text" value="{{ $vehicle->vihecle_brand ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">Tipe</label>
                            <input type="text" value="{{ $vehicle->vihecle_type ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">Nama Kendaraan</label>
                            <input type="text" value="{{ $vehicle->vihecle_name ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">Warna</label>
                            <input type="text" value="{{ $vehicle->vihecle_color ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">No. Registrasi</label>
                            <input type="text" value="{{ $vehicle->registration_number ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">No. Identitas</label>
                            <input type="text" value="{{ $vehicle->registration_vihecle_identity_number ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs text-gray-600 font-medium">No. Mesin</label>
                            <input type="text" value="{{ $vehicle->registration_engine_number ?? '-' }}" disabled 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-gray-600 font-medium mb-2">Foto STNK</label>
                    @if($vehicle->registration_image)
                        <img src="{{ asset('storage/' . $vehicle->registration_image) }}" 
                             class="w-full rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition"
                             onclick="window.open('{{ asset('storage/' . $vehicle->registration_image) }}', '_blank')"
                             alt="Foto STNK">
                    @else
                        <div class="w-full h-40 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </section>
        @endforeach
    @else
        <section>
            <h3 class="font-semibold mb-4">Informasi Kendaraan</h3>
            <p class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg border border-gray-300">Belum ada kendaraan terdaftar.</p>
        </section>
    @endif

    <section class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-4">
            <h3 class="font-semibold">Informasi SKCK</h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nomor SKCK</label>
                    <input type="text" value="{{ $partner->skck_number ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Masa Berlaku</label>
                    <input type="text" value="{{ $partner->skck_valid_date ? \Carbon\Carbon::parse($partner->skck_valid_date)->format('d/m/Y') : '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-600 font-medium mb-2">Foto SKCK</label>
            @if($partner->skck_image)
                <img src="{{ asset('storage/' . $partner->skck_image) }}" 
                     class="w-full rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition"
                     onclick="window.open('{{ asset('storage/' . $partner->skck_image) }}', '_blank')"
                     alt="Foto SKCK">
            @else
                <div class="w-full h-40 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-4">
            <h3 class="font-semibold mb-4">Informasi Rekening Bank</h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nomor Rekening</label>
                    <input type="text" value="{{ $partner->no_rekening ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nama Bank</label>
                    <input type="text" value="{{ $partner->nama_bank ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-600 font-medium mb-2">Foto Buku Rekening</label>
            @if($partner->bank_image)
                <img src="{{ asset('storage/' . $partner->bank_image) }}" 
                     class="w-full rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition"
                     onclick="window.open('{{ asset('storage/' . $partner->bank_image) }}', '_blank')"
                     alt="Foto Buku Rekening">
            @else
                <div class="w-full h-40 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            @endif
        </div>
    </section>

</div>
@endsection
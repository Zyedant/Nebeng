@extends('layouts.app')

@section('title', 'Detail Pos Mitra')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8 bg-white rounded-xl shadow">

    <div class="flex items-center justify-between bg-blue-50 p-6 rounded-xl">
        <div class="flex items-center gap-4">
            <img
                src="{{ $partnerPost->user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($partnerPost->user->name ?? 'User') }}"
                class="w-16 h-16 rounded-full object-cover"
                alt="Avatar"
            >
            <div>
                <h2 class="font-semibold text-lg">{{ $partnerPost->user->name ?? '-' }}</h2>
                <p class="text-sm text-gray-500">{{ $partnerPost->nama_usaha ?? '-' }}</p>
            </div>
        </div>

        <div class="text-right">
            <p class="text-xs text-gray-500">KODE REFERRAL</p>
            <p class="font-semibold text-blue-600">
                {{ $partnerPost->id_number ?? '-' }}
            </p>
        </div>
    </div>

    <section class="space-y-4">
        <h3 class="font-semibold mb-4">Informasi Pribadi</h3>

        <div class="grid grid-cols-3 gap-4">
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Nama Lengkap</label>
                <input type="text" value="{{ $partnerPost->user->name ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Email</label>
                <input type="text" value="{{ $partnerPost->user->email ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Jenis Kelamin</label>
                <input type="text" value="{{ $partnerPost->user->gender ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">No. Telepon</label>
                <input type="text" value="{{ $partnerPost->user->phone_number ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Terminal</label>
                <input type="text" value="{{ $partnerPost->terminal_name ?? '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>

            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Tanggal Lahir</label>
                <input type="text" value="{{ $partnerPost->user->birth_date ? \Carbon\Carbon::parse($partnerPost->user->birth_date)->format('d/m/Y') : '-' }}" disabled 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
            </div>
        </div>

        <div class="space-y-1">
            <label class="block text-xs text-gray-600 font-medium">Alamat Terminal</label>
            <textarea rows="3" disabled
                class="w-2/3 px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">{{ $partnerPost->terminal_address ?? '-' }}</textarea>
        </div>
    </section>

    <section class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-4">
            <h3 class="font-semibold">Informasi KTP</h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nama KTP</label>
                    <input type="text" value="{{ $partnerPost->id_fullname ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">NIK</label>
                    <input type="text" value="{{ $partnerPost->id_number ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Jenis Kelamin</label>
                    <input type="text" value="{{ $partnerPost->user->gender ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Tanggal Lahir</label>
                    <input type="text" value="{{ $partnerPost->user->birth_date ? \Carbon\Carbon::parse($partnerPost->user->birth_date)->format('d/m/Y') : '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs text-gray-600 font-medium mb-2">Foto KTP</label>
            @if($partnerPost->id_image)
                <img src="{{ asset('storage/' . $partnerPost->id_image) }}" 
                     class="w-full rounded-lg border border-gray-300 cursor-pointer hover:opacity-90 transition"
                     onclick="window.open('{{ asset('storage/' . $partnerPost->id_image) }}', '_blank')"
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
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
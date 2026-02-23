@extends('layouts.app')

@section('title', 'Detail Pencairan')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <div class="bg-blue-100 rounded-2xl p-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($withdrawal->partner->user->name) }}&size=100&background=3b82f6&color=fff"
                 class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">

            <div>
                <h2 class="text-lg font-semibold">
                    {{ $withdrawal->partner->user->name }}
                </h2>

                <p class="text-sm text-gray-600">
                    @if($withdrawal->partner->vehicles && $withdrawal->partner->vehicles->count() > 0)
                        {{ $withdrawal->partner->vehicles->first()->vehicle_type }}
                    @else
                        Nebeng Motor
                    @endif
                </p>

                <span class="inline-block mt-2 px-4 py-1 rounded-full text-xs font-medium 
                    @if($withdrawal->status == 'Diterima') bg-green-500 text-white
                    @elseif($withdrawal->status == 'Diproses') bg-yellow-400 text-white
                    @elseif($withdrawal->status == 'Ditolak') bg-red-600 text-white
                    @endif">
                    {{ $withdrawal->status }}
                </span>
            </div>
        </div>

        <a href="{{ route('withdrawals.partner.index') }}" class="px-4 py-2 bg-blue-200 hover:bg-blue-300 rounded-full text-sm transition">
            Kembali
        </a>
    </div>


    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-6">Informasi Pribadi</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Nama Lengkap</label>
                <input type="text" disabled
                       value="{{ $withdrawal->partner->id_fullname }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Username</label>
                <input type="text" disabled
                       value="{{ $withdrawal->partner->user->username ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Email</label>
                <input type="text" disabled
                       value="{{ $withdrawal->partner->user->email }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">No. Tlp</label>
                <input type="text" disabled
                       value="{{ $withdrawal->partner->user->phone_number ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Metode Pencairan</label>
                <input type="text" disabled
                       value="Transfer Bank"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Layanan</label>
                <input type="text" disabled
                       value="@if($withdrawal->partner->vehicles && $withdrawal->partner->vehicles->count() > 0){{ $withdrawal->partner->vehicles->first()->vehicle_type }}@else Nebeng Motor @endif"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Total Saldo</label>
                <input type="text" disabled
                       value="Rp {{ number_format((float) ($withdrawal->partner->balance ?? 0), 0, ',', '.') }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Total Pencairan</label>
                <input type="text" disabled
                       value="Rp {{ number_format((float) $withdrawal->amount, 0, ',', '.') }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm font-bold text-green-600">
            </div>

        </div>

        @if($withdrawal->transfer_proof && $withdrawal->status == 'Diterima')
        <div class="flex justify-end mt-6">
            <a href="{{ asset('storage/' . $withdrawal->transfer_proof) }}" target="_blank"
               class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Bukti Transfer
            </a>
        </div>
        @endif
    </div>

    @if($withdrawal->status == 'Diproses')
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold mb-4">Proses Pencairan</h3>

        <div class="flex gap-3">
            <button onclick="openAcceptModal()" 
                    class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold transition">
                Terima
            </button>

            <button onclick="openRejectModal()" 
                    class="px-8 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition">
                Tolak
            </button>
        </div>
    </div>
    @endif

</div>

<div id="acceptModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Konfirmasi Pencairan</h3>
            <p class="text-gray-600 mb-6">
                Anda akan menyetujui pencairan dana sebesar 
                <span class="font-bold text-green-600">Rp {{ number_format((float) $withdrawal->amount, 0, ',', '.') }}</span>
                kepada <span class="font-semibold">{{ $withdrawal->partner->user->name }}</span>.
            </p>
            
            <p class="text-sm text-blue-600 bg-blue-50 p-3 rounded-lg mb-4">
                ℹ️ Bukti transfer akan otomatis dibuat oleh sistem
            </p>
            
            <form action="{{ route('withdrawals.partner.update', $withdrawal->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="Diterima">

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAcceptModal()" 
                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                        Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pencairan</h3>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menolak pencairan dana sebesar 
                <span class="font-bold text-red-600">Rp {{ number_format((float) $withdrawal->amount, 0, ',', '.') }}</span>?
            </p>
            
            <form action="{{ route('withdrawals.partner.update', $withdrawal->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="Ditolak">
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAcceptModal() {
        document.getElementById('acceptModal').classList.remove('hidden');
        document.getElementById('acceptModal').classList.add('flex');
    }

    function closeAcceptModal() {
        document.getElementById('acceptModal').classList.add('hidden');
        document.getElementById('acceptModal').classList.remove('flex');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
@extends('layouts.app')

@section('title', 'Detail Transaksi #' . ($transaction->order_number ?? $transaction->id))

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">

    <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow">
        <p class="font-semibold text-sm">
            ID Pesanan :
            <span class="ml-2 font-bold text-gray-800">{{ $transaction->order_number ?? 'ORD-'.str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</span>
        </p>
        <button class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1" onclick="copyToClipboard('{{ $transaction->order_number ?? 'ORD-'.str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}')">
            📋 Salin
        </button>
    </div>

    @php
        $status = 'proses'; 
        if ($transaction->payment) {
            if ($transaction->payment->status === 'Diterima') {
                $status = 'selesai';
            } elseif ($transaction->payment->status === 'Diproses') {
                $status = 'proses';
            } elseif ($transaction->payment->status === 'Ditolak') {
                $status = 'batal';
            }
        } elseif ($transaction->refund && $transaction->refund->status === 'Diterima') {
            $status = 'batal';
        }
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-blue-50 p-6 rounded-xl space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gray-300 flex items-center justify-center text-white text-xl font-bold">
                    {{ $transaction->customer && $transaction->customer->user && $transaction->customer->user->name ? strtoupper(substr($transaction->customer->user->name, 0, 1)) : 'C' }}
                </div>
                <div>
                    <p class="font-semibold">{{ $transaction->customer->user->name ?? 'Customer' }}</p>
                    <p class="text-xs text-gray-500">Customer</p>
                    <span class="inline-block mt-1 px-3 py-1 text-xs 
                        @if($status === 'selesai')
                            bg-green-100 text-green-700
                        @elseif($status === 'proses')
                            bg-yellow-100 text-yellow-700
                        @else
                            bg-red-100 text-red-700
                        @endif
                        rounded-full font-semibold">
                        {{ strtoupper($status) }}
                    </span>
                </div>
            </div>

            <h3 class="font-semibold text-sm">Informasi Customer</h3>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nama Lengkap</label>
                    <input type="text" value="{{ $transaction->customer->user->name ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">No. Tlp</label>
                    <input type="text" value="{{ $transaction->customer->user->phone_number ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
            </div>
            
            <div class="space-y-1">
                <label class="block text-xs text-gray-600 font-medium">Catatan Untuk Driver</label>
                <textarea rows="3" disabled 
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">{{ $transaction->note ?? 'Tidak ada catatan' }}</textarea>
            </div>
        </div>

        <div class="bg-blue-50 p-6 rounded-xl space-y-4">
            <div class="flex items-center gap-4 justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gray-300 flex items-center justify-center text-white text-xl font-bold">
                        {{ $transaction->partner && $transaction->partner->user && $transaction->partner->user->name ? strtoupper(substr($transaction->partner->user->name, 0, 1)) : 'M' }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $transaction->partner->user->name ?? 'Mitra' }}</p>
                        <p class="text-xs text-gray-500">Mitra</p>
                        <span class="inline-block mt-1 px-3 py-1 text-xs 
                            @if($status === 'selesai')
                                bg-green-100 text-green-700
                            @elseif($status === 'proses')
                                bg-yellow-100 text-yellow-700
                            @else
                                bg-red-100 text-red-700
                            @endif
                            rounded-full font-semibold">
                            {{ strtoupper($status) }}
                        </span>
                    </div>
                </div>
                <div class="text-xs text-blue-600 border border-blue-300 rounded-lg px-3 py-1">
                    ID MITRA<br>
                    <span class="font-semibold">{{ $transaction->partner ? str_pad($transaction->partner->id, 6, '0', STR_PAD_LEFT) : '000000' }}</span>
                </div>
            </div>

            <h3 class="font-semibold text-sm">Informasi Mitra</h3>

            @if($transaction->partner)
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nama Lengkap</label>
                    <input type="text" value="{{ $transaction->partner->user->name ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">No. Tlp</label>
                    <input type="text" value="{{ $transaction->partner->user->phone_number ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
                
                @php
                    $primaryVehicle = $transaction->partner->vihecles->first();
                @endphp
                
                @if($primaryVehicle)
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Tipe Kendaraan</label>
                    <input type="text" value="{{ $primaryVehicle->vihecle_type ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Merk Kendaraan</label>
                    <input type="text" value="{{ $primaryVehicle->vihecle_brand ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Nama Kendaraan</label>
                    <input type="text" value="{{ $primaryVehicle->vihecle_name ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs text-gray-600 font-medium">Plat Nomor</label>
                    <input type="text" value="{{ $primaryVehicle->vihecle_plate_number ?? '-' }}" disabled 
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
                @else
                <div class="col-span-2">
                    <p class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg border border-gray-300">Data kendaraan tidak tersedia</p>
                </div>
                @endif
            </div>
            @else
            <p class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg border border-gray-300">Data mitra tidak tersedia</p>
            @endif
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-xl shadow space-y-4">
            <h3 class="font-semibold">Rincian Perjalanan</h3>

            <div class="flex justify-between text-sm text-gray-500">
                <p>{{ $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('d F Y') : '-' }}</p>
                <p>{{ $transaction->time ?? '-' }} WIB</p>
            </div>

            <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-blue-600 font-semibold">Titik Jemput</p>
                    <p class="font-semibold">{{ $transaction->departurePost->terminal_name ?? '-' }}</p>
                    <p class="text-gray-500 mt-2">{{ $transaction->departurePost->terminal_address ?? '-' }}</p>
                    @if($transaction->departurePost)
                    <p class="text-gray-500 text-xs mt-1">
                        {{ $transaction->departurePost->full_location ?? '-' }}
                    </p>
                    @endif
                </div>
                <div>
                    <p class="text-blue-600 font-semibold">Tujuan</p>
                    <p class="font-semibold">{{ $transaction->destinationPost->terminal_name ?? '-' }}</p>
                    <p class="text-gray-500 mt-2">{{ $transaction->destinationPost->terminal_address ?? '-' }}</p>
                    @if($transaction->destinationPost)
                    <p class="text-gray-500 text-xs mt-1">
                        {{ $transaction->destinationPost->full_location ?? '-' }}
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow space-y-4">
            <h3 class="font-semibold">Rincian Pembayaran</h3>

            @if($transaction->payment)
            @php
                $biayaPerNebeng = $transaction->payment->payment_amount ?? 0;
                $biayaAdmin = $biayaPerNebeng * 0.1;
                $total = $biayaPerNebeng + $biayaAdmin;
            @endphp
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span>Type Pembayaran</span>
                    <span class="font-semibold">{{ $transaction->payment->payment_method ?? '-' }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Tanggal</span>
                    <span>{{ $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') : '-' }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>ID Pesanan</span>
                    <span class="font-mono">{{ $transaction->order_number ?? '-' }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>No. Transaksi</span>
                    <span class="font-mono">{{ $transaction->invoice ?? $transaction->payment->invoice_number ?? '-' }}</span>
                </div>

                <hr>

                <div class="flex justify-between text-sm">
                    <span>Biaya Per Nebeng</span>
                    <span>Rp {{ $biayaPerNebeng > 0 ? number_format($biayaPerNebeng, 0, ',', '.') : '-' }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Biaya Admin (10%)</span>
                    <span>Rp {{ $biayaAdmin > 0 ? number_format($biayaAdmin, 0, ',', '.') : '-' }}</span>
                </div>

                <hr>

                <div class="flex justify-between text-sm">
                    <span>Status Pembayaran</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        @if($transaction->payment->status === 'Diterima')
                            bg-green-100 text-green-700
                        @elseif($transaction->payment->status === 'Diproses')
                            bg-yellow-100 text-yellow-700
                        @elseif($transaction->payment->status === 'Ditolak')
                            bg-red-100 text-red-700
                        @endif">
                        {{ $transaction->payment->status ?? '-' }}
                    </span>
                </div>

                <hr>

                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span class="text-green-600">Rp {{ $total > 0 ? number_format($total, 0, ',', '.') : '-' }}</span>
                </div>
            </div>
            @else
            <p class="text-gray-500 text-center py-4 bg-gray-50 rounded-lg border border-gray-300">Belum ada data pembayaran</p>
            @endif
        </div>
    </div>

    <div class="flex justify-end items-center gap-3">
        @if($transaction->payment && $transaction->payment->payment_proof)
        <button onclick="window.open('{{ asset('storage/' . $transaction->payment->payment_proof) }}', '_blank')" 
                class="px-6 py-2 rounded-full bg-blue-600 text-white hover:bg-blue-700">
            Lihat Bukti
        </button>
        @endif

        @if($status === 'proses')
        <form action="{{ route('transactions.accept', $transaction->id) }}" method="POST" class="inline">
            @csrf
            @method('PUT')
            <button type="submit" 
                    class="px-6 py-2 rounded-full bg-green-600 text-white hover:bg-green-700"
                    onclick="return confirm('Terima transaksi ini?\\n\\nPastikan semua data sudah sesuai.')">
                Terima
            </button>
        </form>
        
        <button type="button" 
                onclick="openRejectModal()"
                class="px-6 py-2 rounded-full bg-red-600 text-white hover:bg-red-700">
            Tolak
        </button>
        @endif
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Transaksi</h3>
            <form action="{{ route('transactions.reject', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="rejection_reason" 
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan alasan mengapa transaksi ini ditolak..."
                        required
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">Alasan penolakan akan dicatat untuk keperluan audit.</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" 
                            onclick="closeRejectModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            onclick="return confirm('Apakah Anda yakin ingin menolak transaksi ini?')">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('ID Pesanan berhasil disalin: ' + text);
    }, function(err) {
        console.error('Gagal menyalin: ', err);
        alert('Gagal menyalin. Silakan salin manual: ' + text);
    });
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
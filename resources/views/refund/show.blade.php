@extends('layouts.app')

@section('title', 'Detail Refund')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <div class="bg-blue-100 rounded-2xl p-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($refund->order->customer->user->name ?? 'Customer') }}&size=100&background=3b82f6&color=fff"
                 class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">

            <div>
                <h2 class="text-lg font-semibold">
                    {{ $refund->order->customer->user->name ?? 'Customer' }}
                </h2>

                <p class="text-sm text-gray-600">
                    Refund Order #{{ str_pad($refund->order->id, 3, '0', STR_PAD_LEFT) }}
                </p>

                <span class="inline-block mt-2 px-4 py-1 rounded-full text-xs font-medium 
                    @if($refund->status == 'Diterima') bg-green-500 text-white
                    @elseif($refund->status == 'Diproses') bg-yellow-400 text-white
                    @elseif($refund->status == 'Ditolak') bg-red-600 text-white
                    @endif">
                    {{ $refund->status }}
                </span>
            </div>
        </div>

        <a href="{{ route('refund.index') }}" class="px-4 py-2 bg-blue-200 hover:bg-blue-300 rounded-full text-sm transition">
            Kembali
        </a>
    </div>


    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-6">Informasi Refund</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-600 mb-2 block">No. Order</label>
                <input type="text" disabled
                       value="ORD-{{ str_pad($refund->order->id, 3, '0', STR_PAD_LEFT) }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">No. Transaksi</label>
                <input type="text" disabled
                       value="TRX-{{ str_pad($refund->order->id, 3, '0', STR_PAD_LEFT) }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Tanggal Pengajuan</label>
                <input type="text" disabled
                       value="{{ $refund->created_at->format('d/m/Y H:i') }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Terakhir Update</label>
                <input type="text" disabled
                       value="{{ $refund->updated_at->format('d/m/Y H:i') }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 mb-2 block">Alasan Refund</label>
                <textarea disabled rows="3"
                          class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">{{ $refund->reason ?? 'Tidak ada alasan' }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-6">Informasi Customer</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Nama Lengkap</label>
                <input type="text" disabled
                       value="{{ $refund->order->customer->user->name ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Email</label>
                <input type="text" disabled
                       value="{{ $refund->order->customer->user->email ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">No. Telepon</label>
                <input type="text" disabled
                       value="{{ $refund->order->customer->user->phone_number ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Status Verifikasi</label>
                <input type="text" disabled
                       value="{{ $refund->order->customer->verified_status ?? 'Belum Verifikasi' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-6">Informasi Driver</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Nama Driver</label>
                <input type="text" disabled
                       value="{{ $refund->order->partner->user->name ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Email Driver</label>
                <input type="text" disabled
                       value="{{ $refund->order->partner->user->email ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold mb-6">Informasi Pembayaran</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Metode Pembayaran</label>
                <input type="text" disabled
                       value="{{ $refund->order->payment->payment_method_text ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div>
                <label class="text-sm text-gray-600 mb-2 block">Status Pembayaran</label>
                <input type="text" disabled
                       value="{{ $refund->order->payment->status ?? '-' }}"
                       class="w-full px-4 py-2.5 bg-gray-100 rounded-lg border border-gray-200 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 mb-2 block">Jumlah Refund</label>
                <input type="text" disabled
                       value="Rp {{ number_format((float) ($refund->order->payment->payment_amount ?? 0), 0, ',', '.') }}"
                       class="w-full px-4 py-2.5 bg-blue-50 text-blue-700 font-bold rounded-lg border border-blue-200 text-sm">
            </div>
        </div>
    </div>

    @if($refund->status == 'Diproses')
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h3 class="text-lg font-semibold mb-4">Proses Refund</h3>

        <div class="flex gap-3">
            <button onclick="openAcceptModal()" 
                    class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold transition">
                Terima Refund
            </button>

            <button onclick="openRejectModal()" 
                    class="px-8 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition">
                Tolak Refund
            </button>
        </div>
    </div>
    @endif

</div>

<div id="acceptModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Konfirmasi Refund</h3>
            <p class="text-gray-600 mb-6">
                Anda akan menyetujui refund sebesar 
                <span class="font-bold text-green-600">Rp {{ number_format((float) ($refund->order->payment->payment_amount ?? 0), 0, ',', '.') }}</span>
                kepada <span class="font-semibold">{{ $refund->order->customer->user->name ?? 'Customer' }}</span>.
            </p>
            
            <p class="text-sm text-blue-600 bg-blue-50 p-3 rounded-lg mb-4">
                ℹ️ Saldo customer akan otomatis bertambah sebesar jumlah refund
            </p>
            
            <form action="{{ route('refund.update', $refund->id) }}" method="POST">
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
            <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Refund</h3>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menolak refund sebesar
                <span class="font-bold text-red-600">Rp {{ number_format((float) ($refund->order->payment->payment_amount ?? 0), 0, ',', '.') }}</span>?
            </p>
            
            <form action="{{ route('refund.update', $refund->id) }}" method="POST">
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
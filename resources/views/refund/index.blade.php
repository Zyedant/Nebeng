@extends('layouts.app')

@section('title', 'Daftar Refund')

@section('content')
<div class="bg-white rounded-xl shadow p-6 space-y-6">

    <div class="px-6 pt-6 pb-4">
        <h1 class="text-lg font-semibold text-gray-800 mb-4">
            Daftar Refund
        </h1>

        <div class="flex items-center justify-between">
            <form action="{{ route('refund.index') }}" method="GET" class="relative w-64">
                <input
                    type="text"
                    name="search"
                    placeholder="Search..."
                    value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M16.65 10.825a5.825 5.825 0 11-11.65 0 5.825 5.825 0 0111.65 0z" />
                </svg>
            </form>

            <form action="{{ route('refund.index') }}" method="GET">
                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="Status" {{ request('status') === 'Status' || request('status') === '' ? 'selected' : '' }}>Status</option>
                    <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Proses</option>
                    <option value="Diterima" {{ request('status') === 'Diterima' ? 'selected' : '' }}>Selesai</option>
                    <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Batal</option>
                </select>
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">NO.</th>
                    <th class="px-6 py-3 text-left font-medium">NO. ORDER</th>
                    <th class="px-6 py-3 text-left font-medium">NAMA CUSTOMER</th>
                    <th class="px-6 py-3 text-left font-medium">NAMA DRIVER</th>
                    <th class="px-6 py-3 text-left font-medium">TANGGAL</th>
                    <th class="px-6 py-3 text-left font-medium">NO. TRANSAKSI</th>
                    <th class="px-6 py-3 text-left font-medium">JUMLAH REFUND</th>
                    <th class="px-6 py-3 text-left font-medium">STATUS</th>
                    <th class="px-6 py-3 text-center font-medium">AKSI</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($refunds as $refund)
                @php
                    $order = $refund->order;
                    $paymentAmount = $order->payment->payment_amount ?? 0;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">
                        {{ ($refunds->currentPage() - 1) * $refunds->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        {{ $order->customer->user->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        {{ $order->partner->user->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        TRX-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        Rp {{ $paymentAmount > 0 ? number_format($paymentAmount, 0, ',', '.') : '-' }}
                    </td>

                    <td class="px-6 py-4">
                        @if ($refund->status === 'Diterima')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                         bg-green-500 text-white">
                                SELESAI
                            </span>
                        @elseif ($refund->status === 'Diproses')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                         bg-yellow-400 text-white">
                                PROSES
                            </span>
                        @elseif ($refund->status === 'Ditolak')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                         bg-red-600 text-white">
                                BATAL
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                         bg-gray-400 text-white">
                                {{ $refund->status }}
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('refund.show', $refund->id) }}"
                           class="inline-flex w-8 h-8 items-center justify-center
                                  bg-gray-400 hover:bg-gray-500 text-white rounded-md
                                  transition"
                           title="Lihat Detail Refund">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5
                                         c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7
                                         -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        Tidak ada data refund ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 flex items-center justify-between
                text-sm text-gray-600 border-t">
        <div>
            Showing {{ $refunds->firstItem() ?? 0 }} to {{ $refunds->lastItem() ?? 0 }} 
            of {{ $refunds->total() }} entries
        </div>

        <div class="flex items-center gap-2">
            @if ($refunds->onFirstPage())
                <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $refunds->previousPageUrl() }}" 
                   class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            @foreach ($refunds->getUrlRange(1, $refunds->lastPage()) as $page => $url)
                @if ($page == $refunds->currentPage())
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded border border-blue-200">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" 
                       class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if ($refunds->hasMorePages())
                <a href="{{ $refunds->nextPageUrl() }}" 
                   class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </div>

</div>
@endsection
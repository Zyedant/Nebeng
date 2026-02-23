@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="bg-white rounded-xl shadow p-6 space-y-6">

    <div class="px-6 pt-6 pb-4">
        <h1 class="text-lg font-semibold text-gray-800 mb-4">
            Daftar Transaksi
        </h1>

        <div class="flex items-center justify-between">
            <form action="{{ route('transactions.index') }}" method="GET" class="relative w-64">
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

            <form action="{{ route('transactions.index') }}" method="GET">
                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="Status" {{ request('status') === 'Status' ? 'selected' : '' }}>Status</option>
                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Proses" {{ request('status') === 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Batal" {{ request('status') === 'Batal' ? 'selected' : '' }}>Batal</option>
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
                    <th class="px-6 py-3 text-left font-medium">TANGGAL</th>
                    <th class="px-6 py-3 text-left font-medium">NAMA DRIVER</th>
                    <th class="px-6 py-3 text-left font-medium">NAMA CUSTOMER</th>
                    <th class="px-6 py-3 text-left font-medium">NO. TRANSAKSI</th>
                    <th class="px-6 py-3 text-left font-medium">NO. ORDERAN</th>
                    <th class="px-6 py-3 text-left font-medium">STATUS</th>
                    <th class="px-6 py-3 text-center font-medium">AKSI</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach ($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">
                        {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $transaction->date ? \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') : '-' }} 
                        {{ $transaction->time ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        {{ $transaction->partner->user->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        {{ $transaction->customer->user->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $transaction->invoice ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $transaction->order_number ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
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
                        
                        @if ($status === 'selesai')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-500 text-white">
                                SELESAI
                            </span>
                        @elseif ($status === 'proses')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-400 text-white">
                                PROSES
                            </span> 
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-600 text-white">
                                BATAL
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center">
                        <a
                            href="{{ route('transactions.show', $transaction->id) }}"
                            class="inline-flex w-8 h-8 items-center justify-center
                                   bg-gray-400 hover:bg-gray-500 text-white
                                   rounded-md transition"
                            title="Lihat Detail Transaksi"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7
                                         -4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
                
                @if($transactions->isEmpty())
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        Tidak ada data transaksi ditemukan.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 flex items-center justify-between text-sm text-gray-600 border-t">
        <div>
            Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} 
            of {{ $transactions->total() }} entries
        </div>

        <div class="flex items-center gap-2">
            @if ($transactions->onFirstPage())
                <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $transactions->previousPageUrl() }}" 
                   class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                @if ($page == $transactions->currentPage())
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

            @if ($transactions->hasMorePages())
                <a href="{{ $transactions->nextPageUrl() }}" 
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
@extends('layouts.app')

@section('title', 'Pencairan Dana Mitra')

@section('content')
<div class="bg-white rounded-xl shadow p-6 space-y-6">

    <div class="px-6 pt-6 pb-4">
        <h1 class="text-lg font-semibold text-gray-800 mb-4">
            Pencairan Dana Mitra
        </h1>

        <div class="flex items-center justify-between">
            <form action="{{ route('withdrawals.partner.index') }}" method="GET" class="relative w-64">
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

            <form action="{{ route('withdrawals.partner.index') }}" method="GET">
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
                    <th class="px-6 py-3 text-left font-medium">NAMA DRIVER</th>
                    <th class="px-6 py-3 text-left font-medium">JUMLAH PENCAIRAN</th>
                    <th class="px-6 py-3 text-left font-medium">TANGGAL</th>
                    <th class="px-6 py-3 text-left font-medium">STATUS</th>
                    <th class="px-6 py-3 text-center font-medium">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($withdrawals as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">
                        {{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        <div class="font-medium">{{ $item->partner->user->name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-green-600 font-medium">
                        Rp {{ $item->amount > 0 ? number_format($item->amount, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($item->status == 'Diterima')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white">
                                SELESAI
                            </span>
                        @elseif($item->status == 'Diproses')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-white">
                                PROSES
                            </span>
                        @elseif($item->status == 'Ditolak')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">
                                BATAL
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-400 text-white">
                                {{ $item->status ?? '-' }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('withdrawals.partner.show', $item->id) }}"
                           class="inline-flex w-8 h-8 items-center justify-center
                                  bg-gray-400 hover:bg-gray-500 text-white rounded-md
                                  transition"
                           title="Lihat Detail Pencairan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5
                                         c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7
                                         -4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z"/>
                            </svg>
                            <p class="text-gray-600">Tidak ada data pencairan mitra ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 flex items-center justify-between text-sm text-gray-600 border-t">
        <div>
            Showing {{ $withdrawals->firstItem() ?? 0 }} to {{ $withdrawals->lastItem() ?? 0 }} 
            of {{ $withdrawals->total() }} entries
        </div>

        <div class="flex items-center gap-2">
            @if ($withdrawals->onFirstPage())
                <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $withdrawals->previousPageUrl() }}" 
                   class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            @foreach ($withdrawals->getUrlRange(1, $withdrawals->lastPage()) as $page => $url)
                @if ($page == $withdrawals->currentPage())
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

            @if ($withdrawals->hasMorePages())
                <a href="{{ $withdrawals->nextPageUrl() }}" 
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
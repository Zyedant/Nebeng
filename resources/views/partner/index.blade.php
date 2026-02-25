@extends('layouts.app')

@section('title', 'Data Mitra')

@section('content')
<div class="bg-white rounded-xl shadow p-6 space-y-6">

    <div class="px-6 pt-6 pb-4">
        <h1 class="text-lg font-semibold text-gray-800 mb-4">
            Daftar Mitra
        </h1>

        <div class="flex items-center justify-between">
            <form action="{{ route('partner.index') }}" method="GET" class="relative w-64">
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
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left font-medium">ID</th>
                    <th class="px-6 py-3 text-left font-medium">NAMA</th>
                    <th class="px-6 py-3 text-left font-medium">EMAIL</th>
                    <th class="px-6 py-3 text-left font-medium">NO. TLP</th>
                    <th class="px-6 py-3 text-left font-medium">LAYANAN</th>
                    <th class="px-6 py-3 text-center font-medium">AKSI</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($partnervihecles as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">
                        {{ $item['partner']->user_id ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        {{ $item['partner']->user->name ?? $item['partner']->id_fullname ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $item['partner']->user->email ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $item['partner']->user->phone_number ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $item['vihecle']->vihecle_type ?? 'Motor' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a
                            href="{{ route('partner.show', $item['partner']->id) }}"
                            class="inline-flex w-8 h-8 items-center justify-center
                                   bg-gray-400 hover:bg-gray-500 text-white
                                   rounded-md transition"
                            title="Lihat Detail Mitra"
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
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        Tidak ada data mitra ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 flex items-center justify-between text-sm text-gray-600 border-t">
        <div>
            Showing {{ $partners->firstItem() ?? 0 }} to {{ $partners->lastItem() ?? 0 }} 
            of {{ $partners->total() }} entries
        </div>

        <div class="flex items-center gap-2">
            @if ($partners->onFirstPage())
                <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $partners->previousPageUrl() }}" 
                   class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            @foreach ($partners->getUrlRange(1, $partners->lastPage()) as $page => $url)
                @if ($page == $partners->currentPage())
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

            @if ($partners->hasMorePages())
                <a href="{{ $partners->nextPageUrl() }}" 
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
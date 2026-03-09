@extends('layouts.app')

@section('title', 'Daftar Pos Mitra')

@section('content')
<div class="bg-white rounded-xl shadow p-6 space-y-6">

    <div class="px-6 pt-6 pb-4">
        <h1 class="text-lg font-semibold text-gray-800 mb-4">
            Daftar Pos Mitra
        </h1>

        <div class="relative w-64">
            <input
                type="text"
                placeholder="Search"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M16.65 10.825a5.825 5.825 0 11-11.65 0 5.825 5.825 0 0111.65 0z" />
            </svg>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left font-medium w-12">NO</th>
                    <th class="px-6 py-3 text-left font-medium">NAMA</th>
                    <th class="px-6 py-3 text-left font-medium">KODE REFERRAL</th>
                    <th class="px-6 py-3 text-left font-medium">TERMINAL</th>
                    <th class="px-6 py-3 text-left font-medium">ALAMAT TERMINAL</th>
                    <th class="px-6 py-3 text-center font-medium w-16">AKSI</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach ($partnerPosts as $index => $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        {{ $post->id_fullname }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $post->id_number ?? 'sssad23' }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 font-medium">
                        {{ $post->terminal_name }}
                    </td>
                    <td class="px-6 py-4 text-gray-600 leading-relaxed">
                        {{ $post->terminal_address }}
                        @if ($post->district || $post->regency)
                            <br>
                            {{ $post->district?->name }},
                            {{ $post->regency?->name }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('partner-post.show', $post->id) }}"
                            class="inline-flex w-8 h-8 items-center justify-center
                                   bg-gray-400 hover:bg-gray-500
                                   text-white rounded-md transition"
                            title="Lihat Detail">
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
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 flex items-center justify-between text-sm text-gray-600">
        <div>
            10 <span class="text-gray-400">of 120 entries</span>
        </div>

        <div class="flex items-center gap-2">
            <button class="px-2 py-1 rounded hover:bg-gray-100">‹</button>
            <button class="px-3 py-1 bg-blue-50 text-blue-600 rounded">1</button>
            <button class="px-3 py-1 hover:bg-gray-100 rounded">2</button>
            <button class="px-3 py-1 hover:bg-gray-100 rounded">3</button>
            <span class="px-2">…</span>
            <button class="px-3 py-1 hover:bg-gray-100 rounded">6</button>
            <button class="px-2 py-1 rounded hover:bg-gray-100">›</button>
        </div>
    </div>

</div>
@endsection
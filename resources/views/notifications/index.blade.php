@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Semua Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Total {{ $notifications->total() }} notifikasi</p>
        </div>
        
        <div class="flex items-center gap-3">
            <select onchange="window.location.href = this.value" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                <option value="{{ route('notifications.index') }}" {{ !request('filter') ? 'selected' : '' }}>
                    Semua
                </option>
                <option value="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                        {{ request('filter') == 'unread' ? 'selected' : '' }}>
                    Belum Dibaca ({{ $unreadCount }})
                </option>
                <option value="{{ route('notifications.index', ['filter' => 'read']) }}" 
                        {{ request('filter') == 'read' ? 'selected' : '' }}>
                    Sudah Dibaca
                </option>
            </select>

            @if($unreadCount > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                    Tandai Semua Sudah Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @forelse($notifications as $notification)
        <div class="flex items-start gap-4 px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition
                    {{ !$notification->read ? 'bg-blue-50/30' : '' }}">
            
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl flex-shrink-0
                        {{ !$notification->read ? 'bg-blue-100' : 'bg-gray-100' }}">
                @switch($notification->type ?? 'default')
                    @case('payment')
                        💳
                        @break
                    @case('refund')
                        🔄
                        @break
                    @case('withdrawal')
                        💰
                        @break
                    @default
                        🔔
                @endswitch
            </div>

            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-semibold text-gray-900 {{ !$notification->read ? 'text-blue-900' : '' }}">
                            {{ $notification->title }}
                        </h4>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $notification->description }}</p>
                    </div>
                    
                    <div class="flex items-center gap-3 ml-4">
                        <span class="text-xs text-gray-400 whitespace-nowrap">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                        
                        @if(!$notification->read)
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        @endif
                    </div>
                </div>

                @if($notification->link)
                <div class="mt-2">
                    <a href="{{ $notification->link }}" 
                       class="text-xs text-blue-600 hover:text-blue-700 inline-flex items-center gap-1"
                       onclick="markAsRead({{ $notification->id }})">
                        Lihat Detail
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-1">Tidak ada notifikasi</h4>
            <p class="text-sm text-gray-500">
                @if(request('filter') == 'unread')
                    Tidak ada notifikasi yang belum dibaca
                @elseif(request('filter') == 'read')
                    Tidak ada notifikasi yang sudah dibaca
                @else
                    Belum ada notifikasi
                @endif
            </p>
        </div>
        @endforelse

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $notifications->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}
</script>
@endpush
@endsection
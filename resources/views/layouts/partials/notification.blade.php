@php
    use App\Models\Notification;
    $recentNotifications = Notification::where('user_id', auth()->id())
        ->latest()
        ->take(5)
        ->get();
    $unreadCount = Notification::where('user_id', auth()->id())
        ->where('read', false)
        ->count();
@endphp

<div class="relative" x-data="notificationHandler()" x-init="init()">
    <button @click="toggleDropdown" class="relative p-2 hover:bg-gray-100 rounded-lg transition">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-semibold" 
                  x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </template>
    </button>

    <div x-show="open" @click.outside="closeDropdown" 
         class="absolute right-0 mt-3 w-[420px] bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-bold text-gray-900">Notifikasi</h3>
            <div class="flex items-center gap-3">
                <template x-if="notifications.length > 0">
                    <span class="text-xs text-gray-500" x-text="unreadCount + ' belum dibaca'"></span>
                </template>
                <button @click="closeDropdown" class="text-gray-400 hover:text-gray-600 text-xl font-bold leading-none">
                    ×
                </button>
            </div>
        </div>

        <div class="max-h-[500px] overflow-y-auto divide-y divide-gray-100">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="px-6 py-4 hover:bg-gray-50 transition"
                     :class="{ 'bg-blue-50/30': !notification.read }">
                    
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl flex-shrink-0"
                             :class="notification.read ? 'bg-gray-100' : 'bg-blue-100'">
                            <span x-text="notification.icon || '🔔'"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm mb-0.5 leading-tight" 
                               x-text="notification.title"></p>
                            <p class="text-xs text-gray-500 leading-tight line-clamp-2" 
                               x-text="notification.description"></p>
                            
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs text-gray-400" 
                                      x-text="notification.time || 'baru saja'"></span>
                                
                                <template x-if="!notification.read">
                                    <>
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span class="text-xs text-blue-600">Belum dibaca</span>
                                    </>
                                </template>
                            </div>

                            <template x-if="notification.link">
                                <div class="mt-2">
                                    <a :href="notification.link" 
                                       @click="markAsRead(notification.id)"
                                       class="text-xs text-blue-600 hover:text-blue-700 inline-flex items-center gap-1">
                                        Lihat Detail
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </template>
                        </div>

                        <template x-if="!notification.read">
                            <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="notifications.length === 0">
                <div class="p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Tidak ada notifikasi</h4>
                    <p class="text-sm text-gray-500">Belum ada notifikasi untuk Anda</p>
                </div>
            </template>
        </div>

        <template x-if="notifications.length > 0">
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <button @click="markAllAsRead" 
                            class="text-xs text-gray-500 hover:text-gray-700 transition">
                        Tandai semua sudah dibaca
                    </button>
                    <a href="{{ route('notifications.index') }}" 
                       class="text-sm text-blue-600 hover:text-blue-700 font-medium transition"
                       @click="closeDropdown">
                        Lihat Semua Notifikasi →
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function notificationHandler() {
    return {
        open: false,
        notifications: @json($recentNotifications),
        unreadCount: {{ $unreadCount }},
        
        init() {
            setInterval(() => {
                this.loadNotifications();
            }, 30000);
        },
        
        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.loadNotifications();
            }
        },
        
        closeDropdown() {
            this.open = false;
        },
        
        loadNotifications() {
            fetch('{{ route("notifications.latest") }}')
                .then(response => response.json())
                .then(data => {
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                })
                .catch(error => console.error('Error loading notifications:', error));
        },
        
        markAsRead(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notification = this.notifications.find(n => n.id === id);
                    if (notification) {
                        notification.read = true;
                    }
                    this.unreadCount = this.notifications.filter(n => !n.read).length;
                }
            })
            .catch(error => console.error('Error marking as read:', error));
        },
        
        markAllAsRead() {
            fetch('{{ route("notifications.read-all") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.notifications.forEach(n => n.read = true);
                    this.unreadCount = 0;
                    
                    alert('Semua notifikasi telah ditandai sudah dibaca');
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
        }
    }
}
</script>
@endpush
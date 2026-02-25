<aside class="w-64 bg-gradient-to-b from-[#1e3a8a] to-[#1e40af] text-white flex flex-col fixed h-screen">
    
    <div class="px-6 py-6 border-b border-blue-700/30">
        <h1 class="text-xl font-bold tracking-tight">NEBENG</h1>
        <p class="text-xs text-blue-200 mt-1">TRANSPORTASI MENJADI LEBIH MUDAH</p>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1">
        <div class="text-xs font-semibold text-blue-300 px-3 mb-3">MAIN MENU</div>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('transactions.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span class="font-medium">Transaksi</span>
        </a>

        <a href="{{ route('partner.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('partner.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="font-medium">Mitra</span>
        </a>

        <a href="{{ route('partner-post.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('partner-post.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="font-medium">Pos Mitra</span>
        </a>

        <div class="relative">
            <button 
                onclick="toggleDropdown('pencairanDropdown')"
                class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('withdrawals.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition"
            >
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Pencairan Dana</span>
                </div>
                <svg 
                    class="w-4 h-4 transition-transform duration-200"
                    id="pencairanDropdownIcon"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="pencairanDropdown" class="hidden mt-1 space-y-1 pl-3">
                <a 
                    href="{{ route('withdrawals.partner.index') }}" 
                    class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('withdrawals.partner.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-sm">Pencairan Mitra</span>
                </a>
                <a 
                    href="{{ route('withdrawals.partner-post.index') }}" 
                    class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('withdrawals.partner-post.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 21v-4H7v4M7 3v4h10V3" />
                    </svg>
                    <span class="text-sm">Pencairan Pos Mitra</span>
                </a>
            </div>
        </div>

        <a href="{{ route('refund.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('refund.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
            </svg>
            <span class="font-medium">Refund</span>
        </a>

        <div class="text-xs font-semibold text-blue-300 px-3 mb-3 mt-6">HELP & SUPPORT</div>
        
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-blue-700/50' : 'hover:bg-blue-700/30' }} transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="font-medium">Pengaturan</span>
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-blue-700/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 w-full rounded-lg hover:bg-blue-700/30 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="font-medium">Keluar</span>
            </button>
        </form>
    </div>
</aside>

@push('scripts')
<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        const icon = document.getElementById(dropdownId + 'Icon');
        
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.href.includes('withdrawals')) {
            const dropdown = document.getElementById('pencairanDropdown');
            const icon = document.getElementById('pencairanDropdownIcon');
            if (dropdown && icon) {
                dropdown.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }
    });
</script>
@endpush
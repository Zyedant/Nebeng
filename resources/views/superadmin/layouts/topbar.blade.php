{{-- resources/views/superadmin/layouts/topbar.blade.php --}}

@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Str;

  $u = Auth::user();
  $displayName = $u?->name ?: ($u?->username ?: ($u?->email ?: 'Admin'));
  $initial = strtoupper(substr($displayName, 0, 1));

  // ===== Avatar URL (support: "avatars/xx.png" OR "storage/avatars/xx.png" OR full url) =====
  $avatarUrl = null;
  if (!empty($u?->image)) {
      $img = $u->image;

      if (Str::startsWith($img, ['http://', 'https://'])) {
          $avatarUrl = $img; // full url
      } elseif (Str::startsWith($img, 'storage/')) {
          $avatarUrl = asset($img); // already storage/...
      } else {
          // assume stored in storage/app/public -> accessible via /storage symlink
          $avatarUrl = asset('storage/' . ltrim($img, '/'));
      }
  }

  // variabel dari AppServiceProvider (atau composer view)
  $topbarUnreadCount = $topbarUnreadCount ?? 0;
  $topbarNotifications = $topbarNotifications ?? collect();

  // helper styling notifikasi (type => bg + icon)
  function notifStyle($type) {
      return match($type) {
          'refund' => ['bg' => 'bg-blue-50', 'icon' => '💸'],
          'laporan' => ['bg' => 'bg-red-50', 'icon' => '⚠️'],
          'verifikasi_mitra' => ['bg' => 'bg-green-100', 'icon' => '🟢'],
          'verifikasi_customer' => ['bg' => 'bg-emerald-100', 'icon' => '✅'],
          'transaksi' => ['bg' => 'bg-purple-50', 'icon' => '🧾'],
          'pos_mitra' => ['bg' => 'bg-indigo-50', 'icon' => '📍'],
          'akun_update' => ['bg' => 'bg-orange-100', 'icon' => '🟠'],
          default => ['bg' => 'bg-gray-100', 'icon' => '🔔'],
      };
  }
@endphp

<header class="px-8 pt-7">
    <div class="flex items-center justify-between">

        {{-- Greeting --}}
        <div class="font-semibold text-[28px] leading-[28px] tracking-[0px] text-slate-900">
            Selamat Datang, {{ $displayName }} 👋
        </div>

        <div class="flex items-center gap-4">

            {{-- Search --}}
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10.5 18a7.5 7.5 0 1 1 0-15a7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </span>

                <input
                    type="text"
                    placeholder="Search"
                    class="w-[280px] bg-white rounded-full pl-10 pr-4 py-2 text-sm border border-slate-200
                           focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-300"
                />
            </div>

            <div class="h-7 w-px bg-slate-200"></div>

            {{-- Notification --}}
            <button
                type="button"
                class="relative bg-white rounded-lg border border-slate-200 w-10 h-10 flex items-center justify-center
                       cursor-pointer pointer-events-auto"
                onclick="document.getElementById('notifModal')?.classList.remove('hidden')"
                title="Notifikasi"
            >
                <svg class="w-5 h-5 text-slate-500" viewBox="0 0 24 24" fill="none">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.7 21a2 2 0 0 1-3.4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>

                @if($topbarUnreadCount > 0)
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </button>

            {{-- Profile dropdown --}}
            <div class="relative">
                <button
                    type="button"
                    class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 bg-white flex items-center justify-center font-semibold"
                    onclick="document.getElementById('profileDropdown')?.classList.toggle('hidden')"
                    title="Menu"
                >
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" class="w-full h-full object-cover" alt="avatar">
                    @else
                        <span class="text-slate-800">{{ $initial }}</span>
                    @endif
                </button>

                <div id="profileDropdown"
                     class="hidden absolute right-0 mt-3 w-[260px] bg-white rounded-2xl
                            shadow-[0_12px_30px_rgba(0,0,0,0.18)]
                            border border-slate-100 z-[999] overflow-hidden">

                    <a
                       href="{{ route('profile.edit') }}"
                        class="flex items-center justify-between px-6 py-4 text-slate-900 hover:bg-slate-50 transition"
                    >
                        <span class="text-[16px] font-medium">View Profil</span>
                        <svg class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none">
                            <path d="M12 12a4 4 0 1 0-4-4a4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M20 21c-1.6-4-12.4-4-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>

                    <div class="h-px bg-slate-100"></div>

                    <a
                        href="#"
                        onclick="event.preventDefault(); document.getElementById('logoutForm')?.submit();"
                        class="flex items-center justify-between px-6 py-4 text-slate-900 hover:bg-slate-50 transition"
                    >
                        <span class="text-[16px] font-medium">Log out</span>
                        <svg class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none">
                            <path d="M10 7v-1a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2v-1" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M15 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M6 9l-3 3 3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- logout form --}}
    <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

    {{-- Modal Notifikasi --}}
    <div id="notifModal" class="hidden fixed inset-0 z-[999]">
        <div class="absolute inset-0 bg-black/20"
             onclick="document.getElementById('notifModal').classList.add('hidden')"></div>

        <div class="absolute left-1/2 top-[110px] -translate-x-1/2 w-[520px] max-w-[92vw] bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="text-[24px] font-semibold text-slate-900">Notifikasi</div>

                <div class="flex items-center gap-3">
                    @if($topbarUnreadCount > 0)
                        <form method="POST" action="{{ route('sa.notifications.readAll') }}">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:underline">
                                Tandai semua dibaca
                            </button>
                        </form>
                    @endif

                    <button class="text-slate-500 text-xl leading-none"
                            onclick="document.getElementById('notifModal').classList.add('hidden')">×</button>
                </div>
            </div>

            <div class="h-px bg-slate-200"></div>

            <div class="max-h-[420px] overflow-y-auto">
                @if($topbarNotifications->isEmpty())
                    <div class="px-6 py-5 text-slate-500 text-sm">
                        Belum ada notifikasi.
                    </div>
                @else
                    @foreach($topbarNotifications as $n)
                        @php $style = notifStyle($n->type ?? null); @endphp

                        <div class="px-6 py-4 border-b border-slate-100">
                            <div class="flex items-start gap-4">

                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $style['bg'] }}">
                                    <span class="text-lg">{{ $style['icon'] }}</span>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-semibold text-slate-900">
                                                {{ $n->title }}
                                            </div>

                                            @if(!(bool)$n->read)
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">
                                                    Baru
                                                </span>
                                            @endif
                                        </div>

                                        <div class="text-xs text-slate-400">
                                            {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}
                                        </div>
                                    </div>

                                    <div class="text-sm text-slate-500 mt-1">
                                        {{ $n->description }}
                                    </div>

                                    @if(!(bool)$n->read)
                                        <div class="mt-2">
                                            <form method="POST" action="{{ route('sa.notifications.read', $n->id) }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-green-600 hover:underline">
                                                    Tandai dibaca
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('click', function(e){
    const dd = document.getElementById('profileDropdown');
    if(!dd) return;

    const btn = dd.previousElementSibling;
    const isClickInside = dd.contains(e.target) || (btn && btn.contains(e.target));

    if(!isClickInside) dd.classList.add('hidden');
});
</script>
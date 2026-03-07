<div class="logo">
  <div style="font-size:20px; font-weight:bold;">NEBENG</div>
  <div style="font-size:11px; color:rgba(255,255,255,.7); margin-top:6px;">
    TRANSPORTASI MENJADI LEBIH MUDAH
  </div>
</div>

<div class="menu">
  <small>MAIN MENU</small>
  <div class="nav">
    <a href="{{ route('sa.dashboard') }}" class="{{ request()->routeIs('sa.dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
    <a href="{{ route('sa.verifikasi') }}" class="{{ request()->routeIs('sa.verifikasi') ? 'active' : '' }}">✅ Verifikasi Data</a>
    <a href="{{ route('sa.mitra') }}" class="{{ request()->routeIs('sa.mitra') ? 'active' : '' }}">👥 Mitra</a>
    <a href="{{ route('sa.customer') }}" class="{{ request()->routeIs('sa.customer') ? 'active' : '' }}">🙋 Customer</a>
    <a href="{{ route('sa.transaksi') }}" class="{{ request()->routeIs('sa.transaksi') ? 'active' : '' }}">🧾 Pesanan</a>
    <a href="{{ route('sa.refund') }}" class="{{ request()->routeIs('sa.refund') ? 'active' : '' }}">💸 Refund</a>
    <a href="{{ route('sa.laporan') }}" class="{{ request()->routeIs('sa.laporan') ? 'active' : '' }}">📄 Laporan</a>
  </div>

  <small style="margin-top:18px;">HELP & SUPPORT</small>
  <div class="nav">
    <a href="{{ route('sa.pengaturan') }}" class="{{ request()->routeIs('sa.pengaturan') ? 'active' : '' }}">⚙️ Pengaturan</a>
  </div>
</div>

<div class="logout">
  <button onclick="alert('Logout sementara (mock). Nanti pakai logout asli dari auth temanmu.')">
    🚪 Keluar
  </button>
</div>

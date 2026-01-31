@php
  // sementara hardcode. nanti ganti jadi Auth::user()->name
  $name = "Kaori";
@endphp

<div class="greet">Selamat Datang, {{ $name }} 👋</div>

<div class="top-actions">
  <input class="search" placeholder="Search" />

  <button class="icon-btn" title="Notifikasi">
    🔔
    <span class="dot"></span>
  </button>

  <button class="profile" title="Profile">
    <div class="avatar"></div>
    <div class="pname">{{ $name }}</div>
    <div class="caret">▾</div>
  </button>
</div>

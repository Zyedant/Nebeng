@php $name = "Kaori"; @endphp

<div class="topbar">
  <div style="font-size:18px; font-weight:600;">Selamat Datang, {{ $name }} 👋</div>

  <div style="display:flex; align-items:center; gap:14px;">
    <input class="search" placeholder="Search" />

    <button style="width:36px; height:36px; border-radius:999px; border:0; background:#F1F5F9; cursor:pointer; position:relative;">
      🔔
      <span style="position:absolute; top:6px; right:8px; width:8px; height:8px; background:red; border-radius:999px;"></span>
    </button>

    <button style="display:flex; align-items:center; gap:10px; border:0; background:transparent; cursor:pointer;">
      <div style="width:32px; height:32px; border-radius:999px; background:#CBD5E1;"></div>
      <div style="font-weight:600; font-size:14px;">{{ $name }}</div>
      <div style="font-size:12px;">▾</div>
    </button>
  </div>
</div>

@extends('superadmin.layouts.app')

@section('content')
  {{-- Summary Cards (kosong / 0) --}}
  <div class="cardgrid">
    <div class="card"><div class="t">Total Customer</div><div class="v">0</div></div>
    <div class="card"><div class="t">Total Mitra</div><div class="v">0</div></div>
    <div class="card"><div class="t">Transaksi Berjalan</div><div class="v">0</div></div>
    <div class="card"><div class="t">Transaksi Selesai</div><div class="v">0</div></div>
    <div class="card"><div class="t">Pending Verifikasi</div><div class="v">0</div></div>
    <div class="card"><div class="t">Pending Refund</div><div class="v">0</div></div>
  </div>

  <div class="section-head">
    <div class="section-title">Transaksi Terbaru</div>
    <a href="{{ route('sa.transaksi') }}" class="btn-dark">Lihat Semua</a>
  </div>

  {{-- Empty state transaksi --}}
  <div class="tablebox" style="padding:28px;">
    <div style="text-align:center; padding:22px 10px;">
      <div style="font-size:48px; line-height:1;">🗂️</div>
      <div style="margin-top:10px; font-weight:800; font-size:16px;">Belum ada transaksi</div>
      <div class="muted" style="margin-top:6px;">
        Data transaksi akan muncul setelah customer melakukan pemesanan.
      </div>

      <div style="margin-top:14px;">
        <a href="{{ route('sa.verifikasi') }}" class="btn-dark" style="background:#0B3E7A;">
          Cek Verifikasi Data
        </a>
      </div>
    </div>
  </div>
@endsection

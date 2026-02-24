@extends('superadmin.layouts.app')

@section('content')
@php $isEdit = request('edit') == 1; @endphp

@push('modals')
<div id="modalVehicleSaved"
     class="fixed inset-0 z-[2147483647] hidden items-center justify-center bg-black/40">
  <div class="bg-white w-[520px] max-w-[92vw] rounded-2xl p-6 text-center shadow-xl">
    <h2 class="text-[22px] font-semibold text-slate-800 mb-6">
      Data yang diedit Berhasil Di Simpan
    </h2>

    <div class="flex justify-center mb-6">
      <div class="w-[110px] h-[110px] rounded-2xl bg-slate-50 flex items-center justify-center relative">
        <svg class="w-14 h-14 text-slate-400" viewBox="0 0 24 24" fill="none">
          <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="currentColor" stroke-width="2"/>
          <path d="M20 21a8 8 0 1 0-16 0" stroke="currentColor" stroke-width="2"/>
        </svg>
        <div class="absolute -top-2 -right-2 w-9 h-9 rounded-full bg-emerald-500 flex items-center justify-center">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
            <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      </div>
    </div>

    <button type="button"
      class="h-10 px-8 rounded-lg bg-[#0B3A82] text-white text-sm"
      onclick="closeVehicleSavedModal()">
      Oke
    </button>
  </div>
</div>
@endpush

@push('scripts')
<script>
  function openVehicleSavedModal() {
    const el = document.getElementById('modalVehicleSaved');
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('flex');
  }

  function closeVehicleSavedModal() {
    const el = document.getElementById('modalVehicleSaved');
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
  }

  @if(session('vehicle_updated_success'))
    openVehicleSavedModal();
  @endif
</script>
@endpush

<div class="px-4 md:px-6 py-6 font-['Urbanist']">

  <div class="flex items-center justify-between mb-4">
    <div>
      <h1 class="text-[22px] font-semibold text-slate-800">Detail Kendaraan Mitra</h1>
      <p class="text-[12px] text-slate-500">ID Kendaraan: {{ $data->vehicle_id }}</p>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('sa.mitra.kendaraan') }}"
         class="h-9 px-4 rounded-lg border border-slate-200 bg-white text-slate-700 text-[13px] flex items-center gap-2 hover:bg-slate-50">
        Kembali
      </a>

      <button type="submit" form="formKendaraan" id="btnSimpan"
              class="h-9 px-4 rounded-lg bg-[#0B3A82] text-white text-[13px] {{ $isEdit ? '' : 'hidden' }}">
        Simpan
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-4 p-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 text-sm">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-4 p-3 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">

    <div class="grid grid-cols-12 gap-6">

      {{-- FOTO KENDARAAN (KIRI) --}}
      <div class="col-span-12 md:col-span-4">
        @php
          $img = $data->image ?? null;
          $hasImage = $img && !str_contains($img, 'via.placeholder.com');
        @endphp

        @if($hasImage)
          <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50">
            <img
              src="{{ $img }}"
              alt="vehicle"
              class="w-full h-[220px] object-cover"
              onerror="this.onerror=null; this.src='https://via.placeholder.com/400x220';"
            >
          </div>
        @else
          <div class="rounded-2xl border border-dashed border-slate-200 bg-white h-[220px] flex items-center justify-center text-center p-6">
            <div>
              <div class="text-sm text-slate-600">Foto kendaraan belum ada</div>
              <div class="text-xs text-slate-400 mt-1">Data vehicle_image kosong / file tidak ditemukan</div>
            </div>
          </div>
        @endif

        <div class="mt-3 text-[12px] text-slate-500">
          Sumber tabel: <span class="font-semibold">{{ $source }}</span>
        </div>
      </div>

      {{-- FORM KENDARAAN (KANAN) --}}
      <div class="col-span-12 md:col-span-8">
        <form id="formKendaraan"
              method="POST"
              action="{{ route('sa.mitra.kendaraan.update', ['id' => $data->vehicle_id]) }}">
          @csrf
          @method('PUT')

          <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
              <label class="text-xs text-slate-600">Nama Mitra</label>
              <input value="{{ $data->name }}"
                     class="mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm bg-slate-50"
                     readonly>
            </div>

            <div>
              <label class="text-xs text-slate-600">Kendaraan</label>
              <input name="kendaraan"
                     value="{{ old('kendaraan', $data->kendaraan) }}"
                     class="editable mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm"
                     {{ $isEdit ? '' : 'readonly' }}>
            </div>

            <div>
              <label class="text-xs text-slate-600">Merk</label>
              <input name="merk"
                     value="{{ old('merk', $data->merk) }}"
                     class="editable mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm"
                     {{ $isEdit ? '' : 'readonly' }}>
            </div>

            <div>
              <label class="text-xs text-slate-600">Plat</label>
              <input name="plat"
                     value="{{ old('plat', $data->plat) }}"
                     class="editable mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm"
                     {{ $isEdit ? '' : 'readonly' }}>
            </div>

            <div>
              <label class="text-xs text-slate-600">Warna</label>
              <input name="warna"
                     value="{{ old('warna', $data->warna) }}"
                     class="editable mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm"
                     {{ $isEdit ? '' : 'readonly' }}>
            </div>
          </div>
        </form>
      </div>

      {{-- =======================
           INFORMASI STNK (PINDAH KE GRID UTAMA!)
           -> supaya foto STNK start dari kolom kiri, sejajar foto kendaraan
           ======================= --}}
      @php
        $stnkNo   = $data->stnk_number ?? null;
        $stnkExp  = $data->stnk_expired_at ?? null;
        $stnkImg  = $data->stnk_image ?? null;

        $stnkExpText  = $stnkExp ? \Carbon\Carbon::parse($stnkExp)->format('d-m-Y') : '—';
        $hasStnkImage = $stnkImg && !str_contains($stnkImg, 'via.placeholder.com');
      @endphp

      <div class="col-span-12">
        <div class="text-[13px] font-semibold text-slate-800 mb-3">Informasi STNK</div>

        <div class="grid grid-cols-12 gap-6 items-start">
          {{-- FOTO STNK (KIRI) -> sama start dengan FOTO KENDARAAN --}}
          <div class="col-span-12 md:col-span-4">
            @if($hasStnkImage)
              <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50">
                <img
                  src="{{ $stnkImg }}"
                  alt="stnk"
                  class="w-full h-[220px] object-cover"
                  onerror="this.onerror=null; this.src='https://via.placeholder.com/400x220';"
                >
              </div>
            @else
              <div class="rounded-2xl border border-dashed border-slate-200 bg-white h-[220px] flex items-center justify-center text-center p-6">
                <div>
                  <div class="text-sm text-slate-600">Foto STNK belum ada</div>
                  <div class="text-xs text-slate-400 mt-1">Data stnk_image kosong / file tidak ditemukan</div>
                </div>
              </div>
            @endif
          </div>

          {{-- FORM STNK (KANAN) --}}
          <div class="col-span-12 md:col-span-8">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="text-xs text-slate-600">Nomor Plat Kendaraan</label>
                <input
                  value="{{ $data->plat ?? '—' }}"
                  class="mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm bg-slate-50"
                  readonly
                >
              </div>

              <div>
                <label class="text-xs text-slate-600">Merk</label>
                <input
                  value="{{ $data->merk ?? '—' }}"
                  class="mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm bg-slate-50"
                  readonly
                >
              </div>

              <div>
                <label class="text-xs text-slate-600">Nomor STNK</label>
                <input
                  value="{{ $stnkNo ?: '—' }}"
                  class="mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm bg-slate-50"
                  readonly
                >
              </div>

              <div>
                <label class="text-xs text-slate-600">Masa Berlaku</label>
                <input
                  value="{{ $stnkExpText }}"
                  class="mt-1 w-full h-9 rounded-lg border border-slate-200 px-3 text-sm bg-slate-50"
                  readonly
                >
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<script>
  const btnEdit = document.getElementById('btnEdit');
  const btnSimpan = document.getElementById('btnSimpan');
  const editable = document.querySelectorAll('.editable');

  if (btnEdit) {
    btnEdit.addEventListener('click', () => {
      editable.forEach(el => el.removeAttribute('readonly'));
      btnEdit.classList.add('hidden');
      btnSimpan.classList.remove('hidden');

      const url = new URL(window.location.href);
      url.searchParams.set('edit', '1');
      window.history.replaceState({}, '', url.toString());
    });
  }
</script>
@endsection
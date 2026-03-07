@extends('superadmin.layouts.app')

@section('content')
@php
  $u = auth()->user();

  $displayName = $u?->name ?: ($u?->username ?: ($u?->email ?: 'Admin'));
  $roleText = $u?->role ?: 'Superadmin';

  $editProfile  = request('edit_profile') == '1';
  $editPassword = request('edit_password') == '1';

  if ($errors->any()) {
      $editPassword = true;
  }

  /**
   * DB disarankan simpan: "avatars/xxx.png"
   * Maka URL tampil: asset('storage/'.$u->image)
   */
  $avatarUrl = !empty($u?->image) ? asset('storage/'.$u->image) : null;
@endphp

<div class="font-['Urbanist']">

  {{-- Header Page --}}
  <div class="flex items-center justify-between mb-6">
    <div class="text-[26px] md:text-[28px] font-semibold text-slate-900">Pengaturan</div>
  </div>

  {{-- Card utama --}}
  <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">

    {{-- Header profil biru muda --}}
    <div class="rounded-2xl bg-[#EAF6FF] border border-[#D8EEFF] p-5 flex items-center justify-between gap-4">
      <div class="flex items-center gap-4 min-w-0">

        {{-- Avatar --}}
        <div class="relative">
          <button type="button"
                  @if($editProfile) onclick="document.getElementById('avatarInput').click()" @endif
                  class="w-16 h-16 rounded-2xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 {{ $editProfile ? 'cursor-pointer' : 'cursor-default' }}">
            @if($avatarUrl)
              <img id="avatarPreview" src="{{ $avatarUrl }}" class="w-full h-full object-cover" alt="avatar">
              <svg id="avatarPreviewSvg" class="hidden w-10 h-10 text-slate-400" viewBox="0 0 24 24" fill="none">
                <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                <path d="M4 20c1.5-4 14.5-4 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            @else
              <svg id="avatarPreviewSvg" class="w-10 h-10 text-slate-400" viewBox="0 0 24 24" fill="none">
                <path d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                <path d="M4 20c1.5-4 14.5-4 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              <img id="avatarPreview" src="" class="hidden w-full h-full object-cover" alt="avatar">
            @endif
          </button>

          @if($editProfile)
            <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-[#0B4A8B] text-white flex items-center justify-center border-2 border-white">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none">
                <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          @endif
        </div>

        <div class="min-w-0">
          <div class="text-[16px] font-semibold text-slate-900 leading-tight truncate">{{ $displayName }}</div>
          <div class="text-[12px] text-slate-500 truncate">{{ $roleText }}</div>
        </div>

      </div>

      {{-- Tombol kanan atas --}}
      <div class="shrink-0">
        @if(!$editProfile)
          <a href="{{ route('sa.pengaturan', ['edit_profile' => 1]) }}"
             class="h-9 px-4 rounded-full bg-white border border-slate-200 text-slate-700 text-[12px] font-semibold inline-flex items-center gap-2 hover:bg-slate-50">
            Edit
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"
                    stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </a>
        @else
          <div class="flex items-center gap-2">
            <a href="{{ route('sa.pengaturan') }}"
               class="h-9 px-4 rounded-full bg-white border border-slate-200 text-slate-700 text-[12px] font-semibold inline-flex items-center hover:bg-slate-50">
              Batal
            </a>

            <button type="submit" form="formUpdateProfile"
                    class="h-9 px-6 rounded-full bg-[#0B4A8B] text-white text-[12px] font-semibold hover:opacity-90">
              Simpan
            </button>
          </div>
        @endif
      </div>
    </div>

    {{-- Informasi Pribadi --}}
    <div class="mt-7">
      <div class="text-[14px] font-semibold text-slate-900 mb-4">Informasi Pribadi</div>

      <form id="formUpdateProfile"
            method="POST"
            action="{{ route('sa.pengaturan.updateProfile') }}"
            enctype="multipart/form-data"
            class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PATCH')

        {{-- Nama --}}
        <div>
          <div class="text-[12px] text-slate-600 mb-1">Nama Lengkap</div>
          <input name="name" value="{{ old('name', $u->name) }}"
                 {{ $editProfile ? '' : 'disabled' }}
                 class="w-full h-10 px-4 rounded-lg border border-slate-200 text-[13px]
                        {{ $editProfile ? 'bg-white' : 'bg-[#F3FAFF]' }}
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
          @error('name') <div class="text-[12px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Email --}}
        <div>
          <div class="text-[12px] text-slate-600 mb-1">Email</div>
          <input name="email" value="{{ old('email', $u->email) }}"
                 {{ $editProfile ? '' : 'disabled' }}
                 class="w-full h-10 px-4 rounded-lg border border-slate-200 text-[13px]
                        {{ $editProfile ? 'bg-white' : 'bg-[#F3FAFF]' }}
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
          @error('email') <div class="text-[12px] text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Tempat Lahir --}}
        <div>
          <div class="text-[12px] text-slate-600 mb-1">Tempat Lahir</div>
          <input name="birth_place" value="{{ old('birth_place', $u->birth_place ?? '') }}"
                 {{ $editProfile ? '' : 'disabled' }}
                 class="w-full h-10 px-4 rounded-lg border border-slate-200 text-[13px]
                        {{ $editProfile ? 'bg-white' : 'bg-[#F3FAFF]' }}
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
        </div>

        {{-- Tanggal Lahir --}}
@php
  $birthDateVal = old('birth_date');
  if (!$birthDateVal && !empty($u?->birth_date)) {
      try {
          $birthDateVal = \Carbon\Carbon::parse($u->birth_date)->format('Y-m-d');
      } catch (\Exception $e) {
          $birthDateVal = null;
      }
  }
@endphp

  <div>
      <div class="text-[12px] text-slate-600 mb-1">Tanggal Lahir</div>
      <input type="date"
            name="birth_date"
            value="{{ $birthDateVal }}"
            {{ $editProfile ? '' : 'disabled' }}
            class="w-full h-10 px-4 rounded-lg border border-slate-200 text-[13px]
                    {{ $editProfile ? 'bg-white' : 'bg-[#F3FAFF]' }}
                    focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
    </div>

        {{-- Jenis Kelamin --}}
        <div>
          <div class="text-[12px] text-slate-600 mb-1">Jenis Kelamin</div>
          <select name="gender"
                  {{ $editProfile ? '' : 'disabled' }}
                  class="w-full h-10 px-4 rounded-lg border border-slate-200 text-[13px]
                         {{ $editProfile ? 'bg-white' : 'bg-[#F3FAFF]' }}
                         focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300">
            <option value="">—</option>
            <option value="Laki-laki" {{ old('gender', $u->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ old('gender', $u->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
          </select>
        </div>

        {{-- No Tlp --}}
        <div>
          <div class="text-[12px] text-slate-600 mb-1">No. Tlp</div>
          <input name="phone_number" value="{{ old('phone_number', $u->phone_number ?? '') }}"
                 {{ $editProfile ? '' : 'disabled' }}
                 class="w-full h-10 px-4 rounded-lg border border-slate-200 text-[13px]
                        {{ $editProfile ? 'bg-white' : 'bg-[#F3FAFF]' }}
                        focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
        </div>

        {{-- Upload Avatar --}}
        <div class="md:col-span-2">
          <div class="text-[12px] text-slate-600 mb-1">Foto Profil</div>
          <input id="avatarInput" type="file" name="avatar" accept="image/*"
                 {{ $editProfile ? '' : 'disabled' }}
                 class="w-full h-10 px-4 py-2 rounded-lg border border-slate-200 text-[13px]
                        {{ $editProfile ? 'bg-white' : 'bg-[#F3FAFF]' }}" />
          @error('avatar') <div class="text-[12px] text-red-600 mt-1">{{ $message }}</div> @enderror
          <div class="text-[11px] text-slate-400 mt-1">jpg/jpeg/png/webp max 2MB</div>
        </div>
      </form>
    </div>

    {{-- Informasi Akun (Password) --}}
    <div class="mt-9">
      <div class="flex items-center justify-between mb-4">
        <div class="text-[14px] font-semibold text-slate-900">Informasi Akun</div>

        @if(!$editPassword)
          <a href="{{ route('sa.pengaturan', ['edit_password' => 1]) }}"
             class="h-9 px-4 rounded-full bg-white border border-slate-200 text-slate-700 text-[12px] font-semibold inline-flex items-center gap-2 hover:bg-slate-50">
            Edit
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"
                    stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </a>
        @endif
      </div>

      <form id="formUpdatePassword" method="POST" action="{{ route('sa.pengaturan.updatePassword') }}">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <div class="md:col-span-2">
            <div class="text-[12px] text-slate-600 mb-1">Password Saat Ini</div>
            <div class="relative">
              <input id="pwCurrent" name="current_password" type="password"
                     placeholder="Masukkan Password Saat Ini"
                     {{ $editPassword ? '' : 'disabled' }}
                     class="w-full h-10 px-4 pr-10 rounded-lg border border-slate-200 text-[13px]
                            {{ $editPassword ? 'bg-white' : 'bg-[#F3FAFF]' }}
                            focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
              <button type="button"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500"
                      onclick="togglePw('pwCurrent')">👁</button>
            </div>
            @error('current_password') <div class="text-[12px] text-red-600 mt-1">{{ $message }}</div> @enderror
          </div>

          <div>
            <div class="text-[12px] text-slate-600 mb-1">Password Baru</div>
            <div class="relative">
              <input id="pwNew" name="password" type="password" placeholder="Masukkan Password Baru"
                     {{ $editPassword ? '' : 'disabled' }}
                     class="w-full h-10 px-4 pr-10 rounded-lg border border-slate-200 text-[13px]
                            {{ $editPassword ? 'bg-white' : 'bg-[#F3FAFF]' }}
                            focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
              <button type="button"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500"
                      onclick="togglePw('pwNew')">👁</button>
            </div>
            @error('password') <div class="text-[12px] text-red-600 mt-1">{{ $message }}</div> @enderror
          </div>

          <div>
            <div class="text-[12px] text-slate-600 mb-1">Konfirmasi Password Baru</div>
            <div class="relative">
              <input id="pwConfirm" name="password_confirmation" type="password" placeholder="Masukkan Password Baru"
                     {{ $editPassword ? '' : 'disabled' }}
                     class="w-full h-10 px-4 pr-10 rounded-lg border border-slate-200 text-[13px]
                            {{ $editPassword ? 'bg-white' : 'bg-[#F3FAFF]' }}
                            focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-300" />
              <button type="button"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500"
                      onclick="togglePw('pwConfirm')">👁</button>
            </div>
          </div>

          @if($editPassword)
            <div class="md:col-span-2 flex justify-end gap-2 mt-2">
              <a href="{{ route('sa.pengaturan') }}"
                 class="h-10 px-6 rounded-full bg-slate-200 text-slate-800 text-[13px] font-semibold inline-flex items-center">
                Batal
              </a>

              <button type="submit"
                      class="h-10 px-8 rounded-full bg-[#0B4A8B] text-white text-[13px] font-semibold hover:opacity-90">
                Konfirmasi
              </button>
            </div>
          @endif

        </div>
      </form>
    </div>

  </div>

  {{-- Modal sukses --}}
  <div id="modalSaved" class="{{ session('saved_success') ? '' : 'hidden' }} fixed inset-0 z-[999]">
    <div class="absolute inset-0 bg-[#5A5D8D]/70"></div>

    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[760px] max-w-[92vw] bg-white rounded-2xl shadow-xl p-10 text-center">
      <div class="text-[28px] font-semibold text-slate-900">Data terbaru berhasil disimpan</div>

      <div class="mt-6 flex items-center justify-center">
        <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center relative">
          <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">✓</div>
          <svg class="w-10 h-10 text-blue-600" viewBox="0 0 24 24" fill="none">
            <path d="M7 20h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2"/>
            <path d="M9 8h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M9 12h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </div>
      </div>

      <button type="button"
              class="mt-7 h-11 px-10 rounded-lg bg-[#0B4A8B] text-white font-semibold"
              onclick="document.getElementById('modalSaved').classList.add('hidden'); window.location='{{ route('sa.pengaturan') }}';">
        Oke
      </button>
    </div>
  </div>

</div>

<script>
  function togglePw(id){
    const el = document.getElementById(id);
    if(!el) return;
    el.type = (el.type === 'password') ? 'text' : 'password';
  }

  // PREVIEW AVATAR
  const avatarInput = document.getElementById('avatarInput');
  if (avatarInput) {
    avatarInput.addEventListener('change', function () {
      const file = this.files && this.files[0];
      if (!file) return;

      const img = document.getElementById('avatarPreview');
      const svg = document.getElementById('avatarPreviewSvg');
      const url = URL.createObjectURL(file);

      if (img) {
        img.src = url;
        img.classList.remove('hidden');
      }
      if (svg) svg.classList.add('hidden');
    });
  }
</script>
@endsection
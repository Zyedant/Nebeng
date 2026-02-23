@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8 bg-white rounded-xl shadow">

    <div class="flex items-center justify-between bg-blue-50 p-6 rounded-xl">
        <div class="flex items-center gap-4">
            <img
                src="{{ Auth::user()->image ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}"
                class="w-16 h-16 rounded-full object-cover"
            >
            <div>
                <h2 class="font-semibold text-lg">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500">{{ Auth::user()->role ?? 'User' }}</p>
            </div>
        </div>

        <button
            onclick="toggleProfileEditMode()"
            id="editProfileButton"
            class="flex items-center gap-2 px-5 py-2.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full text-sm font-medium transition-colors duration-200"
        >
            Edit
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </button>
    </div>

    <section class="space-y-4">
        <h3 class="font-semibold text-gray-800">Informasi Pribadi</h3>

        <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ Auth::user()->name }}"
                        disabled
                        class="profile-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition-all duration-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ Auth::user()->email }}"
                        disabled
                        class="profile-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition-all duration-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                    <input
                        type="text"
                        name="birth_place"
                        value="{{ Auth::user()->birth_place ?? '' }}"
                        disabled
                        class="profile-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition-all duration-200"
                        placeholder="Masukkan tempat lahir"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input
                        type="date"
                        name="birth_date"
                        value="{{ Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->format('Y-m-d') : '' }}"
                        disabled
                        class="profile-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition-all duration-200"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select
                        name="gender"
                        disabled
                        class="profile-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition-all duration-200"
                    >
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ Auth::user()->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ Auth::user()->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input
                        type="tel"
                        name="phone_number"
                        value="{{ Auth::user()->phone_number ?? '' }}"
                        disabled
                        class="profile-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed transition-all duration-200"
                        placeholder="Masukkan nomor telepon"
                    >
                </div>
            </div>
        </form>
    </section>

    <section class="space-y-4">
        <h3 class="font-semibold text-gray-800">Keamanan Akun</h3>

        <form method="POST" action="{{ route('password.update') }}" id="passwordForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input
                                type="password"
                                name="current_password"
                                disabled
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                placeholder="Masukkan password saat ini"
                            >
                        </div>
                    </div>
                    

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input
                            type="password"
                            name="password"
                            disabled
                            class="password-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                            placeholder="Masukkan password baru"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            disabled
                            class="password-input w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                            placeholder="Konfirmasi password baru"
                        >
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        onclick="togglePasswordEditMode()"
                        id="editPasswordButton"
                        class="flex items-center gap-2 px-5 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full text-sm font-medium transition-colors duration-200 whitespace-nowrap"
                    >
                        Edit
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>

@push('scripts')
<script>
let isProfileEditMode = false;
let isPasswordEditMode = false;

function toggleProfileEditMode() {
    const profileInputs = document.querySelectorAll('.profile-input');
    const editProfileButton = document.getElementById('editProfileButton');
    
    isProfileEditMode = !isProfileEditMode;
    
    if (isProfileEditMode) {
        profileInputs.forEach(input => {
            input.disabled = false;
            input.classList.remove('disabled:bg-gray-100', 'disabled:cursor-not-allowed');
            input.classList.add('bg-white', 'cursor-text');
        });
        
        editProfileButton.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Simpan
        `;
        editProfileButton.classList.remove('bg-blue-100', 'text-blue-700', 'hover:bg-blue-200');
        editProfileButton.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        editProfileButton.onclick = saveProfile;
    } else {
        profileInputs.forEach(input => {
            input.disabled = true;
            input.classList.add('disabled:bg-gray-100', 'disabled:cursor-not-allowed');
            input.classList.remove('bg-white', 'cursor-text');
        });
        
        editProfileButton.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        `;
        editProfileButton.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        editProfileButton.classList.add('bg-blue-100', 'text-blue-700', 'hover:bg-blue-200');
        editProfileButton.onclick = toggleProfileEditMode;
    }
}

function saveProfile() {
    document.getElementById('profileForm').submit();
}

function togglePasswordEditMode() {
    const passwordInputs = document.querySelectorAll('#passwordForm input');
    const editPasswordButton = document.getElementById('editPasswordButton');
    
    isPasswordEditMode = !isPasswordEditMode;
    
    if (isPasswordEditMode) {
        passwordInputs.forEach(input => {
            input.disabled = false;
            input.classList.remove('disabled:bg-gray-100', 'disabled:cursor-not-allowed');
            input.classList.add('bg-white', 'cursor-text');
        });
        
        document.querySelector('input[name="current_password"]').value = '';
        document.querySelector('input[name="password"]').value = '';
        document.querySelector('input[name="password_confirmation"]').value = '';
        
        editPasswordButton.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Simpan
        `;
        editPasswordButton.classList.remove('bg-blue-100', 'text-blue-700', 'hover:bg-blue-200');
        editPasswordButton.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        editPasswordButton.onclick = savePassword;
    } else {
        cancelPasswordEdit();
    }
}

function savePassword() {
    const currentPassword = document.querySelector('input[name="current_password"]').value;
    const newPassword = document.querySelector('input[name="password"]').value;
    const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
    
    if (!currentPassword) {
        alert('Password saat ini harus diisi!');
        return;
    }
    
    if (!newPassword) {
        alert('Password baru harus diisi!');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        alert('Password baru dan konfirmasi password tidak sama!');
        return;
    }
    
    if (newPassword.length < 8) {
        alert('Password baru minimal 8 karakter!');
        return;
    }
    
    document.getElementById('passwordForm').submit();
}

function cancelPasswordEdit() {
    isPasswordEditMode = false;
    const passwordInputs = document.querySelectorAll('#passwordForm input');
    const editPasswordButton = document.getElementById('editPasswordButton');
    
    passwordInputs.forEach(input => {
        input.disabled = true;
        input.value = '';
        input.classList.add('disabled:bg-gray-100', 'disabled:cursor-not-allowed');
        input.classList.remove('bg-white', 'cursor-text');
    });
    
    document.querySelector('input[name="current_password"]').placeholder = '••••••••';
    
    editPasswordButton.innerHTML = `
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
        </svg>
        Edit
    `;
    editPasswordButton.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
    editPasswordButton.classList.add('bg-blue-100', 'text-blue-700', 'hover:bg-blue-200');
    editPasswordButton.onclick = togglePasswordEditMode;
}

document.getElementById('profileForm').addEventListener('submit', function(e) {
    if (!isProfileEditMode) {
        e.preventDefault();
        toggleProfileEditMode();
    }
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    if (!isPasswordEditMode) {
        e.preventDefault();
        togglePasswordEditMode();
    } else {
        const newPassword = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak sama!');
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#passwordForm input').forEach(input => {
        input.disabled = true;
    });
    
    document.querySelectorAll('.profile-input').forEach(input => {
        input.disabled = true;
    });
});
</script>
@endpush

<style>
.profile-input:disabled,
.password-input:disabled {
    background-color: #f9fafb;
    color: #6b7280;
    cursor: not-allowed;
}

input:focus, select:focus {
    outline: none;
    ring-width: 2px;
}

#passwordForm input {
    min-height: 48px;
}
</style>
@endsection
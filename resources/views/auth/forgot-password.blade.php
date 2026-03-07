<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Nebeng</title>

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-white">

<div class="flex min-h-screen">

    {{-- LEFT PANEL --}}
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-900 to-blue-700 text-white px-16 py-12 flex-col justify-between">
        <div class="text-4xl font-bold">
            Nebeng
        </div>

        <div>
            <h1 class="text-6xl font-semibold leading-tight mb-6">
                Lupa<br>
                <span class="font-bold">Password?</span>
            </h1>

            <p class="max-w-md text-sm leading-relaxed opacity-90">
                Masukkan email kamu, nanti kami kirim link untuk reset password.
            </p>
        </div>

        <p class="text-xs opacity-80">
            * Aplikasi yang membantu masyarakat dalam mencari <u>transportasi</u>
        </p>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-8">
        <div class="w-full max-w-md">

            <h2 class="text-2xl font-semibold mb-3">
                <span class="text-blue-800 font-bold">Reset Password</span>
            </h2>
            <p class="text-sm text-gray-600 mb-8">
                Masukkan email yang terdaftar untuk menerima link reset password.
            </p>

            {{-- STATUS --}}
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan Email"
                        required
                        autofocus
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-800 hover:bg-blue-900
                           text-white py-3 rounded-lg font-semibold transition"
                >
                    Kirim Link Reset Password
                </button>

                <div class="text-sm text-gray-600">
                    Kembali ke halaman
                    <a href="{{ route('login') }}" class="text-blue-700 hover:underline font-medium">
                        Login
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>

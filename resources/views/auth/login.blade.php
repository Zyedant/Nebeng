<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nebeng</title>

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-white">

<div class="flex min-h-screen">

    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-900 to-blue-700 text-white px-16 py-12 flex-col justify-between">
        <div class="text-4xl font-bold">
            Nebeng
        </div>

        <div>
            <h1 class="text-6xl font-semibold leading-tight mb-6">
                Hallo,<br>
                <span class="font-bold">Selamat Datang</span>
            </h1>

            <p class="max-w-md text-1sm leading-relaxed opacity-90">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Totam, harum!
            </p>
        </div>

        <p class="text-xs opacity-80">
            * Aplikasi yang membantu masyarakat dalam mencari <u>transportasi</u>
        </p>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center px-8">
        <div class="w-full max-w-md">

            <h2 class="text-2xl font-semibold mb-8">
                <span class="text-blue-800 font-bold">Login</span>
                untuk melanjutkan ke Dashboard Nebeng
            </h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan Email"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan Password"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded">
                        Remember me
                    </label>

                    <a href="forgot-password" class="text-blue-700 hover:underline">
                        Lupa password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-1/3 bg-blue-800 hover:bg-blue-900
                           text-white py-3 rounded-lg font-semibold transition"
                >
                    Masuk
                </button>
            </form>

        </div>
    </div>

</div>

</body>
</html>

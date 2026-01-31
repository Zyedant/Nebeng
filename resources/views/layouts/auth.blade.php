<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nebeng')</title>

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-white">

<div class="flex min-h-screen">

    <!-- LEFT SIDE -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-900 to-blue-700 text-white px-16 py-12 flex-col justify-between">
        <div class="text-2xl font-bold">
            Nebeng
        </div>

        <div>
            <h1 class="text-5xl font-semibold leading-tight mb-6">
                Hallo,<br>
                <span class="font-bold">Selamat Datang</span>
            </h1>

            <p class="max-w-md text-sm leading-relaxed opacity-90">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Totam, harum!
            </p>
        </div>

        <p class="text-xs opacity-80">
            * Aplikasi yang membantu masyarakat dalam mencari <u>transportasi</u>
        </p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-8">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </div>

</div>

</body>
</html>

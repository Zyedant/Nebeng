<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <div class="text-lg font-semibold">
                        Halo, {{ auth()->user()->name }}!
                    </div>

                    <div>
                        Email: <span class="font-medium">{{ auth()->user()->email }}</span>
                    </div>

                    <div>
                        Role: <span class="font-medium">{{ auth()->user()->role ?? 'Customer' }}</span>
                    </div>

                    <div class="text-sm text-gray-500 pt-2">
                        Kamu berhasil login. Silakan lanjutkan sesuai role kamu.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

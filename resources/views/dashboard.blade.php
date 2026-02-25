@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-gradient-to-br from-blue-800 to-blue-900 text-white rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-700/30 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-700/20 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-blue-200">Pendapatan</span>
                <select class="bg-blue-700/50 text-white text-xs px-3 py-1.5 rounded-lg border-0 focus:ring-2 focus:ring-blue-400">
                    <option>Jun 2025</option>
                    <option>May 2025</option>
                    <option>Apr 2025</option>
                </select>
            </div>
            
            <div class="flex items-center gap-3 mb-3">
                <h3 class="text-3xl font-bold">Rp 200.000,00</h3>
                <button class="p-1.5 hover:bg-blue-700/50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            
            <div class="flex items-center justify-between text-sm">
                <span class="text-blue-200">No. Rekening</span>
                <span class="font-mono">7981 0283 9877 897</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition relative">
        <button class="absolute top-4 right-4 p-1.5 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
        </button>
        
        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        
        <h4 class="text-4xl font-bold text-gray-900 mb-2">10.213</h4>
        <p class="text-sm text-gray-500">Total Pengguna Mitra</p>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition relative">
        <button class="absolute top-4 right-4 p-1.5 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
        </button>
        
        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        
        <h4 class="text-4xl font-bold text-gray-900 mb-2">10.213</h4>
        <p class="text-sm text-gray-500">Total Pengguna Costumer</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Pendapatan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Pendapatan dari perjalanan Nebeng</p>
            </div>
            <select class="bg-gray-50 text-gray-700 text-sm px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500">
                <option>May 2025</option>
                <option>Jun 2025</option>
                <option>Jul 2025</option>
            </select>
        </div>
        
        <div class="relative h-72">
            <canvas id="pendapatanChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Pesanan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Pesanan dari Layanan</p>
            </div>
            <select class="bg-gray-50 text-gray-700 text-sm px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500">
                <option>Jun 2025</option>
                <option>May 2025</option>
            </select>
        </div>
        
        <div class="relative h-72">
            <canvas id="pesananChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Daftar Transaksi</h3>
            <select class="bg-gray-50 text-gray-700 text-sm px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500">
                <option>May 2025</option>
                <option>Jun 2025</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NO.</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TANGGAL</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NAMA DRIVER</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NAMA COSTUMER</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NO. TRANSAKSI</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NO. ORDERAN</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">STATUS</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach([
                    ['no' => 1, 'tanggal' => 'Rabu, 17 Okt 2023', 'driver' => 'Maulana Injil', 'costumer' => 'Ocha Putra', 'transaksi' => 'INV-123456789', 'orderan' => '000123456789', 'status' => 'proses', 'color' => 'yellow'],
                    ['no' => 2, 'tanggal' => 'Rabu, 17 Okt 2023', 'driver' => 'Maulana Injil', 'costumer' => 'Ocha Putra', 'transaksi' => 'INV-123456789', 'orderan' => '000123456789', 'status' => 'selesai', 'color' => 'green'],
                    ['no' => 3, 'tanggal' => 'Rabu, 17 Okt 2023', 'driver' => 'Maulana Injil', 'costumer' => 'Ocha Putra', 'transaksi' => 'INV-123456789', 'orderan' => '000123456789', 'status' => 'batal', 'color' => 'red'],
                    ['no' => 4, 'tanggal' => 'Rabu, 17 Okt 2023', 'driver' => 'Maulana Injil', 'costumer' => 'Ocha Putra', 'transaksi' => 'INV-123456789', 'orderan' => '000123456789', 'status' => 'selesai', 'color' => 'green'],
                    ['no' => 5, 'tanggal' => 'Kamis, 18 Okt 2023', 'driver' => 'Maulana Injil', 'costumer' => 'Ocha Putra', 'transaksi' => 'INV-123456789', 'orderan' => '000123456789', 'status' => 'proses', 'color' => 'yellow'],
                ] as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item['no'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item['tanggal'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item['driver'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item['costumer'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-mono">{{ $item['transaksi'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-mono">{{ $item['orderan'] }}</td>
                    <td class="px-6 py-4">
                        @if($item['status'] === 'proses')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                PROSES
                            </span>
                        @elseif($item['status'] === 'selesai')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                SELESAI
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                BATAL
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctxPendapatan = document.getElementById('pendapatanChart').getContext('2d');
    new Chart(ctxPendapatan, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Pendapatan',
                data: [2800000, 3200000, 3500000, 3100000, 4200000, 5100000, 4800000, 4500000, 5200000, 4600000, 3900000, 5000000],
                fill: true,
                backgroundColor: function(context) {
                    const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
                    return gradient;
                },
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 2.5,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: 'rgb(59, 130, 246)',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return (value / 1000000) + 'M';
                        },
                        color: '#9ca3af',
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#9ca3af',
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    const ctxPesanan = document.getElementById('pesananChart').getContext('2d');
    new Chart(ctxPesanan, {
        type: 'bar',
        data: {
            labels: ['Nebeng\nMobil', 'Nebeng\nMotor', 'Nebeng\nBarang', 'Titip\nBarang'],
            datasets: [{
                label: 'Pesanan',
                data: [150, 80, 350, 600],
                backgroundColor: [
                    'rgba(30, 58, 138, 0.9)',
                    'rgba(30, 64, 175, 0.9)',
                    'rgba(37, 99, 235, 0.9)',
                    'rgba(59, 130, 246, 0.9)'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9ca3af',
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#9ca3af',
                        font: {
                            size: 10
                        },
                        maxRotation: 0,
                        minRotation: 0
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
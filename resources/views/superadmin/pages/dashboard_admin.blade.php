@extends('superadmin.layouts.app')

@section('content')
@php
    // Pastikan format chart selalu 4 item
    $pesananChart = is_array($pesananChart ?? null) ? $pesananChart : [0,0,0,0];
    $pesananChart = array_pad($pesananChart, 4, 0);
    $pesananChart = array_slice($pesananChart, 0, 4);

    $maxVal = max($pesananChart);

    // ====== NICE SCALE (maxY ikut data, rapi) ======
    // Tentukan step berdasarkan skala data
    if ($maxVal <= 0) {
        $step = 5;
        $maxY = 5;
    } elseif ($maxVal <= 20) {
        $step = 5;
        $maxY = (int) ceil($maxVal / $step) * $step;     // contoh 4 => 5, 18 => 20
    } elseif ($maxVal <= 100) {
        $step = 10;
        $maxY = (int) ceil($maxVal / $step) * $step;
    } elseif ($maxVal <= 500) {
        $step = 50;
        $maxY = (int) ceil($maxVal / $step) * $step;
    } else {
        $step = 100;
        $maxY = (int) ceil($maxVal / $step) * $step;
    }

    // Tambah ruang atas 1 step biar tidak mentok
    $maxY = $maxY + $step;

    // ====== TICK LABELS (0..maxY jadi 5 garis) ======
    $ticks = 5;
    $tickVals = [];
    for ($i = $ticks; $i >= 0; $i--) {
        $tickVals[] = (int) round($maxY * ($i / $ticks));
    }

    // ====== HITUNG BAR HEIGHT ======
   $barArea = 228; // 260px tinggi container - 32px (bottom-8) = 228px
    $bars = array_map(function($v) use ($maxY, $barArea) {
    if ($maxY <= 0) return 0;
    if ($v <= 0) return 0; // nol beneran nol
    return ($v / $maxY) * $barArea; // biarin decimal
}, $pesananChart);
@endphp


<div class="space-y-6 font-['Urbanist']">

    {{-- STAT CARDS (match UI) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Total Mitra --}}
        <div class="bg-white rounded-xl border border-slate-100 px-5 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-[6px] h-12 bg-[#1D4ED8] rounded-full"></div>

                <div>
                    <div class="text-[30px] font-semibold text-slate-900 leading-none">
                        {{ number_format($totalMitra ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-[16px] text-slate-500 mt-2">Total Mitra</div>
                </div>
            </div>

            <div class="w-11 h-11 rounded-xl bg-[#EEF5FF] flex items-center justify-center">
                <svg class="w-59 h-59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M58.5117 24.9159L52.6279 13.162C52.3583 12.6196 51.9845 12.1356 51.5277 11.7377C51.071 11.3399 50.5403 11.0359 49.966 10.8433C49.3918 10.6507 48.7852 10.5731 48.1809 10.615C47.5766 10.657 46.9865 10.8176 46.4444 11.0878L41.1021 13.7589L30.3531 10.2742C29.7984 10.0967 29.2022 10.0967 28.6476 10.2742L17.8985 13.7589L12.5563 11.0878C12.0141 10.8176 11.4241 10.657 10.8198 10.615C10.2155 10.5731 9.60891 10.6507 9.03463 10.8433C8.46036 11.0359 7.92968 11.3399 7.47295 11.7377C7.01621 12.1356 6.64237 12.6196 6.3728 13.162L0.488934 24.9159C0.218788 25.4581 0.0581472 26.0481 0.0161956 26.6524C-0.025756 27.2567 0.0518042 27.8633 0.244442 28.4376C0.43708 29.0118 0.741017 29.5425 1.13888 29.9992C1.53674 30.456 2.02072 30.8298 2.56315 31.0994L8.70975 34.1738L20.5051 42.7957C20.7846 43.0001 21.0995 43.1509 21.4339 43.2405L35.723 46.928C35.949 46.9856 36.1812 47.015 36.4144 47.0156C37.1476 47.0149 37.8506 46.7231 38.3688 46.2043L50.496 34.077L56.4491 31.0994C57.5425 30.5529 58.3742 29.5944 58.761 28.4348C59.1478 27.2752 59.0582 26.0094 58.5117 24.9159ZM45.1169 31.6341L38.7744 25.6419C38.2501 25.1458 37.5526 24.8744 36.8309 24.8856C36.1092 24.8968 35.4204 25.1897 34.9117 25.7018C32.6854 27.9466 29.1362 29.576 25.5063 27.9028L34.3079 19.3754H39.5442L45.4926 31.2745L45.1169 31.6341ZM10.9061 16.4485L13.9644 17.9765L8.89413 28.0941L5.83581 26.5661L10.9061 16.4485ZM35.5663 41.1778L23.3307 38.0273L13.651 30.9611L19.6294 19.0136L29.5003 15.8124L29.8898 15.9391L20.7425 24.8168L20.7126 24.8445C20.2251 25.3324 19.8534 25.9236 19.6248 26.5744C19.3962 27.2252 19.3165 27.9189 19.3917 28.6046C19.4669 29.2902 19.695 29.9502 20.0593 30.536C20.4235 31.1217 20.9145 31.6183 21.4962 31.989C26.3245 35.075 32.0977 34.7708 36.7832 31.3598L41.2082 35.5428L35.5663 41.1778ZM50.0858 28.0941L45.0155 17.9765L48.0738 16.4485L53.1326 26.5661L50.0858 28.0941Z" fill="#10367D"/>
                </svg>
            </div>
        </div>

        {{-- Total Pelanggan --}}
        <div class="bg-white rounded-xl border border-slate-100 px-5 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-[6px] h-12 bg-[#1D4ED8] rounded-full"></div>

                <div>
                    <div class="text-[30px] font-semibold text-slate-900 leading-none">
                        {{ number_format($totalPelanggan ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-[16px] text-slate-500 mt-2">Total Pelanggan</div>
                </div>
            </div>

            <div class="w-11 h-11 rounded-xl bg-[#EEF5FF] flex items-center justify-center">
                <svg class="w-59 h-59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M37.8844 41.7379C39.5857 40.0721 40.7522 37.9373 41.2352 35.6057C41.7181 33.2742 41.4956 30.8516 40.5961 28.647C39.6965 26.4425 38.1606 24.5558 36.1843 23.2278C34.208 21.8997 31.881 21.1905 29.4999 21.1905C27.1189 21.1905 24.7918 21.8997 22.8155 23.2278C20.8393 24.5558 19.3034 26.4425 18.4038 28.647C17.5042 30.8516 17.2817 33.2742 17.7647 35.6057C18.2476 37.9373 19.4142 40.0721 21.1155 41.7379C18.235 43.2691 15.8401 45.5754 14.2014 48.3962C13.8505 49.0303 13.7625 49.7768 13.9564 50.4752C14.1503 51.1735 14.6105 51.7678 15.2382 52.1303C15.8658 52.4927 16.6106 52.5943 17.3124 52.4132C18.0141 52.232 18.6167 51.7827 18.9906 51.1618C20.0713 49.3359 21.6089 47.823 23.4521 46.772C25.2953 45.7211 27.3805 45.1684 29.5022 45.1684C31.624 45.1684 33.7092 45.7211 35.5524 46.772C37.3956 47.823 38.9332 49.3359 40.0139 51.1618C40.1918 51.4833 40.432 51.7661 40.7205 51.9937C41.0089 52.2213 41.3398 52.3892 41.6938 52.4875C42.0479 52.5858 42.418 52.6125 42.7825 52.5662C43.147 52.5199 43.4986 52.4014 43.8168 52.2176C44.1349 52.0339 44.4133 51.7886 44.6356 51.496C44.858 51.2035 45.0198 50.8696 45.1116 50.5138C45.2034 50.158 45.2234 49.7875 45.1704 49.4239C45.1175 49.0603 44.9926 48.7109 44.8031 48.3962C43.1631 45.5748 40.7666 43.2684 37.8844 41.7379ZM23.0468 33.1875C23.0468 31.9112 23.4253 30.6636 24.1344 29.6024C24.8434 28.5411 25.8513 27.714 27.0304 27.2256C28.2096 26.7372 29.5071 26.6094 30.7589 26.8584C32.0107 27.1074 33.1605 27.722 34.063 28.6245C34.9655 29.527 35.5801 30.6768 35.8291 31.9286C36.0781 33.1804 35.9503 34.4779 35.4618 35.657C34.9734 36.8362 34.1463 37.844 33.0851 38.5531C32.0239 39.2622 30.7762 39.6406 29.4999 39.6406C27.7885 39.6406 26.1471 38.9608 24.9369 37.7506C23.7267 36.5404 23.0468 34.899 23.0468 33.1875Z" fill="#10367D"/>
                </svg>
            </div>
        </div>

        {{-- Verifikasi Mitra --}}
        <div class="bg-white rounded-xl border border-slate-100 px-5 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-[6px] h-12 bg-[#1D4ED8] rounded-full"></div>

                <div>
                    <div class="text-[30px] font-semibold text-slate-900 leading-none">
                        {{ number_format($verifMitra ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-[16px] text-slate-500 mt-2">Verifikasi Mitra</div>
                </div>
            </div>

            <div class="w-11 h-11 rounded-xl bg-[#EEF5FF] flex items-center justify-center">
                <svg class="w-59 h-59" viewBox="0 0 24 24" fill="none">
                    <path d="M9 12l2 2 4-4" stroke="#10367D" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 2l8 4v6c0 5-3 9-8 10C7 21 4 17 4 12V6l8-4Z"
                          stroke="#10367D" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Verifikasi Pelanggan --}}
        <div class="bg-white rounded-xl border border-slate-100 px-5 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-[6px] h-12 bg-[#1D4ED8] rounded-full"></div>

                <div>
                    <div class="text-[30px] font-semibold text-slate-900 leading-none">
                        {{ number_format($verifPelanggan ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-[16px] text-slate-500 mt-2">Verifikasi Pelanggan</div>
                </div>
            </div>

            <div class="w-11 h-11 rounded-xl bg-[#EEF5FF] flex items-center justify-center">
                {{-- SVG kamu tetap --}}
                <svg class="w-59 h-59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_vp_1690_5623)">
                        <path d="M35.1465 36.0776C37.8591 33.945 39.8394 31.0199 40.8118 27.7093C41.7842 24.3987 41.7005 20.8672 40.5721 17.6065C39.4438 14.3457 37.327 11.5177 34.5164 9.51616C31.7058 7.51459 28.3412 6.43896 24.8907 6.43896C21.4402 6.43896 18.0755 7.51459 15.2649 9.51616C12.4543 11.5177 10.3375 14.3457 9.2092 17.6065C8.08087 20.8672 7.9971 24.3987 8.96954 27.7093C9.94198 31.0199 11.9223 33.945 14.6348 36.0776C10.2726 37.8564 6.41771 40.686 3.41328 44.3146C2.94141 44.8763 2.712 45.6025 2.77554 46.3334C2.83907 47.0642 3.19034 47.7399 3.75207 48.2118C4.3138 48.6837 5.03998 48.9131 5.77085 48.8496C6.50172 48.786 7.17742 48.4347 7.6493 47.873C10.8044 44.1164 16.3633 39.6407 24.8907 39.6407C33.418 39.6407 38.9769 44.1164 42.132 47.873C42.6039 48.4347 43.2796 48.786 44.0105 48.8496C44.7414 48.9131 45.4675 48.6837 46.0293 48.2118C46.591 47.7399 46.9423 47.0642 47.0058 46.3334C47.0693 45.6025 46.8399 44.8763 46.368 44.3146C43.3644 40.6852 39.5093 37.8555 35.1465 36.0776Z" fill="#10367D"/>
                        <path d="M58.1911 31.4567L50.8161 38.8317C50.5592 39.0896 50.2539 39.2941 49.9177 39.4337C49.5815 39.5733 49.2211 39.6452 48.8571 39.6452C48.4931 39.6452 48.1327 39.5733 47.7965 39.4337C47.4604 39.2941 47.1551 39.0896 46.8981 38.8317L43.2106 35.1442C42.6911 34.6247 42.3992 33.92 42.3992 33.1852C42.3992 32.4505 42.6911 31.7458 43.2106 31.2263C43.7302 30.7067 44.4349 30.4148 45.1696 30.4148C45.9044 30.4148 46.609 30.7067 47.1286 31.2263L48.8594 32.9571L54.2777 27.5365C54.535 27.2792 54.8404 27.0751 55.1765 26.9359C55.5127 26.7967 55.8729 26.725 56.2367 26.725C56.6005 26.725 56.9608 26.7967 57.2969 26.9359C57.633 27.0751 57.9384 27.2792 58.1957 27.5365C58.453 27.7937 58.657 28.0991 58.7963 28.4352C58.9355 28.7714 59.0071 29.1316 59.0071 29.4954C59.0071 29.8593 58.9355 30.2195 58.7963 30.5556C58.657 30.8918 58.453 31.1972 58.1957 31.4544L58.1911 31.4567Z" fill="#10367D"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_vp_1690_5623">
                            <rect width="59" height="59" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
        </div>

    </div>

    {{-- ROW: PESANAN + TUJUAN --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Pesanan --}}
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="text-[25px] font-semibold text-slate-900">Pesanan</div>
                        <div class="text-[16px] text-slate-400">
                            ({{ number_format($totalPesanan ?? 0, 0, ',', '.') }} Pesanan)
                        </div>
                    </div>
                </div>
                <div class="text-[16px] text-slate-400">
                    {{ $periodeLabel ?? now()->locale('id')->translatedFormat('M Y') }}
                </div>
            </div>

            <hr class="my-3 border-slate-100">

            @php
                $totalPesanan = $totalPesanan ?? 0;
                $pesananChart = is_array($pesananChart ?? null) ? $pesananChart : [0,0,0,0];
                $chartTotal = array_sum($pesananChart);
                $isEmptyChart = ($totalPesanan === 0 || $chartTotal === 0);
            @endphp

            @if($isEmptyChart)
                <div class="h-[260px] mt-2 flex items-center justify-center">
                    <div class="text-slate-400 text-sm bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg">
                        Belum ada pesanan.
                    </div>
                </div>

                <div class="grid grid-cols-4 text-center text-[11px] text-urbanst-400 mt-2">
                    <div>Nebeng Mobil</div>
                    <div>Nebeng Motor</div>
                    <div>Nebeng Barang</div>
                    <div>Titip Barang</div>
                </div>
            @else

                <div class="relative h-[260px] mt-2">
                    <div class="absolute left-0 top-0 bottom-8 w-[42px] flex flex-col justify-between text-[12px] text-slate-400">
    @foreach($tickVals as $t)
        <div>{{ $t }}</div>
    @endforeach
</div>

                    <div class="absolute left-[42px] right-0 top-0 bottom-8">
                        <div class="h-full flex flex-col justify-between">
                            <div class="h-px bg-slate-100"></div>
                            <div class="h-px bg-slate-100"></div>
                            <div class="h-px bg-slate-100"></div>
                            <div class="h-px bg-slate-100"></div>
                            <div class="h-px bg-slate-100"></div>
                            <div class="h-px bg-slate-100"></div>
                        </div>
                    </div>

                    <div class="absolute left-[42px] right-0 top-0 bottom-8 flex items-end justify-around px-8">
                        <div class="w-[60px] rounded-[10px]" style="height: {{ $bars[0] }}px; background:#BFE0FF;"></div>
                        <div class="w-[60px] rounded-[10px]" style="height: {{ $bars[1] }}px; background:#BFE0FF;"></div>
                        <div class="w-[60px] rounded-[10px]" style="height: {{ $bars[2] }}px; background:#7EC3FF;"></div>
                        <div class="w-[60px] rounded-[10px]" style="height: {{ $bars[3] }}px; background: linear-gradient(180deg, #8B5CF6 0%, #D8B4FE 100%);"></div>
                    </div>

                    <div class="absolute left-[42px] right-0 bottom-0 grid grid-cols-4 text-center text-[11px] text-urbanst-400 mt-2 px-6">
                        <div>Nebeng Mobil</div>
                        <div>Nebeng Motor</div>
                        <div>Nebeng Barang</div>
                      <div>Titip Barang</div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Tujuan Terbanyak --}}
        <div class="bg-white rounded-xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="text-[25px] font-semibold text-slate-900">Tujuan Terbanyak</div>
                <div class="text-[16px] text-slate-400">
                    {{ $periodeLabel ?? now()->locale('id')->translatedFormat('M Y') }}
                </div>
            </div>

            <hr class="my-3 border-slate-100">

            @php
                $topDestinations = $topDestinations ?? collect();
                $rows = $topDestinations->take(7);
                $isEmpty = $rows->isEmpty();
            @endphp

            <div class="overflow-x-auto">
  <div class="max-h-[260px] overflow-y-auto pr-1">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[12px] text-slate-400 border-b border-slate-100">
                            <th class="py-3 w-[50px] font-medium">No</th>
                            <th class="py-3 font-medium">Kota Asal</th>
                            <th class="py-3 font-medium">Kota Tujuan</th>
                            <th class="py-3 text-right pr-6 font-medium">Tot. Perjalanan</th>
                        </tr>
                    </thead>

                    <tbody class="text-[13px] text-slate-600">
                        @if($isEmpty)
                            @for($i=1; $i<=7; $i++)
                                <tr class="border-b border-slate-50">
                                    <td class="py-3 text-slate-400">{{ $i }}</td>
                                    <td class="py-3 text-slate-300">—</td>
                                    <td class="py-3 text-slate-300">—</td>
                                    <td class="py-3 text-right text-slate-300">—</td>
                                </tr>
                            @endfor
                        @else
                            @foreach($rows as $idx => $row)
                                <tr class="border-b border-slate-50">
                                    <td class="py-3 text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3">{{ $row->kota_asal ?? '-' }}</td>
                                    <td class="py-3">{{ $row->kota_tujuan ?? '-' }}</td>
                                   <td class="py-3 text-right pr-6">
                                        {{ number_format($row->total_perjalanan ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

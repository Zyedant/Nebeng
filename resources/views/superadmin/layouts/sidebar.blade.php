<aside class="nebeng-sidebar w-[240px] min-h-full shrink-0 text-white bg-gradient-to-b from-[#0B4A8B] to-[#06325F] flex flex-col">

    {{-- Brand --}}
    <div class="px-8 pt-7">
        <div class="text-[22px] font-extrabold tracking-wide">NEBENG</div>
        <div class="text-[10px] opacity-80 mt-1 leading-4">
            TRANSPORTASI MENJADI LEBIH MUDAH
        </div>
    </div>

    {{-- MAIN MENU --}}
    <div class="px-8 mt-10 text-[11px] font-semibold tracking-wide opacity-70">
        MAIN MENU
    </div>

    @php
        // ACTIVE STATES (sesuai routes kamu)
        $isDashboard = request()->routeIs('sa.home') || request()->routeIs('sa.dashboard');

        // Verifikasi (parent + child)
        $isVerifikasiParent = request()->routeIs('sa.verifikasi*');
        $isVerifikasiMitra = request()->routeIs('sa.verifikasi.mitra') || request()->routeIs('sa.verifikasi.mitra.*');
        $isVerifikasiCustomer = request()->routeIs('sa.verifikasi.customer') || request()->routeIs('sa.verifikasi.customer.*');

        // Mitra (parent + child)
        $isMitraParent = request()->routeIs('sa.mitra*');
        $isMitraDaftar = request()->routeIs('sa.mitra.daftar');
        $isMitraKendaraan = request()->routeIs('sa.mitra.kendaraan');
        $isMitraBlokir = request()->routeIs('sa.mitra.blokir');

        // Single menus
        $isCustomer = request()->routeIs('sa.customer');
        $isPesanan = request()->routeIs('sa.transaksi');
        $isRefund = request()->routeIs('sa.refund');
        $isLaporan = request()->routeIs('sa.laporan');
        $isPengaturan = request()->routeIs('sa.pengaturan');
    @endphp

    <nav class="mt-4 px-2 space-y-2">

        {{-- Dashboard --}}
        <a href="{{ route('sa.dashboard') }}"Dashboard
           class="flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isDashboard ? 'bg-white/10' : 'hover:bg-white/10' }}">
            <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                {{-- SVG dari snippet kamu --}}
                <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[25.31px] h-[25.31px] text-white">
                    <path d="M10.9945 13.6687C11.2139 13.5471 11.3968 13.3689 11.5241 13.1528C11.6515 12.9366 11.7187 12.6903 11.7188 12.4395V4.77422C11.7188 4.53853 11.6596 4.30662 11.5466 4.0998C11.4336 3.89297 11.2704 3.71787 11.072 3.59057C10.8737 3.46328 10.6465 3.38788 10.4114 3.3713C10.1763 3.35472 9.94081 3.3975 9.72656 3.4957C7.52609 4.50839 5.66153 6.12987 4.35325 8.1685C3.04498 10.2071 2.34767 12.5777 2.34375 15C2.34375 15.3949 2.3625 15.7922 2.39883 16.1836C2.4206 16.4162 2.49996 16.6397 2.62975 16.8339C2.75954 17.0281 2.93566 17.1869 3.14221 17.296C3.34876 17.4051 3.57924 17.461 3.81281 17.4587C4.04639 17.4564 4.27572 17.396 4.48008 17.2828L10.9945 13.6687ZM8.90625 7.27266V11.6086L5.25117 13.6359C5.60836 11.1246 6.91689 8.84649 8.90625 7.27266ZM15 2.34375C14.627 2.34375 14.2694 2.49191 14.0056 2.75563C13.7419 3.01935 13.5938 3.37704 13.5938 3.75V14.2418L4.59141 19.4848C4.43132 19.578 4.29124 19.702 4.17921 19.8495C4.06717 19.997 3.98539 20.1653 3.93857 20.3445C3.89174 20.5237 3.8808 20.7105 3.90636 20.8939C3.93191 21.0774 3.99347 21.2541 4.0875 21.4137C5.21117 23.3252 6.81734 24.908 8.74516 26.0036C10.673 27.0991 12.8548 27.669 15.0721 27.656C17.2895 27.6431 19.4645 27.0478 21.3794 25.9299C23.2943 24.8119 24.8819 23.2105 25.9832 21.2859C27.0845 19.3614 27.6608 17.1813 27.6545 14.9639C27.6482 12.7466 27.0594 10.5698 25.9472 8.65156C24.8349 6.73332 23.2382 5.14096 21.317 4.03393C19.3957 2.9269 17.2174 2.34405 15 2.34375ZM15 24.8438C13.5251 24.8398 12.0697 24.5063 10.7403 23.8676C9.41083 23.2289 8.2409 22.3012 7.31602 21.1523L15.7031 16.2645C15.9165 16.1412 16.0938 15.9641 16.2172 15.7509C16.3406 15.5376 16.4058 15.2956 16.4063 15.0492V5.25586C18.8714 5.60923 21.111 6.88381 22.6737 8.82277C24.2365 10.7617 25.0063 13.2209 24.8279 15.7049C24.6496 18.1888 23.5364 20.5129 21.7127 22.2087C19.889 23.9046 17.4903 24.8461 15 24.8438Z" fill="currentColor"/>
                </svg>
            </span>
            <span class="ml-4 text-[18px] font-medium leading-none">Dashboard</span>
        </a>

        {{-- Verifikasi Data (Dropdown) --}}
        <div class="rounded-lg {{ $isVerifikasiParent ? 'bg-white/10' : '' }}">
            <button type="button"
                onclick="toggleMenu('verifikasiSubmenu','verifikasiArrow')"
                class="w-full flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isVerifikasiParent ? '' : 'hover:bg-white/10' }}">
                <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                    {{-- SVG dari snippet kamu --}}
                    <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[25.31px] h-[25.31px] text-white">
                        <path d="M25.8621 11.7387C25.6799 11.7692 25.5054 11.8354 25.3487 11.9334C25.1921 12.0314 25.0563 12.1593 24.9491 12.3098C24.8419 12.4603 24.7654 12.6305 24.724 12.8106C24.6826 12.9907 24.6771 13.1772 24.7078 13.3594C24.7983 13.9016 24.8438 14.4503 24.8438 15C24.8471 17.2087 24.1038 19.3538 22.7344 21.0867C21.8229 19.9274 20.6611 18.9892 19.3359 18.3422C20.1805 17.487 20.7533 16.4011 20.9822 15.2211C21.2111 14.0411 21.086 12.8198 20.6225 11.7108C20.1591 10.6018 19.378 9.65458 18.3775 8.98838C17.3771 8.32217 16.202 7.96671 15 7.96671C13.798 7.96671 12.6229 8.32217 11.6225 8.98838C10.622 9.65458 9.84093 10.6018 9.37747 11.7108C8.91401 12.8198 8.78887 14.0411 9.0178 15.2211C9.24673 16.4011 9.8195 17.487 10.6641 18.3422C9.33887 18.9892 8.1771 19.9274 7.26562 21.0867C6.12055 19.6357 5.40748 17.8914 5.20814 16.0538C5.0088 14.2162 5.33126 12.3596 6.13856 10.6968C6.94586 9.03396 8.20534 7.63227 9.77262 6.65235C11.3399 5.67243 13.1516 5.15393 15 5.15628C15.5497 5.15624 16.0984 5.20171 16.6406 5.29221C17.0085 5.35438 17.3859 5.26787 17.69 5.05173C17.994 4.83558 18.1998 4.50751 18.2619 4.13968C18.3241 3.77184 18.2376 3.39438 18.0214 3.09033C17.8053 2.78628 17.4772 2.58055 17.1094 2.51839C15.2947 2.21369 13.4353 2.30799 11.6608 2.79474C9.88619 3.28149 8.23896 4.14901 6.83365 5.33693C5.42835 6.52486 4.29872 8.00468 3.52333 9.67346C2.74794 11.3422 2.34541 13.1599 2.34375 15C2.34375 18.3567 3.67717 21.5758 6.05068 23.9493C8.42419 26.3229 11.6434 27.6563 15 27.6563C18.3566 27.6563 21.5758 26.3229 23.9493 23.9493C26.3228 21.5758 27.6562 18.3567 27.6562 15C27.6564 14.2933 27.598 13.5877 27.4816 12.8907C27.4194 12.5232 27.2139 12.1956 26.9102 11.9796C26.6066 11.7636 26.2296 11.6769 25.8621 11.7387ZM11.7188 14.0625C11.7188 13.4136 11.9112 12.7792 12.2717 12.2396C12.6323 11.7 13.1448 11.2794 13.7443 11.031C14.3439 10.7827 15.0036 10.7177 15.6401 10.8443C16.2766 10.9709 16.8613 11.2834 17.3202 11.7423C17.7791 12.2012 18.0916 12.7859 18.2182 13.4224C18.3448 14.0589 18.2798 14.7186 18.0315 15.3182C17.7831 15.9178 17.3626 16.4302 16.823 16.7908C16.2834 17.1513 15.649 17.3438 15 17.3438C14.1298 17.3438 13.2952 16.9981 12.6798 16.3827C12.0645 15.7674 11.7188 14.9328 11.7188 14.0625ZM9.32461 23.0356C9.97743 22.143 10.8316 21.4169 11.8177 20.9164C12.8038 20.4159 13.8941 20.155 15 20.155C16.1059 20.155 17.1961 20.4159 18.1823 20.9164C19.1684 20.4169 20.0226 22.143 20.6754 23.0356C19.0167 24.2119 17.0335 24.8437 15 24.8437C12.9665 24.8437 10.9833 24.2119 9.32461 23.0356ZM28.1824 5.68245L24.4324 9.43245C24.3018 9.56355 24.1465 9.66757 23.9756 9.73855C23.8047 9.80952 23.6214 9.84606 23.4363 9.84606C23.2512 9.84606 23.068 9.80952 22.8971 9.73855C22.7261 9.66757 22.5709 9.56355 22.4402 9.43245L20.5652 7.55745C20.3011 7.29327 20.1526 6.93496 20.1526 6.56136C20.1526 6.18775 20.3011 5.82944 20.5652 5.56526C20.8294 5.30108 21.1877 5.15267 21.5613 5.15267C21.9349 5.15267 22.2932 5.30108 22.5574 5.56526L23.4375 6.44534L26.1926 3.68909C26.4568 3.42491 26.8151 3.27649 27.1887 3.27649C27.5623 3.27649 27.9206 3.42491 28.1848 3.68909C28.4489 3.95327 28.5974 4.31158 28.5974 4.68518C28.5974 5.05879 28.4489 5.4171 28.1848 5.68128L28.1824 5.68245Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="ml-4 text-[18px] font-medium leading-none">Verifikasi Data</span>

                <span id="verifikasiArrow" class="ml-auto text-white/70 text-xl leading-none transition"
                      style="{{ $isVerifikasiParent ? 'transform: rotate(90deg); display:inline-block;' : 'transform: rotate(0deg); display:inline-block;' }}">›</span>
            </button>

            <div id="verifikasiSubmenu" class="{{ $isVerifikasiParent ? '' : 'hidden' }}">
                <a href="{{ route('sa.verifikasi.mitra') }}"
                   class="block h-[49px] px-16 flex items-center rounded-lg transition text-white {{ $isVerifikasiMitra ? 'bg-black/30' : 'hover:bg-black/20' }}">
                    <span class="text-[16px] font-medium leading-none">Verifikasi Mitra</span>
                </a>

                <a href="{{ route('sa.verifikasi.customer') }}"
                   class="block h-[49px] px-16 flex items-center rounded-lg transition text-white {{ $isVerifikasiCustomer ? 'bg-black/30' : 'hover:bg-black/20' }}">
                    <span class="text-[16px] font-medium leading-none">Verifikasi Customer</span>
                </a>
            </div>
        </div>

        {{-- Mitra (Dropdown) --}}
        <div class="rounded-lg {{ $isMitraParent ? 'bg-white/10' : '' }}">
            <button type="button"
                onclick="toggleMenu('mitraSubmenu','mitraArrow')"
                class="w-full flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isMitraParent ? '' : 'hover:bg-white/10' }}">
                <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                    {{-- ✅ SVG MITRA (baru dari kamu) --}}
                    <svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="w-[25.31px] h-[25.31px]">
                        <path d="M9.375 10.3125C9.375 9.93954 9.52316 9.58185 9.78688 9.31813C10.0506 9.05441 10.4083 8.90625 10.7812 8.90625H14.5312C14.9042 8.90625 15.2619 9.05441 15.5256 9.31813C15.7893 9.58185 15.9375 9.93954 15.9375 10.3125C15.9375 10.6855 15.7893 11.0431 15.5256 11.3069C15.2619 11.5706 14.9042 11.7188 14.5312 11.7188H10.7812C10.4083 11.7188 10.0506 11.5706 9.78688 11.3069C9.52316 11.0431 9.375 10.6855 9.375 10.3125ZM25.3125 6.5625V21.5625C25.3125 22.1841 25.0656 22.7802 24.626 23.2198C24.1865 23.6593 23.5904 23.9062 22.9688 23.9062H2.34375C1.72215 23.9062 1.12601 23.6593 0.686468 23.2198C0.24693 22.7802 0 22.1841 0 21.5625V6.5625C0 5.9409 0.24693 5.34476 0.686468 4.90522C1.12601 4.46568 1.72215 4.21875 2.34375 4.21875H6.5625V3.28125C6.5625 2.41101 6.9082 1.57641 7.52356 0.961056C8.13891 0.345702 8.97351 0 9.84375 0H15.4688C16.339 0 17.1736 0.345702 17.7889 0.961056C18.4043 1.57641 18.75 2.41101 18.75 3.28125V4.21875H22.9688C23.5904 4.21875 24.1865 4.46568 24.626 4.90522C25.0656 5.34476 25.3125 5.9409 25.3125 6.5625ZM9.375 4.21875H15.9375V3.28125C15.9375 3.15693 15.8881 3.0377 15.8002 2.94979C15.7123 2.86189 15.5931 2.8125 15.4688 2.8125H9.84375C9.71943 2.8125 9.6002 2.86189 9.51229 2.94979C9.42439 3.0377 9.375 3.15693 9.375 3.28125V4.21875ZM2.8125 7.03125V11.1598C5.84713 12.7592 9.22593 13.5946 12.6562 13.5938C16.0867 13.5945 19.4656 12.7587 22.5 11.1586V7.03125H2.8125ZM22.5 21.0938V14.2898C19.4059 15.6852 16.0504 16.4066 12.6562 16.4062C9.26204 16.4069 5.90653 15.6854 2.8125 14.2898V21.0938H22.5Z"
                            fill="white"/>
                    </svg>
             </span>
                <span class="ml-4 text-[18px] font-medium leading-none">Mitra</span>

                <span id="mitraArrow" class="ml-auto text-white/70 text-xl leading-none transition"
                      style="{{ $isMitraParent ? 'transform: rotate(90deg); display:inline-block;' : 'transform: rotate(0deg); display:inline-block;' }}">›</span>
            </button>

            <div id="mitraSubmenu" class="{{ $isMitraParent ? '' : 'hidden' }}">
                <a href="{{ route('sa.mitra.index') }}"
                   class="block h-[49px] px-16 flex items-center rounded-lg transition text-white {{ $isMitraKendaraan ? 'bg-black/30' : 'hover:bg-black/20' }}">
                    <span class="text-[16px] font-medium leading-none">Daftar Mitra</span>
                </a>

                <a href="{{ route('sa.mitra.kendaraan') }}"
                   class="block h-[49px] px-16 flex items-center rounded-lg transition text-white {{ $isMitraKendaraan ? 'bg-black/30' : 'hover:bg-black/20' }}">
                    <span class="text-[16px] font-medium leading-none">Kendaraan Mitra</span>
                </a>

                {{-- OPTIONAL: kalau route sa.mitra.blokir belum ada, jangan pakai dulu.
                     Kalau kamu sudah punya route-nya, biarkan aktif.
                --}}
            </div>
        </div>

        {{-- Customer (menu single) --}}
        <a href="{{ route('sa.customer.index') }}"
           class="flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isCustomer ? 'bg-white/10' : 'hover:bg-white/10' }}">
            <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                {{-- pakai SVG dari sidebar lama (rapi) --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" class="w-[25.31px] h-[25.31px]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8a4 4 0 0 0 0 8Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20c1.5-4 14.5-4 16 0" />
                </svg>
            </span>
            <span class="ml-4 text-[18px] font-medium leading-none">Customer</span>
        </a>

        {{-- Pesanan --}}
        <a href="{{ route('sa.transaksi') }}"
           class="flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isPesanan ? 'bg-white/10' : 'hover:bg-white/10' }}">
            <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                {{-- SVG dari snippet kamu --}}
                <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[25.31px] h-[25.31px] text-white">
                    <path d="M27.3293 6.59883C27.1973 6.44076 27.0323 6.31359 26.8458 6.22632C26.6593 6.13904 26.4559 6.09379 26.25 6.09375H7.73438L7.09336 2.56055C7.03439 2.23673 6.8637 1.94386 6.61102 1.73294C6.35835 1.52202 6.03969 1.40641 5.71055 1.40625H2.8125C2.43954 1.40625 2.08185 1.55441 1.81813 1.81813C1.55441 2.08185 1.40625 2.43954 1.40625 2.8125C1.40625 3.18546 1.55441 3.54315 1.81813 3.80687C2.08185 4.07059 2.43954 4.21875 2.8125 4.21875H4.53633L7.45547 20.2734C7.53384 20.6999 7.69595 21.1065 7.93242 21.4699C7.51377 21.9598 7.2284 22.5493 7.10386 23.1815C6.97931 23.8137 7.01979 24.4674 7.22138 25.0794C7.42297 25.6915 7.77887 26.2412 8.25476 26.6757C8.73065 27.1102 9.31047 27.4147 9.93829 27.5599C10.5661 27.705 11.2207 27.686 11.8391 27.5046C12.4574 27.3231 13.0185 26.9854 13.4683 26.524C13.9181 26.0626 14.2415 25.493 14.4071 24.8703C14.5728 24.2476 14.5752 23.5927 14.4141 22.9688H18.3984C18.2029 23.7259 18.249 24.5253 18.5303 25.2549C18.8115 25.9846 19.3138 26.6081 19.9669 27.0382C20.6201 27.4683 21.3913 27.6834 22.1727 27.6535C22.9541 27.6236 23.7067 27.3502 24.325 26.8715C24.9434 26.3927 25.3966 25.7327 25.6212 24.9837C25.8459 24.2346 25.8308 23.4341 25.578 22.6941C25.3253 21.9541 24.8475 21.3116 24.2115 20.8565C23.5756 20.4015 22.8132 20.1566 22.0312 20.1562H10.684C10.5744 20.1561 10.4683 20.1176 10.3842 20.0474C10.3 19.9772 10.2432 19.8797 10.2234 19.7719L9.95156 18.2812H22.9805C23.7491 18.2813 24.4933 18.0114 25.0833 17.5189C25.6734 17.0263 26.0717 16.3422 26.209 15.5859L27.634 7.75078C27.6706 7.54817 27.6623 7.33999 27.6097 7.14094C27.557 6.94189 27.4613 6.75683 27.3293 6.59883Z" fill="currentColor"/>
                </svg>
            </span>
            <span class="ml-4 text-[18px] font-medium leading-none">Pesanan</span>
        </a>

        {{-- Refund --}}
        <a href="{{ route('sa.refund.index') }}"
           class="flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isRefund ? 'bg-white/10' : 'hover:bg-white/10' }}">
            <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                {{-- SVG dari snippet kamu --}}
                <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[25.31px] h-[25.31px] text-white">
                    <path d="M26.7188 15C26.719 18.0809 25.506 21.0381 23.3421 23.2313C21.1783 25.4246 18.2377 26.6774 15.157 26.7187H15C12.0083 26.725 9.12886 25.5799 6.95859 23.5207C6.68742 23.2646 6.52909 22.9112 6.51843 22.5384C6.50777 22.1655 6.64566 21.8037 6.90176 21.5326C7.15786 21.2614 7.51119 21.1031 7.88403 21.0924C8.25687 21.0818 8.61867 21.2196 8.88984 21.4757C10.1628 22.6775 11.7617 23.4775 13.4867 23.7757C15.2117 24.0739 16.9864 23.8571 18.5889 23.1525C20.1914 22.4478 21.5507 21.2865 22.497 19.8137C23.4432 18.3408 23.9344 16.6218 23.9092 14.8714C23.884 13.1209 23.3435 11.4168 22.3552 9.97178C21.3669 8.52681 19.9747 7.40513 18.3526 6.74691C16.7304 6.08869 14.9503 5.92312 13.2346 6.2709C11.5188 6.61867 9.94366 7.46437 8.70586 8.7023C8.69063 8.71753 8.67656 8.7316 8.66016 8.74566L6.43242 10.7812H8.4375C8.81046 10.7812 9.16815 10.9294 9.43187 11.1931C9.69559 11.4568 9.84375 11.8145 9.84375 12.1875C9.84375 12.5604 9.69559 12.9181 9.43187 13.1818C9.16815 13.4455 8.81046 13.5937 8.4375 13.5937H2.8125C2.43954 13.5937 2.08185 13.4455 1.81813 13.1818C1.55441 12.9181 1.40625 12.5604 1.40625 12.1875V6.56246C1.40625 6.1895 1.55441 5.83181 1.81813 5.56809C2.08185 5.30436 2.43954 5.15621 2.8125 5.15621C3.18546 5.15621 3.54315 5.30436 3.80687 5.56809C4.07059 5.83181 4.21875 6.1895 4.21875 6.56246V8.99058L6.73594 6.68667C8.37761 5.05382 10.4662 3.94389 12.7381 3.49697C15.01 3.05006 17.3634 3.2862 19.5013 4.17558C21.6391 5.06497 23.4655 6.56773 24.7501 8.4942C26.0346 10.4207 26.7197 12.6845 26.7188 15Z" fill="currentColor"/>
                </svg>
            </span>
            <span class="ml-4 text-[18px] font-medium leading-none">Refund</span>
        </a>

        {{-- Laporan --}}
        <a href="{{ route('sa.laporan') }}"
           class="flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isLaporan ? 'bg-white/10' : 'hover:bg-white/10' }}">
            <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" class="w-[25.31px] h-[25.31px]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 19V10" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V7" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 19V12" />
                </svg>
            </span>
            <span class="ml-4 text-[18px] font-medium leading-none">Laporan</span>
        </a>

    </nav>

    {{-- HELP & SUPPORT --}}
    <div class="px-8 mt-10 text-[18px] font-semibold tracking-wide opacity-70">
        HELP &amp; SUPPORT
    </div>

    {{-- Pengaturan --}}
    <div class="mt-4 px-2">
        <a href="{{ route('sa.pengaturan') }}"
           class="flex items-center h-[49px] px-6 rounded-lg transition text-white {{ $isPengaturan ? 'bg-white/10' : 'hover:bg-white/10' }}">
            <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[25.31px] h-[25.31px] text-white">
                    <path d="M12 15.5a3.5 3.5 0 1 0 0-7a3.5 3.5 0 0 0 0 7Z" stroke="currentColor" stroke-width="2.2"/>
                    <path d="M19 12a7 7 0 0 0-.1-1l2-1.5-2-3.5-2.4 1a7.3 7.3 0 0 0-1.7-1l-.3-2.6H9.5L9.2 6a7.3 7.3 0 0 0-1.7 1L5.1 6l-2 3.5L5 11a7 7 0 0 0 0 2l-1.9 1.5 2 3.5 2.4-1a7.3 7.3 0 0 0 1.7 1l.3 2.6h5l.3-2.6a7.3 7.3 0 0 0 1.7-1l2.4 1 2-3.5-2-1.5c.1-.3.1-.7.1-1Z"
                          stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="ml-4 text-[18px] font-medium leading-none">Pengaturan</span>
        </a>
    </div>

    <div class="flex-1"></div>

    {{-- Logout --}}
    <div class="px-2 pb-7">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center h-[49px] px-6 rounded-lg bg-white/10 hover:bg-white/15 transition text-white">
                <span class="w-[25.31px] h-[25.31px] flex items-center justify-center">
                    {{-- SVG dari snippet kamu --}}
                    <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[25.31px] h-[25.31px] text-white">
                        <path d="M5 15C5 15.3315 5.1317 15.6495 5.36612 15.8839C5.60054 16.1183 5.91848 16.25 6.25 16.25H15.7375L12.8625 19.1125C12.7453 19.2287 12.6523 19.367 12.5889 19.5193C12.5254 19.6716 12.4928 19.835 12.4928 20C12.4928 20.165 12.5254 20.3284 12.5889 20.4807C12.6523 20.633 12.7453 20.7713 12.8625 20.8875C12.9787 21.0047 13.117 21.0977 13.2693 21.1611C13.4216 21.2246 13.585 21.2572 13.75 21.2572C13.915 21.2572 14.0784 21.2246 14.2307 21.1611C14.383 21.0977 14.5213 21.0047 14.6375 20.8875L19.6375 15.8875C19.7513 15.7686 19.8405 15.6284 19.9 15.475C20.025 15.1707 20.025 14.8293 19.9 14.525C19.8405 14.3716 19.7513 14.2314 19.6375 14.1125L14.6375 9.1125C14.521 8.99595 14.3826 8.9035 14.2303 8.84043C14.078 8.77735 13.9148 8.74489 13.75 8.74489C13.5852 8.74489 13.422 8.77735 13.2697 8.84043C13.1174 8.9035 12.979 8.99595 12.8625 9.1125C12.746 9.22905 12.6535 9.36741 12.5904 9.51969C12.5273 9.67197 12.4949 9.83518 12.4949 10C12.4949 10.1648 12.5273 10.328 12.5904 10.4803C12.6535 10.6326 12.746 10.771 12.8625 10.8875L15.7375 13.75H6.25C5.91848 13.75 5.60054 13.8817 5.36612 14.1161C5.1317 14.3505 5 14.6685 5 15ZM21.25 2.5H8.75C7.75544 2.5 6.80161 2.89509 6.09835 3.59835C5.39509 4.30161 5 5.25544 5 6.25V10C5 10.3315 5.1317 10.6495 5.36612 10.8839C5.60054 11.1183 5.91848 11.25 6.25 11.25C6.58152 11.25 6.89946 11.1183 7.13388 10.8839C7.3683 10.6495 7.5 10.3315 7.5 10V6.25C7.5 5.91848 7.6317 5.60054 7.86612 5.36612C8.10054 5.1317 8.41848 5 8.75 5H21.25C21.5815 5 21.8995 5.1317 22.1339 5.36612C22.3683 5.60054 22.5 5.91848 22.5 6.25V23.75C22.5 24.0815 22.3683 24.3995 22.1339 24.6339C21.8995 24.8683 21.5815 25 21.25 25H8.75C8.41848 25 8.10054 24.8683 7.86612 24.6339C7.6317 24.3995 7.5 24.0815 7.5 23.75V20C7.5 19.6685 7.3683 19.3505 7.13388 19.1161C6.89946 18.8817 6.58152 18.75 6.25 18.75C5.91848 18.75 5.60054 18.8817 5.36612 19.1161C5.1317 19.3505 5 19.6685 5 20V23.75C5 24.7446 5.39509 25.6984 6.09835 26.4017C6.80161 27.1049 7.75544 27.5 8.75 27.5H21.25C22.2446 27.5 23.1984 27.1049 23.9017 26.4017C24.6049 25.6984 25 24.7446 25 23.75V6.25C25 5.25544 24.6049 4.30161 23.9017 3.59835C23.1984 2.89509 22.2446 2.5 21.25 2.5Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="ml-4 text-[18px] font-medium leading-none">Keluar</span>
            </button>
        </form>
    </div>

    {{-- SCRIPT dropdown --}}
    <script>
        function toggleMenu(submenuId, arrowId) {
            const submenu = document.getElementById(submenuId);
            const arrow = document.getElementById(arrowId);
            if (!submenu || !arrow) return;

            submenu.classList.toggle('hidden');
            const isHidden = submenu.classList.contains('hidden');
            arrow.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(90deg)';
            arrow.style.display = 'inline-block';
        }
    </script>
</aside>

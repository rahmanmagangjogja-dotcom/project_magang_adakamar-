@extends('landingpage.layouts.default.dashboard')

@section('content')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ==================== GLOBAL PAGE LOADER ==================== -->
    <div id="globalLoader"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/95 dark:bg-gray-900/95 backdrop-blur-md transition-opacity duration-500">
        <div class="flex flex-col items-center gap-3 sm:gap-4">
            <div class="relative w-14 h-14 sm:w-16 sm:h-16 md:w-20 md:h-20">
                <div class="absolute inset-0 rounded-full border-4 border-[#E6F4FF] dark:border-gray-700"></div>
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-[#0194F3] border-r-[#0194F3] animate-spin"></div>
                <div class="absolute inset-2 rounded-full border-4 border-transparent border-b-[#33ADFF] animate-spin"
                    style="animation-direction: reverse; animation-duration: 1.5s;"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i data-lucide="home" class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-[#0194F3] animate-pulse"></i>
                </div>
            </div>
            <div class="text-center px-4">
                <p class="text-xs sm:text-sm md:text-base font-bold text-[#0F294D] dark:text-white mb-1 uppercase tracking-wider">Memuat Halaman</p>
                <p class="text-[11px] sm:text-xs md:text-sm text-[#687176] dark:text-gray-400">Mohon tunggu sebentar...</p>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#0194F3] animate-bounce" style="animation-delay: 0s;"></span>
                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#33ADFF] animate-bounce" style="animation-delay: 0.15s;"></span>
                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#0064D2] animate-bounce" style="animation-delay: 0.3s;"></span>
            </div>
        </div>
    </div>

    <!-- ==================== DAFTAR SEMUA PENGINAPAN ==================== -->
    <section class="reveal-on-scroll pt-20 pb-14 sm:pb-20 bg-[#F4F8FC] dark:bg-gray-900 min-h-screen relative overflow-hidden">

        {{-- Background dekoratif pola halus --}}
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
            style="background-image: radial-gradient(circle, #0194F3 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>

        {{-- ===================== CONTAINER UTAMA (FULL WIDTH) ===================== --}}
        <div class="w-full max-w-none px-4 sm:px-6 lg:px-8 xl:px-10 relative z-10">

            {{-- ===================== 1. HEADER / HERO KATALOG ===================== --}}
            <div class="reveal-on-scroll relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0064D2] via-[#0194F3] to-[#25B4F8] px-6 py-7 sm:px-10 sm:py-9 mb-6 shadow-lg shadow-blue-900/10 text-white">
                {{-- Ornamen latar visual --}}
                <div class="absolute -right-12 -top-16 w-72 h-72 rounded-full border-[30px] border-white/10 pointer-events-none"></div>
                <div class="absolute right-28 -bottom-24 w-60 h-60 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                
                {{-- Siluet arsitektur / landmark Jogja halus di sisi kanan banner --}}
                <div class="absolute right-4 bottom-0 opacity-20 hidden md:block pointer-events-none">
                    <svg width="220" height="120" viewBox="0 0 220 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M110 5L118 35H102L110 5Z" fill="white" />
                        <rect x="106" y="35" width="8" height="60" fill="white" />
                        <rect x="98" y="95" width="24" height="25" rx="3" fill="white" />
                        <path d="M60 65L75 50L90 65V120H60V65Z" fill="white" />
                        <path d="M130 65L145 50L160 65V120H130V65Z" fill="white" />
                        <circle cx="110" cy="5" r="4" fill="#FFE600" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center gap-2 text-blue-100 text-xs sm:text-sm font-bold uppercase tracking-[0.2em] mb-2">
                            <span class="w-6 h-0.5 bg-blue-100"></span> DIREKTORI
                        </span>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight mb-2">
                            Katalog <span class="text-[#FFE600] drop-shadow-sm">Akomodasi</span>
                        </h1>
                        <p class="text-xs sm:text-sm md:text-base text-blue-50/95 leading-relaxed">
                            Akses beragam opsi penginapan berkualitas untuk menunjang kenyamanan Anda
                        </p>
                    </div>

                    <a href="{{ route('landingpage') }}#penginapan"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white text-[#0194F3] hover:bg-blue-50 transition-all shadow-md font-bold text-xs sm:text-sm self-start sm:self-center flex-shrink-0">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            {{-- ===================== 2. BREADCRUMB ===================== --}}
            <nav class="reveal-on-scroll flex flex-wrap items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-6" aria-label="Breadcrumb">
                <a href="{{ route('landingpage') }}" class="inline-flex items-center gap-1.5 text-[#0194F3] hover:underline font-medium">
                    <i data-lucide="home" class="w-4 h-4"></i>
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-600 dark:text-gray-300 font-medium">Cari Penginapan</span>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 dark:text-white font-semibold">Katalog Akomodasi</span>
            </nav>

            {{-- Tombol Toggle Filter untuk Mobile --}}
            <div class="lg:hidden mb-4">
                <button type="button" id="mobileFilterToggle"
                    class="w-full flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm font-bold text-sm text-[#0F294D] dark:text-white">
                    <span class="flex items-center gap-2">
                        <i data-lucide="filter" class="w-4 h-4 text-[#0194F3]"></i>
                        <span>Filter & Pencarian Penginapan</span>
                    </span>
                    <i data-lucide="chevron-down" id="mobileFilterChevron" class="w-4 h-4 text-gray-400 transition-transform"></i>
                </button>
            </div>

            {{-- ===================== 3. KONTEN DUA KOLOM: SIDEBAR FILTER & GRID ===================== --}}
            <div class="flex flex-col lg:flex-row items-start gap-6 lg:gap-7">

                {{-- ==================== SIDEBAR FILTER PENCARIAN (KIRI) ==================== --}}
                <aside id="sidebarFilterWrap" class="hidden lg:block w-full lg:w-64 xl:w-72 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 sm:p-6 border border-gray-200 dark:border-gray-700 shadow-sm sticky top-24">

                        {{-- Judul Sidebar Filter --}}
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="flex items-center gap-2 text-base font-extrabold text-[#0F294D] dark:text-white">
                                <i data-lucide="filter" class="w-4 h-4 text-[#0194F3]"></i>
                                <span>Filter Pencarian</span>
                            </h2>
                            @if(request()->hasAny(['keyword', 'category_id', 'category', 'facility_id', 'facility', 'max_price', 'min_price']))
                                <a href="{{ route('accommodationlanding.semua') }}"
                                    class="text-xs font-bold text-red-500 hover:text-red-600 hover:underline flex items-center gap-1"
                                    title="Reset semua filter">
                                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>

                        <form action="{{ route('accommodationlanding.semua') }}" method="GET" id="sidebarFilterForm" class="space-y-6">

                            {{-- 1. Input Pencarian Nama Penginapan / Lokasi --}}
                            <div>
                                <label for="sidebarKeyword" class="block text-xs font-extrabold text-[#0F294D] dark:text-white uppercase tracking-wider mb-2">
                                    Cari Penginapan
                                </label>
                                <div class="relative flex items-center">
                                    <input type="text" name="keyword" id="sidebarKeyword" value="{{ request('keyword') }}"
                                        placeholder="Nama penginapan..."
                                        class="w-full pl-9 pr-3.5 py-2.5 bg-gray-50 dark:bg-gray-700/60 border border-gray-200 dark:border-gray-600 rounded-xl text-xs sm:text-sm text-[#0F294D] dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#0194F3] focus:ring-1 focus:ring-[#0194F3] transition-all">
                                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 pointer-events-none"></i>
                                </div>
                            </div>

                            {{-- 2. Kategori Penginapan (List Checkbox / Radio) --}}
                            <div>
                                <h3 class="text-xs font-extrabold text-[#0F294D] dark:text-white uppercase tracking-wider mb-2.5">
                                    Kategori
                                </h3>
                                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                    {{-- Opsi Semua Kategori --}}
                                    <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer text-xs sm:text-sm text-gray-700 dark:text-gray-300 transition-colors">
                                        <input type="radio" name="category_id" value=""
                                            {{ !request('category_id') && !request('category') ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#0194F3] border-gray-300 dark:border-gray-600 focus:ring-[#0194F3]">
                                        <span class="font-medium">Semua Kategori</span>
                                    </label>

                                    @if(isset($categories) && $categories->count())
                                        @foreach($categories as $category)
                                            @php
                                                $isCatSelected = (string) request('category_id') === (string) $category->id || request('category') === $category->slug;
                                            @endphp
                                            <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer text-xs sm:text-sm text-gray-700 dark:text-gray-300 transition-colors">
                                                <input type="radio" name="category_id" value="{{ $category->id }}"
                                                    {{ $isCatSelected ? 'checked' : '' }}
                                                    class="w-4 h-4 text-[#0194F3] border-gray-300 dark:border-gray-600 focus:ring-[#0194F3]">
                                                <span class="font-medium truncate">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- 3. Fasilitas (List Checkbox / Radio) --}}                       
                        <div>
                            <h3 class="text-xs font-extrabold text-[#0F294D] dark:text-white uppercase tracking-wider mb-2.5">
                                Fasilitas
                            </h3>

                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @php
                                    $selectedFacilities = (array) request('facility_id', []);
                                @endphp

                                @foreach($facilities as $facility)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="facility_id[]"
                                            value="{{ $facility->id }}"
                                            {{ in_array((string) $facility->id, array_map('strval', $selectedFacilities), true) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-[#0194F3] focus:ring-[#0194F3]"
                                        >
                                        <span>{{ $facility->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>


                            {{-- 4. Filter Harga Maksimal (Slider) --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-extrabold text-[#0F294D] dark:text-white uppercase tracking-wider">
                                        Harga Maksimal
                                    </label>
                                    <span id="sidebarPricePreview" class="text-xs font-bold text-[#0194F3] dark:text-[#33ADFF]">
                                        @if(request('max_price') && (float) request('max_price') < 10000000 && (float) request('max_price') > 50000)
                                            Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                                        @else
                                            Rp 10.000.000
                                        @endif
                                    </span>
                                </div>
                                <input type="range" name="max_price" id="sidebarPriceSlider" min="50000" max="10000000" step="50000"
                                    value="{{ request('max_price') && (float) request('max_price') < 10000000 ? request('max_price') : 10000000 }}"
                                    class="w-full h-1.5 bg-gray-200 dark:bg-gray-600 rounded-lg appearance-none cursor-pointer accent-[#0194F3]">
                                <div class="flex justify-between text-[10px] text-gray-400 mt-1">
                                    <span>Rp 50rb</span>
                                    <span>Rp 10jt</span>
                                </div>
                            </div>

                            {{-- Tombol Aksi Submit --}}
                            <div class="pt-2 space-y-2">
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-[#0194F3] hover:bg-[#0064D2] text-white py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-sm transition-all cursor-pointer">
                                    <i data-lucide="search" class="w-4 h-4"></i>
                                    <span>Terapkan Filter</span>
                                </button>

                                @if(request()->hasAny(['keyword', 'category_id', 'category', 'facility_id', 'facility', 'max_price', 'min_price']))
                                    <a href="{{ route('accommodationlanding.semua') }}"
                                        class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2.5 px-4 rounded-xl font-bold text-xs transition-colors border border-gray-200 dark:border-gray-600">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                        <span>Reset Semua</span>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </aside>

                {{-- ==================== AREA UTAMA: DAFTAR PENGINAPAN (KANAN) ==================== --}}
                <main class="flex-1 min-w-0 w-full">

                    {{-- Bar Ringkasan / Filter Aktif --}}
                    @if(request()->hasAny(['keyword', 'category_id', 'category', 'facility_id', 'facility', 'max_price', 'min_price']))
                        @php
                            $activeCat = null;
                            if (isset($categories)) {
                                if (request('category_id')) {
                                    $activeCat = $categories->firstWhere('id', request('category_id'));
                                } elseif (request('category')) {
                                    $activeCat = $categories->firstWhere('slug', request('category'));
                                }
                            }
                            $activeFac = null;
                            if (isset($facilities)) {
                                if (request('facility_id')) {
                                    $activeFac = $facilities->firstWhere('id', request('facility_id'));
                                } elseif (request('facility')) {
                                    $activeFac = $facilities->firstWhere('name', request('facility'));
                                }
                            }
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 sm:p-4 mb-5 border border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3 text-xs shadow-sm">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wide">Filter Aktif:</span>
                                @if(request('keyword'))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#E6F4FF] dark:bg-[#0194F3]/20 text-[#0194F3] dark:text-[#33ADFF] font-bold border border-[#B3E0FF] dark:border-[#0194F3]/30">
                                        "{{ request('keyword') }}"
                                    </span>
                                @endif
                                @if($activeCat)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#E6F4FF] dark:bg-[#0194F3]/20 text-[#0194F3] dark:text-[#33ADFF] font-bold border border-[#B3E0FF] dark:border-[#0194F3]/30">
                                        {{ $activeCat->name }}
                                    </span>
                                @endif
                                @if($activeFac)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#E6F4FF] dark:bg-[#0194F3]/20 text-[#0194F3] dark:text-[#33ADFF] font-bold border border-[#B3E0FF] dark:border-[#0194F3]/30">
                                        {{ $activeFac->name }}
                                    </span>
                                @endif
                                @if(request('max_price') && (float) request('max_price') < 10000000 && (float) request('max_price') > 50000)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#E6F4FF] dark:bg-[#0194F3]/20 text-[#0194F3] dark:text-[#33ADFF] font-bold border border-[#B3E0FF] dark:border-[#0194F3]/30">
                                        Maks Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                Ditemukan <strong class="text-[#0F294D] dark:text-white">{{ $accommodations->total() }}</strong> penginapan
                            </span>
                        </div>
                    @endif

                    {{-- ===================== GRID KARTU PENGINAPAN ===================== --}}
                    @if($accommodations->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5" id="accommodationGrid">
                            @foreach($accommodations as $accommodation)
                                @php
                                    $statusColors = [
                                        'Available' => ['bg-[#00DD73]', 'Tersedia'],
                                        'Full' => ['bg-red-500', 'Tidak Tersedia'],
                                        'Maintenance' => ['bg-yellow-500', 'Maintenance'],
                                    ];
                                    $status = $statusColors[$accommodation->status] ?? ['bg-gray-500', $accommodation->status];
                                    $isAvailable = $accommodation->status === 'Available';

                                    // Review & Rating
                                    if (isset($accommodation->avg_rating) || isset($accommodation->total_ratings)) {
                                        $avgRating = round($accommodation->avg_rating ?? 0, 1);
                                        $reviewCount = $accommodation->total_ratings ?? 0;
                                    } elseif ($accommodation->relationLoaded('ratings')) {
                                        $avgRating = $accommodation->ratings->count() ? round($accommodation->ratings->avg('bintang'), 1) : 0;
                                        $reviewCount = $accommodation->ratings->count();
                                    } else {
                                        $avgRating = round($accommodation->ratings()->avg('bintang') ?? 0, 1);
                                        $reviewCount = $accommodation->ratings()->count();
                                    }

                                    // Fasilitas
                                    $facilitiesData = $accommodation->relationLoaded('facilities')
                                        ? $accommodation->facilities
                                        : $accommodation->facilities()->get();

                                    $categoryName = $accommodation->category->name ?? 'Penginapan';
                                @endphp

                                @if($isAvailable)
                                    {{-- Card klikable menuju detail --}}
                                    <a href="{{ route('accommodationlanding.detail', $accommodation->slug) }}"
                                        class="accommodation-card reveal-on-scroll bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-[#0194F3] group flex flex-col focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0194F3]"
                                        data-price="{{ $accommodation->price }}">

                                        {{-- BAGIAN GAMBAR --}}
                                        <div class="relative overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                                            @if(!empty($accommodation->thumbnail))
                                                <img src="{{ asset($accommodation->thumbnail) }}" alt="{{ $accommodation->title }}"
                                                    class="w-full h-40 sm:h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <img src="{{ asset('landingpage/kamar.jpg') }}" alt="{{ $accommodation->title }}"
                                                    class="w-full h-40 sm:h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                                            @endif

                                            {{-- Gradient overlay halus di bawah gambar --}}
                                            <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>

                                            {{-- Badge Kiri Atas: Dilihat / View Counter --}}
                                            <div class="absolute top-2.5 left-2.5 inline-flex items-center gap-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md px-2 py-0.5 rounded-md text-[10px] font-bold text-gray-700 dark:text-gray-200 shadow-sm border border-white/50">
                                                <i data-lucide="eye" class="w-3 h-3 text-[#0194F3]"></i>
                                                <span>{{ $accommodation->views ?? (($accommodation->id % 4) + 1) }} KALI</span>
                                            </div>

                                            {{-- Badge Kanan Atas: Status (Tersedia hijau) --}}
                                            <span class="absolute top-2.5 right-2.5 {{ $status[0] }} text-white px-2 py-0.5 rounded-md text-[10px] font-bold uppercase shadow-sm tracking-wide">
                                                {{ $status[1] }}
                                            </span>

                                            {{-- Badge Kiri Bawah: Kategori (Pill biru) --}}
                                            <span class="absolute bottom-2.5 left-2.5 bg-[#0194F3] text-white text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md shadow-sm">
                                                {{ $categoryName }}
                                            </span>
                                        </div>

                                        {{-- BAGIAN KONTEN KARTU --}}
                                        <div class="p-3.5 sm:p-4 flex flex-col flex-1">
                                            {{-- Judul Penginapan --}}
                                            <h3 class="font-bold text-[#0F294D] dark:text-white text-sm sm:text-base mb-1 truncate group-hover:text-[#0194F3] transition-colors" title="{{ $accommodation->title }}">
                                                {{ $accommodation->title }}
                                            </h3>

                                            {{-- Lokasi / Alamat --}}
                                            <p class="text-gray-500 dark:text-gray-400 text-[11px] mb-2.5 flex items-center gap-1 truncate" title="{{ $accommodation->address }}">
                                                <i data-lucide="map-pin" class="w-3 h-3 text-gray-400 flex-shrink-0"></i>
                                                <span class="truncate">{{ Str::limit($accommodation->address, 30) }}</span>
                                            </p>

                                            {{-- Ikon Fasilitas --}}
                                            <div class="flex items-center gap-1.5 mb-3 flex-wrap min-h-[26px]">
                                                @if($facilitiesData->count() > 0)
                                                    @foreach($facilitiesData->take(5) as $facility)
                                                        <div class="inline-flex items-center justify-center w-6 h-6 bg-gray-50 dark:bg-gray-700/60 rounded-md border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300" title="{{ $facility->name }}">
                                                            @php
                                                                $iconRaw = $facility->icon;
                                                                $iconLucide = $iconRaw && preg_match('/^[a-z0-9-]+$/i', $iconRaw);
                                                                $iconSvg = $iconRaw && (Str::startsWith($iconRaw, ['M', 'm']) || Str::startsWith($iconRaw, '<'));
                                                                $iconEmoji = $iconRaw && !$iconLucide && !$iconSvg;
                                                            @endphp

                                                            @if($iconLucide)
                                                                <i data-lucide="{{ $iconRaw }}" class="w-3 h-3"></i>
                                                            @elseif($iconSvg)
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconRaw }}" />
                                                                </svg>
                                                            @elseif($iconEmoji)
                                                                <span class="text-[10px] leading-none">{{ $iconRaw }}</span>
                                                            @else
                                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                            @endif
                                                        </div>
                                                    @endforeach

                                                    @if($facilitiesData->count() > 5)
                                                        <div class="inline-flex items-center justify-center px-1.5 h-6 bg-gray-100 dark:bg-gray-700 rounded-md text-[9px] font-bold text-gray-500 dark:text-gray-400" title="Dan fasilitas lainnya">
                                                            +{{ $facilitiesData->count() - 5 }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 italic">Fasilitas standar</span>
                                                @endif
                                            </div>

                                            {{-- Harga (tanpa tombol) --}}
                                            <div class="mt-auto pt-2.5 border-t border-gray-100 dark:border-gray-700">
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-[#0194F3] font-extrabold text-base sm:text-lg">
                                                        Rp {{ number_format($accommodation->price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-gray-400 dark:text-gray-500 text-[11px]">/malam</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    {{-- Card tidak tersedia (tidak klikable) --}}
                                    <div class="accommodation-card reveal-on-scroll bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col opacity-80 cursor-not-allowed"
                                        data-price="{{ $accommodation->price }}">

                                        {{-- BAGIAN GAMBAR --}}
                                        <div class="relative overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                                            @if(!empty($accommodation->thumbnail))
                                                <img src="{{ asset($accommodation->thumbnail) }}" alt="{{ $accommodation->title }}"
                                                    class="w-full h-40 sm:h-44 object-cover grayscale">
                                            @else
                                                <img src="{{ asset('landingpage/kamar.jpg') }}" alt="{{ $accommodation->title }}"
                                                    class="w-full h-40 sm:h-44 object-cover grayscale">
                                            @endif

                                            <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>

                                            <div class="absolute top-2.5 left-2.5 inline-flex items-center gap-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md px-2 py-0.5 rounded-md text-[10px] font-bold text-gray-700 dark:text-gray-200 shadow-sm border border-white/50">
                                                <i data-lucide="eye" class="w-3 h-3 text-[#0194F3]"></i>
                                                <span>{{ $accommodation->views ?? (($accommodation->id % 4) + 1) }} KALI</span>
                                            </div>

                                            <span class="absolute top-2.5 right-2.5 {{ $status[0] }} text-white px-2 py-0.5 rounded-md text-[10px] font-bold uppercase shadow-sm tracking-wide">
                                                {{ $status[1] }}
                                            </span>

                                            <span class="absolute bottom-2.5 left-2.5 bg-[#0194F3] text-white text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-md shadow-sm">
                                                {{ $categoryName }}
                                            </span>
                                        </div>

                                        <div class="p-3.5 sm:p-4 flex flex-col flex-1">
                                            <h3 class="font-bold text-[#0F294D] dark:text-white text-sm sm:text-base mb-1 truncate" title="{{ $accommodation->title }}">
                                                {{ $accommodation->title }}
                                            </h3>

                                            <p class="text-gray-500 dark:text-gray-400 text-[11px] mb-2.5 flex items-center gap-1 truncate" title="{{ $accommodation->address }}">
                                                <i data-lucide="map-pin" class="w-3 h-3 text-gray-400 flex-shrink-0"></i>
                                                <span class="truncate">{{ Str::limit($accommodation->address, 30) }}</span>
                                            </p>

                                            <div class="flex items-center gap-1.5 mb-3 flex-wrap min-h-[26px]">
                                                @if($facilitiesData->count() > 0)
                                                    @foreach($facilitiesData->take(5) as $facility)
                                                        <div class="inline-flex items-center justify-center w-6 h-6 bg-gray-50 dark:bg-gray-700/60 rounded-md border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300" title="{{ $facility->name }}">
                                                            @php
                                                                $iconRaw = $facility->icon;
                                                                $iconLucide = $iconRaw && preg_match('/^[a-z0-9-]+$/i', $iconRaw);
                                                                $iconSvg = $iconRaw && (Str::startsWith($iconRaw, ['M', 'm']) || Str::startsWith($iconRaw, '<'));
                                                                $iconEmoji = $iconRaw && !$iconLucide && !$iconSvg;
                                                            @endphp

                                                            @if($iconLucide)
                                                                <i data-lucide="{{ $iconRaw }}" class="w-3 h-3"></i>
                                                            @elseif($iconSvg)
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconRaw }}" />
                                                                </svg>
                                                            @elseif($iconEmoji)
                                                                <span class="text-[10px] leading-none">{{ $iconRaw }}</span>
                                                            @else
                                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                            @endif
                                                        </div>
                                                    @endforeach

                                                    @if($facilitiesData->count() > 5)
                                                        <div class="inline-flex items-center justify-center px-1.5 h-6 bg-gray-100 dark:bg-gray-700 rounded-md text-[9px] font-bold text-gray-500 dark:text-gray-400" title="Dan fasilitas lainnya">
                                                            +{{ $facilitiesData->count() - 5 }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 italic">Fasilitas standar</span>
                                                @endif
                                            </div>

                                            <div class="mt-auto pt-2.5 border-t border-gray-100 dark:border-gray-700">
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-[#0194F3] font-extrabold text-base sm:text-lg">
                                                        Rp {{ number_format($accommodation->price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-gray-400 dark:text-gray-500 text-[11px]">/malam</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- ===================== PAGINATION ===================== --}}
                        @if($accommodations->hasPages())
                            <div class="reveal-on-scroll mt-8 sm:mt-10 flex justify-end">
                                {{ $accommodations->links() }}
                            </div>
                        @endif
                    @else
                        {{-- ===================== EMPTY STATE ===================== --}}
                        <div class="reveal-on-scroll text-center py-16 sm:py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-[#0194F3]">
                                <i data-lucide="search-x" class="w-8 h-8 sm:w-10 sm:h-10"></i>
                            </div>

                            @if(request()->hasAny(['keyword', 'category_id', 'category', 'facility_id', 'facility', 'max_price', 'min_price']))
                                <h3 class="text-lg font-bold text-[#0F294D] dark:text-white mb-2">Penginapan Tidak Ditemukan</h3>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6 px-4">
                                    Tidak ada penginapan yang cocok dengan kriteria filter pencarian Anda. Silakan ubah filter atau kata kunci.
                                </p>
                                <a href="{{ route('accommodationlanding.semua') }}"
                                    class="inline-flex items-center gap-2 bg-[#0194F3] text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wide hover:bg-[#0064D2] transition-all shadow-sm">
                                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                    <span>Reset Filter Pencarian</span>
                                </a>
                            @else
                                <h3 class="text-lg font-bold text-[#0F294D] dark:text-white mb-2">Belum Ada Penginapan</h3>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6 px-4">
                                    Saat ini belum ada data penginapan yang tersedia. Silakan cek kembali nanti.
                                </p>
                                <a href="{{ route('landingpage') }}"
                                    class="inline-flex items-center gap-2 bg-[#0194F3] text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wide hover:bg-[#0064D2] transition-all shadow-sm">
                                    <i data-lucide="home" class="w-4 h-4"></i>
                                    <span>Kembali ke Beranda</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </main>

            </div>

        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ==================== GLOBAL LOADER ====================
                const loader = document.getElementById('globalLoader');
                if (loader) {
                    setTimeout(() => {
                        loader.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => loader.remove(), 500);
                    }, 300);
                }

                // ==================== MOBILE FILTER TOGGLE ====================
                const mobileFilterToggle = document.getElementById('mobileFilterToggle');
                const sidebarWrap = document.getElementById('sidebarFilterWrap');
                const mobileFilterChevron = document.getElementById('mobileFilterChevron');

                if (mobileFilterToggle && sidebarWrap) {
                    mobileFilterToggle.addEventListener('click', function () {
                        const isHidden = sidebarWrap.classList.contains('hidden');
                        if (isHidden) {
                            sidebarWrap.classList.remove('hidden');
                            if (mobileFilterChevron) mobileFilterChevron.style.transform = 'rotate(180deg)';
                        } else {
                            sidebarWrap.classList.add('hidden');
                            if (mobileFilterChevron) mobileFilterChevron.style.transform = 'rotate(0deg)';
                        }
                    });
                }

                // ==================== SLIDER HARGA SIDEBAR ====================
                const sidebarSlider = document.getElementById('sidebarPriceSlider');
                const sidebarPreview = document.getElementById('sidebarPricePreview');

                function formatRupiah(number) {
                    return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                if (sidebarSlider && sidebarPreview) {
                    sidebarSlider.addEventListener('input', function() {
                        const val = parseInt(this.value, 10);
                        if (val >= 10000000) {
                            sidebarPreview.textContent = 'Rp 10.000.000';
                        } else {
                            sidebarPreview.textContent = formatRupiah(val);
                        }
                    });
                }

                // ==================== AUTO SUBMIT RADIO PADA SIDEBAR (OPSIONAL/INSTAN) ====================
                const sidebarForm = document.getElementById('sidebarFilterForm');
                if (sidebarForm) {
                    const radios = sidebarForm.querySelectorAll('input[type="radio"]');
                    radios.forEach(radio => {
                        radio.addEventListener('change', function () {
                            sidebarForm.submit();
                        });
                    });
                }

                // ==================== REVEAL ON SCROLL ====================
                const revealElements = document.querySelectorAll('.reveal-on-scroll');
                if ('IntersectionObserver' in window) {
                    const revealObserver = new IntersectionObserver((entries) => {
                        entries.forEach((entry, index) => {
                            if (entry.isIntersecting) {
                                setTimeout(() => {
                                    entry.target.classList.add('is-visible');
                                }, index * 40);
                                revealObserver.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
                    revealElements.forEach(el => revealObserver.observe(el));
                } else {
                    revealElements.forEach(el => el.classList.add('is-visible'));
                }

                // ==================== LUCIDE ICONS ====================
                if (window.renderLucide) {
                    window.renderLucide();
                } else if (window.lucide) {
                    lucide.createIcons();
                }
            });
        </script>
    @endpush
@endsection
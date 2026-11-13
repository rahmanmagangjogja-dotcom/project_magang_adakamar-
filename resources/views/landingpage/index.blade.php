@extends('landingpage.layouts.default.dashboard')

@section('content')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts for Script & Display accents --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        .font-script { font-family: 'Caveat', cursive; }
        .font-serif-display { font-family: 'Playfair Display', Georgia, serif; }
        .landing-v2 {
            --brand: #0194F3;
            --brand-deep: #0064D2;
            --ink: #0F294D;
            --mist: #E6F4FF;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .landing-v2 section:not(#beranda) { scroll-margin-top: 5.5rem; }
        
        .landing-v2 a, .landing-v2 button { transition-duration: .22s; }
        
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #E6F4FF; }
        ::-webkit-scrollbar-thumb { background: #0194F3; border-radius: 4px; }
        .dark ::-webkit-scrollbar-track { background: #1f2937; }
        .dark ::-webkit-scrollbar-thumb { background: #33ADFF; }
        
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-on-scroll.is-visible { opacity: 1; transform: translateY(0); }
        
        #globalLoader.loader-hidden { opacity: 0; pointer-events: none; }
        body.loading-active { overflow: hidden; }

        .polaroid-card {
            box-shadow: 0 16px 36px -10px rgba(15, 41, 77, 0.16);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .polaroid-card:hover {
            transform: translateY(-8px) scale(1.03) rotate(0deg) !important;
            box-shadow: 0 24px 44px -8px rgba(1, 148, 243, 0.22);
            z-index: 20;
        }

        .bg-travel-dots {
            background-image: radial-gradient(circle, rgba(1, 148, 243, 0.12) 1.5px, transparent 1.5px);
            background-size: 26px 26px;
        }
        .dark .bg-travel-dots {
            background-image: radial-gradient(circle, rgba(51, 173, 255, 0.08) 1.5px, transparent 1.5px);
        }

        /* ===== Gallery Slider ===== */
        .gallery-slider-viewport {
            overflow: hidden;
            border-radius: 1.5rem;
            width: 100%;
        }
        .gallery-slider-track {
            display: flex;
            gap: 0.75rem;
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform;
        }
        @media (min-width: 640px) {
            .gallery-slider-track { gap: 1rem; }
        }
        .gallery-slider-slide {
            flex: 0 0 100%;
            min-width: 0;
            max-width: 100%;
        }

        .gallery-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 30;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.92);
            color: #0F294D;
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 22px -6px rgba(15, 41, 77, 0.35);
            backdrop-filter: blur(6px);
            cursor: pointer;
            transition: all .22s ease;
        }
        @media (min-width: 640px) {
            .gallery-arrow { width: 2.5rem; height: 2.5rem; }
        }
        @media (min-width: 1024px) {
            .gallery-arrow { width: 2.75rem; height: 2.75rem; }
        }
        .gallery-arrow:hover:not(:disabled) {
            background: #0194F3;
            color: #fff;
            border-color: #0194F3;
            transform: translateY(-50%) scale(1.08);
        }
        .gallery-arrow:disabled {
            opacity: 0;
            pointer-events: none;
        }
        .gallery-arrow-left { left: 0.5rem; }
        .gallery-arrow-right { right: 0.5rem; }
        @media (min-width: 640px) {
            .gallery-arrow-left { left: 0.75rem; }
            .gallery-arrow-right { right: 0.75rem; }
        }
        @media (min-width: 1024px) {
            .gallery-arrow-left { left: 1rem; }
            .gallery-arrow-right { right: 1rem; }
        }
        .dark .gallery-arrow {
            background: rgba(31, 41, 55, 0.9);
            color: #e5e7eb;
            border-color: rgba(75, 85, 99, 0.6);
        }
        .dark .gallery-arrow:hover:not(:disabled) {
            background: #0194F3;
            color: #fff;
            border-color: #0194F3;
        }

        /* Mobile dropdown panel positioning */
        .hero-mobile-panel {
            position: absolute;
            left: 0;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            z-index: 100;
        }
    </style>

    <div class="landing-v2 bg-[#FAFBFD] dark:bg-gray-900 text-[#0F294D] dark:text-gray-100 relative overflow-hidden">

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
                    <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 dark:text-gray-400">Mohon tunggu sebentar...</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#0194F3] animate-bounce" style="animation-delay: 0s;"></span>
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#33ADFF] animate-bounce" style="animation-delay: 0.15s;"></span>
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#0064D2] animate-bounce" style="animation-delay: 0.3s;"></span>
                </div>
            </div>
        </div>

        <!-- ==================== HERO SECTION ==================== -->
        <section id="beranda"
            class="relative z-30 min-h-[620px] sm:min-h-[680px] lg:min-h-[760px] flex items-center justify-center pt-28 sm:pt-32 lg:pt-36 pb-20 sm:pb-24">

            <div class="absolute inset-0 rounded-b-[36px] sm:rounded-b-[48px] overflow-hidden pointer-events-none z-0">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                    style="background-image: url('{{ $banner && $banner->image ? asset($banner->image) : asset('landingpage/herosection.jpg') }}');"></div>
                <div class="absolute inset-0 bg-black/35"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#0F294D]/85 via-[#0064D2]/45 to-black/30 dark:from-[#0B1B33]/95 dark:via-[#0E2340]/80 dark:to-black/50"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0F294D]/90 via-transparent to-black/30"></div>
            </div>

            <div class="relative container mx-auto px-4 sm:px-6 z-20 w-full">
                <div class="max-w-4xl mx-auto text-center text-white">

                    {{-- Main Headline --}}
                    @if($banner && $banner->title)
                        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold md:font-extrabold leading-[1.2] mb-4 sm:mb-5 drop-shadow-xl tracking-normal">
                            {{ $banner->title }}
                        </h1>
                    @else
                        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold md:font-extrabold leading-[1.2] mb-4 sm:mb-5 drop-shadow-xl tracking-normal">
                            Temukan Penginapan <br class="hidden sm:inline">Terbaik di Seluruh Indonesia
                        </h1>
                    @endif

                    {{-- Sub-headline --}}
                    @if($banner && $banner->subtitle)
                        <p class="text-sm sm:text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-8 sm:mb-10 drop-shadow leading-relaxed font-normal">
                            {{ $banner->subtitle }}
                        </p>
                    @else
                        <p class="text-sm sm:text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-8 sm:mb-10 drop-shadow leading-relaxed font-normal">
                            Nikmati pengalaman menginap yang nyaman, aman, dan berkesan bersama {{ $setting->site_name ?? 'adakamar.id' }}.
                        </p>
                    @endif

                    {{-- ==================== SEARCH BAR DESKTOP (lg+) ==================== --}}
                    <div class="hidden lg:flex items-center gap-3">

                        {{-- Filter Lokasi --}}
                        <div class="flex-1 min-w-0 flex items-center gap-3 px-5 py-4
                            bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600
                            rounded-full hover:border-[#0194F3] transition-all">

                            <i data-lucide="search" class="w-5 h-5 text-[#0194F3] flex-shrink-0"></i>

                            <input type="text" id="heroSearchInputDesktop"
                                placeholder="Cari penginapan atau lokasi..."
                                autocomplete="off"
                                class="w-full min-w-0 p-0 border-0 bg-transparent text-sm
                                text-[#0F294D] dark:text-white placeholder-gray-400
                                focus:outline-none focus:ring-0 font-medium" />
                        </div>

                        {{-- Filter Tipe Penginapan --}}
                        <div class="w-56 relative flex-shrink-0" id="heroCategoryWrapDesktop">

                            <select id="heroCategoryDesktop" class="sr-only">
                                <option value="">Semua Kategori</option>
                                @if(isset($categories) && $categories->count())
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <button type="button" id="heroCategoryTriggerDesktop"
                                class="w-full flex items-center gap-2 px-4 py-4
                                bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600
                                rounded-full hover:border-[#0194F3] transition-all
                                text-sm font-semibold text-[#0F294D] dark:text-white">

                                <i data-lucide="bed-double" class="w-4 h-4 text-[#0194F3] flex-shrink-0"></i>

                                <span id="heroCategoryLabelDesktop"
                                    class="flex-1 text-left truncate whitespace-nowrap">
                                    Tipe Penginapan
                                </span>

                                <i data-lucide="chevron-down" id="heroCategoryChevronDesktop"
                                    class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"></i>
                            </button>

                            <div id="heroCategoryPanelDesktop"
                                class="hidden absolute left-0 top-full mt-3 z-[100] w-64
                                bg-white dark:bg-gray-800 rounded-2xl shadow-2xl
                                border border-gray-200 dark:border-gray-700 overflow-hidden ring-1 ring-black/5">

                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50
                                    border-b border-gray-100 dark:border-gray-700
                                    text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    Pilih Tipe Penginapan
                                </div>

                                <ul class="py-1.5 max-h-60 overflow-y-auto text-sm
                                    divide-y divide-gray-50 dark:divide-gray-700/40">

                                    <li>
                                        <button type="button" data-value="" data-label="Tipe Penginapan"
                                            class="hero-cat-opt-desktop w-full text-left px-4 py-3
                                            font-semibold text-[#0F294D] dark:text-white
                                            hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                            transition-colors flex items-center gap-2 is-selected">
                                            <i data-lucide="check" class="w-3.5 h-3.5 text-[#0194F3] check-icon"></i>
                                            Semua Tipe
                                        </button>
                                    </li>

                                    @if(isset($categories) && $categories->count())
                                        @foreach($categories as $category)
                                            <li>
                                                <button type="button"
                                                    data-value="{{ $category->id }}"
                                                    data-label="{{ $category->name }}"
                                                    class="hero-cat-opt-desktop w-full text-left px-4 py-3
                                                    font-semibold text-[#0F294D] dark:text-white
                                                    hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                                    transition-colors flex items-center gap-2">
                                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#0194F3] check-icon opacity-0"></i>
                                                    {{ $category->name }}
                                                </button>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        {{-- Filter Fasilitas --}}
                        <div class="w-56 relative flex-shrink-0" id="heroFacilityWrapDesktop">

                            <select id="heroFacilityDesktop" class="sr-only">
                                <option value="">Semua Fasilitas</option>
                                @if(isset($facilities) && $facilities->count())
                                    @foreach($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <button type="button" id="heroFacilityTriggerDesktop"
                                class="w-full flex items-center gap-2 px-4 py-4
                                bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600
                                rounded-full hover:border-[#0194F3] transition-all
                                text-sm font-semibold text-[#0F294D] dark:text-white">

                                <i data-lucide="sliders-horizontal" class="w-4 h-4 text-[#0194F3] flex-shrink-0"></i>

                                <span id="heroFacilityLabelDesktop"
                                    class="flex-1 text-left truncate whitespace-nowrap">
                                    Fasilitas
                                </span>

                                <i data-lucide="chevron-down" id="heroFacilityChevronDesktop"
                                    class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"></i>
                            </button>

                            <div id="heroFacilityPanelDesktop"
                                class="hidden absolute left-0 top-full mt-3 z-[100] w-64
                                bg-white dark:bg-gray-800 rounded-2xl shadow-2xl
                                border border-gray-200 dark:border-gray-700 overflow-hidden ring-1 ring-black/5">

                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50
                                    border-b border-gray-100 dark:border-gray-700
                                    text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    Pilih Fasilitas
                                </div>

                                <ul class="py-1.5 max-h-60 overflow-y-auto text-sm
                                    divide-y divide-gray-50 dark:divide-gray-700/40">

                                    <li>
                                        <button type="button" data-value="" data-label="Fasilitas"
                                            class="hero-fac-opt-desktop w-full text-left px-4 py-3
                                            font-semibold text-[#0F294D] dark:text-white
                                            hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                            transition-colors flex items-center gap-2 is-selected">
                                            <i data-lucide="check" class="w-3.5 h-3.5 text-[#0194F3] check-icon"></i>
                                            Semua Fasilitas
                                        </button>
                                    </li>

                                    @if(isset($facilities) && $facilities->count())
                                        @foreach($facilities as $facility)
                                            <li>
                                                <button type="button"
                                                    data-value="{{ $facility->id }}"
                                                    data-label="{{ $facility->name }}"
                                                    class="hero-fac-opt-desktop w-full text-left px-4 py-3
                                                    font-semibold text-[#0F294D] dark:text-white
                                                    hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                                    transition-colors flex items-center gap-2">
                                                    <i data-lucide="check" class="w-3.5 h-3.5 text-[#0194F3] check-icon opacity-0"></i>
                                                    {{ $facility->name }}
                                                </button>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        {{-- Hidden Price --}}
                        <input type="range" id="heroPriceSliderDesktop"
                            min="50000" max="10000000" step="50000"
                            value="50000" class="sr-only" />

                        {{-- Tombol Cari --}}
                        <button type="button" id="heroSearchBtnDesktop"
                            class="inline-flex items-center justify-center gap-2
                            bg-[#0194F3] hover:bg-[#0064D2] text-white
                            px-7 py-4 rounded-full font-bold text-sm
                            shadow-md hover:shadow-lg hover:scale-[1.02]
                            transition-all whitespace-nowrap flex-shrink-0 cursor-pointer">

                            <span>Cari Sekarang</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>

                    </div>

                    {{-- ==================== SEARCH BAR MOBILE/TABLET (< lg) ==================== --}}
                    <div class="lg:hidden mt-2">
                        <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-2xl sm:rounded-3xl shadow-2xl p-3 sm:p-4 border border-white/40 dark:border-gray-700">

                            {{-- Input pencarian --}}
                            <div class="flex items-center gap-2.5 px-4 py-3
                                bg-gray-50 dark:bg-gray-700/60 border border-gray-200 dark:border-gray-600
                                rounded-xl sm:rounded-2xl focus-within:border-[#0194F3] transition-all">
                                <i data-lucide="search" class="w-4 h-4 sm:w-5 sm:h-5 text-[#0194F3] flex-shrink-0"></i>
                                <input type="text" id="heroSearchInputMobile"
                                    placeholder="Cari penginapan atau lokasi..."
                                    autocomplete="off"
                                    class="w-full min-w-0 p-0 border-0 bg-transparent text-sm
                                    text-[#0F294D] dark:text-white placeholder-gray-400
                                    focus:outline-none focus:ring-0 font-medium" />
                            </div>

                            {{-- Dropdown Tipe & Fasilitas --}}
                            <div class="grid grid-cols-2 gap-2 sm:gap-3 mt-2 sm:mt-3">

                                {{-- Tipe Penginapan --}}
                                <div class="relative" id="heroCategoryWrapMobile">
                                    <select id="heroCategoryMobile" class="sr-only">
                                        <option value="">Semua Kategori</option>
                                        @if(isset($categories) && $categories->count())
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <button type="button" id="heroCategoryTriggerMobile"
                                        class="w-full flex items-center gap-1.5 px-3 py-2.5
                                        bg-gray-50 dark:bg-gray-700/60 border border-gray-200 dark:border-gray-600
                                        rounded-xl hover:border-[#0194F3] transition-all
                                        text-xs sm:text-sm font-semibold text-[#0F294D] dark:text-white">
                                        <i data-lucide="bed-double" class="w-3.5 h-3.5 text-[#0194F3] flex-shrink-0"></i>
                                        <span id="heroCategoryLabelMobile" class="flex-1 text-left truncate whitespace-nowrap">Tipe</span>
                                        <i data-lucide="chevron-down" id="heroCategoryChevronMobile"
                                            class="w-3.5 h-3.5 text-gray-400 flex-shrink-0 transition-transform duration-200"></i>
                                    </button>

                                    <div id="heroCategoryPanelMobile"
                                        class="hero-mobile-panel hidden bg-white dark:bg-gray-800 rounded-xl shadow-2xl
                                        border border-gray-200 dark:border-gray-700 overflow-hidden ring-1 ring-black/5">
                                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700/50
                                            border-b border-gray-100 dark:border-gray-700
                                            text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                            Tipe Penginapan
                                        </div>
                                        <ul class="py-1 max-h-52 overflow-y-auto text-xs sm:text-sm
                                            divide-y divide-gray-50 dark:divide-gray-700/40">
                                            <li>
                                                <button type="button" data-value="" data-label="Tipe"
                                                    class="hero-cat-opt-mobile w-full text-left px-3 py-2.5
                                                    font-semibold text-[#0F294D] dark:text-white
                                                    hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                                    transition-colors flex items-center gap-2 is-selected">
                                                    <i data-lucide="check" class="w-3 h-3 text-[#0194F3] check-icon"></i>
                                                    Semua Tipe
                                                </button>
                                            </li>
                                            @if(isset($categories) && $categories->count())
                                                @foreach($categories as $category)
                                                    <li>
                                                        <button type="button"
                                                            data-value="{{ $category->id }}"
                                                            data-label="{{ $category->name }}"
                                                            class="hero-cat-opt-mobile w-full text-left px-3 py-2.5
                                                            font-semibold text-[#0F294D] dark:text-white
                                                            hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                                            transition-colors flex items-center gap-2">
                                                            <i data-lucide="check" class="w-3 h-3 text-[#0194F3] check-icon opacity-0"></i>
                                                            {{ $category->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                {{-- Fasilitas --}}
                                <div class="relative" id="heroFacilityWrapMobile">
                                    <select id="heroFacilityMobile" class="sr-only">
                                        <option value="">Semua Fasilitas</option>
                                        @if(isset($facilities) && $facilities->count())
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <button type="button" id="heroFacilityTriggerMobile"
                                        class="w-full flex items-center gap-1.5 px-3 py-2.5
                                        bg-gray-50 dark:bg-gray-700/60 border border-gray-200 dark:border-gray-600
                                        rounded-xl hover:border-[#0194F3] transition-all
                                        text-xs sm:text-sm font-semibold text-[#0F294D] dark:text-white">
                                        <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-[#0194F3] flex-shrink-0"></i>
                                        <span id="heroFacilityLabelMobile" class="flex-1 text-left truncate whitespace-nowrap">Fasilitas</span>
                                        <i data-lucide="chevron-down" id="heroFacilityChevronMobile"
                                            class="w-3.5 h-3.5 text-gray-400 flex-shrink-0 transition-transform duration-200"></i>
                                    </button>

                                    <div id="heroFacilityPanelMobile"
                                        class="hero-mobile-panel hidden bg-white dark:bg-gray-800 rounded-xl shadow-2xl
                                        border border-gray-200 dark:border-gray-700 overflow-hidden ring-1 ring-black/5">
                                        <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700/50
                                            border-b border-gray-100 dark:border-gray-700
                                            text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                            Fasilitas
                                        </div>
                                        <ul class="py-1 max-h-52 overflow-y-auto text-xs sm:text-sm
                                            divide-y divide-gray-50 dark:divide-gray-700/40">
                                            <li>
                                                <button type="button" data-value="" data-label="Fasilitas"
                                                    class="hero-fac-opt-mobile w-full text-left px-3 py-2.5
                                                    font-semibold text-[#0F294D] dark:text-white
                                                    hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                                    transition-colors flex items-center gap-2 is-selected">
                                                    <i data-lucide="check" class="w-3 h-3 text-[#0194F3] check-icon"></i>
                                                    Semua Fasilitas
                                                </button>
                                            </li>
                                            @if(isset($facilities) && $facilities->count())
                                                @foreach($facilities as $facility)
                                                    <li>
                                                        <button type="button"
                                                            data-value="{{ $facility->id }}"
                                                            data-label="{{ $facility->name }}"
                                                            class="hero-fac-opt-mobile w-full text-left px-3 py-2.5
                                                            font-semibold text-[#0F294D] dark:text-white
                                                            hover:bg-[#E6F4FF] dark:hover:bg-[#0194F3]/20
                                                            transition-colors flex items-center gap-2">
                                                            <i data-lucide="check" class="w-3 h-3 text-[#0194F3] check-icon opacity-0"></i>
                                                            {{ $facility->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden price --}}
                            <input type="range" id="heroPriceSliderMobile"
                                min="50000" max="10000000" step="50000"
                                value="50000" class="sr-only" />

                            {{-- Tombol Cari Mobile --}}
                            <button type="button" id="heroSearchBtnMobile"
                                class="mt-2 sm:mt-3 w-full inline-flex items-center justify-center gap-2
                                bg-[#0194F3] hover:bg-[#0064D2] text-white
                                px-5 py-3 rounded-xl sm:rounded-2xl font-bold text-sm
                                shadow-md active:scale-[0.98]
                                transition-all cursor-pointer">
                                <span>Cari Sekarang</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ==================== WRAPPER BERGAYA ELEGAN & ARTISTIK ==================== -->
        <div class="relative">
            
            <div class="absolute inset-0 bg-travel-dots opacity-40 pointer-events-none"></div>

            <div class="absolute top-16 -left-20 w-96 h-96 bg-[#0194F3]/10 dark:bg-[#0194F3]/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/3 -right-20 w-[450px] h-[450px] bg-[#33ADFF]/10 dark:bg-[#33ADFF]/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-2/3 -left-24 w-[400px] h-[400px] bg-[#0064D2]/8 dark:bg-[#0064D2]/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-32 right-0 w-[480px] h-[480px] bg-[#0194F3]/10 dark:bg-[#0194F3]/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="hidden xl:block absolute -left-8 top-16 w-36 h-64 pointer-events-none opacity-45 dark:opacity-20 select-none">
                <svg viewBox="0 0 140 240" fill="none" class="w-full h-full text-[#0194F3]/60 dark:text-[#33ADFF]/40">
                    <path d="M-20,30 Q40,40 85,95 Q115,140 100,210 Q60,170 30,135 Q0,100 -20,30 Z" fill="currentColor"/>
                    <path d="M0,60 Q65,85 90,135 Q110,185 80,240 Q40,195 20,150 Q-10,105 0,60 Z" fill="currentColor" opacity="0.75"/>
                    <path d="M-10,110 Q50,130 75,175 Q90,210 65,260 Q30,220 10,180 Q-15,145 -10,110 Z" fill="currentColor" opacity="0.5"/>
                </svg>
            </div>

            <div class="hidden xl:block absolute -right-8 top-28 w-36 h-64 pointer-events-none opacity-45 dark:opacity-20 select-none">
                <svg viewBox="0 0 140 240" fill="none" class="w-full h-full text-[#0194F3]/60 dark:text-[#33ADFF]/40">
                    <path d="M160,30 Q100,40 55,95 Q25,140 40,210 Q80,170 110,135 Q140,100 160,30 Z" fill="currentColor"/>
                    <path d="M140,60 Q75,85 50,135 Q30,185 60,240 Q100,195 120,150 Q150,105 140,60 Z" fill="currentColor" opacity="0.75"/>
                    <path d="M150,110 Q90,130 65,175 Q50,210 75,260 Q110,220 130,180 Q155,145 150,110 Z" fill="currentColor" opacity="0.5"/>
                </svg>
            </div>

            <div class="hidden xl:block absolute -left-10 top-[45%] w-40 h-72 pointer-events-none opacity-45 dark:opacity-20 select-none">
                <svg viewBox="0 0 160 260" fill="none" class="w-full h-full text-[#0194F3]/60 dark:text-[#33ADFF]/40">
                    <path d="M-30,40 C30,50 80,100 110,160 C130,205 110,250 85,270 C60,230 40,185 0,140 C-30,95 -30,40 -30,40 Z" fill="currentColor"/>
                    <path d="M-10,80 C40,100 80,140 95,190 C105,230 85,265 65,280 C45,245 25,205 5,170 C-15,130 -10,80 -10,80 Z" fill="currentColor" opacity="0.6"/>
                </svg>
            </div>

            <div class="hidden xl:block absolute -right-10 top-[72%] w-40 h-72 pointer-events-none opacity-45 dark:opacity-20 select-none">
                <svg viewBox="0 0 160 260" fill="none" class="w-full h-full text-[#0194F3]/60 dark:text-[#33ADFF]/40">
                    <path d="M190,40 C130,50 80,100 50,160 C30,205 50,250 75,270 C100,230 120,185 160,140 C190,95 190,40 190,40 Z" fill="currentColor"/>
                    <path d="M170,80 C120,100 80,140 65,190 C55,230 75,265 95,280 C115,245 135,205 155,170 C175,130 170,80 170,80 Z" fill="currentColor" opacity="0.6"/>
                </svg>
            </div>

            <!-- ==================== SECTION 1: PENGINAPAN POPULER (TANPA HEADER, 4 CARD, CARD KLIKABLE) ==================== -->
            <section id="penginapan" class="reveal-on-scroll py-8 sm:py-12 lg:py-16 relative z-10">
                <div class="container mx-auto px-4 sm:px-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6" id="accommodationGrid">
                        @forelse($accommodations->take(4) as $accommodation)
                            @php
                                $isAvailable = $accommodation->status === 'Available';
                                $avgRating = $accommodation->average_rating
                                    ?? ($accommodation->relationLoaded('ratings')
                                        ? ($accommodation->ratings->count() ? round((float) $accommodation->ratings->avg('bintang'), 1) : 0)
                                        : ($accommodation->ratings()->count() ? round((float) $accommodation->ratings()->avg('bintang'), 1) : 0));
                                $reviewCount = $accommodation->total_reviews
                                    ?? ($accommodation->relationLoaded('ratings')
                                        ? $accommodation->ratings->count()
                                        : $accommodation->ratings()->count());
                            @endphp

                            @if($isAvailable)
                                {{-- Card klikable menuju detail --}}
                                <a href="{{ route('accommodationlanding.detail', $accommodation->slug) }}"
                                    class="group block bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-3xl p-3 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-700/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0194F3]">
                                    
                                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3] w-full mb-3 flex-shrink-0">
                                        @if($accommodation->thumbnail)
                                            <img src="{{ asset($accommodation->thumbnail) }}" alt="{{ $accommodation->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <img src="{{ asset('landingpage/kamar.jpg') }}" alt="{{ $accommodation->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @endif

                                        <div class="absolute top-3 left-3">
                                            <span class="bg-[#0194F3] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm uppercase tracking-wide">
                                                {{ $accommodation->category->name ?? 'Penginapan' }}
                                            </span>
                                        </div>

                                        <div class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm border border-gray-200/60 dark:border-gray-700">
                                            <i data-lucide="star" class="w-3.5 h-3.5 {{ $reviewCount > 0 ? 'text-[#FFB800] fill-[#FFB800]' : 'text-gray-400' }}"></i>
                                            @if($reviewCount > 0)
                                                <span class="text-xs font-extrabold text-[#0F294D] dark:text-white">{{ number_format($avgRating, 1) }}</span>
                                                <span class="text-[10px] text-gray-500 dark:text-gray-400">({{ $reviewCount }} ulasan)</span>
                                            @else
                                                <span class="text-xs font-extrabold text-gray-400 dark:text-gray-500">0.0</span>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500">(0 ulasan)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="px-1 flex flex-col flex-1">
                                        <h3 class="font-bold text-[#0F294D] dark:text-white text-base mb-1 line-clamp-1 group-hover:text-[#0194F3] transition-colors" title="{{ $accommodation->title }}">
                                            {{ $accommodation->title }}
                                        </h3>

                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mb-4 line-clamp-1">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400 flex-shrink-0"></i>
                                            <span>{{ $accommodation->address ?? 'Indonesia' }}</span>
                                        </p>

                                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60 mt-auto">
                                            <span class="text-sm sm:text-base font-extrabold text-[#0F294D] dark:text-white">
                                                Rp {{ number_format($accommodation->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] text-gray-400 font-normal"> / malam</span>
                                        </div>
                                    </div>
                                </a>
                            @else
                                {{-- Card tidak tersedia (tidak klikable) --}}
                                <div class="group block bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-3xl p-3 shadow-md border border-gray-100 dark:border-gray-700/80 opacity-75 cursor-not-allowed">
                                    
                                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3] w-full mb-3 flex-shrink-0">
                                        @if($accommodation->thumbnail)
                                            <img src="{{ asset($accommodation->thumbnail) }}" alt="{{ $accommodation->title }}" class="w-full h-full object-cover grayscale">
                                        @else
                                            <img src="{{ asset('landingpage/kamar.jpg') }}" alt="{{ $accommodation->title }}" class="w-full h-full object-cover grayscale">
                                        @endif

                                        <div class="absolute top-3 left-3">
                                            <span class="bg-[#0194F3] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm uppercase tracking-wide">
                                                {{ $accommodation->category->name ?? 'Penginapan' }}
                                            </span>
                                        </div>

                                        <div class="absolute top-3 right-3">
                                            <span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm uppercase tracking-wide">
                                                Penuh
                                            </span>
                                        </div>

                                        <div class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm border border-gray-200/60 dark:border-gray-700">
                                            <i data-lucide="star" class="w-3.5 h-3.5 {{ $reviewCount > 0 ? 'text-[#FFB800] fill-[#FFB800]' : 'text-gray-400' }}"></i>
                                            @if($reviewCount > 0)
                                                <span class="text-xs font-extrabold text-[#0F294D] dark:text-white">{{ number_format($avgRating, 1) }}</span>
                                                <span class="text-[10px] text-gray-500 dark:text-gray-400">({{ $reviewCount }} ulasan)</span>
                                            @else
                                                <span class="text-xs font-extrabold text-gray-400 dark:text-gray-500">0.0</span>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500">(0 ulasan)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="px-1 flex flex-col flex-1">
                                        <h3 class="font-bold text-[#0F294D] dark:text-white text-base mb-1 line-clamp-1" title="{{ $accommodation->title }}">
                                            {{ $accommodation->title }}
                                        </h3>

                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1 mb-4 line-clamp-1">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400 flex-shrink-0"></i>
                                            <span>{{ $accommodation->address ?? 'Indonesia' }}</span>
                                        </p>

                                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60 mt-auto">
                                            <span class="text-sm sm:text-base font-extrabold text-[#0F294D] dark:text-white">
                                                Rp {{ number_format($accommodation->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] text-gray-400 font-normal"> / malam</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700">
                                <p class="text-gray-500">Belum ada penginapan tersedia.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol Lihat Semua Penginapan (di bawah grid) --}}
                    @if($accommodations->count() > 0)
                        <div class="flex justify-center mt-8 sm:mt-10">
                            <a href="{{ route('accommodationlanding.semua') }}"
                                class="inline-flex items-center justify-between gap-3 bg-[#0F294D] dark:bg-[#0194F3] text-white hover:bg-[#0194F3] dark:hover:bg-[#0064D2] px-6 sm:px-7 py-3 rounded-full font-bold text-sm shadow-md hover:shadow-lg transition-all group">
                                <span>Lihat Semua Penginapan</span>
                                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center group-hover:translate-x-1 transition-transform">
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </div>
                            </a>
                        </div>
                    @endif

                </div>
            </section>

            <!-- ==================== SECTION 2: GALERI ==================== -->
            <section id="galeri" class="reveal-on-scroll py-10 sm:py-14 lg:py-16 relative z-10 my-4">
                
                <div class="absolute inset-0 bg-gradient-to-r from-[#E6F4FF]/50 via-[#F0F8FF]/70 to-[#E6F4FF]/50 dark:from-gray-800/40 dark:via-gray-800/70 dark:to-gray-800/40 rounded-3xl sm:rounded-[40px] mx-2 sm:mx-6 border border-[#B3E0FF]/30 dark:border-gray-700/50 pointer-events-none"></div>

                <div class="container mx-auto px-4 sm:px-6 relative z-10">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start lg:items-center">
                        
                        <div class="lg:col-span-3 lg:pr-2 flex flex-col justify-center">
                            <div class="inline-flex items-center gap-2 mb-3">
                                <span class="bg-[#0194F3] text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                    Galeri
                                </span>
                                <div class="w-8 h-0.5 bg-[#0194F3]/40 rounded-full"></div>
                            </div>

                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0F294D] dark:text-white leading-tight mb-3">
                                Jelajahi Keindahan <br class="hidden sm:inline">Penginapan Kami
                            </h2>

                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                Lihat lebih dekat suasana dan fasilitas penginapan melalui galeri foto kami. Geser panah untuk melihat koleksi lengkapnya.
                            </p>
                        </div>

                        <div class="lg:col-span-9 relative w-full min-w-0">
                            
                            <div class="hidden sm:block absolute -top-8 right-20 text-[#0194F3]/40 dark:text-[#33ADFF]/40 pointer-events-none select-none z-10">
                                <svg class="w-12 h-8" viewBox="0 0 48 32" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 24 C6 24 2 20 2 15 C2 10 7 7 12 7 C14 3 20 1 25 3 C30 1 37 3 39 8 C44 9 46 14 45 19 C45 24 41 24 36 24 Z" stroke-dasharray="3 2" />
                                </svg>
                            </div>

                            @if($galleries->count())
                                <div class="relative">
                                    <button type="button" id="galleryPrevBtn" class="gallery-arrow gallery-arrow-left" aria-label="Foto Sebelumnya">
                                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                                    </button>

                                    <button type="button" id="galleryNextBtn" class="gallery-arrow gallery-arrow-right" aria-label="Foto Berikutnya">
                                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                    </button>

                                    <div class="gallery-slider-viewport" id="gallerySliderViewport">
                                        <div class="gallery-slider-track" id="gallerySliderTrack">
                                            @foreach($galleries as $gallery)
                                                <div class="gallery-slider-slide">
                                                    <div class="polaroid-card p-2.5 pb-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 cursor-pointer transition-all duration-300 h-full">
                                                        <button type="button"
                                                            class="gallery-open-btn w-full text-left relative overflow-hidden rounded-xl aspect-[4/3] group block"
                                                            data-image="{{ asset($gallery->image) }}" data-title="{{ $gallery->title ?? 'Foto Galeri' }}">
                                                            <img src="{{ asset($gallery->image) }}" alt="{{ $gallery->title ?? 'Foto Galeri' }}"
                                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                                            
                                                            <div class="absolute top-2 right-2 w-7 h-7 bg-black/40 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <i data-lucide="zoom-in" class="w-3.5 h-3.5 text-white"></i>
                                                            </div>
                                                        </button>
                                                        <p class="text-[11px] font-semibold text-[#0F294D] dark:text-gray-300 mt-2 truncate text-center">
                                                            {{ $gallery->title ?? 'Foto Penginapan' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700">
                                    <p class="text-gray-500">Belum ada galeri foto.</p>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </section>

            <!-- ==================== SECTION 3: ARTIKEL ==================== -->
            <section id="artikel" class="reveal-on-scroll py-8 sm:py-12 lg:py-16 relative z-10">
                <div class="container mx-auto px-4 sm:px-6">

                    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-10 gap-4">
                        <div class="max-w-xl">
                            <div class="inline-flex items-center gap-2 mb-2">
                                <span class="bg-[#E6F4FF] dark:bg-[#0194F3]/20 text-[#0194F3] dark:text-[#33ADFF] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                    Artikel Terbaru
                                </span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0F294D] dark:text-white leading-tight mb-2">
                                Tips & Informasi Seputar Perjalanan
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Dapatkan tips, rekomendasi, dan panduan lengkap untuk liburan yang lebih menyenangkan.
                            </p>
                        </div>

                        @if($articles->count() > 0)
                            <a href="{{ route('article.semua') }}"
                                class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-[#0F294D] dark:text-white hover:bg-[#0194F3] hover:text-white hover:border-[#0194F3] px-6 py-2.5 rounded-full font-bold text-xs sm:text-sm shadow-sm transition-all flex-shrink-0 self-start sm:self-auto group">
                                <span>Baca Artikel Lainnya</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @endif
                    </div>

                    @if($articles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($articles->take(3) as $article)
                                <article class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-3xl p-3 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700/80 flex flex-col group">
                                    <a href="{{ route('article.detail', $article->slug) }}" class="block relative rounded-2xl overflow-hidden aspect-[16/10] w-full mb-3.5 flex-shrink-0">
                                        @if($article->thumbnail)
                                            <img src="{{ asset($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                        @else
                                            <img src="{{ asset('landingpage/kamar.jpg') }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                        @endif

                                        @if($article->category)
                                            <span class="absolute top-3 left-3 bg-[#0194F3] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm uppercase tracking-wide">
                                                {{ $article->category->name }}
                                            </span>
                                        @endif
                                    </a>

                                    <div class="px-1 flex flex-col flex-1">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-2 font-medium">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#0194F3]"></i>
                                            <span>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                                        </div>

                                        <h3 class="text-base font-bold text-[#0F294D] dark:text-white group-hover:text-[#0194F3] transition-colors line-clamp-2 leading-snug mb-2">
                                            <a href="{{ route('article.detail', $article->slug) }}">
                                                {{ $article->title }}
                                            </a>
                                        </h3>

                                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 leading-relaxed">
                                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content ?? ''), 90) }}
                                        </p>

                                        <div class="mt-auto pt-2 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                                            <span class="text-[11px] text-gray-400 font-medium">
                                                By {{ $article->author->name ?? $setting->site_name ?? 'Admin' }}
                                            </span>
                                            <a href="{{ route('article.detail', $article->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#0194F3] hover:text-[#0064D2] uppercase tracking-wide">
                                                <span>Baca</span>
                                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700">
                            <p class="text-gray-500">Belum ada artikel yang dipublikasikan.</p>
                        </div>
                    @endif

                </div>
            </section>

        </div>

        <!-- ==================== SECTION 4: CTA ==================== -->
        <section class="reveal-on-scroll py-8 sm:py-12 relative z-10">
            <div class="container mx-auto px-4 sm:px-6">
                
                <div class="relative rounded-[32px] sm:rounded-[40px] overflow-hidden p-8 sm:p-12 lg:p-14 shadow-2xl bg-gradient-to-r from-[#0F294D] via-[#0A3E76] to-[#0194F3] text-white">
                    
                    <div class="absolute -right-16 -top-16 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-[#0194F3]/30 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="absolute left-6 bottom-4 opacity-15 pointer-events-none select-none">
                        <i data-lucide="compass" class="w-24 h-24 text-white"></i>
                    </div>

                    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8">
                        
                        <div class="text-center lg:text-left">
                            <span class="font-script text-3xl sm:text-4xl text-[#E6F4FF] font-bold block mb-1">
                                Siap untuk perjalanan selanjutnya?
                            </span>
                            <p class="text-xs sm:text-sm md:text-base text-white/90 max-w-xl leading-relaxed">
                                Pesan penginapan sekarang dan nikmati pengalaman liburan terbaik bersama {{ $setting->site_name ?? 'adakamar.id' }}.
                            </p>
                        </div>

                        <div class="flex-shrink-0 flex items-center gap-3">
                            <a href="{{ route('accommodationlanding.semua') }}"
                                class="inline-flex items-center gap-2 bg-white text-[#0F294D] hover:bg-[#E6F4FF] hover:text-[#0194F3] px-7 sm:px-8 py-3.5 sm:py-4 rounded-full font-extrabold text-sm shadow-xl transition-all hover:scale-105 cursor-pointer">
                                <span>Pesan Sekarang</span>
                                <i data-lucide="send" class="w-4 h-4 text-[#0194F3]"></i>
                            </a>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        <!-- ==================== SECTION 5: LOKASI & KONTAK ==================== -->
        <section id="kontak" class="reveal-on-scroll py-8 sm:py-12 lg:py-16 relative z-10">
            <div class="container mx-auto px-4 sm:px-6">

                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 sm:mb-10 gap-4">
                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 mb-2">
                            <span class="bg-[#E6F4FF] dark:bg-[#0194F3]/20 text-[#0194F3] dark:text-[#33ADFF] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                Kontak
                            </span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0F294D] dark:text-white leading-tight mb-2">
                            Lokasi & Kontak Kami
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Kunjungi kantor kami atau hubungi kami untuk informasi dan reservasi penginapan.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-stretch">
                    
                    <div class="lg:col-span-5 space-y-3.5 flex flex-col justify-center">
                        @if(!empty($setting->address))
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex items-start gap-4 shadow-sm">
                                <div class="w-10 h-10 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center flex-shrink-0 text-[#0194F3]">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alamat</p>
                                    <p class="font-bold text-[#0F294D] dark:text-white text-sm mt-0.5">{{ $setting->address }}</p>
                                </div>
                            </div>
                        @endif

                        @if(!empty($setting->email))
                            <a href="mailto:{{ $setting->email }}" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex items-start gap-4 shadow-sm hover:border-[#0194F3] transition-colors block">
                                <div class="w-10 h-10 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center flex-shrink-0 text-[#0194F3]">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</p>
                                    <p class="font-bold text-[#0F294D] dark:text-white text-sm mt-0.5 break-all">{{ $setting->email }}</p>
                                </div>
                            </a>
                        @endif

                        @if(!empty($setting->phone))
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $setting->phone) }}" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex items-start gap-4 shadow-sm hover:border-[#0194F3] transition-colors block">
                                <div class="w-10 h-10 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center flex-shrink-0 text-[#0194F3]">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Telepon</p>
                                    <p class="font-bold text-[#0F294D] dark:text-white text-sm mt-0.5">{{ $setting->phone }}</p>
                                </div>
                            </a>
                        @endif

                        @if(!empty($setting->whatsapp))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 sm:p-5 flex items-start gap-4 shadow-sm hover:border-green-500 transition-colors block">
                                <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center flex-shrink-0 text-green-500">
                                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-green-600 uppercase tracking-wider">WhatsApp</p>
                                    <p class="font-bold text-[#0F294D] dark:text-white text-sm mt-0.5">{{ $setting->whatsapp }}</p>
                                </div>
                            </a>
                        @endif
                    </div>

                    <div class="lg:col-span-7">
                        <div class="relative rounded-3xl shadow-sm overflow-hidden h-full min-h-[300px] lg:min-h-[400px] border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                            @if(!empty($setting->maps_embed))
                                @php
                                    $embed = trim($setting->maps_embed);
                                    if (stripos($embed, '<iframe') === 0) {
                                        $embed = preg_replace('/\s*width="[^"]*"/i', '', $embed);
                                        $embed = preg_replace('/\s*height="[^"]*"/i', '', $embed);
                                        $embed = preg_replace('/\s*style="[^"]*"/i', '', $embed);
                                        $embed = preg_replace('/<iframe/i', '<iframe class="absolute inset-0 w-full h-full border-0"', $embed);
                                    } else {
                                        $embed = '<iframe src="' . e($embed) . '" class="absolute inset-0 w-full h-full border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi ' . e($setting->site_name ?? '') . '"></iframe>';
                                    }
                                @endphp
                                {!! $embed !!}
                            @else
                                <div class="absolute inset-0 bg-gray-50 dark:bg-gray-800 flex flex-col items-center justify-center text-center p-6">
                                    <div class="w-16 h-16 bg-[#E6F4FF] dark:bg-[#0194F3]/20 rounded-full flex items-center justify-center mb-3 text-[#0194F3]">
                                        <i data-lucide="map" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="text-base font-bold text-[#0F294D] dark:text-white mb-1">Lokasi Belum Diatur</h4>
                                    <p class="text-xs text-gray-400 max-w-xs">Peta lokasi akan tampil di sini setelah pengaturan diisi.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </section>

        {{-- ===================== LIGHTBOX MODAL GALERI ===================== --}}
        <div id="galleryPopup" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/85 backdrop-blur-sm p-3 sm:p-5 opacity-0 transition-opacity duration-300">
            <div id="galleryPopupContent" class="relative w-full max-w-4xl max-h-[92vh] flex flex-col bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 border border-gray-700">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/80 flex-shrink-0">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 bg-[#E6F4FF] dark:bg-[#0194F3]/20 rounded-full flex items-center justify-center text-[#0194F3]">
                            <i data-lucide="image" class="w-4 h-4"></i>
                        </div>
                        <h3 id="galleryPopupTitle" class="text-sm font-bold text-[#0F294D] dark:text-white truncate">Foto Galeri</h3>
                    </div>
                    <button type="button" id="closeGalleryPopup" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 transition-colors" title="Tutup (Esc)">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="relative flex-1 bg-black flex items-center justify-center overflow-hidden min-h-[260px] sm:min-h-[380px]">
                    <img id="galleryPopupImage" src="" alt="Foto Galeri" class="max-w-full max-h-[65vh] object-contain transition-opacity duration-300 select-none">
                    <button type="button" id="galleryPopupFullscreen" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-gray-800/80 hover:bg-gray-700 backdrop-blur-md text-white flex items-center justify-center transition-all" title="Buka di tab baru">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Page loader
                const globalLoader = document.getElementById('globalLoader');
                window.addEventListener('load', function () {
                    setTimeout(() => {
                        if (globalLoader) {
                            globalLoader.classList.add('loader-hidden');
                            document.body.classList.remove('loading-active');
                            setTimeout(() => {
                                globalLoader.style.display = 'none';
                            }, 500);
                        }
                    }, 250);
                });

                document.querySelectorAll('a[href]').forEach(link => {
                    const href = link.getAttribute('href');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:') || link.target === '_blank' || link.hasAttribute('download')) return;
                    try {
                        const url = new URL(link.href, window.location.origin);
                        if (url.origin !== window.location.origin) return;
                    } catch (e) { return; }

                    link.addEventListener('click', function (e) {
                        if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
                        if (globalLoader) {
                            globalLoader.style.display = 'flex';
                            globalLoader.classList.remove('loader-hidden');
                            document.body.classList.add('loading-active');
                        }
                    });
                });

                // Scroll reveal
                const revealElements = document.querySelectorAll('.reveal-on-scroll');
                if ('IntersectionObserver' in window) {
                    const revealObserver = new IntersectionObserver((entries) => {
                        entries.forEach((entry, index) => {
                            if (entry.isIntersecting) {
                                setTimeout(() => {
                                    entry.target.classList.add('is-visible');
                                }, index * 60);
                                revealObserver.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
                    revealElements.forEach(el => revealObserver.observe(el));
                } else {
                    revealElements.forEach(el => el.classList.add('is-visible'));
                }

                // ================= GALLERY SLIDER RESPONSIF =================
                (function() {
                    const track = document.getElementById('gallerySliderTrack');
                    const viewport = document.getElementById('gallerySliderViewport');
                    const prevBtn = document.getElementById('galleryPrevBtn');
                    const nextBtn = document.getElementById('galleryNextBtn');
                    if (!track || !viewport) return;

                    const slides = Array.from(track.children);
                    if (slides.length === 0) return;

                    let currentIndex = 0;
                    let slidesPerView = 1;
                    let gap = 12;

                    function readGap() {
                        const styles = window.getComputedStyle(track);
                        const g = parseFloat(styles.columnGap || styles.gap || '12');
                        gap = isNaN(g) ? 12 : g;
                    }

                    function getSlidesPerView() {
                        const w = window.innerWidth;
                        if (w < 640) return 1;
                        if (w < 1024) return 2;
                        return 3;
                    }

                    function calculateMaxIndex() {
                        return Math.max(0, slides.length - slidesPerView);
                    }

                    function updateSlideWidths() {
                        slidesPerView = getSlidesPerView();
                        readGap();
                        const viewportWidth = viewport.clientWidth;
                        const totalGap = gap * (slidesPerView - 1);
                        const slideWidth = Math.max(0, (viewportWidth - totalGap) / slidesPerView);
                        slides.forEach(slide => {
                            slide.style.flex = `0 0 ${slideWidth}px`;
                            slide.style.maxWidth = `${slideWidth}px`;
                        });
                    }

                    function updateButtons() {
                        const maxIndex = calculateMaxIndex();
                        if (prevBtn) prevBtn.disabled = currentIndex <= 0;
                        if (nextBtn) nextBtn.disabled = currentIndex >= maxIndex;
                    }

                    function applyTransform() {
                        if (slides.length === 0) return;
                        const slideWidth = slides[0].getBoundingClientRect().width;
                        const offset = currentIndex * (slideWidth + gap);
                        track.style.transform = `translateX(-${offset}px)`;
                        updateButtons();
                    }

                    function goToIndex(index) {
                        const maxIndex = calculateMaxIndex();
                        currentIndex = Math.max(0, Math.min(index, maxIndex));
                        applyTransform();
                    }

                    function next() { goToIndex(currentIndex + 1); }
                    function prev() { goToIndex(currentIndex - 1); }

                    if (prevBtn) prevBtn.addEventListener('click', prev);
                    if (nextBtn) nextBtn.addEventListener('click', next);

                    // Touch swipe
                    let touchStartX = 0;
                    let touchEndX = 0;
                    viewport.addEventListener('touchstart', (e) => {
                        touchStartX = e.changedTouches[0].screenX;
                    }, { passive: true });
                    viewport.addEventListener('touchend', (e) => {
                        touchEndX = e.changedTouches[0].screenX;
                        const diff = touchStartX - touchEndX;
                        if (Math.abs(diff) > 40) {
                            if (diff > 0) next(); else prev();
                        }
                    }, { passive: true });

                    let rafId = null;
                    function scheduleUpdate() {
                        if (rafId) cancelAnimationFrame(rafId);
                        rafId = requestAnimationFrame(() => {
                            updateSlideWidths();
                            goToIndex(Math.min(currentIndex, calculateMaxIndex()));
                        });
                    }

                    if ('ResizeObserver' in window) {
                        const ro = new ResizeObserver(scheduleUpdate);
                        ro.observe(viewport);
                    } else {
                        window.addEventListener('resize', scheduleUpdate);
                    }

                    updateSlideWidths();
                    applyTransform();
                })();

                // Modal Galeri
                const popup = document.getElementById('galleryPopup');
                const popupContent = document.getElementById('galleryPopupContent');
                const popupImage = document.getElementById('galleryPopupImage');
                const popupTitle = document.getElementById('galleryPopupTitle');
                const closeBtn = document.getElementById('closeGalleryPopup');
                const fullscreenBtn = document.getElementById('galleryPopupFullscreen');
                let currentImage = '';

                function openPopup(image, title) {
                    currentImage = image;
                    popupImage.style.opacity = '0';
                    setTimeout(() => {
                        popupImage.src = image;
                        popupImage.style.opacity = '1';
                    }, 150);
                    popupTitle.textContent = title || 'Foto Galeri';
                    popup.classList.remove('hidden');
                    popup.classList.add('flex');
                    requestAnimationFrame(() => {
                        popup.classList.remove('opacity-0');
                        popupContent.classList.remove('scale-95');
                        popupContent.classList.add('scale-100');
                    });
                    document.body.style.overflow = 'hidden';
                }

                function closePopup() {
                    popup.classList.add('opacity-0');
                    popupContent.classList.remove('scale-100');
                    popupContent.classList.add('scale-95');
                    setTimeout(() => {
                        popup.classList.add('hidden');
                        popup.classList.remove('flex');
                        document.body.style.overflow = '';
                    }, 250);
                }

                document.querySelectorAll('.gallery-open-btn').forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        openPopup(this.dataset.image, this.dataset.title);
                    });
                });

                if (closeBtn) closeBtn.addEventListener('click', closePopup);
                if (popup) {
                    popup.addEventListener('click', function (e) {
                        if (e.target === popup) closePopup();
                    });
                }
                if (fullscreenBtn) {
                    fullscreenBtn.addEventListener('click', function () {
                        if (currentImage) window.open(currentImage, '_blank');
                    });
                }
                document.addEventListener('keydown', function (e) {
                    if (!popup || popup.classList.contains('hidden')) return;
                    if (e.key === 'Escape') closePopup();
                });

                // Custom Dropdown Search
                function setupCategoryDropdown(triggerId, panelId, chevronId, labelId, selectId, optClass) {
                    const trigger  = document.getElementById(triggerId);
                    const panel    = document.getElementById(panelId);
                    const chevron  = document.getElementById(chevronId);
                    const label    = document.getElementById(labelId);
                    const select   = document.getElementById(selectId);
                    if (!trigger || !panel) return;

                    trigger.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const isOpen = !panel.classList.contains('hidden');
                        
                        ['heroCategoryPanelDesktop','heroCategoryPanelMobile','heroFacilityPanelDesktop','heroFacilityPanelMobile'].forEach(function(pid) {
                            if (pid !== panelId) {
                                const otherP = document.getElementById(pid);
                                if (otherP) otherP.classList.add('hidden');
                            }
                        });
                        ['heroCategoryChevronDesktop','heroCategoryChevronMobile','heroFacilityChevronDesktop','heroFacilityChevronMobile'].forEach(function(cid) {
                            if (cid !== chevronId) {
                                const otherC = document.getElementById(cid);
                                if (otherC) otherC.style.transform = '';
                            }
                        });

                        panel.classList.toggle('hidden');
                        if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
                        if (!isOpen && window.lucide) lucide.createIcons();
                    });

                    document.querySelectorAll('.' + optClass).forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const value = this.dataset.value;
                            const lbl   = this.dataset.label;
                            if (label) label.textContent = lbl;
                            if (select) select.value = value;
                            
                            document.querySelectorAll('.' + optClass).forEach(function (b) {
                                const icon = b.querySelector('.check-icon');
                                if (b.dataset.value === value) {
                                    icon && icon.classList.remove('opacity-0');
                                } else {
                                    icon && icon.classList.add('opacity-0');
                                }
                            });
                            panel.classList.add('hidden');
                            if (chevron) chevron.style.transform = '';
                        });
                    });
                }

                setupCategoryDropdown('heroCategoryTriggerDesktop','heroCategoryPanelDesktop','heroCategoryChevronDesktop','heroCategoryLabelDesktop','heroCategoryDesktop','hero-cat-opt-desktop');
                setupCategoryDropdown('heroCategoryTriggerMobile','heroCategoryPanelMobile','heroCategoryChevronMobile','heroCategoryLabelMobile','heroCategoryMobile','hero-cat-opt-mobile');
                setupCategoryDropdown('heroFacilityTriggerDesktop','heroFacilityPanelDesktop','heroFacilityChevronDesktop','heroFacilityLabelDesktop','heroFacilityDesktop','hero-fac-opt-desktop');
                setupCategoryDropdown('heroFacilityTriggerMobile','heroFacilityPanelMobile','heroFacilityChevronMobile','heroFacilityLabelMobile','heroFacilityMobile','hero-fac-opt-mobile');

                document.addEventListener('click', function () {
                    ['heroCategoryPanelDesktop','heroCategoryPanelMobile','heroFacilityPanelDesktop','heroFacilityPanelMobile'].forEach(function (id) {
                        const p = document.getElementById(id);
                        if (p) p.classList.add('hidden');
                    });
                    ['heroCategoryChevronDesktop','heroCategoryChevronMobile','heroFacilityChevronDesktop','heroFacilityChevronMobile'].forEach(function(id) {
                        const c = document.getElementById(id);
                        if (c) c.style.transform = '';
                    });
                });

                // Search handler
                function handleHeroSearch(categorySelectId, facilitySelectId, searchInputId) {
                    const catSelect = document.getElementById(categorySelectId);
                    const facSelect = document.getElementById(facilitySelectId);
                    const searchInput = document.getElementById(searchInputId);

                    const keyword = searchInput ? searchInput.value.trim() : '';
                    const categoryVal = catSelect ? catSelect.value : '';
                    const facilityVal = facSelect ? facSelect.value : '';

                    const baseUrl = "{{ route('accommodationlanding.semua') }}";
                    const params = new URLSearchParams();

                    if (keyword) params.set('keyword', keyword);
                    if (categoryVal) params.set('category_id', categoryVal);
                    if (facilityVal) params.set('facility_id', facilityVal);

                    const finalUrl = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
                    window.location.href = finalUrl;
                }

                const heroSearchBtnDesktop = document.getElementById('heroSearchBtnDesktop');
                if (heroSearchBtnDesktop) {
                    heroSearchBtnDesktop.addEventListener('click', function() {
                        handleHeroSearch('heroCategoryDesktop', 'heroFacilityDesktop', 'heroSearchInputDesktop');
                    });
                }

                const heroSearchBtnMobile = document.getElementById('heroSearchBtnMobile');
                if (heroSearchBtnMobile) {
                    heroSearchBtnMobile.addEventListener('click', function() {
                        handleHeroSearch('heroCategoryMobile', 'heroFacilityMobile', 'heroSearchInputMobile');
                    });
                }

                const heroSearchInputDesktop = document.getElementById('heroSearchInputDesktop');
                if (heroSearchInputDesktop) {
                    heroSearchInputDesktop.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            handleHeroSearch('heroCategoryDesktop', 'heroFacilityDesktop', 'heroSearchInputDesktop');
                        }
                    });
                }

                const heroSearchInputMobile = document.getElementById('heroSearchInputMobile');
                if (heroSearchInputMobile) {
                    heroSearchInputMobile.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            handleHeroSearch('heroCategoryMobile', 'heroFacilityMobile', 'heroSearchInputMobile');
                        }
                    });
                }

                if (window.renderLucide) {
                    window.renderLucide();
                } else if (window.lucide) {
                    lucide.createIcons();
                }
            });
        </script>
    @endpush
@endsection
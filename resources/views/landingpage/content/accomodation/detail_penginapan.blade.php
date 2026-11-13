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

    @php
        $images = collect();

        if (!empty($accommodation->thumbnail)) {
            $images->push((object) ['image' => $accommodation->thumbnail, 'alt' => $accommodation->title]);
        }

        if ($accommodation->relationLoaded('images') && $accommodation->images->count()) {
            foreach ($accommodation->images as $img) {
                $images->push((object) ['image' => $img->image, 'alt' => $accommodation->title]);
            }
        }

        $images = $images->filter(fn($i) => !empty($i->image))->values();

        $statusMap = [
            'Available' => ['bg-[#00DD73]', 'Tersedia'],
            'Full' => ['bg-red-500', 'Tidak Tersedia'],
            'Maintenance' => ['bg-yellow-500', 'Maintenance'],
        ];
        [$statusBg, $statusText] = $statusMap[$accommodation->status] ?? ['bg-gray-500', $accommodation->status];

        $categoryName = $accommodation->category->name ?? 'Penginapan';
        $totalImages = $images->count();
        $allPhotoUrls = $images->map(fn($i) => asset($i->image))->values()->all();

        // Kontak & WhatsApp Pemesanan
        $accPhone = !empty(trim((string) ($accommodation->phone ?? ''))) ? trim((string) $accommodation->phone) : null;
        $accWa = !empty(trim((string) ($accommodation->whatsapp ?? ''))) ? trim((string) $accommodation->whatsapp) : null;
        $accEmail = !empty(trim((string) ($accommodation->email ?? ''))) ? trim((string) $accommodation->email) : null;

        $accFb = !empty(trim((string) ($accommodation->facebook ?? ''))) ? trim((string) $accommodation->facebook) : null;
        $accIg = !empty(trim((string) ($accommodation->instagram ?? ''))) ? trim((string) $accommodation->instagram) : null;
        $accYt = !empty(trim((string) ($accommodation->youtube ?? ''))) ? trim((string) $accommodation->youtube) : null;
        $accX = !empty(trim((string) ($accommodation->x ?? ''))) ? trim((string) $accommodation->x) : null;
        $accTiktok = !empty(trim((string) ($accommodation->tiktok ?? ''))) ? trim((string) $accommodation->tiktok) : null;

        $hasContact = !empty($accPhone) || !empty($accWa) || !empty($accEmail);
        $hasSosmed = !empty($accFb) || !empty($accIg) || !empty($accYt) || !empty($accX) || !empty($accTiktok);

        $rawWa = $accWa ?: ($accPhone ?: '');
        $cleanWa = preg_replace('/[^0-9]/', '', (string) $rawWa);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        } elseif (!empty($cleanWa) && !str_starts_with($cleanWa, '62')) {
            $cleanWa = '62' . $cleanWa;
        }

        $pageUrl = url()->current();
        $formattedPrice = 'Rp ' . number_format($accommodation->price ?? 0, 0, ',', '.') . '/malam';
        $waMessage = "Halo, saya ingin memesan/booking penginapan berikut:\n\n"
            . "*Nama Penginapan:* " . $accommodation->title . "\n"
            . "*Kategori:* " . ($categoryName ?? 'Penginapan') . "\n"
            . "*Harga:* " . $formattedPrice . "\n"
            . "*Link Halaman:* " . $pageUrl . "\n\n"
            . "Mohon informasi ketersediaan kamar dan prosedur pemesanannya. Terima kasih!";
        $bookingWaUrl = !empty($cleanWa) 
            ? 'https://wa.me/' . $cleanWa . '?text=' . rawurlencode($waMessage) 
            : null;

        // Rating calculation
        $avgRating = $accommodation->average_rating
            ?? ($accommodation->relationLoaded('ratings')
                ? round($accommodation->ratings->avg('bintang') ?? 0, 1)
                : 0);

        $totalReviews = $accommodation->total_reviews
            ?? ($accommodation->relationLoaded('ratings')
                ? $accommodation->ratings->count()
                : 0);

        $fullStars = (int) floor($avgRating);
        $hasHalfStar = ($avgRating - $fullStars) >= 0.5;

        $reviews = $accommodation->relationLoaded('ratings')
            ? $accommodation->ratings->sortByDesc('created_at')
            : collect();
    @endphp

    <div class="bg-[#FAFBFD] dark:bg-gray-900 text-[#0F294D] dark:text-gray-100 min-h-screen pt-20 sm:pt-24 pb-16">

        <div class="container mx-auto px-4 sm:px-6">

            <!-- ==================== 2. ACCOMMODATION HEADER & BOOKING ROW ==================== -->
            <div class="mb-6">
                
                {{-- Breadcrumbs --}}
                <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs text-gray-400 mb-2 font-medium">
                    <a href="{{ route('landingpage') }}" class="hover:text-[#0194F3] transition-colors">Beranda</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <a href="{{ route('accommodationlanding.semua') }}" class="hover:text-[#0194F3] transition-colors">Cari Penginapan</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-gray-600 dark:text-gray-300 font-semibold truncate">{{ $accommodation->title }}</span>
                </nav>

                {{-- Header Row: Title & Meta on Left, Price & Booking Button on Right --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-6 pb-4">
                    
                    {{-- Sisi Kiri: Judul, Rating & Lokasi --}}
                    <div class="max-w-2xl">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0F294D] dark:text-white leading-tight mb-2 tracking-tight">
                            {{ $accommodation->title }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            {{-- Rating --}}
                            <div class="inline-flex items-center gap-1.5 font-bold text-[#0F294D] dark:text-white">
                                <i data-lucide="star" class="w-4 h-4 text-[#FFB800] fill-[#FFB800]"></i>
                                <span>{{ number_format($avgRating, 1) }}</span>
                                <span class="text-gray-400 font-normal">({{ $totalReviews }} ulasan)</span>
                            </div>

                            <span class="text-gray-300 dark:text-gray-600">•</span>

                            {{-- Lokasi / Alamat --}}
                            @if($accommodation->address)
                                <div class="inline-flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-[#0194F3]"></i>
                                    <span class="truncate max-w-[280px] sm:max-w-md">{{ $accommodation->address }}</span>
                                </div>
                            @endif

                            <span class="text-gray-300 dark:text-gray-600">•</span>

                            {{-- Kategori Badge --}}
                            <span class="text-[11px] font-bold text-[#0194F3] bg-[#E6F4FF] dark:bg-[#0194F3]/20 px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                {{ $categoryName }}
                            </span>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Harga & Tombol Pesan Sekarang --}}
                    <div class="flex items-center justify-between md:justify-end gap-4 sm:gap-6 flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-800">
                        <div class="text-left md:text-right">
                            <span class="text-2xl sm:text-3xl font-extrabold text-[#0F294D] dark:text-white block leading-none">
                                Rp {{ number_format($accommodation->price ?? 0, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">/ malam</span>
                        </div>

                        {{-- Tombol Pesan Sekarang --}}
                        @if(!empty($bookingWaUrl))
                            <a href="{{ $bookingWaUrl }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 bg-[#0F294D] dark:bg-[#0194F3] hover:bg-[#0194F3] dark:hover:bg-[#0064D2] text-white px-7 py-3 rounded-full font-bold text-sm shadow-md transition-all group">
                                <span>Pesan Sekarang</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @else
                            <a href="#kontak"
                                class="inline-flex items-center justify-center gap-2 bg-[#0F294D] dark:bg-[#0194F3] hover:bg-[#0194F3] dark:hover:bg-[#0064D2] text-white px-7 py-3 rounded-full font-bold text-sm shadow-md transition-all group">
                                <span>Pesan Sekarang</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        @endif
                    </div>

                </div>

            </div>

          

            <!-- ==================== 4. SECTION: GALERI PENGINAPAN ==================== -->
            <section id="galeri-penginapan" class="mb-12 sm:mb-16 scroll-mt-32">
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F294D] dark:text-white mb-4 sm:mb-6">
                    Galeri Penginapan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4">
                    
                    {{-- Foto Utama Kiri (Besar) --}}          
                    <div class="md:col-span-6 lg:col-span-6">
                        @if($images->count() > 0)
                            <button type="button" class="open-gallery-btn block w-full h-[280px] sm:h-[360px] md:h-[400px] rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800 group relative text-left"
                                data-photos='@json($allPhotoUrls)' data-index="0">
                                <img src="{{ asset($images[0]->image) }}" alt="{{ $images[0]->alt }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center text-[#0194F3]">
                                        <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                    </div>
                                </div>
                            </button>
                        @endif
                    </div>

                    {{-- Grid 4 Foto Kanan (2x2) --}}
                    <div class="md:col-span-6 lg:col-span-6">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 h-[280px] sm:h-[360px] md:h-[400px]">
                            @for($i = 1; $i <= 4; $i++)
                                @if(isset($images[$i]))
                                    <button type="button" class="open-gallery-btn relative w-full h-full rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800 group text-left"
                                        data-photos='@json($allPhotoUrls)' data-index="{{ $i }}">
                                        <img src="{{ asset($images[$i]->image) }}" alt="{{ $images[$i]->alt }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                        {{-- Overlay "+X Foto" pada item ke-4 jika foto lebih dari 5 --}}
                                        @if($i === 4 && $totalImages > 5)
                                            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex flex-col items-center justify-center text-white font-bold transition-all group-hover:bg-black/70">
                                                <i data-lucide="images" class="w-6 h-6 mb-1"></i>
                                                <span class="text-base font-extrabold">+{{ $totalImages - 4 }} Foto</span>
                                                <span class="text-[10px] uppercase tracking-wider text-gray-300">Lihat Semua</span>
                                            </div>
                                        @else
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <div class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center text-[#0194F3]">
                                                    <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </button>
                                @else
                                    {{-- Placeholder jika foto kurang dari 5 --}}
                                    <div class="rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-300 dark:text-gray-600">
                                        <i data-lucide="image" class="w-6 h-6"></i>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>

                </div>
            </section>

            <!-- ==================== TENTANG & SPESIFIKASI PENGINAPAN ==================== -->
            @if($accommodation->description || $accommodation->capacity || $accommodation->bedroom || $accommodation->bathroom || $accommodation->size)
                <section class="mb-12 sm:mb-16 pt-4">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F294D] dark:text-white mb-4">
                        Tentang Penginapan
                    </h2>

                    {{-- Spesifikasi Info Chips --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-3.5 border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center text-[#0194F3]">
                                <i data-lucide="users" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Kapasitas</span>
                                <p class="text-xs sm:text-sm font-bold text-[#0F294D] dark:text-white">{{ $accommodation->capacity ?? '-' }} Orang</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-3.5 border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center text-[#0194F3]">
                                <i data-lucide="bed" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Kamar Tidur</span>
                                <p class="text-xs sm:text-sm font-bold text-[#0F294D] dark:text-white">{{ $accommodation->bedroom ?? '-' }} Kamar</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-3.5 border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center text-[#0194F3]">
                                <i data-lucide="bath" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Kamar Mandi</span>
                                <p class="text-xs sm:text-sm font-bold text-[#0F294D] dark:text-white">{{ $accommodation->bathroom ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-3.5 border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center text-[#0194F3]">
                                <i data-lucide="maximize" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Ukuran</span>
                                <p class="text-xs sm:text-sm font-bold text-[#0F294D] dark:text-white">{{ $accommodation->size ?: '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if(!empty($accommodation->description))
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                            {{ $accommodation->description }}
                        </p>
                    @endif
                </section>
            @endif

            <!-- ==================== 5. SECTION: FASILITAS ==================== -->
            <section id="fasilitas" class="mb-12 sm:mb-16 scroll-mt-32 pt-4">
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F294D] dark:text-white mb-6">
                    Fasilitas
                </h2>

                @if($accommodation->relationLoaded('facilities') && $accommodation->facilities->count())
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-5 gap-x-6">
                        @foreach($accommodation->facilities as $facility)
                            <div class="flex items-center gap-3">
                                @php
                                    $iconRaw    = $facility->icon;
                                    $iconLucide = $iconRaw && preg_match('/^[a-z0-9-]+$/i', $iconRaw);
                                    $iconSvg    = $iconRaw && (Str::startsWith($iconRaw, ['M', 'm']) || Str::startsWith($iconRaw, '<'));
                                    $iconEmoji  = $iconRaw && !$iconLucide && !$iconSvg;
                                @endphp

                                <div class="w-8 h-8 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center text-[#0194F3] flex-shrink-0">
                                    @if($iconLucide)
                                        <i data-lucide="{{ $iconRaw }}" class="w-4 h-4"></i>
                                    @elseif($iconSvg)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $iconRaw }}" />
                                        </svg>
                                    @elseif($iconEmoji)
                                        <span class="text-sm leading-none">{{ $iconRaw }}</span>
                                    @else
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    @endif
                                </div>

                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $facility->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400">Informasi fasilitas belum ditambahkan.</p>
                @endif
            </section>

            <!-- ==================== 6. SECTION: ATURAN PENGINAPAN ==================== -->
            <section id="aturan" class="mb-12 sm:mb-16 scroll-mt-32 pt-4">
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F294D] dark:text-white mb-6">
                    Aturan Penginapan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3.5 gap-x-8">
                    {{-- Default Standard Rules --}}
                    <div class="flex items-start gap-3">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 leading-relaxed">
                            Check-in mulai pukul 14.00 WIB
                        </span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 leading-relaxed">
                            Dilarang merokok di dalam kamar
                        </span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 leading-relaxed">
                            Check-out maksimal pukul 12.00 WIB
                        </span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 leading-relaxed">
                            Menjaga ketenangan lingkungan
                        </span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 leading-relaxed">
                            Tidak diperbolehkan membawa hewan peliharaan
                        </span>
                    </div>

                    {{-- Dynamic Rules from DB if present --}}
                    @if($accommodation->relationLoaded('rules') && $accommodation->rules->count())
                        @foreach($accommodation->rules as $rule)
                            <div class="flex items-start gap-3">
                                <i data-lucide="check" class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 leading-relaxed block">
                                        {{ $rule->name }}
                                    </span>
                                    @if(!empty($rule->description))
                                        <span class="text-xs text-gray-400 block mt-0.5">{{ $rule->description }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>

            <!-- ==================== 7. SECTION: LOKASI ==================== -->
            <section id="lokasi" class="mb-12 sm:mb-16 scroll-mt-32 pt-4">
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F294D] dark:text-white mb-6">
                    Lokasi
                </h2>

                <div class="rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 relative">
                    @if($accommodation->maps_embed)
                        @php
                            $embed = trim($accommodation->maps_embed);
                            if (stripos($embed, '<iframe') === 0) {
                                $embed = preg_replace('/\s*width="[^"]*"/i', '', $embed);
                                $embed = preg_replace('/\s*height="[^"]*"/i', '', $embed);
                                $embed = preg_replace('/\s*style="[^"]*"/i', '', $embed);
                                $embed = preg_replace(
                                    '/<iframe/i',
                                    '<iframe class="w-full h-72 sm:h-96 md:h-[420px] border-0 block"',
                                    $embed
                                );
                            } else {
                                $embed = '<iframe src="' . e($embed) . '" class="w-full h-72 sm:h-96 md:h-[420px] border-0 block" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>';
                            }
                        @endphp
                        {!! $embed !!}
                    @else
                        {{-- Fallback jika belum diatur embed maps --}}
                        <div class="w-full h-64 sm:h-80 bg-gray-100 dark:bg-gray-800 flex flex-col items-center justify-center p-6 text-center">
                            <div class="w-12 h-12 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center text-[#0194F3] mb-3">
                                <i data-lucide="map-pin" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-base font-bold text-[#0F294D] dark:text-white mb-1">{{ $accommodation->title }}</h3>
                            <p class="text-xs text-gray-500 max-w-sm mb-4">{{ $accommodation->address ?? 'Lokasi belum disematkan pada peta' }}</p>
                        </div>
                    @endif

                    @if($accommodation->google_maps_url)
                        <div class="p-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $accommodation->address }}</span>
                            <a href="{{ $accommodation->google_maps_url }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0194F3] hover:underline">
                                <span>Buka di Google Maps</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </section>

            <!-- ==================== 9. SECTION: ULASAN PENGGUNA ==================== -->
            <section id="ulasan" class="mb-12 sm:mb-16 scroll-mt-32 pt-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0F294D] dark:text-white">
                        Ulasan Pengguna ({{ $totalReviews }})
                    </h2>

                    <button type="button" id="openRatingModal"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#0194F3] hover:bg-[#0064D2] text-white text-xs font-bold transition-all shadow-sm">
                        <i data-lucide="star" class="w-3.5 h-3.5"></i>
                        <span>Tulis Ulasan</span>
                    </button>
                </div>

                @if($reviews->count() > 0)
                    <div id="reviewsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($reviews as $index => $review)
                            <div class="review-item p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm {{ $index >= 6 ? 'hidden' : '' }}"
                                data-index="{{ $index }}">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 text-[#0194F3] flex items-center justify-center text-xs font-bold">
                                            {{ strtoupper(substr($review->nama ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs sm:text-sm font-bold text-[#0F294D] dark:text-white">
                                                {{ strtoupper(substr($review->nama ?? 'Anonim', 0, 1)) . str_repeat('*', 4) }}
                                            </p>
                                            <p class="text-[10px] text-gray-400">
                                                {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= ($review->bintang ?? 0) ? 'text-[#FFB800] fill-[#FFB800]' : 'text-gray-200 dark:text-gray-700' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if(!empty($review->keterangan))
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed mt-2">
                                        {{ $review->keterangan }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($totalReviews > 6)
                        <div class="text-center mt-6">
                            <button type="button" id="toggleAllReviews"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white dark:bg-gray-800 text-[#0F294D] dark:text-white text-xs font-bold border border-gray-200 dark:border-gray-700 hover:bg-[#0194F3] hover:text-white transition-all shadow-sm">
                                <span id="toggleReviewsText">Lihat Semua Ulasan ({{ $totalReviews }})</span>
                                <i data-lucide="chevron-down" id="toggleReviewsIcon" class="w-3.5 h-3.5 transition-transform duration-300"></i>
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <i data-lucide="message-square" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-xs sm:text-sm text-gray-500 font-medium mb-3">Belum ada ulasan untuk penginapan ini.</p>
                        <button type="button" id="openRatingModalEmpty"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#0194F3] text-white text-xs font-bold shadow-sm">
                            <i data-lucide="star" class="w-3.5 h-3.5"></i>
                            <span>Tulis Ulasan Pertama</span>
                        </button>
                    </div>
                @endif
            </section>

        </div>

    </div>

    {{-- ===================== LIGHTBOX MODAL GALERI ===================== --}}
    <div id="galleryModal"
        class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/85 backdrop-blur-sm p-2 xs:p-3 sm:p-4 opacity-0 transition-opacity duration-300 overflow-hidden">

        <div class="relative w-full sm:max-w-4xl max-h-[92vh] flex flex-col bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 border border-gray-700"
            id="galleryModalContent">

            <div class="flex items-center justify-between px-4 sm:px-5 py-3 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 flex-shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-[#E6F4FF] dark:bg-[#0194F3]/20 flex items-center justify-center text-[#0194F3]">
                        <i data-lucide="image" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-[#0F294D] dark:text-white truncate">
                            Galeri {{ $accommodation->title }}
                        </h3>
                        <p class="text-[10px] text-gray-400">
                            <span id="galleryCounter">1</span> dari <span id="galleryTotal">{{ $totalImages }}</span> foto
                        </p>
                    </div>
                </div>

                <button type="button" id="closeGalleryModal"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-red-50 hover:text-red-500 transition-colors"
                    title="Tutup (Esc)">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="relative flex-1 bg-black flex items-center justify-center overflow-hidden min-h-[250px] sm:min-h-[350px]">
                <button type="button" id="galleryPrev"
                    class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-black/50 hover:bg-black/80 backdrop-blur-md text-white flex items-center justify-center transition-all border border-white/20">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>

                <img id="galleryMainImage" src="" alt="Foto Galeri"
                    class="max-w-full max-h-[60vh] object-contain transition-opacity duration-300 select-none">

                <button type="button" id="galleryNext"
                    class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-black/50 hover:bg-black/80 backdrop-blur-md text-white flex items-center justify-center transition-all border border-white/20">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>

                <button type="button" id="galleryFullscreen"
                    class="absolute top-3 right-3 z-10 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-black/50 hover:bg-black/80 backdrop-blur-md text-white flex items-center justify-center transition-all border border-white/20"
                    title="Buka di tab baru">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="px-4 py-2.5 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 flex-shrink-0">
                <div id="galleryThumbs" class="flex gap-2 overflow-x-auto pb-1"></div>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL TAMBAH RATING / ULASAN ===================== --}}
    @include('landingpage.layouts.partials.modal_add_rating', ['accommodation' => $accommodation])

    <style>
        #galleryThumbs::-webkit-scrollbar { height: 6px; }
        #galleryThumbs::-webkit-scrollbar-track { background: transparent; }
        #galleryThumbs::-webkit-scrollbar-thumb { background: rgba(1, 148, 243, 0.4); border-radius: 3px; }
        #globalLoader.loader-hidden { opacity: 0; pointer-events: none; }
        body.loading-active { overflow: hidden; }
        .review-item { transition: opacity 0.3s ease, transform 0.3s ease; }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // Global Page Loader
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

                // Tab link active indicator on scroll
                const tabLinks = document.querySelectorAll('.tab-link');
                const sections = ['galeri-penginapan', 'fasilitas', 'aturan', 'lokasi', 'ulasan'].map(id => document.getElementById(id)).filter(Boolean);

                function updateActiveTab() {
                    let current = '';
                    sections.forEach(sec => {
                        const top = sec.offsetTop - 140;
                        if (window.scrollY >= top) {
                            current = sec.getAttribute('id');
                        }
                    });

                    tabLinks.forEach(link => {
                        const target = link.getAttribute('href').replace('#', '');
                        if (target === current) {
                            link.classList.add('bg-white', 'dark:bg-gray-800', 'shadow-sm', 'border', 'border-gray-200', 'dark:border-gray-700', 'text-[#0194F3]');
                            link.classList.remove('text-gray-500');
                        } else {
                            link.classList.remove('bg-white', 'dark:bg-gray-800', 'shadow-sm', 'border', 'border-gray-200', 'dark:border-gray-700', 'text-[#0194F3]');
                            link.classList.add('text-gray-500');
                        }
                    });
                }
                window.addEventListener('scroll', updateActiveTab, { passive: true });

                // GALLERY MODAL
                const modal = document.getElementById('galleryModal');
                const modalContent = document.getElementById('galleryModalContent');
                const mainImage = document.getElementById('galleryMainImage');
                const thumbsWrap = document.getElementById('galleryThumbs');
                const counterEl = document.getElementById('galleryCounter');
                const totalEl = document.getElementById('galleryTotal');
                const closeBtn = document.getElementById('closeGalleryModal');
                const prevBtn = document.getElementById('galleryPrev');
                const nextBtn = document.getElementById('galleryNext');
                const fullscreenBtn = document.getElementById('galleryFullscreen');

                let photos = [];
                let current = 0;

                function openModal(images, startIndex) {
                    photos = images;
                    current = startIndex || 0;
                    if (totalEl) totalEl.textContent = photos.length;
                    buildThumbs();
                    showPhoto(current);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    requestAnimationFrame(() => {
                        modal.classList.remove('opacity-0');
                        modalContent.classList.remove('scale-95');
                        modalContent.classList.add('scale-100');
                    });
                    document.body.style.overflow = 'hidden';
                }

                function closeModal() {
                    modal.classList.add('opacity-0');
                    modalContent.classList.remove('scale-100');
                    modalContent.classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        document.body.style.overflow = '';
                    }, 250);
                }

                function showPhoto(index) {
                    if (!photos.length) return;
                    if (index < 0) index = photos.length - 1;
                    if (index >= photos.length) index = 0;
                    current = index;
                    mainImage.style.opacity = '0';
                    setTimeout(() => {
                        mainImage.src = photos[current];
                        mainImage.style.opacity = '1';
                    }, 120);
                    if (counterEl) counterEl.textContent = current + 1;
                    document.querySelectorAll('#galleryThumbs .gallery-thumb-item').forEach((el, i) => {
                        el.classList.toggle('ring-2', i === current);
                        el.classList.toggle('ring-[#0194F3]', i === current);
                        el.classList.toggle('opacity-50', i !== current);
                    });
                    const activeThumb = document.querySelectorAll('#galleryThumbs .gallery-thumb-item')[current];
                    if (activeThumb) {
                        activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    }
                }

                function buildThumbs() {
                    if (!thumbsWrap) return;
                    thumbsWrap.innerHTML = '';
                    photos.forEach((src, i) => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'gallery-thumb-item flex-shrink-0 w-14 h-14 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-all hover:scale-105';
                        btn.innerHTML = `<img src="${src}" class="w-full h-full object-cover" alt="Thumb ${i + 1}" loading="lazy">`;
                        btn.addEventListener('click', () => showPhoto(i));
                        thumbsWrap.appendChild(btn);
                    });
                }

                document.querySelectorAll('.open-gallery-btn').forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const data = this.dataset.photos;
                        const idx = parseInt(this.dataset.index || '0', 10);
                        let parsed = [];
                        try { parsed = JSON.parse(data); } catch (err) { parsed = []; }
                        if (parsed.length) openModal(parsed, idx);
                    });
                });

                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (modal) modal.addEventListener('click', function (e) {
                    if (e.target === modal) closeModal();
                });
                if (prevBtn) prevBtn.addEventListener('click', () => showPhoto(current - 1));
                if (nextBtn) nextBtn.addEventListener('click', () => showPhoto(current + 1));
                if (fullscreenBtn) fullscreenBtn.addEventListener('click', () => {
                    if (photos[current]) window.open(photos[current], '_blank');
                });

                document.addEventListener('keydown', function (e) {
                    if (!modal || modal.classList.contains('hidden')) return;
                    if (e.key === 'Escape') closeModal();
                    if (e.key === 'ArrowLeft') showPhoto(current - 1);
                    if (e.key === 'ArrowRight') showPhoto(current + 1);
                });

                // RATING MODAL
                const ratingModal = document.getElementById('ratingModal')
                    || document.getElementById('modalAddRating')
                    || document.getElementById('addRatingModal')
                    || document.getElementById('modal_add_rating');

                function openRatingModal() {
                    if (!ratingModal) return;
                    ratingModal.classList.remove('hidden');
                    ratingModal.classList.add('flex');
                    requestAnimationFrame(() => ratingModal.classList.remove('opacity-0'));
                    document.body.style.overflow = 'hidden';
                }

                function closeRatingModal() {
                    if (!ratingModal) return;
                    ratingModal.classList.add('opacity-0');
                    setTimeout(() => {
                        ratingModal.classList.add('hidden');
                        ratingModal.classList.remove('flex');
                        document.body.style.overflow = '';
                    }, 200);
                }

                ['openRatingModal', 'openRatingModalEmpty'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener('click', openRatingModal);
                });

                if (ratingModal) {
                    ratingModal.addEventListener('click', function (e) {
                        if (e.target === ratingModal) closeRatingModal();
                    });
                    ratingModal.querySelectorAll('[data-close-rating-modal], #closeRatingModal, .close-rating-modal, [data-modal-close]').forEach(btn => {
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            closeRatingModal();
                        });
                    });
                }

                // TOGGLE ALL REVIEWS
                const toggleBtn = document.getElementById('toggleAllReviews');
                if (toggleBtn) {
                    let isExpanded = false;
                    toggleBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const allReviews = document.querySelectorAll('.review-item');
                        const textEl = document.getElementById('toggleReviewsText');
                        const iconEl = document.getElementById('toggleReviewsIcon');
                        isExpanded = !isExpanded;

                        allReviews.forEach((item, index) => {
                            if (index >= 6) {
                                if (isExpanded) {
                                    item.classList.remove('hidden');
                                } else {
                                    item.classList.add('hidden');
                                }
                            }
                        });

                        if (isExpanded) {
                            textEl.textContent = 'Sembunyikan Sebagian Ulasan';
                            if (iconEl) iconEl.style.transform = 'rotate(180deg)';
                        } else {
                            textEl.textContent = 'Lihat Semua Ulasan ({{ $totalReviews }})';
                            if (iconEl) iconEl.style.transform = 'rotate(0deg)';
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
@php
  $isHeroPage = isset($isHeroPage) ? $isHeroPage : (request()->routeIs('landingpage') || request()->is('/'));
  $homeUrl = isset($homeUrl) ? $homeUrl : (Route::has('landingpage') ? route('landingpage') : url('/'));
  $navHref = isset($navHref) ? $navHref : function($id) use ($isHeroPage, $homeUrl) {
      return $isHeroPage ? '#' . $id : $homeUrl . '#' . $id;
  };
@endphp

<!-- ==================== COMPACT WAVY FOOTER SECTION ==================== -->
<div class="relative w-full overflow-hidden leading-none select-none pointer-events-none -mb-1 mt-6 sm:mt-8">
    {{-- Sleek, low-profile organic SVG wave (ketinggian kompak) --}}
    <svg class="w-full h-6 sm:h-8 md:h-10 block" viewBox="0 0 1440 50" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        {{-- Layer 1: Soft Brand Blue Accent Wave --}}
        <path d="M0,15 C240,38 480,5 720,24 C960,42 1200,8 1440,26 L1440,50 L0,50 Z" class="fill-[#0194F3]/25 dark:fill-[#0194F3]/15"></path>
        {{-- Layer 2: Deeper Blue Wave --}}
        <path d="M0,24 C280,7 540,36 820,18 C1100,-2 1300,32 1440,21 L1440,50 L0,50 Z" class="fill-[#0064D2]/40 dark:fill-[#0F294D]/70"></path>
        {{-- Layer 3: Solid Main Footer Body Wave --}}
        <path d="M0,30 C320,48 620,14 960,32 C1180,44 1340,24 1440,29 L1440,50 L0,50 Z" class="fill-[#0F294D] dark:fill-gray-950"></path>
    </svg>
</div>

<!-- Footer Body (Kompak & Rendah) -->
<footer class="bg-[#0F294D] text-white pt-4 pb-6 sm:pb-8 dark:bg-gray-950 relative overflow-hidden">
    
    {{-- Decorative subtle glow inside footer --}}
    <div class="absolute -top-16 -left-16 w-64 h-64 bg-[#0194F3]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -right-16 w-64 h-64 bg-[#0064D2]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 lg:gap-8">

            {{-- =============== KOLOM 1: BRAND + TAGLINE + SOSIAL MEDIA (4 Cols) =============== --}}
            <div class="lg:col-span-4 flex flex-col justify-between">
                <div>
                    <h3 class="text-base sm:text-lg font-extrabold mb-2 text-white flex items-center gap-2 tracking-wide">
                        @if(!empty($setting->logo))
                            <img src="{{ asset($setting->logo) }}" class="h-6 w-auto object-contain" alt="{{ $setting->site_name ?? 'adakamar.id' }} Logo" />
                        @else
                            <img src="{{ asset('landingpage/home.png') }}" class="h-6 w-auto object-contain" alt="{{ $setting->site_name ?? 'adakamar.id' }} Logo" />
                        @endif
                        <span class="break-words uppercase tracking-wider">{{ $setting->site_name ?? 'adakamar.id' }}</span>
                    </h3>           

                    <p class="text-xs text-gray-300/80 leading-relaxed max-w-sm mb-3.5">
                        {{ $setting->tagline ?? 'Platform penginapan terpercaya untuk kebutuhan liburan dan menginap Anda di seluruh Indonesia.' }}
                    </p>
                </div>

                {{-- Sosial Media Buttons --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if(!empty($setting->facebook))
                        <a href="{{ $setting->facebook }}" target="_blank" rel="noopener noreferrer"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#0194F3] text-gray-300 hover:text-white transition-all shadow-sm" title="Facebook">
                            <i data-lucide="facebook" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                    @if(!empty($setting->twitter))
                        <a href="{{ $setting->twitter }}" target="_blank" rel="noopener noreferrer"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#0194F3] text-gray-300 hover:text-white transition-all shadow-sm" title="Twitter / X">
                            <i data-lucide="twitter" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                    @if(!empty($setting->instagram))
                        <a href="{{ $setting->instagram }}" target="_blank" rel="noopener noreferrer"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#0194F3] text-gray-300 hover:text-white transition-all shadow-sm" title="Instagram">
                            <i data-lucide="instagram" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                    @if(!empty($setting->youtube))
                        <a href="{{ $setting->youtube }}" target="_blank" rel="noopener noreferrer"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#0194F3] text-gray-300 hover:text-white transition-all shadow-sm" title="YouTube">
                            <i data-lucide="youtube" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                    @if(!empty($setting->tiktok))
                        <a href="{{ $setting->tiktok }}" target="_blank" rel="noopener noreferrer"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-[#0194F3] text-gray-300 hover:text-white transition-all shadow-sm" title="TikTok">
                            <i data-lucide="music-2" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- =============== KOLOM 2: MENU / NAVIGASI (2 Cols) =============== --}}
            <div class="lg:col-span-2">
                <h4 class="font-bold mb-2.5 text-white text-xs uppercase tracking-wider border-b border-white/15 pb-1.5 inline-block min-w-[75px]">
                    Navigasi
                </h4>
                <ul class="space-y-1.5 text-xs text-gray-300/80">
                    <li><a href="{{ $navHref('beranda') }}" class="hover:text-[#33ADFF] transition-colors flex items-center gap-1.5"><i data-lucide="chevron-right" class="w-3 h-3 text-[#0194F3]"></i> Beranda</a></li>
                    <li><a href="{{ route('accommodationlanding.semua') }}" class="hover:text-[#33ADFF] transition-colors flex items-center gap-1.5"><i data-lucide="chevron-right" class="w-3 h-3 text-[#0194F3]"></i> Cari Penginapan</a></li>
                    <li><a href="{{ $navHref('galeri') }}" class="hover:text-[#33ADFF] transition-colors flex items-center gap-1.5"><i data-lucide="chevron-right" class="w-3 h-3 text-[#0194F3]"></i> Galeri</a></li>
                   
                    <li><a href="{{ $navHref('kontak') }}" class="hover:text-[#33ADFF] transition-colors flex items-center gap-1.5"><i data-lucide="chevron-right" class="w-3 h-3 text-[#0194F3]"></i> Kontak</a></li>
                </ul>
            </div>

            {{-- =============== KOLOM 3: HUBUNGI KAMI (3 Cols) =============== --}}
            <div class="lg:col-span-3">
                <h4 class="font-bold mb-2.5 text-white text-xs uppercase tracking-wider border-b border-white/15 pb-1.5 inline-block min-w-[95px]">
                    Kontak Kami
                </h4>
                <ul class="space-y-2 text-xs text-gray-300/80">

                    {{-- Telepon --}}
                    @if(!empty($setting->phone))
                        <li class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 text-[#0194F3]">
                                <i data-lucide="phone" class="w-3 h-3"></i>
                            </div>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $setting->phone) }}" class="hover:text-[#33ADFF] transition-colors break-all">
                                {{ $setting->phone }}
                            </a>
                        </li>
                    @endif

                    {{-- Email --}}
                    @if(!empty($setting->email))
                        <li class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 text-[#0194F3]">
                                <i data-lucide="mail" class="w-3 h-3"></i>
                            </div>
                            <a href="mailto:{{ $setting->email }}" class="hover:text-[#33ADFF] transition-colors break-all">
                                {{ $setting->email }}
                            </a>
                        </li>
                    @endif

                    {{-- WhatsApp --}}
                    @if(!empty($setting->whatsapp))
                        <li class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0 text-green-400">
                                <i data-lucide="message-circle" class="w-3 h-3"></i>
                            </div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="hover:text-[#33ADFF] transition-colors break-all">
                                {{ $setting->whatsapp }} (WA)
                            </a>
                        </li>
                    @endif

                    {{-- Alamat --}}
                    @if(!empty($setting->address))
                        <li class="flex items-start gap-2">
                            <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 text-[#0194F3] mt-0.5">
                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                            </div>
                            <span class="leading-snug text-gray-300/80">
                                {{ $setting->address }}
                            </span>
                        </li>
                    @endif

                </ul>
            </div>

            

        </div>

        {{-- =============== COPYRIGHT & LEGAL =============== --}}
        <div class="border-t border-white/10 mt-6 sm:mt-8 pt-3.5 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left text-[11px] text-gray-400">
            <p>{{ $setting->copyright ?? ('© ' . date('Y') . ' ' . ($setting->site_name ?? 'adakamar.id') . '. Semua hak dilindungi.') }}</p>
            <div class="flex items-center gap-3 text-gray-400">
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                <span>•</span>
                <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>

    </div>
</footer>

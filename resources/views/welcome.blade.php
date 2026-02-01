<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuotaumroh.id - Paket Internet Umroh & Haji</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#10b981">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#10b981">

    <!-- Google Fonts - Figtree -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Shared CSS -->
    <link rel="stylesheet" href="{{ asset('shared/styles.css') }}">

    <!-- ⚠️ PENTING: Load config.js PERTAMA sebelum script lain -->
    <script src="{{ asset('shared/config.js') }}?v={{ time() }}"></script>

    <!-- Shared Scripts -->
    <script src="{{ asset('shared/utils.js') }}?v={{ time() }}"></script>

    <!-- Store Config (untuk pricing sesuai agent/role) -->
    <script>
        const STORE_CONFIG = {
            agent_id: @json($agent->id ?? ''),
            catalog_ref_code: @json($agent->link_referal ?? 'kuotaumroh'),
            link_referal: @json($agent->link_referal ?? 'kuotaumroh'),
            is_individual: true,
        };
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        primary: {
                            DEFAULT: "hsl(var(--primary))",
                            foreground: "hsl(var(--primary-foreground))",
                        },
                        secondary: {
                            DEFAULT: "hsl(var(--secondary))",
                            foreground: "hsl(var(--secondary-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                    },
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                },
            },
        }
    </script>
</head>

<body class="min-h-screen bg-background" x-data="trackingOrder()">
    
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
        <div class="container mx-auto flex h-16 items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id" class="h-9 w-9 object-contain">
                <span class="text-xl font-semibold">Kuotaumroh.id</span>
            </div>
            
            <!-- Tracking Order Button -->
            <button @click="openTrackingModal()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary border border-gray-300 rounded-lg hover:border-primary transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="hidden sm:inline">Lacak Pesanan</span>
            </button>
        </div>
    </header>
    
    <!-- Tracking Order Modal -->
    <div x-show="showTrackingModal" 
         x-cloak
         @click.self="closeTrackingModal()"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary to-teal-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Lacak Pesanan</h3>
                            <p class="text-xs text-white/80">Masukkan ID Pembayaran Anda</p>
                        </div>
                    </div>
                    <button @click="closeTrackingModal()" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <!-- Info Banner -->
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Dimana menemukan ID Pembayaran?</p>
                            <p class="text-xs text-blue-600">ID Pembayaran dapat ditemukan di email konfirmasi atau struk pembayaran yang Anda terima setelah melakukan transaksi.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Input Form -->
                <form @submit.prevent="trackOrder()" class="space-y-4">
                    <div>
                        <label for="payment_id" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="payment_id"
                               x-model="paymentId"
                               placeholder="Contoh: PAY-20240201-ABC123"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                               required>
                        <p class="mt-1.5 text-xs text-gray-500">Masukkan ID Pembayaran yang tertera di email/struk konfirmasi</p>
                    </div>
                    
                    <!-- Error Message -->
                    <div x-show="errorMessage" 
                         x-transition
                         class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex gap-2">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-red-800" x-text="errorMessage"></p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button type="button"
                                @click="closeTrackingModal()"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                :disabled="loading || !paymentId"
                                class="flex-1 px-4 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Mencari...' : 'Lacak Pesanan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hero Background Image -->
    <div class="relative w-full min-h-[400px] lg:min-h-[600px] overflow-hidden bg-gradient-to-br from-teal-50 via-blue-50 to-cyan-50">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ asset('images/image.png') }}');"></div>
        <!-- Gradient Overlay - fades to transparent at bottom -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-background"></div>
        
        <!-- Hero Content -->
        <div class="relative z-10 container mx-auto px-4 py-8 lg:py-16 flex flex-col items-start justify-center min-h-[400px] lg:min-h-[600px]">
            <div class="max-w-2xl">
                <h1 class="text-5xl md:text-6xl font-bold tracking-tight mb-6 text-gray-900">
                    Paket Internet<br>Umroh & Haji
                </h1>
                <p class="text-xl md:text-2xl mb-6 text-gray-700">
                    Dapatkan kuota internet terbaik untuk<br>
                    perjalanan ibadah Anda. Proses cepat,<br>
                    harga terjangkau.
                </p>
            
                {{-- <!-- Checkmark Feature -->
                <a href="#pilih-provider" 
                    class="inline-flex items-center justify-center rounded-md bg-teal-600 text-white h-8 px-8 text-lg font-semibold hover:bg-teal-700 transition-colors shadow-lg">
                    Lihat Paket
                </a> --}}
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto pt-1 pb-8 lg:py-16 px-4 max-w-7xl">

        @if(session('pending_agent_link'))
        {{-- <!-- Agent Info Banner -->
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-blue-800">
                    Anda akan diarahkan ke toko <strong>{{ session('pending_agent_link') }}</strong> setelah memilih paket
                </p>
            </div>
        </div> --}}
        @endif

        <!-- Promo Carousel Section -->
        <div class="mb-6 md:mb-10" x-data="promoCarousel()" @mouseenter="stopAutoSlide" @mouseleave="startAutoSlide">
            
            <div class="flex items-center justify-between mb-8 px-1">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-100 to-red-50 flex items-center justify-center border border-orange-100 shadow-sm">
                        <span class="text-2xl animate-pulse">🔥</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-2xl font-black text-gray-900 leading-none tracking-tight">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Best</span> Seller
                            </h2>
                            <span class="px-2 py-0.5 rounded-full bg-red-50 text-red-600 text-[10px] font-bold border border-red-100 uppercase tracking-wide">
                                Populer
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Carousel View (2 columns per slide) -->
            <div class="md:hidden relative group">
                <!-- Navigation Arrows Removed (Swipe Enabled) -->
                
                <div class="overflow-hidden">
                    <template x-if="loading">
                        <div class="text-center py-12 text-gray-500">Memuat paket promo...</div>
                    </template>
                    
                    <template x-if="!loading && promoPackages.length === 0">
                        <div class="text-center py-12 text-gray-500">
                            <p class="mb-2">📦 Paket promo sedang tidak tersedia</p>
                            <p class="text-sm">Silakan cek halaman toko untuk melihat semua paket</p>
                        </div>
                    </template>
                    
                    <template x-if="!loading && promoPackages.length > 0">
                        <div class="flex" 
                             :class="{ 'transition-transform duration-500 ease-out': !isDragging }"
                             :style="`transform: translateX(calc(-${currentSlide * 100}% + ${touchOffset}px))`"
                             @touchstart="handleTouchStart($event)"
                             @touchmove.window="handleTouchMove($event)"
                             @touchend.window="handleTouchEnd($event)"
                             style="touch-action: pan-y">
                            <!-- Generate slides dynamically -->
                            <template x-for="slideIndex in totalSlides" :key="slideIndex">
                                <div class="w-full flex-shrink-0 px-1">
                                    <div class="grid grid-cols-3 gap-2">
                                        <!-- 3 cards per slide for mobile -->
                                        <template x-for="cardIndex in 3" :key="cardIndex">
                                            <template x-if="promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)]">
                                                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300 flex flex-col h-full hover:scale-[1.02] cursor-pointer"
                                                     @click="openCheckoutModal(promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)])">
                                                    <div class="relative flex-shrink-0">
                                                        <span class="absolute top-2 right-2 bg-gradient-to-r from-red-600 to-red-500 text-white px-2.5 py-1 rounded-full text-[10px] font-extrabold z-10 shadow-lg animate-pulse">🔥 HOT</span>
                                                        <div class="h-24 flex items-center justify-center bg-gray-100">
                                                            <img :src="'/images/' + getProviderImage(promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)].provider)" 
                                                                 :alt="promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)].provider"
                                                                 class="h-full w-full object-cover">
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col flex-grow">
                                                        <div class="p-3 pb-0 flex-grow">
                                                            <h3 class="font-extrabold text-xs text-gray-900 leading-tight mb-1.5" x-text="getPackageTitle(promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)])"></h3>
                                                        </div>

                                                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-2 pt-1.5 mt-auto">
                                                            <template x-if="promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)].price_app > promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)].price_customer">
                                                                <p class="text-[9px] text-gray-500 line-through mb-0.5" x-text="formatRupiah(promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)].price_app)"></p>
                                                            </template>
                                                            <p class="text-sm font-black text-emerald-600 mb-1.5" x-text="formatRupiah(promoPackages[(slideIndex - 1) * 3 + (cardIndex - 1)].price_customer)"></p>
                                                            
                                                            <div class="block w-full text-center bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white text-[9px] font-bold py-1 px-1 rounded shadow-md hover:shadow-lg transition-all">
                                                                BELI
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Mobile Indicators -->
                <div class="flex justify-center gap-2 mt-4">
                    <template x-for="(slide, index) in totalSlides" :key="index">
                        <button @click="currentSlide = index" 
                                class="w-2 h-2 rounded-full transition-all"
                                :class="currentSlide === index ? 'bg-emerald-600 w-8' : 'bg-gray-300'">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Desktop Grid View (4 columns with horizontal scroll) -->
            <div class="hidden md:block relative group px-4">
                
                <!-- Navigation Arrows -->
                <button class="absolute top-1/2 left-0 -translate-y-1/2 -ml-2 z-20 bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-gray-50 hover:scale-110 transition-all border border-gray-100 opacity-0 group-hover:opacity-100 duration-300"
                        @click="currentSlide = (currentSlide > 0) ? currentSlide - 1 : totalSlides - 1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <button class="absolute top-1/2 right-0 -translate-y-1/2 -mr-2 z-20 bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:bg-gray-50 hover:scale-110 transition-all border border-gray-100 opacity-0 group-hover:opacity-100 duration-300"
                        @click="currentSlide = (currentSlide < totalSlides - 1) ? currentSlide + 1 : 0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <template x-if="loading">
                    <div class="text-center py-12 text-gray-500">Memuat paket promo...</div>
                </template>
                
                <template x-if="!loading">
                    <div class="overflow-hidden py-4 -my-4 px-1 -mx-1">
                        <div class="flex transition-transform duration-500 ease-out" :style="`transform: translateX(-${currentSlide * 100}%)`">
                            <!-- Generate slides dynamically -->
                            <template x-for="slideIndex in totalSlides" :key="slideIndex">
                                <div class="w-full flex-shrink-0">
                                    <div class="grid grid-cols-4 gap-4 px-1">
                                        <!-- 4 cards per slide for desktop -->
                                        <template x-for="cardIndex in 4" :key="cardIndex">
                                            <template x-if="promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)]">
                                                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer flex flex-col h-full group/card"
                                                     @click="openCheckoutModal(promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)])">
                                                    <div class="relative flex-none">
                                                        <!-- Ribbon Badge Best Seller -->
                                                        <div class="absolute top-0 right-0 z-10">
                                                            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white text-[10px] font-black px-3 py-1 rounded-bl-xl shadow-md flex items-center gap-1">
                                                                <svg class="w-3 h-3 text-yellow-100" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                                BEST SELLER
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="h-40 flex items-center justify-center bg-gray-100 overflow-hidden">
                                                            <img :src="'/images/' + getProviderImage(promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)].provider)" 
                                                                 :alt="promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)].provider"
                                                                 class="h-full w-full object-cover group-hover/card:scale-110 transition-transform duration-500">
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow flex flex-col">
                                                        <div class="p-3 flex-grow">
                                                            <p class="text-[10px] text-gray-500 mb-1 uppercase tracking-wider" x-text="getProviderName(promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)].provider)"></p>
                                                            <h3 class="font-bold text-sm text-gray-900 mb-2 leading-snug" x-text="getPackageTitle(promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)])"></h3>
                                                        </div>

                                                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-3 border-t border-gray-100 mt-auto">
                                                            <template x-if="promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)].price_app > promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)].price_customer">
                                                                <p class="text-xs text-gray-400 line-through mb-0.5" x-text="formatRupiah(promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)].price_app)"></p>
                                                            </template>
                                                            <p class="text-xl font-black text-emerald-600 mb-2" x-text="formatRupiah(promoPackages[(slideIndex - 1) * 4 + (cardIndex - 1)].price_customer)"></p>
                                                            
                                                            <div class="block w-full text-center bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white text-xs font-bold py-2 px-3 rounded-lg shadow-md hover:shadow-lg transition-all transform active:scale-95 duration-200">
                                                                BELI SEKARANG
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Indicators -->
                        <div class="flex justify-center gap-2 mt-6">
                            <template x-for="(slide, index) in totalSlides" :key="index">
                                <button @click="currentSlide = index" 
                                        class="w-2 h-2 rounded-full transition-all"
                                        :class="currentSlide === index ? 'bg-emerald-600 w-8' : 'bg-gray-300'">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Checkout Modal Popup (Inside x-data scope) -->
            <div x-show="showCheckoutModal" 
                 x-cloak
                 @click.self="closeCheckoutModal()"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black bg-opacity-50"
                 style="display: none;">
                <div @click.stop 
                     class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden"
                     x-show="showCheckoutModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90">
                    
                    <!-- Header (Fixed) -->
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 p-6 relative flex-shrink-0">
                        <button @click="closeCheckoutModal()" 
                                class="absolute top-4 right-4 text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <h3 class="text-2xl font-bold text-white">Checkout Paket</h3>
                        <p class="text-emerald-100 text-sm mt-1">Lengkapi data untuk melanjutkan</p>
                    </div>

                    <!-- Content (Scrollable) -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <!-- Package Summary -->
                        <template x-if="selectedPackage">
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Ringkasan Pesanan</h4>
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex items-start gap-3 mb-3">
                                        <img :src="'/images/' + getProviderImage(selectedPackage.provider)" 
                                             :alt="selectedPackage.provider"
                                             class="w-16 h-16 object-cover rounded-lg">
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1" x-text="getProviderName(selectedPackage.provider)"></p>
                                            <h5 class="font-bold text-sm text-gray-900 leading-tight" x-text="getPackageTitle(selectedPackage)"></h5>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t border-gray-200 pt-3 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Kuota</span>
                                            <span class="font-medium text-gray-900" x-text="getQuotaDisplay(selectedPackage)"></span>
                                        </div>
                                        <template x-if="getBonusDisplay(selectedPackage)">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Kuota Transit</span>
                                                <span class="font-medium text-gray-900" x-text="getBonusDisplay(selectedPackage)"></span>
                                            </div>
                                        </template>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Masa Aktif</span>
                                            <span class="font-medium text-gray-900" x-text="selectedPackage.days + ' Hari'"></span>
                                        </div>
                                        <template x-if="selectedPackage.price_app > selectedPackage.price_customer">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Harga Normal</span>
                                                <span class="text-gray-400 line-through" x-text="formatRupiah(selectedPackage.price_app)"></span>
                                            </div>
                                        </template>
                                        <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2">
                                            <span class="text-gray-900">Total Bayar</span>
                                            <span class="text-emerald-600" x-text="formatRupiah(selectedPackage.price_customer)"></span>
                                        </div>
                                        <template x-if="selectedPackage.price_app > selectedPackage.price_customer">
                                            <div class="bg-red-50 text-red-600 text-xs px-3 py-1.5 rounded-lg inline-block">
                                                <span class="font-semibold">Hemat </span>
                                                <span x-text="formatRupiah(selectedPackage.price_app - selectedPackage.price_customer)"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Phone Number Input -->
                        <div class="mb-6">
                            <label for="phoneNumber" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input type="tel" 
                                       id="phoneNumber"
                                       x-model="phoneNumber"
                                       :placeholder="getPhonePlaceholder(selectedPackage?.provider)"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                       maxlength="15"
                                       pattern="[0-9]*"
                                       inputmode="numeric">
                            </div>
                            <p class="text-xs text-gray-500 mt-2" x-text="getPhoneHint(selectedPackage?.provider)"></p>
                        </div>

                        <!-- Payment Method Section (QRIS Only) -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Metode Pembayaran
                            </label>
                            <div class="bg-white border-2 border-emerald-500 bg-emerald-50/50 rounded-xl p-4 flex items-center justify-between shadow-sm cursor-pointer hover:bg-emerald-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <!-- QRIS Badge -->
                                    <div class="h-10 w-16 bg-white border border-gray-200 rounded-lg flex items-center justify-center p-1">
                                        <img src="{{ asset('images/qris_logo.png') }}" alt="QRIS" class="h-full w-full object-contain">
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-gray-900 leading-none">QRIS</h6>
                                        <p class="text-xs text-emerald-600 mt-0.5 font-medium">Scan QR Code</p>
                                    </div>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-emerald-600 flex items-center justify-center bg-emerald-600">
                                     <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer (Fixed) -->
                    <div class="p-6 border-t border-gray-200 bg-white flex-shrink-0">
                        <button @click="proceedToCheckout()"
                                class="w-full bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Lanjut ke Checkout</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Custom Alert Modal -->
            <div x-show="showAlertModal" 
                 x-cloak
                 @click.self="showAlertModal = false"
                 class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black bg-opacity-50"
                 style="display: none;">
                <div @click.stop 
                     class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden"
                     x-show="showAlertModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90">
                    
                    <!-- Icon -->
                    <div class="bg-red-50 p-6 flex justify-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-3" x-text="alertTitle"></h3>
                        <p class="text-gray-600 whitespace-pre-line" x-text="alertMessage"></p>
                    </div>

                    <!-- Button -->
                    <div class="p-6 pt-0">
                        <button @click="showAlertModal = false"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition-colors">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Provider Selection Section -->
        <div id="pilih-provider" class="mb-16 md:mb-20 scroll-mt-24">
            <div class="mb-6 md:mb-10">
                <h2 class="text-2xl md:text-4xl font-bold mb-2 md:mb-3 text-gray-900">Pilih Paket Anda</h2>
            </div>

            <!-- Provider Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-6">

                <!-- Telkomsel Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/Telkomsel.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ route('agent.store.direct', ['link_referal' => $agent->link_referal ?? 'kuotaumroh']) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- Indosat / IM3 Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/Indosat.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ route('agent.store.direct', ['link_referal' => $agent->link_referal ?? 'kuotaumroh']) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- XL Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/XL.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ route('agent.store.direct', ['link_referal' => $agent->link_referal ?? 'kuotaumroh']) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- Axis Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/AXIS.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ route('agent.store.direct', ['link_referal' => $agent->link_referal ?? 'kuotaumroh']) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- Tri Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/3.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ route('agent.store.direct', ['link_referal' => $agent->link_referal ?? 'kuotaumroh']) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- by.U Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/ByU.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ route('agent.store.direct', ['link_referal' => $agent->link_referal ?? 'kuotaumroh']) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Benefits Section -->
        <h2 class="text-xl md:text-2xl font-bold text-center mb-6 md:mb-8 mt-12 md:mt-16">Kenapa harus membeli Kuota Umroh & Haji di Kuotaumroh.id?</h2>
        <div class="mt-8 md:mt-16 grid gap-4 md:gap-6 md:grid-cols-3">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 mb-4">
                    <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Proses Cepat</h3>
                <p class="text-sm text-muted-foreground">Aktivasi otomatis dalam hitungan menit</p>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 mb-4">
                    <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Aman & Terpercaya</h3>
                <p class="text-sm text-muted-foreground">Transaksi aman dengan sistem pembayaran terenkripsi</p>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 mb-4">
                    <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Dukungan 24/7</h3>
                <p class="text-sm text-muted-foreground">Tim support siap membantu kapan saja</p>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-12 md:mt-16 max-w-4xl mx-auto">
            <h2 class="text-xl md:text-2xl font-bold text-center mb-6 md:mb-8">Pertanyaan yang Sering Diajukan</h2>
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow-sm border" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-semibold text-lg">Apakah semua nomor bisa membeli Kuota Umroh Haji?</span>
                        <svg class="h-5 w-5 transform transition-transform duration-200 text-muted-foreground" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6 text-muted-foreground border-t pt-4">
                            Kuota Umroh Haji hanya berlaku untuk nomor prabayar. Kecuali provider XL, Axis, Smartfren, dan Indosat.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-semibold text-lg">Kenapa saya tidak bisa membeli Kuota Umroh Haji?</span>
                        <svg class="h-5 w-5 transform transition-transform duration-200 text-muted-foreground" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6 text-muted-foreground border-t pt-4">
                            <p class="mb-2">Beberapa kemungkinan penyebab:</p>
                            <ul class="list-disc list-inside ml-2">
                                <li>Nomor HP yang dimasukkan salah atau sudah tidak aktif</li>
                                <li>Terjadi gangguan sementara pada sistem operator</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-semibold text-lg">Setelah pembayaran, Kuota Umroh Haji belum masuk. Apa yang harus saya lakukan?</span>
                        <svg class="h-5 w-5 transform transition-transform duration-200 text-muted-foreground" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6 text-muted-foreground border-t pt-4">
                            Mohon tunggu maksimal 90 menit setelah pembayaran terverifikasi. Jika Anda memilih Aktifkan Sesuai Jadwal, maka paket akan otomatis aktif pada tanggal dan waktu yang telah Anda tentukan. Jika paket belum aktif di luar jadwal tersebut, silakan lakukan pengecekan manual sesuai provider Anda.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-semibold text-lg">Bagaimana cara cek Kuota Umroh Haji saya?</span>
                        <svg class="h-5 w-5 transform transition-transform duration-200 text-muted-foreground" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6 text-muted-foreground border-t pt-4">
                            <ul class="space-y-1">
                                <li><span class="font-medium text-gray-700">Telkomsel / by.U:</span> *888#</li>
                                <li><span class="font-medium text-gray-700">Indosat / Tri:</span> *123#</li>
                                <li><span class="font-medium text-gray-700">XL / AXIS:</span> *808#</li>
                                <li><span class="font-medium text-gray-700">Smartfren:</span> *995#</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-semibold text-lg">Transaksi gagal, apakah dana saya hangus?</span>
                        <svg class="h-5 w-5 transform transition-transform duration-200 text-muted-foreground" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6 text-muted-foreground border-t pt-4">
                            Tidak. Jika transaksi gagal, dana akan dikembalikan sesuai metode pembayaran yang digunakan maksimal 1x24 jam hari kerja.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-semibold text-lg">Bagaimana jika Kuota Umroh Haji masih belum masuk setelah dicek?</span>
                        <svg class="h-5 w-5 transform transition-transform duration-200 text-muted-foreground" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6 text-muted-foreground border-t pt-4">
                            Silakan hubungi Customer Service Kuota Umroh melalui WhatsApp untuk bantuan lebih lanjut.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-semibold text-lg">Operator apa saja yang tersedia untuk Kuota Umroh Haji?</span>
                        <svg class="h-5 w-5 transform transition-transform duration-200 text-muted-foreground" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-6 text-muted-foreground border-t pt-4">
                            <p class="mb-2">Kuota Umroh Haji tersedia untuk operator berikut:</p>
                            <ul class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                <li>• Telkomsel</li>
                                <li>• Indosat</li>
                                <li>• XL</li>
                                <li>• AXIS</li>
                                <li>• Tri (3)</li>
                                <li>• Smartfren</li>
                                <li>• by.U</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-primary text-primary-foreground mt-16 pt-12 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id" class="h-10 w-10 object-contain">
                        <span class="text-2xl font-bold">Kuotaumroh.id</span>
                    </div>
                    <p class="max-w-md text-primary-foreground/90">
                        Kuota Umroh menyediakan layanan internet stabil dan aman selama di Tanah Suci. 
                        Mendukung kebutuhan komunikasi jamaah umroh dan haji Indonesia.
                    </p>
                </div>

                <!-- Right Column -->
                <div class="md:text-right space-y-4">
                        <div>
                            <h3 class="font-bold text-lg mb-2">Customer Service</h3>
                            <div class="space-y-1">
                                <p>Email: info@digilabsmitrasolusi.com</p>
                                <p>Wa: +62 8112-994-499</p>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
            </div>
            
            <div class="border-t border-primary-foreground/20 pt-8 text-center text-sm text-primary-foreground/80">
                <p>© 2026 Kuotaumroh.id. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/628112994499" target="_blank"
       class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 bg-[#25D366] rounded-full shadow-lg hover:bg-[#20bd5a] transition-colors hover:scale-105 transform duration-200"
       title="Hubungi Customer Service">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>



    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('promoCarousel', () => ({
                currentSlide: 0,
                totalSlides: 4,
                interval: null,
                promoPackages: [],
                loading: true,
                showCheckoutModal: false,
                selectedPackage: null,
                phoneNumber: '',
                
                openCheckoutModal(pkg) {
                    console.log('Opening checkout modal with package:', pkg);
                    this.selectedPackage = pkg;
                    this.phoneNumber = '';
                    this.showCheckoutModal = true;
                    this.stopAutoSlide();
                    console.log('Modal state:', this.showCheckoutModal);
                },
                
                closeCheckoutModal() {
                    this.showCheckoutModal = false;
                    this.selectedPackage = null;
                    this.phoneNumber = '';
                    this.startAutoSlide();
                },
                
                proceedToCheckout() {
                    // Validasi nomor telepon
                    if (!this.phoneNumber || this.phoneNumber.length < 10) {
                        this.showAlert('Perhatian', 'Mohon masukkan nomor telepon yang valid (minimal 10 digit)');
                        return;
                    }
                    
                    // Validasi nomor sesuai provider
                    const provider = this.selectedPackage.provider.toUpperCase();
                    const phone = this.phoneNumber;
                    const validationResult = this.validatePhoneByProvider(provider, phone);
                    
                    if (!validationResult.valid) {
                        this.showAlert('Nomor Tidak Sesuai', validationResult.message);
                        return;
                    }
                    
                    // Format data untuk checkout page
                    const pkg = this.selectedPackage;
                    const packageName = `${this.getProviderName(pkg.provider)} - ${this.getPackageTitle(pkg)}`;
                    
                    const orderData = {
                        items: [{
                            packageId: pkg.id,
                            package_id: pkg.id,
                            packageName: packageName,
                            price: pkg.price_customer,
                            msisdn: this.phoneNumber,
                            phoneNumber: this.phoneNumber,
                            quota: pkg.quota,
                            bonus: pkg.bonus || '',
                            days: pkg.days,
                            provider: pkg.provider
                        }],
                        subtotal: pkg.price_customer,
                        total: pkg.price_customer,
                        platformFee: 0,
                        uniqueCode: 0,
                        paymentMethod: 'qris',
                        refCode: STORE_CONFIG?.link_referal || 'kuotaumroh',
                        agent_id: STORE_CONFIG?.agent_id || null, // Add agent_id for payment
                        scheduleDate: null,
                        isBulk: false
                    };
                    
                    // Simpan ke localStorage
                    localStorage.setItem('pendingOrder', JSON.stringify(orderData));
                    console.log('💾 Order data saved:', orderData);
                    
                    // Redirect ke halaman checkout public
                    window.location.href = '{{ route("checkout") }}';
                },
                
                // --- Custom Alert ---
                showAlertModal: false,
                alertTitle: '',
                alertMessage: '',
                
                showAlert(title, message) {
                    this.alertTitle = title;
                    this.alertMessage = message;
                    this.showAlertModal = true;
                },

                // --- Helper Functions ---
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { 
                        style: 'currency', 
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(number);
                },
                
                getProviderImage(provider) {
                    const images = {
                        'TELKOMSEL': 'telkomsel.png',
                        'XL': 'xl.png',
                        'INDOSAT': 'indosat.png',
                        'AXIS': 'axis.png',
                        'TRI': 'tri.png',
                        'SMARTFREN': 'smartfren.png',
                        'BYU': 'byu.png'
                    };
                    return images[provider?.toUpperCase()] || 'default.png';
                },
                
                getProviderName(provider) {
                    const names = {
                        'TELKOMSEL': 'Telkomsel',
                        'XL': 'XL Axiata',
                        'INDOSAT': 'Indosat Ooredoo',
                        'AXIS': 'Axis',
                        'TRI': 'Tri (3)',
                        'SMARTFREN': 'Smartfren',
                        'BYU': 'by.U'
                    };
                    return names[provider?.toUpperCase()] || provider;
                },
                
                getPhonePlaceholder(provider) {
                    if (!provider) return 'Contoh: 08123456789';
                    
                    const placeholders = {
                        'TELKOMSEL': 'Contoh: 0811xxxxxxxx',
                        'XL': 'Contoh: 0817xxxxxxxx',
                        'INDOSAT': 'Contoh: 0814xxxxxxxx',
                        'AXIS': 'Contoh: 0831xxxxxxxx',
                        'TRI': 'Contoh: 0895xxxxxxxx',
                        'SMARTFREN': 'Contoh: 0881xxxxxxxx',
                        'BYU': 'Contoh: 0851xxxxxxxx'
                    };
                    
                    return placeholders[provider.toUpperCase()] || 'Contoh: 08123456789';
                },
                
                getPhoneHint(provider) {
                    if (!provider) return 'Nomor yang akan diaktifkan paketnya';
                    
                    const hints = {
                        'TELKOMSEL': 'Gunakan nomor Telkomsel (0811/0812/0813/0821/0822/0823/0852/0853)',
                        'XL': 'Gunakan nomor XL (0817/0818/0819/0859/0877/0878)',
                        'INDOSAT': 'Gunakan nomor Indosat (0814/0815/0816/0855/0856/0857/0858)',
                        'AXIS': 'Gunakan nomor Axis (0831/0832/0833/0838)',
                        'TRI': 'Gunakan nomor Tri (0895/0896/0897/0898/0899)',
                        'SMARTFREN': 'Gunakan nomor Smartfren (0881/0882/0883/0884/0885/0886/0887/0888/0889)',
                        'BYU': 'Gunakan nomor by.U (0851)'
                    };
                    
                    return hints[provider.toUpperCase()] || 'Nomor yang akan diaktifkan paketnya';
                },
                
                validatePhoneByProvider(provider, phone) {
                    // Mapping provider ke prefix nomor
                    const providerPrefixes = {
                        'TELKOMSEL': ['0811', '0812', '0813', '0821', '0822', '0823', '0852', '0853'],
                        'XL': ['0817', '0818', '0819', '0859', '0877', '0878'],
                        'INDOSAT': ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
                        'AXIS': ['0831', '0832', '0833', '0838'],
                        'TRI': ['0895', '0896', '0897', '0898', '0899'],
                        'SMARTFREN': ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
                        'BYU': ['0851']  // by.U menggunakan prefix Telkomsel
                    };
                    
                    const prefixes = providerPrefixes[provider];
                    if (!prefixes) {
                        return { valid: true };
                    }
                    
                    const isValid = prefixes.some(prefix => phone.startsWith(prefix));
                    
                    if (!isValid) {
                        const providerName = this.getProviderName(provider);
                        const examplePrefix = prefixes[0];
                        return {
                            valid: false,
                            message: `Nomor telepon tidak sesuai dengan provider ${providerName}.\n\nGunakan nomor yang dimulai dengan: ${prefixes.join(', ')}\n\nContoh: ${examplePrefix}xxxxxxxx`
                        };
                    }
                    
                    return { valid: true };
                },

                // --- Carousel Logic ---
                startX: 0,
                isDragging: false,
                touchOffset: 0,
                
                handleTouchStart(e) {
                    this.startX = e.touches[0].clientX;
                    this.isDragging = true;
                    this.touchOffset = 0;
                    this.stopAutoSlide();
                },
                
                handleTouchMove(e) {
                    if (!this.isDragging) return;
                    const currentX = e.touches[0].clientX;
                    this.touchOffset = currentX - this.startX;
                },
                
                handleTouchEnd(e) {
                    if (!this.isDragging) return;
                    
                    if (Math.abs(this.touchOffset) > 50) { 
                        if (this.touchOffset > 0) {
                            this.prevSlide();
                        } else {
                            this.nextSlide();
                        }
                    }
                    
                    this.isDragging = false;
                    this.touchOffset = 0;
                    this.startAutoSlide();
                },

                async init() {
                    this.updateTotalSlides();
                    window.addEventListener('resize', () => {
                        this.updateTotalSlides();
                    });
                    
                    // Load promo packages from API
                    await this.loadPromoPackages();
                    
                    this.startAutoSlide();
                },
                
                async loadPromoPackages() {
                    try {
                        this.loading = true;
                        const baseUrl = `${API_BASE_URL}/api/proxy/umroh/package`;
                        const agentId = STORE_CONFIG?.agent_id;
                        let apiUrl = baseUrl;

                        console.log('🎪 Carousel: Loading packages for agent:', agentId);

                        // Samakan logic dengan pengambilan paket data (agent store => VIEW pricing)
                        if (agentId && String(agentId).startsWith('AGT')) {
                            apiUrl = `${baseUrl}?agent_id=${encodeURIComponent(agentId)}&context=store`;
                            console.log('🎪 Carousel: Using agent pricing (store context)');
                        } else {
                            const catalogRefCode = STORE_CONFIG?.catalog_ref_code || '0';
                            apiUrl = `${baseUrl}?ref_code=${encodeURIComponent(catalogRefCode)}`;
                            console.log('🎪 Carousel: Using catalog pricing with ref_code:', catalogRefCode);
                        }

                        console.log('🎪 Carousel: Fetching from:', apiUrl);
                        const response = await fetch(apiUrl);
                        if (!response.ok) throw new Error('Failed to fetch packages');
                        
                        const data = await response.json();
                        console.log('🎪 Carousel: Received', data.length, 'packages from API');
                        
                        if (Array.isArray(data)) {
                            // Map packages - ambil pricing dari VIEW (store context)
                            const allPackages = data
                                .filter(pkg => {
                                    // HANYA tampilkan paket dengan promo "PROMO TERBAIK"
                                    return pkg.promo === 'PROMO TERBAIK';
                                })
                                .map(pkg => {
                                    // UNTUK CAROUSEL: Gunakan toko_harga_coret dan toko_harga_jual (individual/store pricing)
                                    const tokoHargaCoret = parseInt(pkg.toko_harga_coret) || 0;
                                    const tokoHargaJual = parseInt(pkg.toko_harga_jual) || parseInt(pkg.price) || 0;
                                    
                                    return {
                                        id: pkg.id || pkg.package_id,
                                        name: pkg.name || pkg.packageName || pkg.nama_paket || '',
                                        provider: pkg.type || pkg.provider || '',
                                        days: parseInt(pkg.days) || parseInt(pkg.masa_aktif) || 0,
                                        quota: pkg.quota || pkg.kuota_utama || '',
                                        kuota_utama: pkg.kuota_utama || pkg.quota || '',
                                        total_kuota: pkg.total_kuota || '',
                                        bonus: pkg.bonus || pkg.kuota_bonus || '',
                                        kuota_bonus: pkg.kuota_bonus || pkg.bonus || '',
                                        price_app: tokoHargaCoret, // Harga coret dari VIEW (0 = tidak ada harga coret)
                                        price_customer: tokoHargaJual, // Harga jual final
                                        subType: pkg.sub_type || pkg.tipe_paket || '',
                                        promo: pkg.promo || null,
                                    };
                                })
                                .filter(pkg => {
                                    // Filter out packages with invalid pricing (price_customer must be > 0)
                                    return pkg.price_customer > 0;
                                });
                            
                            console.log('🎪 Carousel: After filtering valid prices:', allPackages.length, 'packages');
            
                            // Ambil SEMUA paket dengan "PROMO TERBAIK" (sudah difilter di atas)
                            this.promoPackages = allPackages;
                            
                            console.log('🎪 Carousel: Final promo packages:', this.promoPackages.length, 'packages');
                            console.log('🎪 Carousel: Package details:', this.promoPackages);
                            
                            this.updateTotalSlides();
                        }
                        
                        this.loading = false;
                    } catch (error) {
                        console.error('Error loading promo packages:', error);
                        this.loading = false;
                    }
                },
                
                getPackageTitle(pkg) {
                    const quotaStr = String(pkg.quota || '');
                    const bonusStr = String(pkg.bonus || '');
                    const days = pkg.days || 0;
                    
                    // Extract numbers from quota and bonus
                    const extractNumber = (str) => {
                        const match = str.match(/(\d+(?:\.\d+)?)/);
                        return match ? parseFloat(match[1]) : 0;
                    };
                    
                    let totalGB = 0;
                    if (quotaStr) totalGB += extractNumber(quotaStr);
                    if (bonusStr) totalGB += extractNumber(bonusStr);
                    
                    return totalGB > 0 ? `Kuota ${totalGB}GB - ${days} Hari` : `${pkg.name}`;
                },

                getQuotaDisplay(pkg) {
                    const quotaStr = String(pkg.quota || '');
                    const bonusStr = String(pkg.bonus || '');
                    
                    // Extract numbers from quota and bonus
                    const extractNumber = (str) => {
                        const match = str.match(/(\d+(?:\.\d+)?)/);
                        return match ? parseFloat(match[1]) : 0;
                    };
                    
                    const quotaNum = extractNumber(quotaStr);
                    const bonusNum = extractNumber(bonusStr);
                    
                    if (quotaNum === 0 && bonusNum === 0) return '';
                    
                    // Format: "49 GB Kuota Arab" if quota exists
                    return quotaNum > 0 ? `${quotaNum} GB Kuota Arab` : '';
                },

                getBonusDisplay(pkg) {
                    const bonusStr = String(pkg.bonus || '');
                    
                    // Extract numbers from bonus
                    const extractNumber = (str) => {
                        const match = str.match(/(\d+(?:\.\d+)?)/);
                        return match ? parseFloat(match[1]) : 0;
                    };
                    
                    const bonusNum = extractNumber(bonusStr);
                    
                    if (bonusNum === 0) return '';
                    
                    // Format: "1 GB Kuota Transit"
                    return `${bonusNum} GB Kuota Transit`;
                },
                
                getProviderImage(provider) {
                    const providerMap = {
                        'TELKOMSEL': 'Telkomsel.png',
                        'XL': 'XL.png',
                        'INDOSAT': 'Indosat.png',
                        'AXIS': 'AXIS.png',
                        'TRI': '3.png',
                        'BYU': 'ByU.png'
                    };
                    return providerMap[provider.toUpperCase()] || 'Telkomsel.png';
                },
                
                getProviderName(provider) {
                    const nameMap = {
                        'TELKOMSEL': 'TELKOMSEL',
                        'XL': 'XL AXIATA',
                        'INDOSAT': 'INDOSAT OOREDOO',
                        'AXIS': 'AXIS',
                        'TRI': 'TRI',
                        'BYU': 'BY.U'
                    };
                    return nameMap[provider.toUpperCase()] || provider;
                },
                
                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount);
                },
                
                updateTotalSlides() {
                    const packagesCount = this.promoPackages.length;
                    if (window.innerWidth >= 768) {
                        // Desktop: 4 cards per slide
                        this.totalSlides = packagesCount > 0 ? Math.ceil(packagesCount / 4) : 2;
                    } else {
                        // Mobile: 3 cards per slide
                        this.totalSlides = packagesCount > 0 ? Math.ceil(packagesCount / 3) : 4;
                    }
                    if (this.currentSlide >= this.totalSlides) {
                        this.currentSlide = 0;
                    }
                },
                
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                
                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },
                
                startAutoSlide() {
                    if (this.interval) clearInterval(this.interval);
                    this.interval = setInterval(() => {
                        this.nextSlide();
                    }, 3000);
                },
                
                stopAutoSlide() {
                    if (this.interval) {
                        clearInterval(this.interval);
                        this.interval = null;
                    }
                }
            }));
        });
        
        // Tracking Order Alpine Component
        function trackingOrder() {
            return {
                showTrackingModal: false,
                paymentId: '',
                loading: false,
                errorMessage: '',
                
                openTrackingModal() {
                    this.showTrackingModal = true;
                    this.paymentId = '';
                    this.errorMessage = '';
                    // Focus input after modal opens
                    this.$nextTick(() => {
                        document.getElementById('payment_id')?.focus();
                    });
                },
                
                closeTrackingModal() {
                    this.showTrackingModal = false;
                    this.paymentId = '';
                    this.errorMessage = '';
                },
                
                async trackOrder() {
                    if (!this.paymentId || this.paymentId.trim() === '') {
                        this.errorMessage = 'Mohon masukkan ID Pembayaran';
                        return;
                    }
                    
                    try {
                        this.loading = true;
                        this.errorMessage = '';
                        
                        console.log('🔍 Tracking payment ID:', this.paymentId);
                        
                        // Validate payment exists via API
                        const response = await fetch(`${API_BASE_URL}/api/pembayaran/${this.paymentId}/status`);
                        
                        if (!response.ok) {
                            if (response.status === 404) {
                                throw new Error('ID Pembayaran tidak ditemukan. Mohon periksa kembali ID yang Anda masukkan.');
                            }
                            throw new Error('Gagal memeriksa status pembayaran. Silakan coba lagi.');
                        }
                        
                        const data = await response.json();
                        console.log('✅ Payment found:', data);
                        
                        // Redirect to invoice page
                        window.location.href = `/invoice/${this.paymentId}`;
                        
                    } catch (error) {
                        console.error('❌ Tracking error:', error);
                        this.errorMessage = error.message || 'Terjadi kesalahan saat melacak pesanan. Silakan coba lagi.';
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>

</html>

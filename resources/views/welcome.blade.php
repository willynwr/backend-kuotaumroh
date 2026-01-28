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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Shared CSS -->
    <link rel="stylesheet" href="{{ asset('shared/styles.css') }}">

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

<body class="min-h-screen bg-background">
    
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
        <div class="container mx-auto flex h-16 items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id" class="h-9 w-9 object-contain">
                <span class="text-xl font-semibold">Kuotaumroh.id</span>
            </div>
        </div>
    </header>

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
    <main class="container mx-auto py-8 lg:py-16 px-4 max-w-7xl">

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
        <div class="mb-8 md:mb-12" x-data="promoCarousel()">
            <div class="flex items-center justify-between mb-4 md:mb-6">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🔥</span>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">Spesial Buat Kamu</h2>
                </div>
                <!-- Navigation Buttons (Desktop) -->
                <div class="hidden md:flex items-center gap-2">
                    <button @click="prevSlide" 
                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button @click="nextSlide" 
                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Carousel View (2 columns per slide) -->
            <div class="md:hidden">
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-out" :style="`transform: translateX(-${currentSlide * 100}%)`">
                        
                        <!-- Slide 1 -->
                        <div class="w-full flex-shrink-0 px-1">
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Mobile Card 1 - Telkomsel -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">PROMO TERBAIK</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/Telkomsel.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">TELKOMSEL</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 50GB - 12 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">49 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">1 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 410.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 400.600</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 9.400</p>
                        </div>
                    </div>
                </a>

                <!-- Mobile Card 2 - XL -->
                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">PROMO TERBAIK</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/XL.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">XL AXIATA</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 70GB - 17 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">68 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">2 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 510.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 498.600</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 11.400</p>
                        </div>
                    </div>
                </a>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="w-full flex-shrink-0 px-1">
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Mobile Card 3 - Indosat -->
                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">PROMO TERBAIK</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/Indosat.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">INDOSAT OOREDOO</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 60GB - 15 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">58 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">1.5 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 450.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 435.000</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 15.000</p>
                        </div>
                    </div>
                </a>

                <!-- Mobile Card 4 - Axis -->
                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">PROMO TERBAIK</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/AXIS.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">AXIS</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 45GB - 10 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">44 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">1 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 380.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 365.000</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 15.000</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

                        <!-- Slide 3 -->
                        <div class="w-full flex-shrink-0 px-1">
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Mobile Card 5 - Tri -->
                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">PAKET HEMAT</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/3.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">TRI</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 55GB - 14 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">53 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">1.5 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 420.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 405.000</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 15.000</p>
                        </div>
                    </div>
                </a>

                <!-- Mobile Card 6 - By.U -->
                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-purple-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">BEST SELLER</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/ByU.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">BY.U</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 52GB - 13 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">50 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">1.3 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 415.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 399.000</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 16.000</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

                        <!-- Slide 4 -->
                        <div class="w-full flex-shrink-0 px-1">
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Mobile Card 7 - Telkomsel 75GB -->
                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-orange-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">SPESIAL</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/Telkomsel.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">TELKOMSEL</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 75GB - 20 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">72 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">2 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 550.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 525.000</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 25.000</p>
                        </div>
                    </div>
                </a>

                <!-- Mobile Card 8 - XL 80GB -->
                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <span class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-[10px] font-bold z-10">HOT DEAL</span>
                        <div class="h-32 bg-cover bg-center" style="background-image: url('{{ asset('images/XL.png') }}');"></div>
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-500 mb-1">XL AXIATA</p>
                        <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">Kuota 80GB - 22 Hari</h3>
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">77 GB Kuota Arab</span>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <svg class="w-3 h-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] text-gray-600">2.2 GB Transit</span>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-xs text-gray-400 line-through mb-1">Rp 580.000</p>
                            <p class="text-lg font-bold text-emerald-600 mb-1">Rp 549.000</p>
                            <p class="text-[10px] text-red-600 bg-red-50 px-2 py-1 rounded inline-block">Hemat Rp 31.000</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

            <!-- Mobile Indicators -->
            <div class="md:hidden flex justify-center gap-2 mt-4">
                <template x-for="(slide, index) in 4" :key="index">
                    <button @click="currentSlide = index" 
                            class="w-2 h-2 rounded-full transition-all"
                            :class="currentSlide === index ? 'bg-emerald-600 w-8' : 'bg-gray-300'">
                    </button>
                </template>
            </div>
        </div>

            <!-- Desktop Grid View (4 columns with horizontal scroll) -->
            <div class="hidden md:block relative">
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-out" :style="`transform: translateX(-${currentSlide * 100}%)`">
                        
                        <!-- Slide 1 -->
                        <div class="w-full flex-shrink-0">
                            <div class="grid grid-cols-4 gap-4">
                                <!-- Desktop Card 1 - Telkomsel -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">PROMO TERBAIK</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/Telkomsel.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">TELKOMSEL</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 50GB - 12 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">49 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">12 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 410.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 400.600</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 9.400</p>
                                        </div>
                                    </div>
                                </a>

                                <!-- Desktop Card 2 - XL -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">PROMO TERBAIK</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/XL.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">XL AXIATA</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 70GB - 17 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">68 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">17 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 510.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 498.600</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 11.400</p>
                                        </div>
                                    </div>
                                </a>

                                <!-- Desktop Card 3 - Indosat -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">PROMO TERBAIK</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/Indosat.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">INDOSAT OOREDOO</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 60GB - 15 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">58 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">15 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 450.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 435.000</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 15.000</p>
                                        </div>
                                    </div>
                                </a>

                                <!-- Desktop Card 4 - Axis -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">PROMO TERBAIK</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/AXIS.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">AXIS</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 45GB - 10 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">44 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">10 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 380.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 365.000</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 15.000</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Slide 2 (tambahan promo) -->
                        <div class="w-full flex-shrink-0">
                            <div class="grid grid-cols-4 gap-4">
                                <!-- Bisa diisi dengan promo lainnya -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">PAKET HEMAT</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/3.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">TRI</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 55GB - 14 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">53 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">14 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 420.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 405.000</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 15.000</p>
                                        </div>
                                    </div>
                                </a>

                                <!-- Duplicate atau tambahkan promo lain di sini -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-purple-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">BEST SELLER</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/ByU.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">BY.U</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 52GB - 13 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">50 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">13 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 415.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 399.000</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 16.000</p>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card tambahan -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">SPESIAL</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/Telkomsel.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">TELKOMSEL</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 75GB - 20 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">72 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">20 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 550.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 525.000</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 25.000</p>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card tambahan -->
                                <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                   class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                    <div class="relative">
                                        <span class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold z-10">HOT DEAL</span>
                                        <div class="h-48 bg-cover bg-center" style="background-image: url('{{ asset('images/XL.png') }}');"></div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500 mb-1">XL AXIATA</p>
                                        <h3 class="font-bold text-base text-gray-900 mb-3 min-h-[3rem]">Kuota 80GB - 22 Hari</h3>
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">77 GB Kuota Arab</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-4">
                                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">22 hari</span>
                                        </div>
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-400 line-through mb-1">Rp 580.000</p>
                                            <p class="text-2xl font-bold text-emerald-600 mb-2">Rp 549.000</p>
                                            <p class="text-xs text-red-600 bg-red-50 px-3 py-1.5 rounded inline-block">Hemat Rp 31.000</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Indicators -->
                <div class="flex justify-center gap-2 mt-6">
                    <template x-for="(slide, index) in 2" :key="index">
                        <button @click="currentSlide = index" 
                                class="w-2 h-2 rounded-full transition-all"
                                :class="currentSlide === index ? 'bg-emerald-600 w-8' : 'bg-gray-300'">
                        </button>
                    </template>
                </div>
            </div>
        </div>

            <!-- Desktop Carousel View -->
            <div class="hidden md:block" x-data="promoCarousel()">
                <div class="relative overflow-hidden rounded-2xl">
                    <!-- Carousel Container -->
                    <div class="flex transition-transform duration-500 ease-out" :style="`transform: translateX(-${currentSlide * 100}%)`">
                        
                        <!-- Promo Card 1 -->
                        <div class="w-full flex-shrink-0 px-2">
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl shadow-lg overflow-hidden">
                                <div class="p-6 md:p-8">
                                    <div class="flex flex-col md:flex-row items-center gap-6">
                                        <div class="flex-1">
                                            <div class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold mb-3">
                                                HEMAT 30%
                                            </div>
                                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                                Paket Telkomsel Spesial
                                            </h3>
                                            <p class="text-gray-600 mb-4">
                                                50GB Kuota Arab + 1GB Transit - 12 Hari
                                            </p>
                                            <div class="flex items-baseline gap-2 mb-4">
                                                <span class="text-gray-400 line-through text-lg">Rp 410.000</span>
                                                <span class="text-3xl font-bold text-red-600">Rp 400.600</span>
                                            </div>
                                            <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                               class="inline-flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                                                Beli Sekarang
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="w-full md:w-64 h-48 bg-cover bg-center rounded-xl" 
                                             style="background-image: url('{{ asset('images/Telkomsel.png') }}');">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Promo Card 2 -->
                        <div class="w-full flex-shrink-0 px-2">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg overflow-hidden">
                                <div class="p-6 md:p-8">
                                    <div class="flex flex-col md:flex-row items-center gap-6">
                                        <div class="flex-1">
                                            <div class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold mb-3">
                                                PROMO TERBATAS
                                            </div>
                                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                                XL Axiata Hemat
                                            </h3>
                                            <p class="text-gray-600 mb-4">
                                                68GB Kuota Arab + 2GB Transit - 17 Hari
                                            </p>
                                            <div class="flex items-baseline gap-2 mb-4">
                                                <span class="text-gray-400 line-through text-lg">Rp 510.000</span>
                                                <span class="text-3xl font-bold text-blue-600">Rp 498.600</span>
                                            </div>
                                            <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                               class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                                Beli Sekarang
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="w-full md:w-64 h-48 bg-cover bg-center rounded-xl" 
                                             style="background-image: url('{{ asset('images/XL.png') }}');">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Promo Card 3 -->
                        <div class="w-full flex-shrink-0 px-2">
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-lg overflow-hidden">
                                <div class="p-6 md:p-8">
                                    <div class="flex flex-col md:flex-row items-center gap-6">
                                        <div class="flex-1">
                                            <div class="inline-block bg-purple-500 text-white px-3 py-1 rounded-full text-sm font-bold mb-3">
                                                SUPER HEMAT
                                            </div>
                                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                                                Indosat Ooredoo
                                            </h3>
                                            <p class="text-gray-600 mb-4">
                                                60GB Kuota Arab + 1.5GB Transit - 15 Hari
                                            </p>
                                            <div class="flex items-baseline gap-2 mb-4">
                                                <span class="text-gray-400 line-through text-lg">Rp 450.000</span>
                                                <span class="text-3xl font-bold text-purple-600">Rp 435.000</span>
                                            </div>
                                            <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" 
                                               class="inline-flex items-center gap-2 bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-colors">
                                                Beli Sekarang
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="w-full md:w-64 h-48 bg-cover bg-center rounded-xl" 
                                             style="background-image: url('{{ asset('images/Indosat.png') }}');">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Navigation Buttons -->
                    <button @click="prevSlide" 
                            class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition-all z-10">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button @click="nextSlide" 
                            class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white p-2 rounded-full shadow-lg transition-all z-10">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <!-- Indicators -->
                <div class="flex justify-center gap-2 mt-4">
                    <template x-for="(slide, index) in totalSlides" :key="index">
                        <button @click="currentSlide = index" 
                                class="w-2 h-2 rounded-full transition-all"
                                :class="currentSlide === index ? 'bg-red-600 w-8' : 'bg-gray-300'">
                        </button>
                    </template>
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
                        <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- Indosat / IM3 Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/Indosat.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- XL Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/XL.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- Axis Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/AXIS.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- Tri Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/3.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
                            Pilih Paket
                        </a>
                    </div>
                </div>

                <!-- by.U Card -->
                <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden bg-white">
                    <div class="h-40 sm:h-48 md:h-56 bg-cover bg-center" style="background-image: url('{{ asset('images/ByU.png') }}');"></div>
                    <div class="px-4 py-3 flex justify-center">
                        <a href="{{ session('pending_agent_link') ? route('agent.store.redirect') : '/u/kuotaumroh' }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 text-white font-medium text-sm py-2 px-6 w-full sm:w-auto hover:bg-emerald-800 transition-colors shadow">
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
                            <h3 class="font-bold text-lg mb-2">Alamat</h3>
                            <p class="text-primary-foreground/90 leading-relaxed">
                                Griya Candramas 3 Blok is nomor 31, <br>
                                Desa/Kelurahan Pepe, Kec. Sedati,<br>
                                Kab. Sidoarjo, Provinsi Jawa Timur,<br>
                                Kode Pos: 61253
                            </p>
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
        function promoCarousel() {
            return {
                currentSlide: 0,
                totalSlides: 4,
                autoplayInterval: null,

                init() {
                    this.startAutoplay();
                },

                startAutoplay() {
                    this.autoplayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 5000); // Auto slide every 5 seconds
                },

                stopAutoplay() {
                    if (this.autoplayInterval) {
                        clearInterval(this.autoplayInterval);
                    }
                },

                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },

                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },

                destroy() {
                    this.stopAutoplay();
                }
            }
        }
    </script>

</body>

</html>

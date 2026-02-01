<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agent->nama_travel ?? 'Toko Agent' }} - Kuotaumroh.id</title>

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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Shared CSS -->
    <link rel="stylesheet" href="{{ asset('shared/styles.css') }}">

    <!-- ⚠️ PENTING: Load config.js PERTAMA sebelum script lain -->
    <script src="{{ asset('shared/config.js') }}?v={{ time() }}"></script>

    <!-- Shared Scripts -->
    <script src="{{ asset('shared/utils.js') }}?v={{ time() }}"></script>
    
    <!-- Store Config -->
    <script>
        const STORE_CONFIG = {
            agent_id: '{{ $agent->id ?? "AGT00001" }}',   // Agent ID format AGTxxxxx untuk PackagePricingService
            catalog_ref_code: '{{ $agent->link_referal ?? "kuotaumroh" }}',  // ref_code=link_referal untuk agent/referral
            link_referal: '{{ $agent->link_referal ?? "kuotaumroh" }}',
            nama_travel: '{{ $agent->nama_travel ?? "Kuotaumroh.id" }}',
            is_individual: true,                         // Flag untuk mode individu (tanpa login)
        };
    </script>
    
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
                        destructive: {
                            DEFAULT: "hsl(var(--destructive))",
                            foreground: "hsl(var(--destructive-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                        accent: {
                            DEFAULT: "hsl(var(--accent))",
                            foreground: "hsl(var(--accent-foreground))",
                        },
                        card: {
                            DEFAULT: "hsl(var(--card))",
                            foreground: "hsl(var(--card-foreground))",
                        },
                    },
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    borderRadius: {
                        lg: "var(--radius)",
                        md: "calc(var(--radius) - 2px)",
                        sm: "calc(var(--radius) - 4px)",
                    },
                },
            },
        }
    </script>
</head>

<body class="min-h-screen bg-background">
    <div x-data="publicOrderApp()">

        <!-- Header dengan Info Agent -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <div class="flex items-center gap-3">
                    @if($agent->logo)
                        <img src="{{ Storage::url($agent->logo) }}" alt="{{ $agent->nama_travel }}" class="h-10 w-10 object-contain rounded-full border">
                    @else
                        <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id" class="h-9 w-9 object-contain">
                    @endif
                    <div>
                        <span class="text-lg font-semibold">{{ $agent->nama_travel ?? 'Toko Agent' }}</span>
                        <p class="text-xs text-muted-foreground">{{ $agent->nama_pic }}</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Background Image -->
        <div class="relative w-full h-[200px] overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/image.png') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-background"></div>
        </div>

        <!-- Main Content -->
        <main class="container mx-auto py-8 px-4 max-w-6xl animate-fade-in -mt-[50px] relative z-10">

            <!-- Hero Section dengan Info Agent -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold tracking-tight mb-4">Paket Internet Umroh & Haji</h1>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                    Dapatkan kuota internet terbaik untuk perjalanan umroh dan haji Anda. Proses cepat, harga terjangkau.
                </p>
                @if(!in_array($agent->nama_travel, ['Kuotaumroh.id', 'Kuota Umroh', 'Kuotaumroh']))
                <div class="mt-4 inline-flex items-center gap-2 bg-primary/10 px-4 py-2 rounded-full text-sm text-primary">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Dilayani oleh: {{ $agent->nama_travel }}
                </div>
                @endif
            </div>

            <!-- Order Form (sama dengan welcome.blade.php) -->
            <div class="grid gap-6 lg:grid-cols-3">

                <!-- Form Section (2/3) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Step 1: Phone Number -->
                    <div class="rounded-lg border bg-white shadow-sm">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold flex items-center gap-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold">1</span>
                                Nomor HP
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-2">
                                <label for="msisdn" class="text-sm font-medium">Masukkan Nomor HP</label>
                                <input id="msisdn" type="text" x-model="msisdn" @input="handleMsisdnInput($event)"
                                    placeholder="Contoh: 081234567890"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    :class="errorMessage ? 'border-red-500 focus:ring-red-500' : ''">
                                <p x-show="errorMessage" x-text="errorMessage" class="text-xs text-red-500 font-medium"></p>
                                <p x-show="!errorMessage" class="text-xs text-muted-foreground">Format: 08xxxxxxxxxx</p>
                            </div>

                            <!-- Provider Badge -->
                            <div x-show="provider" class="flex items-center gap-2">
                                <span class="text-sm text-muted-foreground">Provider:</span>
                                <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary"
                                    x-text="provider"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Select Package -->
                    <div class="rounded-lg border bg-white shadow-sm"
                        :class="!provider && 'opacity-50 pointer-events-none'">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold flex items-center gap-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold">2</span>
                                Pilih Paket
                            </h2>
                        </div>
                        <div class="p-6">
                            <template x-if="packagesLoading">
                                <div class="text-center py-8 text-muted-foreground">
                                    Memuat paket...
                                </div>
                            </template>

                            <template x-if="!packagesLoading && provider">
                                <div>
                                    <!-- Search & Filters -->
                                    <div class="p-4 border-b space-y-3">
                                        <!-- Search -->
                                        <div class="relative w-full">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <input type="text" x-model="packageSearch" placeholder="Cari paket"
                                                class="w-full h-10 pl-10 pr-4 rounded-md border border-input bg-background text-sm">
                                        </div>

                                        <!-- SubType Filters as pills -->
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="filter in subTypeFilters" :key="filter.value">
                                                <button @click="selectedSubTypeFilter = filter.value"
                                                    :class="selectedSubTypeFilter === filter.value ? 'bg-primary text-primary-foreground' : 'bg-muted hover:bg-muted/80'"
                                                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap"
                                                    x-text="filter.label"></button>
                                            </template>
                                        </div>

                                        <!-- Duration Filters -->
                                        <div class="flex gap-2 flex-wrap">
                                            <template x-for="filter in durationFilters" :key="filter.value">
                                                <button @click="selectedDurationFilter = filter.value"
                                                    :class="selectedDurationFilter === filter.value ? 'bg-primary text-primary-foreground' : 'bg-muted hover:bg-muted/80'"
                                                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
                                                    x-text="filter.label"></button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Package Cards (sama dengan welcome.blade.php) -->
                                    <div class="p-4">
                                        <template x-if="filteredPackages.length === 0">
                                            <div class="text-center py-8 text-muted-foreground">
                                                <p>Tidak ada paket ditemukan</p>
                                            </div>
                                        </template>

                                        <div x-show="filteredPackages.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <template x-for="pkg in filteredPackages" :key="pkg.id">
                                                <div @click="selectPackage(pkg)"
                                                    class="border rounded-lg p-4 hover:shadow-md transition-shadow bg-white cursor-pointer"
                                                    :class="selectedPackage?.id === pkg.id ? 'border-primary bg-primary/5' : 'border-border'">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <span class="text-sm font-semibold text-gray-700" x-text="provider"></span>
                                                        <span x-show="pkg.promo"
                                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold text-white shadow-sm"
                                                            :class="{
                                                                'bg-red-500': pkg.promo && pkg.promo.toLowerCase().includes('promo'),
                                                                'bg-amber-500': pkg.promo && pkg.promo.toLowerCase().includes('best'),
                                                                'bg-blue-500': !pkg.promo || (!pkg.promo.toLowerCase().includes('promo') && !pkg.promo.toLowerCase().includes('best'))
                                                            }"
                                                            x-text="pkg.promo"></span>
                                                    </div>

                                                    <h3 x-show="getPackageTitle(pkg)" class="font-bold text-lg mb-3 text-gray-900"
                                                        x-text="getPackageTitle(pkg)"></h3>

                                                    <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                                                        <template x-if="pkg.quota && (!pkg.subType || (pkg.subType && String(pkg.subType).toUpperCase().includes('INTERNET')))">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'kuota') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                                                </svg>
                                                                <span x-text="getQuotaDisplay(pkg)"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.telp && pkg.subType && !String(pkg.subType).toUpperCase().includes('INTERNET')">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'telp') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                </svg>
                                                                <span x-text="pkg.telp + ' menit'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.sms && pkg.subType && !String(pkg.subType).toUpperCase().includes('INTERNET')">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'sms') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                                </svg>
                                                                <span x-text="pkg.sms + ' SMS'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.quota && pkg.subType && !String(pkg.subType).toUpperCase().includes('INTERNET')">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'kuota') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                                                </svg>
                                                                <span x-text="pkg.quota"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.bonus">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'bonus') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                                                </svg>
                                                                <span x-text="getBonusDisplay(pkg)"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.telp && (!pkg.subType || (pkg.subType && String(pkg.subType).toUpperCase().includes('INTERNET')))">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'telp') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                </svg>
                                                                <span x-text="pkg.telp + ' menit'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.sms && (!pkg.subType || (pkg.subType && String(pkg.subType).toUpperCase().includes('INTERNET')))">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'sms') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                                </svg>
                                                                <span x-text="pkg.sms + ' SMS'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.days">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'hari') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <span x-text="pkg.days + ' hari'"></span>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <div class="mb-3">
                                                        <!-- Original Price (Strikethrough) if price_app exists and different from price_customer -->
                                                        <template x-if="pkg.price_app && pkg.price_app > pkg.price_customer">
                                                            <p class="text-2l text-gray-500 line-through mb-1" x-text="formatRupiah(pkg.price_app)"></p>
                                                        </template>
                                                        <!-- Discounted Price (price_customer) -->
                                                        <p class="text-2xl font-bold text-primary" x-text="formatRupiah(pkg.sellPrice || pkg.price)"></p>
                                                        <!-- Discount Badge -->
                                                        <template x-if="pkg.price_app && pkg.price_app > pkg.price_customer">
                                                            <span class="inline-block mt-1 bg-red-100 text-red-600 text-xs font-semibold px-2 py-0.5 rounded"
                                                                x-text="'Hemat ' + formatRupiah(pkg.price_app - pkg.price_customer)"></span>
                                                        </template>
                                                    </div>

                                                    <div x-show="selectedPackage?.id === pkg.id" class="flex items-center gap-2 text-primary text-sm font-medium">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Dipilih</span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Step 3: Activation Time -->
                    <div class="rounded-lg border bg-white shadow-sm"
                        :class="!selectedPackage && 'opacity-50 pointer-events-none'">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold flex items-center gap-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold">3</span>
                                Waktu Aktivasi
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Tabs for Activation Type -->
                            <div class="grid grid-cols-2 p-1 bg-muted rounded-lg">
                                <button @click="activationTime = 'now'"
                                    :class="activationTime === 'now' ? 'bg-white text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                                    class="py-2.5 text-sm font-semibold rounded-md transition-all">
                                    AKTIFKAN LANGSUNG
                                </button>
                                <button @click="activationTime = 'scheduled'"
                                    :class="activationTime === 'scheduled' ? 'bg-white text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                                    class="py-2.5 text-sm font-semibold rounded-md transition-all">
                                    PILIH JADWAL AKTIVASI
                                </button>
                            </div>

                            <!-- Description Text -->
                            <p class="text-sm text-muted-foreground text-center" x-show="activationTime === 'now'" x-transition>
                                Paket akan aktif segera setelah pembayaran berhasil.
                            </p>

                            <!-- Scheduled Options -->
                            <div x-show="activationTime === 'scheduled'" x-collapse class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label for="scheduledDate" class="text-xs font-semibold text-muted-foreground uppercase">Tanggal</label>
                                        <input id="scheduledDate" type="date" x-model="scheduledDate"
                                            :min="new Date().toISOString().split('T')[0]"
                                            @click="$el.showPicker()"
                                            class="flex h-11 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer">
                                    </div>
                                    <div class="space-y-2">
                                        <label for="scheduledTime" class="text-xs font-semibold text-muted-foreground uppercase">Jam</label>
                                        <input id="scheduledTime" type="time" x-model="scheduledTime"
                                            @click="$el.showPicker()"
                                            class="flex h-11 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer">
                                    </div>
                                </div>

                                <!-- Preset Time Buttons -->
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-muted-foreground uppercase">Waktu Populer</label>
                                    <div class="grid grid-cols-4 gap-2">
                                        <button @click="scheduledTime = '02:00'" 
                                            :class="scheduledTime === '02:00' ? 'border-primary bg-primary/5 text-primary' : 'border-input hover:border-primary/50 hover:text-primary'"
                                            class="py-2 border rounded-md text-sm font-medium transition-colors">
                                            02:00
                                        </button>
                                        <button @click="scheduledTime = '04:00'" 
                                            :class="scheduledTime === '04:00' ? 'border-primary bg-primary/5 text-primary' : 'border-input hover:border-primary/50 hover:text-primary'"
                                            class="py-2 border rounded-md text-sm font-medium transition-colors">
                                            04:00
                                        </button>
                                        <button @click="scheduledTime = '13:00'" 
                                            :class="scheduledTime === '13:00' ? 'border-primary bg-primary/5 text-primary' : 'border-input hover:border-primary/50 hover:text-primary'"
                                            class="py-2 border rounded-md text-sm font-medium transition-colors">
                                            13:00
                                        </button>
                                        <button @click="scheduledTime = '22:00'" 
                                            :class="scheduledTime === '22:00' ? 'border-primary bg-primary/5 text-primary' : 'border-input hover:border-primary/50 hover:text-primary'"
                                            class="py-2 border rounded-md text-sm font-medium transition-colors">
                                            22:00
                                        </button>
                                    </div>
                                </div>
                                
                                <p class="text-xs text-muted-foreground text-center pt-2">
                                    Waktu mengacu pada Waktu Indonesia Barat (WIB)
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Order Summary Sidebar (1/3) -->
                <div class="lg:col-span-1" id="checkoutSummary">
                    <div class="rounded-lg border bg-white shadow-sm sticky top-24">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Ringkasan Pesanan
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Nomor HP</span>
                                    <span class="font-mono" x-text="msisdn || '-'"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground">Paket</span>
                                    <span class="font-medium" x-text="selectedPackage?.name || '-'"></span>
                                </div>
                                
                                {{-- <!-- Cetak Invoice Toggle -->
                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-muted-foreground text-sm">Cetak Invoice</span>
                                    <button @click="invoiceCetakEnabled = !invoiceCetakEnabled"
                                        :class="invoiceCetakEnabled ? 'bg-primary' : 'bg-muted'"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                                        <span :class="invoiceCetakEnabled ? 'translate-x-6' : 'translate-x-1'"
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                                    </button>
                                </div> --}}

                                <!-- Invoice Method Options (when enabled) -->
                                <div x-show="invoiceCetakEnabled" x-collapse class="border-t pt-3 space-y-3">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Kirim Invoice Via:</label>
                                        
                                        <!-- WhatsApp Option -->
                                        <label class="flex items-center gap-3 p-3 border rounded-md cursor-pointer hover:bg-muted/30 transition"
                                            :class="invoiceMethod === 'whatsapp' ? 'border-primary bg-primary/5' : 'border-border'">
                                            <input type="radio" name="invoiceMethod" value="whatsapp" 
                                                x-model="invoiceMethod" class="w-4 h-4">
                                            <span class="text-sm font-medium flex-1">WhatsApp</span>
                                            <span x-show="invoiceMethod === 'whatsapp'" class="text-xs text-primary">(Default)</span>
                                        </label>

                                        <!-- WhatsApp Number Field -->
                                        <div x-show="invoiceMethod === 'whatsapp'" x-collapse>
                                            <input type="text" x-model="invoiceWhatsapp" disabled
                                                class="w-full h-9 px-3 rounded-md border border-input bg-muted text-sm font-mono">
                                            <p class="text-xs text-muted-foreground mt-1">Nomor otomatis dari input di atas</p>
                                        </div>

                                        <!-- Email Option -->
                                        <label class="flex items-center gap-3 p-3 border rounded-md cursor-pointer hover:bg-muted/30 transition"
                                            :class="invoiceMethod === 'email' ? 'border-primary bg-primary/5' : 'border-border'">
                                            <input type="radio" name="invoiceMethod" value="email" 
                                                x-model="invoiceMethod" class="w-4 h-4">
                                            <span class="text-sm font-medium">Email</span>
                                        </label>

                                        <!-- Email Input Field -->
                                        <div x-show="invoiceMethod === 'email'" x-collapse>
                                            <input type="email" x-model="invoiceEmail" placeholder="Masukkan email Anda"
                                                class="w-full h-9 px-3 rounded-md border border-input bg-background text-sm">
                                            <p class="text-xs text-muted-foreground mt-1">Invoice akan dikirim ke email ini</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex justify-between">
                                    <span class="font-medium">Total Pembayaran</span>
                                    <span class="text-xl font-bold" x-text="formatRupiah(totalAmount)"></span>
                                </div>
                            </div>
                            <button @click="handleCheckout()" :disabled="!canCheckout"
                                :class="canCheckout ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'"
                                class="w-full h-12 rounded-md font-medium transition-colors flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Checkout
                            </button>
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
                    </div>
                </div>
                
                <div class="border-t border-primary-foreground/20 pt-8 text-center text-sm text-primary-foreground/80">
                    <p>© 2026 Kuotaumroh.id. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Floating WhatsApp Button -->
        <a href="https://wa.me/628112994499" target="_blank"
            class="fixed bottom-6 right-6 z-50 inline-flex items-center gap-2 bg-green-500 text-white px-4 py-3 rounded-full shadow-lg hover:bg-green-600 transition">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 1.76.46 3.47 1.34 4.99L2 22l5.12-1.35c1.46.8 3.1 1.22 4.78 1.22h.01c5.5 0 9.96-4.46 9.96-9.96S17.5 2.04 12 2.04Zm4.93 13.96c-.21.6-1.23 1.16-1.7 1.22-.44.06-1 .08-1.62-.1-.37-.12-.85-.28-1.45-.55-2.55-1.1-4.2-3.67-4.33-3.85-.12-.18-1.03-1.37-1.03-2.62 0-1.25.65-1.86.88-2.11.23-.25.51-.31.68-.31.17 0 .34 0 .49.01.16.01.37-.06.58.44.21.5.72 1.73.78 1.85.06.12.1.26.02.42-.08.16-.12.26-.23.4-.12.14-.26.31-.37.42-.12.12-.24.25-.1.5.14.25.62 1.02 1.33 1.65.92.82 1.7 1.08 1.95 1.2.25.12.39.1.53-.06.14-.16.61-.71.77-.95.16-.25.33-.2.55-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.58-.15 1.18Z" />
            </svg>
            <span class="text-sm font-semibold">Hubungi Kami</span>
        </a>

        <!-- Toast Notification -->
        <div x-show="toastVisible" x-transition class="toast">
            <div class="font-semibold mb-1" x-text="toastTitle"></div>
            <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
        </div>
    </div>

    <!-- Page Script (sama dengan welcome.blade.php) -->
    <script>
        function publicOrderApp() {
            return {
                msisdn: '',
                provider: null,
                packages: [],
                allPackages: [], // All packages from API
                packagesLoading: true,
                selectedPackage: null,
                packageSearch: '',
                selectedDurationFilter: 'all',
                durationFilters: [{ value: 'all', label: 'Semua' }],
                selectedSubTypeFilter: 'all',
                subTypeFilters: [{ value: 'all', label: 'Semua Tipe' }],
                activationTime: 'now',
                scheduledDate: '',
                scheduledTime: '',
                toastVisible: false,
                toastTitle: '',
                toastMessage: '',
                invoiceCetakEnabled: false,
                invoiceMethod: 'whatsapp',
                invoiceEmail: '',
                invoiceWhatsapp: '',
                errorMessage: '',

                async init() {
                    // Load all packages on init
                    await this.loadAllPackages();
                },

                async loadAllPackages() {
                    try {
                        this.packagesLoading = true;
                        
                        // ===== GUNAKAN AGENT_ID + CONTEXT=STORE UNTUK AMBIL HARGA DARI VIEW =====
                        const agentId = STORE_CONFIG.agent_id;
                        let apiUrl = `${API_BASE_URL}/api/proxy/umroh/package`;
                        
                        // Jika agent_id valid (AGTxxxxx), gunakan pricing dari VIEW
                        if (agentId && agentId.startsWith('AGT')) {
                            apiUrl = `${API_BASE_URL}/api/proxy/umroh/package?agent_id=${agentId}&context=store`;
                            console.log('📦 Fetching STORE pricing from VIEW for agent:', agentId);
                        } else {
                            // Fallback ke ref_code legacy
                            const catalogRefCode = STORE_CONFIG.catalog_ref_code || '0';
                            apiUrl = `${API_BASE_URL}/api/proxy/umroh/package?ref_code=${catalogRefCode}`;
                            console.log('⚠️ Using legacy ref_code:', catalogRefCode);
                        }
                        
                        const response = await fetch(apiUrl);
                        if (!response.ok) {
                            throw new Error('Failed to fetch packages');
                        }
                        
                        const data = await response.json();
                        console.log('📦 API Response:', data);
                        
                        // Response langsung array, tidak wrapped
                        if (Array.isArray(data)) {
                            this.allPackages = data.map(pkg => {
                                // ===== PRICING DARI VIEW (toko_harga_*) atau LEGACY =====
                                // VIEW: toko_harga_jual, toko_harga_coret, toko_hemat
                                // Legacy: price, price_app, price_customer
                                const priceApp = parseInt(pkg.price_app) || 0;         // Harga coret (dari VIEW atau legacy)
                                const price = parseInt(pkg.price) || 0;                 // Harga jual (dari VIEW atau legacy)
                                const hemat = parseInt(pkg.hemat) || parseInt(pkg.toko_hemat) || (priceApp - price);
                                
                                // Untuk store (individu), tampilkan price (toko_harga_jual) sebagai harga final
                                const displayPrice = price;
                                
                                return {
                                    id: pkg.id,
                                    package_id: pkg.id || pkg.package_id,
                                    packageId: pkg.id || pkg.package_id,
                                    name: pkg.name || pkg.packageName,
                                    packageName: pkg.name || pkg.packageName,
                                    provider: pkg.type || pkg.provider,
                                    days: parseInt(pkg.days) || parseInt(pkg.masa_aktif) || 0,
                                    masa_aktif: parseInt(pkg.days) || parseInt(pkg.masa_aktif) || 0,
                                    quota: pkg.quota || pkg.kuota_utama || '',
                                    kuota_utama: pkg.kuota_utama || pkg.quota || '',
                                    total_kuota: pkg.total_kuota || pkg.quota || '',
                                    kuota_bonus: pkg.kuota_bonus || pkg.bonus || '',
                                    bonus: pkg.bonus || pkg.kuota_bonus || '',
                                    telp: pkg.telp || '',
                                    sms: pkg.sms || '',
                                    // ===== PRICING =====
                                    price: displayPrice,         // Harga jual (toko_harga_jual)
                                    harga: displayPrice,
                                    sellPrice: displayPrice,
                                    displayPrice: displayPrice,
                                    price_app: priceApp,         // Harga coret (toko_harga_coret)
                                    hemat: hemat,                // Hemat (toko_hemat)
                                    // Legacy compatibility
                                    price_customer: displayPrice,
                                    price_bulk: displayPrice,
                                    // Fee/profit (from VIEW)
                                    profit_agent: parseInt(pkg.profit_agent) || parseInt(pkg.mandiri_final_fee_travel) || 0,
                                    profit_affiliate: parseInt(pkg.profit_affiliate) || parseInt(pkg.mandiri_final_fee_affiliate) || 0,
                                    profit: 0, // Tidak tampilkan profit untuk customer
                                    subType: pkg.sub_type || pkg.tipe_paket || '',
                                    tipe_paket: pkg.sub_type || pkg.tipe_paket || '',
                                    is_active: pkg.is_active,
                                    promo: pkg.promo || null,
                                };
                            });
                            console.log('📦 Mapped packages:', this.allPackages.length);
                        }
                        this.packagesLoading = false;
                    } catch (error) {
                        console.error('Error loading packages:', error);
                        this.allPackages = [];
                        this.packagesLoading = false;
                        this.showToast('Error', 'Gagal memuat data paket');
                    }
                },

                async loadPackagesByProvider(provider) {
                    // Filter from allPackages instead of fetching again
                    this.packages = this.allPackages.filter(pkg => {
                        const pkgProvider = (pkg.provider || '').toUpperCase();
                        const targetProvider = (provider || '').toUpperCase();
                        
                        // Normalize provider names to match API response
                        // API menggunakan: TELKOMSEL, INDOSAT, XL, TRI, AXIS, SMARTFREN, BYU
                        if (targetProvider === 'SIMPATI' || targetProvider === 'TSEL') {
                            return pkgProvider === 'TELKOMSEL';
                        }
                        if (targetProvider === 'IM3' || targetProvider === 'ISAT') {
                            return pkgProvider === 'INDOSAT';
                        }
                        if (targetProvider === '3' || targetProvider === 'THREE') {
                            return pkgProvider === 'TRI';
                        }
                        if (targetProvider === 'SF') {
                            return pkgProvider === 'SMARTFREN';
                        }
                        
                        return pkgProvider === targetProvider;
                    });
                    
                    console.log('Filtered packages for', provider, ':', this.packages.length);
                    this.packagesLoading = false;
                },

                handleMsisdnInput(event) {
                    if (event && event.target && typeof event.target.value === 'string') {
                        this.msisdn = event.target.value;
                    } else if (this.msisdn && typeof this.msisdn !== 'string' && this.msisdn.value !== undefined) {
                        this.msisdn = this.msisdn.value;
                    }

                    const cleaned = String(this.msisdn || '').replace(/\D/g, '');
                    this.msisdn = cleaned;
                    this.invoiceWhatsapp = cleaned;  // Sync nomor ke invoice WhatsApp
                    this.errorMessage = ''; // Reset error message

                    // Jika kosong, reset semua
                    if (!cleaned) {
                        this.provider = null;
                        this.selectedPackage = null;
                        this.resetFilters();
                        return;
                    }

                    if (validateMsisdn(cleaned)) {
                        const detectedProvider = detectProvider(cleaned);
                        this.provider = detectedProvider ? normalizeProviderForApi(detectedProvider) : null;
                        
                        if (this.provider) {
                            // Load packages for this provider
                            this.loadPackagesByProvider(this.provider);
                            this.errorMessage = ''; // Valid provider found
                        } else {
                            // Valid format but unknown provider
                            this.errorMessage = 'Provider tidak dikenali. Pastikan nomor HP benar.';
                            this.provider = null;
                        }
                        
                        if (this.selectedPackage && this.selectedPackage.provider !== this.provider) {
                            this.selectedPackage = null;
                        }
                        this.generateDurationFilters();
                        this.generateSubTypeFilters();
                    } else {
                        // Invalid format (length check usually)
                        // Hanya tampilkan error jika panjang sudah mendekati atau lebih (misal > 4 digit) supaya tidak mengganggu saat mengetik
                        if (cleaned.length > 4) {
                             if (cleaned.length < 10) {
                                 this.errorMessage = 'Nomor HP terlalu pendek (min 10 digit)';
                             } else if (cleaned.length > 13) {
                                 this.errorMessage = 'Nomor HP terlalu panjang (max 13 digit)';
                             } else {
                                 this.errorMessage = 'Format nomor HP tidak valid';
                             }
                        }
                        
                        this.provider = null;
                        this.selectedPackage = null;
                        this.resetFilters();
                    }
                },

                get availablePackages() {
                    if (!this.provider) return [];
                    // packages sudah di-filter di loadPackagesByProvider
                    return this.packages;
                },

                get filteredPackages() {
                    let list = this.availablePackages;

                    if (this.packageSearch) {
                        const term = this.packageSearch.toLowerCase();
                        list = list.filter(pkg => (pkg.name || pkg.packageName || '').toLowerCase().includes(term));
                    }

                    if (this.selectedDurationFilter !== 'all') {
                        list = list.filter(pkg => String(pkg.days) === String(this.selectedDurationFilter));
                    }

                    if (this.selectedSubTypeFilter !== 'all') {
                        list = list.filter(pkg => (pkg.subType || '').toUpperCase() === this.selectedSubTypeFilter.toUpperCase());
                    }

                    return list;
                },

                generateDurationFilters() {
                    const days = Array.from(new Set(this.availablePackages.map(pkg => pkg.days).filter(Boolean))).sort((a, b) => a - b);
                    this.durationFilters = [{ value: 'all', label: 'Semua' }, ...days.map(d => ({ value: String(d), label: `${d} Hari` }))];
                    this.selectedDurationFilter = 'all';
                },

                generateSubTypeFilters() {
                    const types = Array.from(new Set(this.availablePackages.map(pkg => pkg.subType).filter(Boolean)));
                    this.subTypeFilters = [{ value: 'all', label: 'Semua Tipe' }, ...types.map(t => ({ value: t, label: t }))];
                    this.selectedSubTypeFilter = 'all';
                },

                resetFilters() {
                    this.packageSearch = '';
                    this.selectedDurationFilter = 'all';
                    this.selectedSubTypeFilter = 'all';
                    this.durationFilters = [{ value: 'all', label: 'Semua' }];
                    this.subTypeFilters = [{ value: 'all', label: 'Semua Tipe' }];
                },

                selectPackage(pkg) {
                    this.selectedPackage = {
                        ...pkg,
                        // Use price_customer (harga diskon) sebagai harga yang dibayar
                        displayPrice: pkg.price_customer || pkg.price || pkg.harga
                    };
                    
                    // Scroll ke checkout summary di mobile
                    this.$nextTick(() => {
                        const checkoutElement = document.getElementById('checkoutSummary');
                        if (checkoutElement && window.innerWidth < 1024) {
                            const elementPosition = checkoutElement.getBoundingClientRect().top + window.scrollY;
                            const offsetPosition = elementPosition - 120; // offset untuk melihat header "Ringkasan Pesanan"
                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                    });
                },

                getPackageTitle(pkg) {
                    const subType = (pkg.subType || pkg.sub_type || '').toUpperCase();
                    const days = pkg.days || pkg.masa_aktif;
                    const daysSuffix = days ? ` - ${days} Hari` : '';
                    
                    // Untuk paket INTERNET atau INTERNET + TELP/SMS
                    if (subType.includes('INTERNET')) {
                        // Hitung total kuota (quota + bonus)
                        const quotaStr = String(pkg.quota || '');
                        const bonusStr = String(pkg.bonus || '');
                        
                        // Helper untuk ambil angka dari string (misal "50GB" -> 50, "50" -> 50)
                        const extractNumber = (str) => {
                            const match = str.match(/(\d+(?:\.\d+)?)/);
                            return match ? parseFloat(match[1]) : 0;
                        };
                        
                        let totalGB = 0;
                        // Ambil angka dari quota dan bonus
                        if (quotaStr) totalGB += extractNumber(quotaStr);
                        if (bonusStr) totalGB += extractNumber(bonusStr);
                        
                        if (totalGB > 0) {
                            // Format: "Kuota 51GB - 12 Hari"
                            return `Kuota ${totalGB}GB${daysSuffix}`;
                        }
                        
                        // Fallback jika tidak ada angka (misal "Unlimited")
                        return pkg.quota ? `${pkg.quota}${daysSuffix}` : `Paket Internet${daysSuffix}`;
                    }
                    
                    // Untuk paket TELP/SMS
                    if (subType.includes('TELP') || subType.includes('SMS')) {
                        const telpStr = pkg.telp ? `Telp ${pkg.telp}` : '';
                        const smsStr = pkg.sms ? `SMS ${pkg.sms}` : '';
                        
                        if (telpStr && smsStr) {
                            return `${telpStr} & ${smsStr}${daysSuffix}`;
                        } else if (telpStr) {
                            return `${telpStr}${daysSuffix}`;
                        } else if (smsStr) {
                            return `${smsStr}${daysSuffix}`;
                        }
                    }
                    
                    // Fallback ke nama asli
                    if (pkg.name) return pkg.name;
                    if (pkg.packageName) return pkg.packageName;
                    
                    const parts = [];
                    if (pkg.quota) parts.push(pkg.quota);
                    if (days) parts.push(`${days} Hari`);
                    return parts.join(' - ') || 'Paket';
                },

                getQuotaDisplay(pkg) {
                    const quotaStr = String(pkg.quota || '');
                    
                    // Extract numbers from quota
                    const extractNumber = (str) => {
                        const match = str.match(/(\d+(?:\.\d+)?)/);
                        return match ? parseFloat(match[1]) : 0;
                    };
                    
                    const quotaNum = extractNumber(quotaStr);
                    
                    if (quotaNum === 0) return '';
                    
                    // Format: "49 GB Kuota Arab" if quota exists
                    return `${quotaNum} GB Kuota Arab`;
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

                isFieldBold(pkg, field) {
                    const subtype = (pkg.subType || '').toUpperCase();
                    if (field === 'kuota') return subtype.includes('INTERNET') || !subtype;
                    if (field === 'telp') return subtype.includes('TELP') || subtype.includes('VOICE');
                    if (field === 'sms') return subtype.includes('SMS');
                    if (field === 'bonus') return true;
                    if (field === 'hari') return true;
                    return false;
                },

                get totalAmount() {
                    return this.selectedPackage?.displayPrice || 0;
                },

                get canCheckout() {
                    if (!this.msisdn || !this.provider || !this.selectedPackage) return false;
                    
                    if (this.activationTime === 'scheduled') {
                        return this.scheduledDate && this.scheduledTime;
                    }
                    
                    return true;
                },

                handleCheckout() {
                    if (!this.canCheckout) return;

                    // Format schedule date if scheduled
                    let scheduleDate = null;
                    if (this.activationTime === 'scheduled' && this.scheduledDate) {
                        const date = new Date(this.scheduledDate);
                        if (this.scheduledTime) {
                            const [hours, minutes] = this.scheduledTime.split(':');
                            date.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                        }
                        scheduleDate = date.toISOString().slice(0, 16); // Format: yyyy-mm-ddThh:mm
                    }

                    // Prepare order data dengan format yang sesuai untuk API
                    // Store = Agent/Referral mode, menggunakan ref_code=link_referal
                    const orderData = {
                        items: [{
                            msisdn: this.msisdn,
                            provider: this.provider,
                            package_id: this.selectedPackage.package_id || this.selectedPackage.packageId || this.selectedPackage.id,
                            packageId: this.selectedPackage.package_id || this.selectedPackage.packageId || this.selectedPackage.id,
                            packageName: this.selectedPackage.name || this.selectedPackage.packageName,
                            nama_paket: this.selectedPackage.name,
                            tipe_paket: this.selectedPackage.tipe_paket || this.selectedPackage.subType,
                            masa_aktif: this.selectedPackage.masa_aktif || this.selectedPackage.days,
                            days: this.selectedPackage.days || this.selectedPackage.masa_aktif,
                            total_kuota: this.selectedPackage.total_kuota || this.selectedPackage.quota,
                            price: this.selectedPackage.price_customer || this.selectedPackage.price || this.selectedPackage.displayPrice,
                            harga: this.selectedPackage.price_customer || this.selectedPackage.harga || this.selectedPackage.price,
                        }],
                        subtotal: this.selectedPackage.price_customer || this.selectedPackage.price || this.selectedPackage.displayPrice,
                        platformFee: 0,
                        total: this.selectedPackage.price_customer || this.selectedPackage.price || this.selectedPackage.displayPrice,
                        paymentMethod: 'qris',
                        activationTime: this.activationTime,
                        scheduleDate: scheduleDate,
                        scheduledTime: this.scheduledTime,
                        refCode: STORE_CONFIG.link_referal,  // ref_code=link_referal untuk Agent/Referral
                        linkReferal: STORE_CONFIG.link_referal || 'kuotaumroh',
                        agent_id: STORE_CONFIG.agent_id || null,  // Add agent_id for payment
                        mode: 'store',
                        isBulk: false,  // Flag untuk INDIVIDUAL payment (Store mode tanpa login)
                        createdAt: new Date().toISOString(),
                    };
                    
                    // Add price to each item for backend processing
                    orderData.items = orderData.items.map(item => ({
                        ...item,
                        price: item.price || item.harga || 0 // Ensure price is included
                    }));

                    // Store in localStorage and redirect to payment
                    localStorage.setItem('pendingOrder', JSON.stringify(orderData));
                    window.location.href = '{{ route('checkout') }}';
                },

                showToast(title, message) {
                    this.toastTitle = title;
                    this.toastMessage = message;
                    this.toastVisible = true;
                    setTimeout(() => {
                        this.toastVisible = false;
                    }, 3000);
                }
            }
        }
    </script>

</body>

</html>

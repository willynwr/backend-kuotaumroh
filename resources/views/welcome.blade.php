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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Shared CSS -->
    <link rel="stylesheet" href="{{ asset('shared/styles.css') }}">

    <!-- ⚠️ PENTING: Load config.js PERTAMA sebelum script lain -->
    <script src="{{ asset('shared/config.js') }}"></script>

    <!-- Shared Scripts -->
    <script src="{{ asset('shared/utils.js') }}"></script>
    <script src="{{ asset('shared/public-api.js') }}"></script>
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

        <!-- Header -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/kabah.png') }}" alt="Kuotaumroh.id" class="h-9 w-9 object-contain">
                    <span class="text-xl font-semibold">Kuotaumroh.id</span>
                </div>
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-9 px-4 text-sm font-medium hover:bg-primary/90 transition-colors">
                    Login Agen
                </a>
            </div>
        </header>

        <!-- Hero Background Image -->
        <div class="relative w-full h-[200px] overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/bg.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-background"></div>
        </div>

        <!-- Main Content -->
        <main class="container mx-auto py-8 px-4 max-w-6xl animate-fade-in -mt-[50px] relative z-10">

            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold tracking-tight mb-4">Paket Internet Umroh & Haji</h1>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                    Dapatkan kuota internet terbaik untuk perjalanan umroh dan haji Anda. Proses cepat, harga
                    terjangkau.
                </p>
            </div>

            <!-- Order Form -->
            <div class="grid gap-6 lg:grid-cols-3">

                <!-- Form Section (2/3) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Step 1: Phone Number -->
                    <div class="rounded-lg border bg-white shadow-sm">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold flex items-center gap-2">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold">1</span>
                                Nomor HP
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-2">
                                <label for="msisdn" class="text-sm font-medium">Masukkan Nomor HP</label>
                                <input id="msisdn" type="text" x-model="msisdn" @input="handleMsisdnInput($event)"
                                    placeholder="Contoh: 081234567890"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <p class="text-xs text-muted-foreground">Format: 08xxxxxxxxxx</p>
                            </div>

                            <!-- Provider Badge -->
                            <div x-show="provider" class="flex items-center gap-2">
                                <span class="text-sm text-muted-foreground">Provider:</span>
                                <span
                                    class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary"
                                    x-text="provider"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Select Package -->
                    <div class="rounded-lg border bg-white shadow-sm"
                        :class="!provider && 'opacity-50 pointer-events-none'">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold flex items-center gap-2">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold">2</span>
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
                                    <div class="p-4 border-b space-y-4">
                                        <div class="flex gap-2">
                                            <!-- Search -->
                                            <div class="relative flex-1">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                <input type="text" x-model="packageSearch" placeholder="Cari paket"
                                                    class="w-full h-10 pl-10 pr-4 rounded-md border border-input bg-background text-sm">
                                            </div>
                                            <!-- SubType Filter -->
                                            <div class="relative w-56">
                                                <select x-model="selectedSubTypeFilter"
                                                    class="w-full h-10 px-3 pr-8 rounded-md border border-input bg-background text-sm appearance-none cursor-pointer">
                                                    <template x-for="filter in subTypeFilters" :key="filter.value">
                                                        <option :value="filter.value" x-text="filter.label"></option>
                                                    </template>
                                                </select>
                                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
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

                                    <!-- Package Cards -->
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
                                                        <template x-if="pkg.quota && (!pkg.subType || pkg.subType.toUpperCase().includes('INTERNET'))">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'kuota') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                                                </svg>
                                                                <span x-text="pkg.quota + ' Kuota Arab'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.telp && pkg.subType && !pkg.subType.toUpperCase().includes('INTERNET')">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'telp') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                </svg>
                                                                <span x-text="pkg.telp + ' menit'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.sms && pkg.subType && !pkg.subType.toUpperCase().includes('INTERNET')">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'sms') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                                </svg>
                                                                <span x-text="pkg.sms + ' SMS'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.quota && pkg.subType && !pkg.subType.toUpperCase().includes('INTERNET')">
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
                                                                <span x-text="pkg.bonus"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.telp && (!pkg.subType || pkg.subType.toUpperCase().includes('INTERNET'))">
                                                            <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'telp') ? 'font-bold' : ''">
                                                                <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                </svg>
                                                                <span x-text="pkg.telp + ' menit'"></span>
                                                            </div>
                                                        </template>

                                                        <template x-if="pkg.sms && (!pkg.subType || pkg.subType.toUpperCase().includes('INTERNET'))">
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
                                                        <p class="text-2xl font-bold text-primary" x-text="formatRupiah(pkg.sellPrice || pkg.price)"></p>
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
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold">3</span>
                                Waktu Aktivasi
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Activation Time Options -->
                            <div class="grid grid-cols-2 gap-3">
                                <div @click="activationTime = 'now'"
                                    :class="activationTime === 'now' ? 'border-primary bg-primary/5' : 'border-border hover:border-primary/50'"
                                    class="border-2 rounded-lg p-4 cursor-pointer transition-all text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="h-8 w-8"
                                            :class="activationTime === 'now' ? 'text-primary' : 'text-muted-foreground'"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="font-medium"
                                            :class="activationTime === 'now' && 'text-primary'">Segera</span>
                                        <span class="text-xs text-muted-foreground">Langsung aktif</span>
                                    </div>
                                </div>
                                <div @click="activationTime = 'scheduled'"
                                    :class="activationTime === 'scheduled' ? 'border-primary bg-primary/5' : 'border-border hover:border-primary/50'"
                                    class="border-2 rounded-lg p-4 cursor-pointer transition-all text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="h-8 w-8"
                                            :class="activationTime === 'scheduled' ? 'text-primary' : 'text-muted-foreground'"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="font-medium"
                                            :class="activationTime === 'scheduled' && 'text-primary'">Terjadwal</span>
                                        <span class="text-xs text-muted-foreground">Pilih waktu</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Scheduled Options -->
                            <div x-show="activationTime === 'scheduled'" x-collapse class="space-y-3 pt-2">
                                <div class="space-y-2">
                                    <label for="scheduledDate" class="text-sm font-medium">Tanggal</label>
                                    <input id="scheduledDate" type="date" x-model="scheduledDate"
                                        :min="new Date().toISOString().split('T')[0]"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label for="scheduledTime" class="text-sm font-medium">Waktu</label>
                                    <input id="scheduledTime" type="time" x-model="scheduledTime"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Order Summary Sidebar (1/3) -->
                <div class="lg:col-span-1">
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

            <!-- Benefits Section -->
            <h2 class="text-2xl font-bold text-center mb-8 mt-16">Kenapa harus membeli Kuota Umroh Haji di Kuotaumroh.id?</h2>
            <div class="mt-16 grid gap-6 md:grid-cols-3">
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
            <div class="mt-16 max-w-4xl mx-auto">
                <h2 class="text-2xl font-bold text-center mb-8">Pertanyaan yang Sering Diajukan</h2>
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
        <footer class="mt-16 bg-gradient-to-r from-primary to-emerald-500 text-white">
            <div class="container mx-auto px-4 py-10 grid gap-8 md:grid-cols-3">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/kabah.png') }}" alt="Kuotaumroh.id" class="h-10 w-10 object-contain">
                        <div>
                            <p class="text-lg font-semibold">Kuotaumroh.id</p>
                            <p class="text-sm opacity-80">Solusi kuota terbaik untuk perjalanan suci Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <h3 class="font-semibold text-lg">Kontak</h3>
                    <p class="text-sm opacity-90">Email: support@kuotaumroh.id</p>
                    <p class="text-sm opacity-90">WhatsApp: +62 812-3456-7890</p>
                    <p class="text-sm opacity-90">Jl. Harmoni No. 123, Jakarta</p>
                </div>
                <div class="space-y-2">
                    <h3 class="font-semibold text-lg">Layanan</h3>
                    <p class="text-sm opacity-90">Paket data, telepon, SMS untuk Umroh & Haji</p>
                    <p class="text-sm opacity-90">Aktivasi otomatis & terjadwal</p>
                    <p class="text-sm opacity-90">Dukungan pelanggan 24/7</p>
                </div>
            </div>
            <div class="border-t border-white/20">
                <div class="container mx-auto px-4 py-4 text-center text-sm opacity-80">
                    © 2026 Kuotaumroh.id. All rights reserved.
                </div>
            </div>
        </footer>

        <!-- Floating WhatsApp Button -->
        <a href="https://wa.me/6281234567890" target="_blank"
            class="fixed bottom-6 right-6 z-50 inline-flex items-center gap-2 bg-green-500 text-white px-4 py-3 rounded-full shadow-lg hover:bg-green-600 transition">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 1.76.46 3.47 1.34 4.99L2 22l5.12-1.35c1.46.8 3.1 1.22 4.78 1.22h.01c5.5 0 9.96-4.46 9.96-9.96S17.5 2.04 12 2.04Zm4.93 13.96c-.21.6-1.23 1.16-1.7 1.22-.44.06-1 .08-1.62-.1-.37-.12-.85-.28-1.45-.55-2.55-1.1-4.2-3.67-4.33-3.85-.12-.18-1.03-1.37-1.03-2.62 0-1.25.65-1.86.88-2.11.23-.25.51-.31.68-.31.17 0 .34 0 .49.01.16.01.37-.06.58.44.21.5.72 1.73.78 1.85.06.12.1.26.02.42-.08.16-.12.26-.23.4-.12.14-.26.31-.37.42-.12.12-.24.25-.1.5.14.25.62 1.02 1.33 1.65.92.82 1.7 1.08 1.95 1.2.25.12.39.1.53-.06.14-.16.61-.71.77-.95.16-.25.33-.2.55-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.58-.15 1.18Z" />
            </svg>
            <span class="text-sm font-semibold">Butuh bantuan?</span>
        </a>

        <!-- Toast Notification -->
        <div x-show="toastVisible" x-transition class="toast">
            <div class="font-semibold mb-1" x-text="toastTitle"></div>
            <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
        </div>
    </div>

    <!-- Page Script -->
    <script>
        function publicOrderApp() {
            return {
                // Phone number
                msisdn: '',
                provider: null,

                // Packages
                packages: [],
                packagesLoading: true,
                selectedPackage: null,

                // Filters
                packageSearch: '',
                selectedDurationFilter: 'all',
                durationFilters: [{ value: 'all', label: 'Durasi: Semua' }],
                selectedSubTypeFilter: 'all',
                subTypeFilters: [{ value: 'all', label: 'Jenis: Semua' }],

                // Activation time
                activationTime: 'now',
                scheduledDate: '',
                scheduledTime: '',

                // Toast
                toastVisible: false,
                toastTitle: '',
                toastMessage: '',

                async init() {
                    // Load all packages
                    try {
                        this.packages = await fetchPackages();
                        this.packagesLoading = false;
                    } catch (error) {
                        console.error('Error loading packages:', error);
                        this.packagesLoading = false;
                    }
                },

                handleMsisdnInput(event) {
                    // Ensure msisdn is always a string value, not an element reference.
                    if (event && event.target && typeof event.target.value === 'string') {
                        this.msisdn = event.target.value;
                    } else if (this.msisdn && typeof this.msisdn !== 'string' && this.msisdn.value !== undefined) {
                        this.msisdn = this.msisdn.value;
                    }

                    // Validate and detect provider
                    const cleaned = String(this.msisdn || '').replace(/\D/g, '');
                    this.msisdn = cleaned;

                    if (validateMsisdn(cleaned)) {
                        const detectedProvider = detectProvider(cleaned);
                        // Normalize provider name for API (Telkomsel → SIMPATI, etc.)
                        this.provider = detectedProvider ? normalizeProviderForApi(detectedProvider) : null;
                        // Reset selected package when provider changes
                        if (this.selectedPackage && this.selectedPackage.provider !== this.provider) {
                            this.selectedPackage = null;
                        }
                        // Regenerate filters when provider changes
                        this.generateDurationFilters();
                        this.generateSubTypeFilters();
                    } else {
                        this.provider = null;
                        this.selectedPackage = null;
                        this.resetFilters();
                    }
                },

                get availablePackages() {
                    if (!this.provider) return [];
                    return this.packages.filter(pkg => pkg.provider === this.provider);
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
                    this.durationFilters = [{ value: 'all', label: 'Durasi: Semua' }, ...days.map(d => ({ value: String(d), label: `${d} Hari` }))];
                    this.selectedDurationFilter = 'all';
                },

                generateSubTypeFilters() {
                    const types = Array.from(new Set(this.availablePackages.map(pkg => pkg.subType).filter(Boolean)));
                    this.subTypeFilters = [{ value: 'all', label: 'Jenis: Semua' }, ...types.map(t => ({ value: t, label: t }))];
                    this.selectedSubTypeFilter = 'all';
                },

                resetFilters() {
                    this.packageSearch = '';
                    this.selectedDurationFilter = 'all';
                    this.selectedSubTypeFilter = 'all';
                    this.durationFilters = [{ value: 'all', label: 'Durasi: Semua' }];
                    this.subTypeFilters = [{ value: 'all', label: 'Jenis: Semua' }];
                },

                selectPackage(pkg) {
                    this.selectedPackage = {
                        ...pkg,
                        // Use sell price for public users
                        displayPrice: pkg.sellPrice || pkg.price
                    };
                },

                getPackageTitle(pkg) {
                    if (pkg.name) return pkg.name;
                    if (pkg.packageName) return pkg.packageName;
                    const parts = [];
                    if (pkg.quota) parts.push(pkg.quota);
                    if (pkg.days) parts.push(`${pkg.days} Hari`);
                    return parts.join(' - ') || 'Paket';
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
                    return this.msisdn && this.provider && this.selectedPackage;
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
                        scheduleDate = date.toISOString();
                    }

                    // Prepare order data
                    const orderData = {
                        items: [{
                            msisdn: this.msisdn,
                            provider: this.provider,
                            packageId: this.selectedPackage.id,
                            packageName: this.selectedPackage.name,
                            price: this.selectedPackage.displayPrice,
                        }],
                        subtotal: this.selectedPackage.displayPrice,
                        platformFee: 0,
                        total: this.selectedPackage.displayPrice,
                        paymentMethod: 'qris',
                        activationTime: this.activationTime,
                        scheduledDate: scheduleDate,
                        scheduledTime: this.scheduledTime,
                        mode: 'public',
                        createdAt: new Date().toISOString(),
                    };

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

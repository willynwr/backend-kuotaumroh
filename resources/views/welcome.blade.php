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

    <!-- ⚠️ PENTING: Load config.js PERTAMA sebelum script lain -->
    <script src="{{ asset('shared/config.js') }}"></script>

    <!-- Shared Scripts -->
    <script src="{{ asset('shared/utils.js') }}"></script>
    <script src="{{ asset('shared/api.js') }}"></script>
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

        <!-- Main Content -->
        <main class="container mx-auto py-8 px-4 max-w-6xl animate-fade-in">

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

                            <template x-if="!packagesLoading">
                                <div class="grid grid-cols-2 gap-3">
                                    <template x-for="pkg in availablePackages" :key="pkg.id">
                                        <div @click="selectPackage(pkg)"
                                            :class="selectedPackage?.id === pkg.id ? 'border-primary bg-primary/5' : 'border-border hover:border-primary/50'"
                                            class="border-2 rounded-lg p-4 cursor-pointer transition-all">
                                            <!-- Package Info -->
                                            <div class="space-y-2">
                                                <h3 class="font-semibold text-sm leading-tight" x-text="pkg.name"></h3>
                                                <p class="text-xs text-muted-foreground">
                                                    <span x-text="pkg.quota"></span> • <span
                                                        x-text="pkg.validity"></span>
                                                </p>
                                                <p class="text-lg font-bold text-primary"
                                                    x-text="formatRupiah(pkg.sellPrice || pkg.price)"></p>
                                                <div x-show="selectedPackage?.id === pkg.id" class="pt-2 border-t">
                                                    <div class="flex items-center gap-2 text-primary text-xs">
                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>Dipilih</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="availablePackages.length === 0">
                                        <div class="col-span-2 text-center py-8 text-muted-foreground">
                                            <p>Tidak ada paket tersedia untuk provider ini</p>
                                        </div>
                                    </template>
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

        </main>

        <!-- Footer -->
        <footer class="border-t mt-16 py-8">
            <div class="container mx-auto px-4 text-center text-sm text-muted-foreground">
                <p>© 2026 Kuotaumroh.id. All rights reserved.</p>
            </div>
        </footer>

        <!-- Toast Notification -->
        <div x-show="toastVisible" x-transition class="toast">
            <div class="font-semibold mb-1" x-text="toastTitle"></div>
            <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
        </div>
    </div>

    <!-- Shared Scripts -->
    <script src="{{ asset('shared/utils.js') }}"></script>
    <script src="{{ asset('shared/api.js') }}"></script>

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
                    } else {
                        this.provider = null;
                        this.selectedPackage = null;
                    }
                },

                get availablePackages() {
                    if (!this.provider) return [];
                    return this.packages.filter(pkg => pkg.provider === this.provider);
                },

                selectPackage(pkg) {
                    this.selectedPackage = {
                        ...pkg,
                        // Use sell price for public users
                        displayPrice: pkg.sellPrice || pkg.price
                    };
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

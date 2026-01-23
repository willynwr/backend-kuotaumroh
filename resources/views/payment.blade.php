<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Kuotaumroh.id</title>

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
 s    <link rel="stylesheet" href="{{ asset('shared/styles.css') }}">

    <!-- ⚠️ PENTING: Load config.js PERTAMA sebelum script lain -->
    <script src="{{ asset('shared/config.js') }}?v={{ time() }}"></script>

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
                        success: {
                            DEFAULT: "hsl(var(--success))",
                            foreground: "hsl(var(--success-foreground))",
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
                    container: {
                        center: true,
                        padding: "2rem",
                        screens: {
                            "2xl": "1400px",
                        },
                    },
                },
            },
        }
    </script>
</head>

<body class="min-h-screen bg-background">
    <div x-data="paymentApp()">

        <!-- Header -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/kabah.png') }}" alt="Kuotaumroh.id Logo" class="h-9 w-9 object-contain">
                    <span class="text-xl font-semibold">Kuotaumroh.id</span>
                </a>

                <!-- Simple Text -->
                <span class="text-sm text-muted-foreground">Pembayaran</span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto py-6 animate-fade-in px-4">

            <!-- Loading State -->
            <div x-show="loading" x-cloak class="flex items-center justify-center min-h-[60vh]">
                <div class="text-center space-y-4">
                    <svg class="animate-spin h-12 w-12 mx-auto text-primary" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-muted-foreground">Memuat data pembayaran...</p>
                </div>
            </div>

            <!-- Error State -->
            <div x-show="error && !loading" x-cloak class="flex items-center justify-center min-h-[60vh]">
                <div class="max-w-md w-full rounded-lg border bg-white shadow-sm">
                    <div class="p-6 text-center space-y-4">
                        <div class="flex justify-center">
                            <div class="rounded-full bg-destructive/10 p-4">
                                <svg class="h-16 w-16 text-destructive" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold">Terjadi Kesalahan</h2>
                        <p class="text-muted-foreground" x-text="errorMessage"></p>
                        <div class="pt-4">
                            <button @click="window.location.href = '{{ route('welcome') }}'"
                                class="w-full inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors">
                                Kembali ke Beranda
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success State -->
            <div x-show="paymentStatus === 'success' && !loading" x-cloak class="flex items-center justify-center min-h-[60vh]">
                <div class="max-w-md w-full rounded-lg border bg-white shadow-sm">
                    <div class="p-6 text-center space-y-4">
                        <div class="flex justify-center">
                            <div class="rounded-full bg-green-500/10 p-4">
                                <svg class="h-16 w-16 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold">Pembayaran Berhasil!</h2>
                        <p class="text-muted-foreground">
                            Pesanan Anda sedang diproses. Paket akan segera diaktifkan ke nomor tujuan.
                        </p>
                        <div class="pt-4 space-y-2">
                            <p class="text-sm text-muted-foreground">ID Transaksi: <span class="font-mono" x-text="paymentData.batch_id"></span></p>
                        </div>
                        <div class="pt-4">
                            <button @click="window.location.href = '{{ route('welcome') }}'"
                                class="w-full inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors">
                                Kembali ke Beranda
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expired State -->
            <div x-show="paymentStatus === 'expired' && !loading" x-cloak class="flex items-center justify-center min-h-[60vh]">
                <div class="max-w-md w-full rounded-lg border bg-white shadow-sm">
                    <div class="p-6 text-center space-y-4">
                        <div class="flex justify-center">
                            <div class="rounded-full bg-destructive/10 p-4">
                                <svg class="h-16 w-16 text-destructive" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold">Pembayaran Kedaluwarsa</h2>
                        <p class="text-muted-foreground">
                            Waktu pembayaran telah habis. Silakan buat pesanan baru untuk melanjutkan.
                        </p>
                        <div class="pt-4">
                            <button @click="window.location.href = '{{ route('welcome') }}'"
                                class="w-full inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors">
                                Buat Pesanan Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending State (Payment Page) -->
            <div x-show="paymentStatus === 'pending' && !loading && !error" x-cloak>
                <!-- Page Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                        <a href="{{ route('welcome') }}" class="hover:text-foreground">Beranda</a>
                        <span>/</span>
                        <span class="text-foreground">Pembayaran</span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight">Pembayaran</h1>
                    <p class="text-muted-foreground mt-2">Selesaikan pembayaran Anda</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <!-- QR Code & Payment Info -->
                    <div class="lg:col-span-1 space-y-4">
                        <!-- QR Card -->
                        <div class="rounded-lg border bg-white shadow-sm">
                            <div class="p-6 border-b">
                                <h3 class="text-lg font-semibold flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    Scan QR Code
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- QR Code -->
                                <div class="flex justify-center">
                                    <div class="bg-white p-4 rounded-lg border-2 border-border">
                                        <template x-if="qrCodeUrl">
                                            <img :src="qrCodeUrl" alt="QR Code QRIS"
                                                class="w-48 h-48 object-contain">
                                        </template>
                                        <template x-if="!qrCodeUrl">
                                            <div class="w-48 h-48 bg-muted flex items-center justify-center">
                                                <svg class="animate-spin h-8 w-8 text-muted-foreground" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="space-y-2">
                                    <p class="text-sm text-muted-foreground text-center">Total Pembayaran</p>
                                    <div class="flex items-center justify-center gap-2">
                                        <p class="text-2xl font-bold text-center" x-text="formatRupiah(paymentData.total_pembayaran)"></p>
                                        <button @click="handleCopyAmount()"
                                            class="h-8 w-8 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors"
                                            title="Salin nominal">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-muted-foreground text-center">
                                        <span x-show="paymentData.payment_unique > 0">
                                            (termasuk kode unik: <span x-text="paymentData.payment_unique"></span>)
                                        </span>
                                    </p>
                                </div>

                                <!-- Timer -->
                                <div class="bg-muted rounded-lg p-4 text-center space-y-2">
                                    <div class="flex items-center justify-center gap-2 text-muted-foreground">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm">Sisa Waktu</span>
                                    </div>
                                    <p class="text-3xl font-bold font-mono" 
                                       :class="{'text-destructive': timeRemaining <= 60}"
                                       x-text="formattedTime"></p>
                                </div>

                                <!-- Payment Method Badge -->
                                <div class="flex justify-center">
                                    <span
                                        class="inline-flex items-center rounded-full bg-secondary px-3 py-1 text-sm font-medium text-secondary-foreground"
                                        x-text="paymentData.metode_pembayaran || 'QRIS'"></span>
                                </div>

                                <!-- Check Payment Button -->
                                <button @click="handleCheckPayment()"
                                    :disabled="checkingPayment"
                                    class="w-full inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors disabled:opacity-50">
                                    <svg x-show="checkingPayment" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="checkingPayment ? 'Memeriksa...' : 'Cek Status Pembayaran'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Instructions Card -->
                        <div class="rounded-lg border bg-white shadow-sm">
                            <div class="p-6 border-b">
                                <h3 class="text-base font-semibold">Cara Pembayaran</h3>
                            </div>
                            <div class="p-6">
                                <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                    <li>Buka aplikasi mobile banking atau e-wallet Anda</li>
                                    <li>Pilih menu Scan QR / QRIS</li>
                                    <li>Arahkan kamera ke QR code di atas</li>
                                    <li>Pastikan nominal sesuai: <strong x-text="formatRupiah(paymentData.total_pembayaran)"></strong></li>
                                    <li>Konfirmasi dan selesaikan pembayaran</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="lg:col-span-2">
                        <div class="rounded-lg border bg-white shadow-sm">
                            <div class="p-6 border-b">
                                <h3 class="text-lg font-semibold">Detail Pesanan</h3>
                                <p class="text-sm text-muted-foreground mt-1">
                                    Batch ID: <span class="font-mono" x-text="paymentData.batch_id"></span>
                                </p>
                            </div>
                            <div class="p-6">
                                <div class="relative overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-xs uppercase bg-muted/50">
                                            <tr>
                                                <th class="px-4 py-3">No</th>
                                                <th class="px-4 py-3">Nomor HP</th>
                                                <th class="px-4 py-3">Paket</th>
                                                <th class="px-4 py-3">Status</th>
                                                <th class="px-4 py-3 text-right">Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(item, index) in paymentData.items" :key="index">
                                                <tr class="border-b">
                                                    <td class="px-4 py-3 text-muted-foreground" x-text="index + 1"></td>
                                                    <td class="px-4 py-3 font-mono" x-text="formatMsisdn(item.msisdn)"></td>
                                                    <td class="px-4 py-3">
                                                        <div>
                                                            <p class="font-medium" x-text="item.nama_paket"></p>
                                                            <p class="text-xs text-muted-foreground" x-text="item.masa_aktif + ' hari'"></p>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium"
                                                              :class="{
                                                                  'bg-yellow-100 text-yellow-800': item.status_aktivasi === 'proses',
                                                                  'bg-green-100 text-green-800': item.status_aktivasi === 'berhasil',
                                                                  'bg-red-100 text-red-800': item.status_aktivasi === 'gagal'
                                                              }"
                                                              x-text="item.status_aktivasi"></span>
                                                    </td>
                                                    <td class="px-4 py-3 text-right font-medium" x-text="formatRupiah(item.harga_jual)"></td>
                                                </tr>
                                            </template>

                                            <!-- Subtotal -->
                                            <tr class="border-b">
                                                <td colspan="4" class="px-4 py-3 text-right font-medium">Subtotal</td>
                                                <td class="px-4 py-3 text-right font-medium"
                                                    x-text="formatRupiah(paymentData.sub_total)"></td>
                                            </tr>

                                            <!-- Platform Fee -->
                                            <tr class="border-b" x-show="paymentData.biaya_platform > 0">
                                                <td colspan="4" class="px-4 py-3 text-right text-muted-foreground">Biaya Platform</td>
                                                <td class="px-4 py-3 text-right text-muted-foreground"
                                                    x-text="formatRupiah(paymentData.biaya_platform)"></td>
                                            </tr>

                                            <!-- Payment Unique -->
                                            <tr class="border-b" x-show="paymentData.payment_unique > 0">
                                                <td colspan="4" class="px-4 py-3 text-right text-muted-foreground">Kode Unik</td>
                                                <td class="px-4 py-3 text-right text-muted-foreground"
                                                    x-text="formatRupiah(paymentData.payment_unique)"></td>
                                            </tr>

                                            <!-- Total -->
                                            <tr class="border-t-2">
                                                <td colspan="4" class="px-4 py-3 text-right font-bold text-lg">Total Pembayaran</td>
                                                <td class="px-4 py-3 text-right font-bold text-lg text-primary"
                                                    x-text="formatRupiah(paymentData.total_pembayaran)"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <!-- Page Script -->
    <script>
        function paymentApp() {
            return {
                // State
                loading: true,
                error: false,
                errorMessage: '',
                paymentStatus: 'pending', // 'pending', 'success', 'expired', 'failed'
                checkingPayment: false,

                // Payment data from API
                paymentData: {
                    payment_id: null,
                    batch_id: '',
                    sub_total: 0,
                    biaya_platform: 0,
                    payment_unique: 0,
                    total_pembayaran: 0,
                    metode_pembayaran: 'QRIS',
                    items: [],
                },

                // QR Code
                qrCodeUrl: null,

                // Timer
                timeRemaining: 0,
                timerInterval: null,
                paymentCheckInterval: null,

                // Toast
                toastVisible: false,
                toastTitle: '',
                toastMessage: '',

                // Lifecycle
                async init() {
                    // Get payment ID from URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const paymentId = urlParams.get('id');

                    if (!paymentId) {
                        this.error = true;
                        this.errorMessage = 'ID pembayaran tidak ditemukan';
                        this.loading = false;
                        return;
                    }

                    // Fetch payment data from API
                    await this.fetchPaymentData(paymentId);
                },

                // Fetch payment data
                async fetchPaymentData(paymentId) {
                    try {
                        const response = await fetch(`${API_BASE}/umroh/payment/status?id=${paymentId}`);
                        const data = await response.json();

                        if (!data.success) {
                            throw new Error(data.message || 'Gagal mengambil data pembayaran');
                        }

                        // Set payment data
                        this.paymentData = {
                            payment_id: data.data.payment_id,
                            batch_id: data.data.batch_id,
                            sub_total: this.calculateSubtotal(data.data.items),
                            biaya_platform: 0, // Will be calculated from response
                            payment_unique: 0,
                            total_pembayaran: data.data.total_pembayaran,
                            metode_pembayaran: data.data.status_pembayaran === 'menunggu pembayaran' ? 'QRIS' : data.data.metode_pembayaran,
                            items: data.data.items || [],
                        };

                        // Set QR Code
                        if (data.data.qris && data.data.qris.qr_code_url) {
                            this.qrCodeUrl = data.data.qris.qr_code_url;
                        }

                        // Set status
                        this.paymentStatus = data.data.status;

                        // Set timer
                        this.timeRemaining = data.data.remaining_time || 0;

                        // Start timer if pending
                        if (this.paymentStatus === 'pending' && this.timeRemaining > 0) {
                            this.startTimer();
                            this.startPaymentPolling();
                        }

                        this.loading = false;
                    } catch (err) {
                        console.error('Error fetching payment:', err);
                        this.error = true;
                        this.errorMessage = err.message || 'Gagal memuat data pembayaran';
                        this.loading = false;
                    }
                },

                // Calculate subtotal from items
                calculateSubtotal(items) {
                    if (!items || !items.length) return 0;
                    return items.reduce((sum, item) => sum + (item.harga_jual || 0), 0);
                },

                // Computed: Formatted time
                get formattedTime() {
                    const minutes = Math.floor(this.timeRemaining / 60);
                    const seconds = this.timeRemaining % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },

                // Start countdown timer
                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.timeRemaining <= 1) {
                            clearInterval(this.timerInterval);
                            clearInterval(this.paymentCheckInterval);
                            this.paymentStatus = 'expired';
                            this.timeRemaining = 0;
                        } else {
                            this.timeRemaining--;
                        }
                    }, 1000);
                },

                // Start polling for payment status
                startPaymentPolling() {
                    // Check every 5 seconds
                    this.paymentCheckInterval = setInterval(async () => {
                        await this.checkPaymentStatus();
                    }, 5000);
                },

                // Check payment status
                async checkPaymentStatus() {
                    if (!this.paymentData.payment_id) return;

                    try {
                        const response = await fetch(`${API_BASE}/umroh/payment/status?id=${this.paymentData.payment_id}`);
                        const data = await response.json();

                        if (data.success) {
                            if (data.data.status === 'success') {
                                this.paymentStatus = 'success';
                                clearInterval(this.paymentCheckInterval);
                                clearInterval(this.timerInterval);
                                this.showToast('Pembayaran Berhasil', 'Pembayaran telah dikonfirmasi');
                            } else if (data.data.status === 'expired' || data.data.status === 'failed') {
                                this.paymentStatus = data.data.status;
                                clearInterval(this.paymentCheckInterval);
                                clearInterval(this.timerInterval);
                            }
                        }
                    } catch (err) {
                        console.error('Error checking payment status:', err);
                    }
                },

                // Manual check payment
                async handleCheckPayment() {
                    this.checkingPayment = true;
                    this.showToast('Memeriksa pembayaran', 'Mohon tunggu...');

                    await this.checkPaymentStatus();

                    if (this.paymentStatus === 'pending') {
                        this.showToast('Pembayaran Pending', 'Pembayaran belum diterima, mohon selesaikan pembayaran');
                    }

                    this.checkingPayment = false;
                },

                // Copy amount to clipboard
                handleCopyAmount() {
                    navigator.clipboard.writeText(this.paymentData.total_pembayaran.toString());
                    this.showToast('Berhasil disalin', 'Nominal pembayaran telah disalin ke clipboard');
                },

                // Format number to Rupiah
                formatRupiah(number) {
                    if (!number) return 'Rp 0';
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                },

                // Format MSISDN for display
                formatMsisdn(msisdn) {
                    if (!msisdn) return '-';
                    // Format 628xxx to 08xxx
                    if (msisdn.startsWith('62')) {
                        return '0' + msisdn.substring(2);
                    }
                    return msisdn;
                },

                // Show toast notification
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

    <style>
        [x-cloak] { display: none !important; }
        
        .toast {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            background: white;
            border: 1px solid hsl(var(--border));
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            z-index: 100;
            max-width: 300px;
        }
    </style>
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Kuotaumroh.id</title>

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

    <!-- QR Code Generator Library (local copy) -->
    <script src="{{ asset('shared/qrcode.min.js') }}"></script>

    <!-- ⚠️ PENTING: Load config.js PERTAMA sebelum script lain -->
    <script src="{{ asset('shared/config.js') }}?v={{ time() }}"></script>

    <!-- Shared Scripts -->
    <script src="{{ asset('shared/utils.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('shared/api.js') }}?v={{ time() }}"></script>

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
    <div x-data="checkoutApp()">

        <!-- Header -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-9 w-9 object-contain">
                    <span class="text-xl font-semibold">Kuotaumroh.id</span>
                </a>

                <!-- Simple Text (no dropdown for public users) -->
                <span class="text-sm text-muted-foreground">Checkout</span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto py-6 animate-fade-in px-4">

            <!-- Success State -->
            <div x-show="paymentStatus === 'success'" x-cloak class="flex items-center justify-center min-h-[60vh]">
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
                            Pesanan Anda sedang diproses. Paket akan segera diaktifkan.
                        </p>
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
            <div x-show="paymentStatus === 'expired'" x-cloak class="flex items-center justify-center min-h-[60vh]">
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
            <div x-show="paymentStatus === 'pending'" x-cloak>
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
                                <h3 class="text-lg font-semibold flex items-center gap-2 mb-4">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    Scan QR Code QRIS
                                </h3>
                                
                                <!-- Payment ID Info -->
                                <div class="bg-white-50 border border-black-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-xs text-black-600 font-medium mb-1">Payment ID</p>
                                            <p class="text-sm font-mono text-black-900 font-semibold" x-text="paymentId"></p>
                                        </div>
                                        <button @click="navigator.clipboard.writeText(paymentId); showToast('Tersalin', 'Payment ID berhasil disalin')"
                                            class="ml-2 h-8 w-8 inline-flex items-center justify-center rounded-md hover:bg-blue-100 transition-colors flex-shrink-0">
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-black-600 mt-2">Simpan ID ini untuk verifikasi pembayaran</p>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- QR Code Container -->
                                <div class="flex justify-center">
                                    <div class="bg-white p-4 rounded-lg border-2 border-border">
                                        <!-- Loading state when QR is not ready -->
                                        <div x-show="!qrisString" class="w-48 h-48 flex items-center justify-center bg-gray-100 rounded">
                                            <div class="text-center">
                                                <svg class="animate-spin h-8 w-8 mx-auto text-primary mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <p class="text-sm text-muted-foreground">Memuat QR Code...</p>
                                            </div>
                                        </div>
                                        <!-- QR Code will be generated here by qrcodejs -->
                                        <div x-show="qrisString" id="qrContainer" class="flex items-center justify-center"></div>
                                    </div>
                                </div>
                                
                                <!-- Toggle Static QRIS (untuk bank BCA dll yang gagal) -->
                                <template x-if="qrisStaticString">
                                    <div class="flex items-center justify-center gap-2">
                                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                                            <input type="checkbox" x-model="useStaticQris" @change="generateQRCode()"
                                                class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="text-muted-foreground">Pakai QRIS Statis (BCA/Bank lain gagal)</span>
                                        </label>
                                    </div>
                                </template>

                                <!-- Amount -->
                                <div class="space-y-2">
                                    <p class="text-sm text-muted-foreground text-center">Total Pembayaran</p>
                                    <div class="flex items-center justify-center gap-2">
                                        <p class="text-2xl font-bold text-center" x-text="formatRupiah(totalAmount)"></p>
                                        <button @click="handleCopyAmount()"
                                            class="h-8 w-8 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
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
                                    <p class="text-3xl font-bold font-mono" x-text="formattedTime"></p>
                                </div>

                                <!-- Payment Method Badge -->
                                <div class="flex justify-center">
                                    <span
                                        class="inline-flex items-center rounded-full bg-secondary px-3 py-1 text-sm font-medium text-secondary-foreground"
                                        x-text="paymentMethodLabel"></span>
                                </div>

                            <!-- Important Note & Instructions Combined -->
                            <div class="space-y-4">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <p class="text-sm text-yellow-800 font-medium mb-2">⚠️ Catatan Penting:</p>
                                    <p class="text-sm text-yellow-700">
                                        Harap membayar sesuai dengan nominal yang tertera.
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-semibold mb-3">Cara Pembayaran</h4>
                                    <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                        <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                                        <li>Scan QR Code QRIS di atas</li>
                                        <li>Pastikan nominal pembayaran sesuai</li>
                                        <li>Konfirmasi pembayaran</li>
                                        <li>Simpan bukti pembayaran</li>
                                    </ol>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                <!-- Check Payment Button -->
                                <button @click="handleCheckPayment()"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Cek Status Pembayaran
                                </button>

                                <!-- View Invoice Button -->
                                <button @click="handleViewInvoice()"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Lihat Invoice
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="lg:col-span-2">
                        <div class="rounded-lg border bg-white shadow-sm">
                            <div class="p-6 border-b">
                                <h3 class="text-lg font-semibold">Detail Pesanan</h3>
                            </div>
                            <div class="p-6">
                                <div class="relative overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-xs uppercase bg-muted/50">
                                            <tr>
                                                <th class="px-4 py-3">No</th>
                                                <th class="px-4 py-3">Nomor HP</th>
                                                <th class="px-4 py-3">Paket</th>
                                                <th class="px-4 py-3 text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(item, index) in orderData.items"
                                                :key="index">
                                                <tr class="border-b">
                                                    <td class="px-4 py-3 text-muted-foreground"
                                                        x-text="index + 1"></td>
                                                    <td class="px-4 py-3 font-mono" x-text="item.msisdn"></td>
                                                    <td class="px-4 py-3" x-text="item.packageName"></td>
                                                    <td class="px-4 py-3 text-right font-medium"
                                                        x-text="formatRupiah(item.price)"></td>
                                                </tr>
                                            </template>

                                            <!-- Subtotal -->
                                            <tr class="border-b">
                                                <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal</td>
                                                <td class="px-4 py-3 text-right font-medium"
                                                    x-text="formatRupiah(orderData.total)"></td>
                                            </tr>

                                            <!-- Platform Fee -->
                                            <tr class="border-b">
                                                <td colspan="3"
                                                    class="px-4 py-3 text-right text-muted-foreground">Biaya Platform</td>
                                                <td class="px-4 py-3 text-right text-muted-foreground"
                                                    x-text="formatRupiah(orderData.platformFee)"></td>
                                            </tr>

                                            <!-- Total -->
                                            <tr class="border-t-2">
                                                <td colspan="3"
                                                    class="px-4 py-3 text-right font-bold text-lg">Total Pembayaran</td>
                                                <td class="px-4 py-3 text-right font-bold text-lg"
                                                    x-text="formatRupiah(totalAmount)"></td>
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
        function checkoutApp() {
            return {
                // Payment state
                paymentStatus: 'pending', // 'pending', 'success', 'expired'
                timeRemaining: 15 * 60, // 15 minutes in seconds

                // Order data from localStorage
                orderData: {
                    items: [],
                    total: 0,
                    platformFee: 0,
                    paymentMethod: 'qris',
                    refCode: null,
                },

                // Payment transaction data
                paymentId: null,
                batchId: null,
                qrCodeUrl: null,
                qrisString: null,      // QRIS string for dynamic QR
                qrisStaticString: null, // QRIS static string (untuk bank yg gagal)
                useStaticQris: false,   // Toggle untuk pakai QRIS static
                paymentAmount: 0,       // Total pembayaran dari API

                // Toast
                toastVisible: false,
                toastTitle: '',
                toastMessage: '',

                // Timer interval
                timerInterval: null,
                paymentCheckInterval: null,

                // Lifecycle
                async init() {
                    // Watch for QRIS type toggle
                    this.$watch('useStaticQris', (value) => {
                        console.log('🔄 Switched to', value ? 'STATIC' : 'DYNAMIC', 'QRIS');
                        this.generateQRCode();
                    });
                    
                    // Load order data from localStorage
                    const savedOrderData = localStorage.getItem('pendingOrder');
                    if (!savedOrderData) {
                        // Redirect back if no order data
                        window.location.href = '{{ route('welcome') }}';
                        return;
                    }

                    const parsedData = JSON.parse(savedOrderData);

                    // Map the data structure to match payment page expectations
                    this.orderData = {
                        items: parsedData.items || [],
                        total: parsedData.subtotal || 0,
                        platformFee: parsedData.platformFee || 0,
                        paymentMethod: parsedData.paymentMethod || 'qris',
                        refCode: parsedData.refCode || null,
                        scheduleDate: parsedData.scheduleDate || null,
                        // Detect if this is bulk (agent) or individual (public) payment
                        // Bulk: has refCode (agent_id), Individual: refCode = null or '0'
                        isBulk: parsedData.isBulk !== undefined ? parsedData.isBulk : (parsedData.refCode && parsedData.refCode !== '0' && parsedData.refCode !== 'guest'),
                    };
                    
                    console.log('📦 Order mode:', this.orderData.isBulk ? 'BULK (Agent)' : 'INDIVIDUAL (Public)');

                    // Check if payment already exists (user refreshed page)
                    if (parsedData.paymentId) {
                        console.log('♻️ Payment sudah ada, menggunakan payment yang sama:', parsedData.paymentId);
                        this.paymentId = parsedData.paymentId;
                        this.batchId = parsedData.batchId || null;
                        
                        // Fetch existing QRIS data
                        await this.fetchQrisData();
                    } else {
                        // Create new payment transaction via API
                        console.log('🆕 Membuat payment baru...');
                        await this.createPayment();
                    }

                    // Start countdown timer
                    this.startTimer();

                    // Start polling for payment status
                    this.startPaymentPolling();
                },

                // Computed: Total amount - prioritize API payment_amount
                get totalAmount() {
                    if (this.paymentAmount > 0) {
                        return this.paymentAmount;
                    }
                    return this.orderData.total + this.orderData.platformFee;
                },

                // Computed: Formatted time
                get formattedTime() {
                    const minutes = Math.floor(this.timeRemaining / 60);
                    const seconds = this.timeRemaining % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },

                // Computed: Payment method label
                get paymentMethodLabel() {
                    return this.orderData.paymentMethod === 'qris' ? 'QRIS' : this.orderData.paymentMethod.toUpperCase();
                },

                // Generate QR Code from QRIS string
                qrCodeInstance: null,
                
                generateQRCode() {
                    const qrisData = this.useStaticQris ? this.qrisStaticString : this.qrisString;
                    if (!qrisData) {
                        console.log('⚠️ No QRIS data available');
                        return;
                    }
                    
                    const container = document.getElementById('qrContainer');
                    if (!container) {
                        console.log('⚠️ QR Container not found');
                        return;
                    }
                    
                    console.log('🎨 Generating QR Code...', this.useStaticQris ? 'STATIC' : 'DYNAMIC');
                    
                    // Clear previous QR code
                    container.innerHTML = '';
                    
                    try {
                        // qrcodejs library uses constructor
                        this.qrCodeInstance = new QRCode(container, {
                            text: qrisData,
                            width: 192,
                            height: 192,
                            colorDark: '#000000',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.M
                        });
                        console.log('✅ QR Code generated successfully');
                    } catch (error) {
                        console.error('❌ QR Code generation error:', error);
                    }
                },

                // Fetch QRIS data from payment endpoint
                async fetchQrisData() {
                    if (!this.paymentId) return;
                    
                    try {
                        console.log('📥 Fetching QRIS data for payment:', this.paymentId);
                        const response = await getPaymentStatus(this.paymentId);
                        
                        // Response is array, get first item
                        const data = Array.isArray(response) ? response[0] : response;
                        
                        console.log('📦 Payment data:', data);
                        
                        if (data && data.qris) {
                            this.qrisString = data.qris;
                            this.qrisStaticString = data.qris_static || null;
                            console.log('✅ QRIS data received');
                            
                            // Generate QR code
                            this.$nextTick(() => {
                                this.generateQRCode();
                            });
                        }
                        
                        // Update payment amount if available
                        if (data && data.payment_amount) {
                            this.paymentAmount = parseInt(data.payment_amount) || 0;
                            this.orderData.paymentUnique = parseInt(data.payment_unique) || 0;
                            console.log('💰 Payment amount:', this.paymentAmount);
                        }
                        
                        // Update time remaining
                        if (data && data.payment_expired) {
                            const expiredDate = new Date(data.payment_expired);
                            const now = new Date();
                            const remainingMs = expiredDate - now;
                            this.timeRemaining = Math.max(0, Math.floor(remainingMs / 1000));
                            console.log('⏰ Time remaining:', this.timeRemaining, 'seconds');
                        }
                    } catch (error) {
                        console.error('❌ Error fetching QRIS:', error);
                    }
                },

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

                async createPayment() {
                    try {
                        let response;
                        
                        if (this.orderData.isBulk) {
                            // =============================
                            // BULK PAYMENT (Agent / Travel)
                            // =============================
                            console.log('💳 Creating BULK payment transaction...');
                            
                            const batchId = 'BATCH_' + Date.now();
                            const batchName = 'ORDER_' + new Date().toISOString().slice(0,10).replace(/-/g,'');
                            
                            // Extract msisdn and package_id arrays from items
                            const msisdnList = this.orderData.items.map(item => {
                                let msisdn = item.msisdn || item.phoneNumber;
                                if (msisdn.startsWith('08')) {
                                    msisdn = '62' + msisdn.substring(1);
                                } else if (msisdn.startsWith('8')) {
                                    msisdn = '62' + msisdn;
                                }
                                return msisdn;
                            });
                            
                            const packageIdList = this.orderData.items.map(item => {
                                return item.packageId || item.package_id;
                            });

                            let detail = null;
                            if (this.orderData.scheduleDate) {
                                detail = `{date: ${this.orderData.scheduleDate}}`;
                            }

                            const requestData = {
                                batch_id: batchId,
                                batch_name: batchName,
                                payment_method: 'QRIS',
                                detail: detail,
                                ref_code: this.orderData.refCode || '1',
                                msisdn: msisdnList,          // Array
                                package_id: packageIdList,   // Array
                            };

                            console.log('📤 Sending BULK payment request:', requestData);
                            response = await createBulkPayment(requestData);
                            
                        } else {
                            // =============================
                            // INDIVIDUAL PAYMENT (Homepage / Public)
                            // =============================
                            console.log('💳 Creating INDIVIDUAL payment transaction...');
                            
                            // For individual, we process each item separately
                            const item = this.orderData.items[0]; // Get first item
                            
                            // Format msisdn (convert 08xx to 62xx)
                            let msisdn = item.msisdn || item.phoneNumber;
                            if (msisdn.startsWith('08')) {
                                msisdn = '62' + msisdn.substring(1);
                            } else if (msisdn.startsWith('8')) {
                                msisdn = '62' + msisdn;
                            }

                            let detail = null;
                            if (this.orderData.scheduleDate) {
                                detail = `{date: ${this.orderData.scheduleDate}}`;
                            }

                            // INDIVIDU Payment: TANPA batch_id, batch_name
                            // package_id dan msisdn bertipe STRING (bukan array)
                            const requestData = {
                                payment_method: 'QRIS',
                                detail: detail,
                                ref_code: this.orderData.refCode || '0',
                                msisdn: msisdn,  // String (untuk validasi provider)
                                package_id: String(item.packageId || item.package_id),  // String (bukan array)
                            };

                            console.log('📤 Sending INDIVIDUAL payment request:', requestData);
                            response = await createIndividualPayment(requestData);
                        }
                        
                        console.log('📥 Payment response:', response);

                        // Handle both response formats:
                        // Format 1: { success: true, data: {...} }
                        // Format 2: Direct data { id: '...', qris: {...}, ... }
                        const data = response.data || response;
                        const isSuccess = response.success === true || (data && data.id);
                        
                        if (isSuccess && data) {
                            this.paymentId = data.payment_id || data.id;
                            this.batchId = data.batch_id || data.location_id;
                            
                            console.log('🔍 Checking QRIS data:', {
                                has_qris: !!data.qris,
                                qris: data.qris,
                                qr_code_url: data.qris?.qr_code_url
                            });
                            
                            // Set QR code URL - cek multiple possible locations
                            if (data.qris && data.qris.qr_code_url) {
                                this.qrCodeUrl = data.qris.qr_code_url;
                                console.log('✅ QR Code URL set:', this.qrCodeUrl);
                            } else if (data.qr_code_url) {
                                this.qrCodeUrl = data.qr_code_url;
                                console.log('✅ QR Code URL set (fallback):', this.qrCodeUrl);
                            } else if (data.payment_method === 'QRIS') {
                                // Generate QR placeholder or show alternative payment method
                                console.log('⚠️ QR Code URL not provided by API, using placeholder');
                                // Keep qrCodeUrl as null, will show placeholder
                            }
                            
                            // Update time remaining from server
                            if (data.remaining_time) {
                                this.timeRemaining = data.remaining_time;
                            } else if (data.payment_expired) {
                                // Calculate remaining time from payment_expired
                                const expiredDate = new Date(data.payment_expired);
                                const now = new Date();
                                const remainingMs = expiredDate - now;
                                this.timeRemaining = Math.max(0, Math.floor(remainingMs / 1000));
                                console.log('⏰ Calculated remaining time:', this.timeRemaining, 'seconds');
                            }

                            // Update total from server (includes payment_unique)
                            if (data.total_pembayaran) {
                                this.orderData.total = data.sub_total || this.orderData.total;
                                this.orderData.platformFee = data.platform_fee || 0;
                                this.orderData.paymentUnique = data.payment_unique || 0;
                            } else if (data.payment_amount) {
                                // Use payment_amount from tokodigi API
                                const paymentAmount = parseInt(data.payment_amount) || 0;
                                const paymentUnique = parseInt(data.payment_unique) || 0;
                                this.orderData.paymentUnique = paymentUnique;
                                console.log('💰 Payment amount:', paymentAmount, 'Unique:', paymentUnique);
                            }

                            console.log('✅ Payment created:', this.paymentId);
                            
                            // Save paymentId to localStorage agar tidak generate ulang saat refresh
                            const savedOrder = localStorage.getItem('pendingOrder');
                            if (savedOrder) {
                                const orderData = JSON.parse(savedOrder);
                                orderData.paymentId = this.paymentId;
                                orderData.batchId = this.batchId;
                                localStorage.setItem('pendingOrder', JSON.stringify(orderData));
                                console.log('💾 PaymentId disimpan ke localStorage');
                            }
                            
                            // Fetch QRIS data after payment created
                            await this.fetchQrisData();
                        } else {
                            throw new Error(response.message || 'Gagal membuat transaksi');
                        }
                    } catch (error) {
                        console.error('❌ Failed to create payment:', error);
                        this.showToast('Error', error.message || 'Gagal membuat transaksi pembayaran');
                    }
                },

                startPaymentPolling() {
                    if (!this.paymentId) return;

                    // Check payment status every 5 seconds
                    this.paymentCheckInterval = setInterval(async () => {
                        try {
                            const response = await getPaymentStatus(this.paymentId);
                            
                            // Response is array, get first item
                            const rawData = Array.isArray(response) ? response[0] : (response.data || response);
                            const data = rawData;

                            if (data && data.id) {
                                // Update QRIS strings if available
                                if (data.qris && !this.qrisString) {
                                    this.qrisString = data.qris;
                                    this.qrisStaticString = data.qris_static || null;
                                    console.log('✅ QRIS data updated from polling');
                                    this.$nextTick(() => this.generateQRCode());
                                }
                                
                                const status = data.status || data.payment_status;
                                console.log('📊 Payment status:', status);
                                
                                if (status === 'success' || status === 'PAID') {
                                    this.paymentStatus = 'success';
                                    clearInterval(this.paymentCheckInterval);
                                    clearInterval(this.timerInterval);
                                    localStorage.removeItem('pendingOrder');
                                    this.showToast('Pembayaran Berhasil', 'Pembayaran telah dikonfirmasi');
                                } else if (status === 'expired' || status === 'failed' || status === 'EXPIRED' || status === 'FAILED') {
                                    this.paymentStatus = 'expired';
                                    clearInterval(this.paymentCheckInterval);
                                    clearInterval(this.timerInterval);
                                    localStorage.removeItem('pendingOrder');
                                }
                            }
                        } catch (error) {
                            console.error('Failed to check payment status:', error);
                        }
                    }, 5000); // Check every 5 seconds
                },

                handleCopyAmount() {
                    const totalWithUnique = this.totalAmount + (this.orderData.paymentUnique || 0);
                    navigator.clipboard.writeText(totalWithUnique.toString());
                    this.showToast(
                        'Berhasil disalin',
                        'Nominal pembayaran telah disalin ke clipboard'
                    );
                },

                async handleCheckPayment() {
                    if (!this.paymentId) {
                        this.showToast('Error', 'Payment ID tidak ditemukan');
                        return;
                    }

                    this.showToast(
                        'Memeriksa pembayaran',
                        'Mohon tunggu, kami sedang memeriksa status pembayaran Anda...'
                    );

                    // Check payment status via API
                    try {
                        const response = await getPaymentStatus(this.paymentId);
                        
                        // Response is array, get first item
                        const rawData = Array.isArray(response) ? response[0] : (response.data || response);
                        const data = rawData;
                        const status = data.status || data.payment_status;
                        
                        console.log('🔍 Manual check - Status:', status, 'Data:', data);

                        if (data && data.id) {
                            // Update QRIS if available
                            if (data.qris && !this.qrisString) {
                                this.qrisString = data.qris;
                                this.qrisStaticString = data.qris_static || null;
                                this.$nextTick(() => this.generateQRCode());
                            }
                            
                            if (status === 'success' || status === 'PAID') {
                                this.paymentStatus = 'success';
                                clearInterval(this.timerInterval);
                                if (this.paymentCheckInterval) {
                                    clearInterval(this.paymentCheckInterval);
                                }
                                localStorage.removeItem('pendingOrder');
                                this.showToast(
                                    'Pembayaran Berhasil',
                                    'Pembayaran Anda telah dikonfirmasi'
                                );
                            } else if (status === 'pending' || status === 'PENDING' || status === 'UNPAID' || status === 'VERIFY') {
                                this.showToast(
                                    'Menunggu Pembayaran',
                                    'Pembayaran belum diterima. Silakan scan QR code dan selesaikan pembayaran.'
                                );
                            } else {
                                this.showToast(
                                    'Status Pembayaran',
                                    'Status: ' + status
                                );
                            }
                        } else {
                            this.showToast('Error', response.message || 'Gagal memeriksa status');
                        }
                    } catch (error) {
                        console.error('Failed to check payment:', error);
                        this.showToast('Error', 'Gagal memeriksa status pembayaran');
                    }
                },

                handleViewInvoice() {
                    if (!this.paymentId) {
                        this.showToast('Error', 'Payment ID tidak ditemukan');
                        return;
                    }

                    // Buka invoice di tab baru dengan payment ID
                    const invoiceUrl = `/invoice/${this.paymentId}`;
                    window.open(invoiceUrl, '_blank');
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

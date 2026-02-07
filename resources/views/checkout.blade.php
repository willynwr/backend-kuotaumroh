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

    <!-- Font Awesome (Step Indicator Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="min-h-screen bg-background overflow-x-hidden">
    <div x-data="checkoutApp()" x-init="init()">

        <!-- Header -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <!-- Logo -->
                <!-- Logo (Acts as Back Button) -->
                <a href="#" @click.prevent="handleUiBack('{{ route('welcome') }}')" class="flex items-center gap-2">
                    <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-9 w-9 object-contain">
                    <span class="text-xl font-semibold">Kuotaumroh.id</span>
                </a>

                <!-- Status Pembayaran Badge - Hidden on mobile, shown on desktop -->
                <div class="hidden sm:flex items-center gap-2">
                    <!-- Step Indicator Desktop -->
                    <div class="step-indicator flex items-center gap-1">
                        <!-- Step 1: Pilih -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs bg-green-500 text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600">Pilih Paket</span>
                        </div>
                        <div class="w-4 h-0.5 bg-green-500"></div>
                        
                        <!-- Step 2: Bayar -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs"
                                :class="paymentStatus === 'pending' ? 'bg-yellow-500 text-white animate-pulse' : 'bg-green-500 text-white'">
                                <i class="fas" :class="paymentStatus === 'pending' ? 'fa-clock' : 'fa-check'"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600">Menunggu Pembayaran</span>
                        </div>
                        <div class="w-4 h-0.5" :class="['verifying', 'activated'].includes(paymentStatus) ? 'bg-green-500' : 'bg-gray-300'"></div>
                        
                        <!-- Step 3: Verifikasi Pembayaran -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs"
                                :class="paymentStatus === 'verifying' ? 'bg-yellow-500 text-white animate-pulse' : (paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500')">
                                <i class="fas" :class="paymentStatus === 'verifying' ? 'fa-spinner fa-spin' : (paymentStatus === 'activated' ? 'fa-check' : 'fa-shield-alt')"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600 whitespace-nowrap">Verifikasi Pembayaran</span>
                        </div>
                        <div class="w-4 h-0.5" :class="paymentStatus === 'activated' ? 'bg-green-500' : 'bg-gray-300'"></div>
                        
                        <!-- Step 4: Paket Aktif -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs"
                                :class="paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500'">
                                <i class="fas" :class="paymentStatus === 'activated' ? 'fa-check' : 'fa-box'"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600 whitespace-nowrap">Paket Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Loading Skeleton (shown before QR ready) -->
        <div x-show="isLoading" x-cloak class="container mx-auto py-6 px-4">
            <div class="flex items-center justify-center min-h-[60vh]">
                <div class="text-center space-y-4">
                    <div class="w-20 h-20 mx-auto border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-lg font-medium text-gray-700">Memuat pembayaran...</p>
                    <p class="text-sm text-muted-foreground">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>

        <!-- Main Content (shown after QR ready) -->
        <main x-show="!isLoading" x-cloak class="container mx-auto py-6 animate-fade-in px-4">

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

            
            <!-- Pending/Verifying/Activated State (Payment Page) - Show checkout page -->
            <div x-show="['pending', 'verifying', 'activated'].includes(paymentStatus)" x-cloak class="overflow-hidden">
                <!-- Page Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2 flex-wrap">
                        <a href="{{ route('welcome') }}" class="hover:text-foreground">Beranda</a>
                        <span>/</span>
                        <span class="text-foreground">Pembayaran</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" x-text="paymentStatus === 'pending' ? 'Pembayaran' : (paymentStatus === 'verifying' ? 'Verifikasi Pembayaran' : 'Paket Aktif')"></h1>
                    <p class="text-muted-foreground mt-2 text-sm sm:text-base" x-text="paymentStatus === 'pending' ? 'Selesaikan pembayaran Anda' : (paymentStatus === 'verifying' ? 'Pembayaran sedang diverifikasi...' : 'Paket kuota umroh Anda sudah aktif!')"></p>
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
                                    <span x-text="paymentStatus === 'pending' ? 'Scan QR Code QRIS' : (paymentStatus === 'verifying' ? 'Verifikasi Pembayaran' : 'Paket Aktif ✓')"></span>
                                </h3>
                                
                                <!-- Payment ID Info -->
                                <div class="bg-white-50 border border-black-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-black-600 font-medium mb-1">Payment ID</p>
                                            <p class="text-sm font-mono text-black-900 font-semibold truncate" x-text="paymentId"></p>
                                        </div>
                                        <button @click="navigator.clipboard.writeText(paymentId); showToast('Tersalin', 'Payment ID berhasil disalin')"
                                            class="ml-2 h-10 w-10 inline-flex items-center justify-center rounded-md hover:bg-blue-100 transition-colors flex-shrink-0">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-black-600 mt-2">Simpan ID ini untuk verifikasi pembayaran</p>
                                </div>
                                
                                <!-- Status Pembayaran Step Indicator - Mobile Only (shown below Payment ID) -->
                                <div class="sm:hidden mt-4">
                                    <div class="step-indicator flex justify-between items-center px-2">
                                        <!-- Step 1: Pilih -->
                                        <div class="step-item text-center flex-1">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1 bg-green-500 text-white">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            <span class="text-[10px] font-medium text-gray-600">Pilih Paket</span>
                                        </div>
                                        
                                        <!-- Connector 1-2 -->
                                        <div class="flex-1 h-0.5 bg-green-500 -mt-4"></div>
                                        
                                        <!-- Step 2: Bayar -->
                                        <div class="step-item text-center flex-1">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1"
                                                :class="paymentStatus === 'pending' ? 'bg-yellow-500 text-white animate-pulse' : 'bg-green-500 text-white'">
                                                <i class="fas text-xs" :class="paymentStatus === 'pending' ? 'fa-clock' : 'fa-check'"></i>
                                            </div>
                                            <span class="text-[10px] font-medium text-gray-600">Menunggu Pembayaran</span>
                                        </div>
                                        
                                        <!-- Connector 2-3 -->
                                        <div class="flex-1 h-0.5 -mt-4" :class="['verifying', 'activated'].includes(paymentStatus) ? 'bg-green-500' : 'bg-gray-300'"></div>
                                        
                                        <!-- Step 3: Verifikasi -->
                                        <div class="step-item text-center flex-1">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1"
                                                :class="paymentStatus === 'verifying' ? 'bg-yellow-500 text-white animate-pulse' : (paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500')">
                                                <i class="fas text-xs" :class="paymentStatus === 'verifying' ? 'fa-spinner fa-spin' : (paymentStatus === 'activated' ? 'fa-check' : 'fa-shield-alt')"></i>
                                            </div>
                                            <span class="text-[10px] font-medium text-gray-600">Verifikasi Pembayaran</span>
                                        </div>
                                        
                                        <!-- Connector 3-4 -->
                                        <div class="flex-1 h-0.5 -mt-4" :class="paymentStatus === 'activated' ? 'bg-green-500' : 'bg-gray-300'"></div>
                                        
                                        <!-- Step 4: Paket Aktif -->
                                        <div class="step-item text-center flex-1">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1"
                                                :class="paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500'">
                                                <i class="fas text-xs" :class="paymentStatus === 'activated' ? 'fa-check' : 'fa-box'"></i>
                                            </div>
                                            <span class="text-[10px] font-medium text-gray-600">Paket Aktif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="payment-summary" class="p-6 space-y-4">
                                
                                <!-- Detail Paket & Nomor (di atas catatan penting) -->
                                <div class="bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-100 rounded-lg p-3 text-center">
                                    <template x-if="orderData.items && orderData.items.length > 0">
                                        <div>
                                            <p class="text-xl font-bold text-gray-800 mb-1 font-mono" x-text="orderData.items[0].msisdn"></p>
                                            <p class="text-sm font-semibold text-gray-700" x-text="orderData.items[0].packageName"></p>
                                            <template x-if="orderData.items.length > 1">
                                                <p class="text-xs text-gray-600 mt-1">+ <span x-text="orderData.items.length - 1"></span> nomor lainnya</p>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Catatan Penting -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 font-medium mb-2">Catatan Penting:</p>
                                    <p class="text-sm text-blue-700">
                                        Harap membayar sesuai dengan nominal yang tertera (termasuk kode unik di belakang).
                                    </p>
                                </div>

                                <!-- Check Payment Button -->
                                <button @click="handleCheckPayment()"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-primary text-primary-foreground h-11 px-4 py-2 hover:bg-primary/90 transition-colors font-medium mt-4">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="paymentStatus === 'activated' ? 'Status Terkonfirmasi' : 'Cek Status Pembayaran'"></span>
                                </button>
                                
                                <!-- QR Code Container -->
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="bg-white p-4 rounded-lg border-2 border-border shadow-sm">
                                        <!-- QR Code will be generated here by qrcodejs -->
                                        <div id="qrContainer" class="flex items-center justify-center"></div>
                                    </div>
                                    
                                    <!-- Download QR Button -->
                                    <button @click="handleDownloadQR()"
                                        class="text-sm text-primary font-medium hover:text-primary/80 flex items-center gap-1.5 px-3 py-1.5 rounded-full hover:bg-primary/5 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Simpan QR Code
                                    </button>
                                </div>
                                
                                <!-- Toggle Static QRIS (untuk bank BCA dll yang gagal) -->
                                <template x-if="qrisStaticString">
                                    <div class="flex flex-col items-center justify-center gap-3 mt-4">
                                        <p class="text-xs text-muted-foreground text-center px-4">
                                            Jika pembayaran QRIS di atas gagal <br> (khususnya BCA Mobile), <br> klik tombol di bawah:
                                        </p>
                                        <button @click="useStaticQris = !useStaticQris"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2"
                                            :class="useStaticQris ? 'bg-primary text-primary-foreground hover:bg-primary/90 border-primary' : ''">
                                            <span x-text="useStaticQris ? 'Kembali ke QRIS Utama' : 'QRIS Alternatif'"></span>
                                        </button>
                                    </div>
                                </template>

                                <!-- Amount -->
                                <div class="space-y-2">
                                    <p class="text-sm text-muted-foreground text-center">Pembayaran:</p>
                                    <p class="text-sm text-center text-gray-600">Rp <span x-text="formatNumber(orderData.total)"></span> + <span x-text="orderData.uniqueCode || orderData.platformFee"></span> (kode unik)</p>
                                    <div class="flex items-center justify-center gap-2">
                                        <p class="text-3xl font-bold text-center" x-text="formatRupiah(totalAmount)"></p>
                                        <button @click="handleCopyAmount()"
                                            class="h-10 w-10 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
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

                            <!-- Check Payment Button (Moved Here) -->
                            <div class="space-y-4">
                                
                                <!-- Instructions -->
                                <div>
                                    <h4 class="text-sm font-semibold mb-3">Cara Pembayaran</h4>
                                    <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                        <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                                        <li>Scan QR Code QRIS di atas</li>
                                        <li>Pastikan nominal pembayaran sesuai (dengan 3 digit kode unik)</li>
                                        <li>Konfirmasi pembayaran</li>
                                        <li>Simpan bukti pembayaran</li>
                                        <li>Klik tombol "Cek Status Pembayaran" di atas</li>
                                    </ol>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="lg:col-span-2">
                        <div class="rounded-lg border bg-white shadow-sm">
                            <div class="p-4 sm:p-6 border-b">
                                <h3 class="text-lg font-semibold">Detail Pesanan</h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <!-- Mobile Card View -->
                                <div class="sm:hidden space-y-3">
                                    <template x-for="(item, index) in orderData.items" :key="index">
                                        <div class="border rounded-lg p-3 bg-muted/30">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="text-xs text-muted-foreground">Item <span x-text="index + 1"></span></span>
                                                <span class="font-semibold text-primary" x-text="formatRupiah(item.price)"></span>
                                            </div>
                                            <p class="font-mono text-sm mb-1" x-text="item.msisdn"></p>
                                            <p class="text-sm text-muted-foreground" x-text="item.packageName"></p>
                                        </div>
                                    </template>
                                    
                                    <!-- Summary Mobile -->
                                    <div class="border-t pt-3 mt-3 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span>Subtotal</span>
                                            <span class="font-medium" x-text="formatRupiah(orderData.total)"></span>
                                        </div>
                                        <div class="flex justify-between text-sm text-muted-foreground">
                                            <span>Kode Unik</span>
                                            <span x-text="formatRupiah(orderData.uniqueCode || orderData.platformFee)"></span>
                                        </div>
                                        <div class="flex justify-between pt-2 border-t">
                                            <span class="font-bold">Total Pembayaran</span>
                                            <span class="font-bold text-primary" x-text="formatRupiah(totalAmount)"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Table View -->
                                <div class="hidden sm:block overflow-x-auto">
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

                                            <!-- Kode Unik (Biaya Platform) -->
                                            <tr class="border-b">
                                                <td colspan="3"
                                                    class="px-4 py-3 text-right text-muted-foreground">Kode Unik</td>
                                                <td class="px-4 py-3 text-right text-muted-foreground"
                                                    x-text="formatRupiah(orderData.uniqueCode || orderData.platformFee)"></td>
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

        <!-- Error Modal -->
        <div x-show="errorModalVisible" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             @click.self="errorModalVisible = false">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
                
                <!-- Modal panel -->
                <div x-show="errorModalVisible"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="errorModalTitle"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-text="errorModalMessage"></p>
                                <p class="text-xs text-gray-400 mt-2">
                                    Kembali dalam <span x-text="errorModalCountdown"></span> detik...
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button @click="redirectAfterError()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-primary-foreground hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Exit Confirmation Modal (Custom UI) -->
        <div x-show="showExitModal" 
             style="display: none;"
             class="fixed inset-0 z-[60] overflow-y-auto"
             x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Background overlay -->
                <div x-show="showExitModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 blur-sm" 
                     @click="showExitModal = false"
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Panel -->
                <div x-show="showExitModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                                    Batalkan Pembayaran?
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Transaksi Anda belum selesai. Jika Anda keluar sekarang, pembayaran ini akan dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button @click="showExitModal = false" type="button" 
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Lanjutkan Pembayaran
                        </button>
                        <button @click="confirmExit()" type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Batalkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Success Modal (Pembayaran Berhasil) -->
        <div x-show="showSuccessModal" 
             style="display: none;"
             class="fixed inset-0 z-[60] overflow-y-auto"
             x-cloak>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Background overlay -->
                <div x-show="showSuccessModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 blur-sm" 
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Panel -->
                <div x-show="showSuccessModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                    
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 px-6 pt-6 pb-5">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-500 mb-4">
                                <svg class="h-9 w-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                Pembayaran Berhasil!
                            </h3>
                            <p class="text-sm text-gray-600">
                                Paket kuota umroh Anda sudah aktif dan siap digunakan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-5 space-y-3">
                        <button @click="redirectToInvoice()" type="button" 
                            class="w-full inline-flex justify-center items-center rounded-lg px-4 py-3 bg-green-600 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Lihat Invoice
                        </button>
                        <button @click="redirectToHome()" type="button" 
                            class="w-full inline-flex justify-center items-center rounded-lg px-4 py-3 bg-gray-100 text-sm font-semibold text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span x-text="sourceType === 'store' ? 'Kembali ke Store' : 'Kembali ke Order'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Script -->
    <script>
        function checkoutApp() {
            return {
                // Loading state
                isLoading: true,
                
                // Payment state - akan di-restore dari localStorage jika ada
                paymentStatus: 'verifying', // 'pending', 'verifying', 'activated', 'expired'
                timeRemaining: 15 * 60, // 15 minutes in seconds

                // Order data from localStorage
                orderData: {
                    items: [],
                    total: 0,
                    platformFee: 0,
                    uniqueCode: 0,
                    paymentMethod: 'qris',
                    refCode: null,
                },

                // Payment transaction data
                paymentId: null,
                batchId: null,
                qrCodeUrl: null,
                qrisString: null,      // QRIS string for dynamic QR
                qrisStaticString: null, // QRIS static string (untuk bank yg gagal)
                qrisStaticDynamicString: null, // QRIS static converted to dynamic
                useStaticQris: false,   // Toggle untuk pakai QRIS static
                paymentAmount: 0,       // Total pembayaran dari API

                // Toast
                toastVisible: false,
                toastTitle: '',
                toastMessage: '',

                // Error Modal
                errorModalVisible: false,
                errorModalTitle: '',
                errorModalMessage: '',
                errorModalCountdown: 5,
                
                // Exit Confirmation Modal
                showExitModal: false,
                isForceExit: false,
                
                // Success Modal
                showSuccessModal: false,
                modalShown: false, // Flag untuk prevent duplicate modal
                sourceType: 'store', // 'store' or 'order'

                // Timer interval
                timerInterval: null,
                paymentCheckInterval: null,

                // Lifecycle
                async init() {
                    // Watch for QRIS type toggle
                    this.$watch('useStaticQris', async (value) => {
                        console.log('🔄 Switched to', value ? 'STATIC' : 'DYNAMIC', 'QRIS');
                        // Public checkout tidak support static dynamic conversion
                        // Just regenerate QR code
                        this.generateQRCode();
                    });

                    // 1. Intercept Browser Back Button (Mobile Friendly)
                    // Push state awal
                    history.pushState(null, null, location.href);
                    
                    // Listen popstate (saat tombol back ditekan)
                    window.addEventListener('popstate', (e) => {
                        if (['pending', 'verifying'].includes(this.paymentStatus)) {
                             // Push state lagi supaya tidak benar-benar kembali (stay di page)
                             history.pushState(null, null, location.href);
                             // Tampilkan modal custom
                             this.showExitModal = true;
                        }
                    });

                    // 2. Fallback: Prevent Accidental Tab Close/Refresh
                    // (Browser akan tetap menampilkan dialog default untuk action ini, tidak bisa dicustom)
                    window.addEventListener('beforeunload', (e) => {
                        if (this.isForceExit) return;
                        if (['pending', 'verifying'].includes(this.paymentStatus)) {
                            e.preventDefault();
                            e.returnValue = ''; // Trigger default browser warning
                        }
                    });
                    
                    // Watch for payment status changes and persist to localStorage
                    this.$watch('paymentStatus', (newStatus) => {
                        console.log('📊 Payment status changed to:', newStatus);
                        this.savePaymentState();
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
                        uniqueCode: parsedData.uniqueCode || 0,
                        paymentMethod: parsedData.paymentMethod || 'qris',
                        refCode: parsedData.refCode || null,
                        scheduleDate: parsedData.scheduleDate || null,
                        // Detect if this is bulk (agent) or individual (public) payment
                        // Bulk: has refCode (agent_id), Individual: refCode = null or '0'
                        isBulk: parsedData.isBulk !== undefined ? parsedData.isBulk : (parsedData.refCode && parsedData.refCode !== '0' && parsedData.refCode !== 'guest'),
                    };
                    
                    console.log('📦 Order mode:', this.orderData.isBulk ? 'BULK (Agent)' : 'INDIVIDUAL (Public)');
                    
                    // Detect source type: dari 'store' (by referal) atau dari 'order' (dashboard agent/affiliate/freelance)
                    // Store: isBulk = false (public user dari store)
                    // Order: isBulk = true (agent/affiliate/freelance dari dashboard)
                    this.sourceType = this.orderData.isBulk ? 'order' : 'store';
                    console.log('📍 Source type:', this.sourceType);
                    
                    // Restore payment status dari localStorage jika ada (untuk handle refresh)
                    // Tapi jangan restore pending - langsung set ke verifying (seperti tokodigi langsung VERIFY)
                    if (parsedData.paymentStatus && ['verifying', 'activated'].includes(parsedData.paymentStatus)) {
                        this.paymentStatus = parsedData.paymentStatus;
                        console.log('♻️ Restored payment status:', this.paymentStatus);
                    } else if (parsedData.paymentStatus === 'pending') {
                        // Override pending ke verifying (seperti tokodigi langsung ke step VERIFY)
                        this.paymentStatus = 'verifying';
                        console.log('♻️ Override pending to verifying (like tokodigi)');
                    }

                    // Check if payment already exists (user refreshed page)
                    if (parsedData.paymentId) {
                        console.log('♻️ Payment sudah ada, menggunakan payment yang sama:', parsedData.paymentId);
                        this.paymentId = parsedData.paymentId;
                        this.batchId = parsedData.batchId || null;
                        
                        // Jika status sudah activated, langsung tampilkan tanpa fetch ulang
                        if (this.paymentStatus === 'activated') {
                            console.log('✅ Status sudah activated, skip loading...');
                            this.isLoading = false;
                            // Tampilkan success modal jika status sudah activated
                            this.showSuccessModal = true;
                        } else {
                            // Fetch existing QRIS data
                            await this.fetchQrisData();
                            
                            // Auto-verify payment saat page load (trigger API verify)
                            // Ini penting untuk meng-update status jika user sudah bayar tapi refresh halaman
                            console.log('🔄 Auto-verifying payment on page load...');
                            await this.autoVerifyPayment();
                        }
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
                
                // Action saat user pilih "Ya, Batalkan"
                confirmExit() {
                    this.isForceExit = true;
                    
                    // Redirect berdasarkan source type
                    if (this.sourceType === 'store') {
                        // Dari store (public user) -> kembali ke store dengan referral yang dipakai
                        const linkReferral = this.orderData.linkReferral || this.orderData.refCode || 'kuotaumroh';
                        window.location.href = `/u/${linkReferral}`;
                    } else {
                        // Dari order (agent/affiliate/freelance) -> kembali ke halaman order
                        const refCode = this.orderData.refCode;
                        if (refCode && refCode.startsWith('AGT')) {
                            window.location.href = '/agent/order';
                        } else if (refCode && refCode.startsWith('AFT')) {
                            const linkReferral = this.getLinkReferral();
                            window.location.href = `/dash/${linkReferral}/order`;
                        } else if (refCode && refCode.startsWith('FRL')) {
                            const linkReferral = this.getLinkReferral();
                            window.location.href = `/dash/${linkReferral}/order`;
                        } else {
                            // Fallback ke welcome page
                            window.location.href = '{{ route('welcome') }}';
                        }
                    }
                },

                // Handle Back Button UI Click
                handleUiBack(url) {
                     if (['pending', 'verifying'].includes(this.paymentStatus)) {
                        this.showExitModal = true;
                    } else {
                        this.isForceExit = true;
                        window.location.href = url;
                    }
                },
                
                // Save payment state ke localStorage (untuk handle refresh)
                savePaymentState() {
                    const savedOrder = localStorage.getItem('pendingOrder');
                    if (savedOrder) {
                        const orderData = JSON.parse(savedOrder);
                        // Jangan save status pending - save verifying supaya saat refresh tetap di step 3
                        orderData.paymentStatus = this.paymentStatus === 'pending' ? 'verifying' : this.paymentStatus;
                        localStorage.setItem('pendingOrder', JSON.stringify(orderData));
                        console.log('💾 Payment status disimpan ke localStorage:', orderData.paymentStatus);
                    }
                },
                
                // Auto-verify payment saat page load (untuk handle refresh)
                // Alur sama seperti tokodigi: verifyPayment (cek mutasi) → getPayment (baca DB)
                async autoVerifyPayment() {
                    if (!this.paymentId) {
                        console.log('⚠️ No payment ID for auto-verify');
                        return;
                    }
                    
                    try {
                        console.log('🔍 Auto-verifying payment:', this.paymentId);
                        
                        // Step 1: Trigger verifyPayment untuk cek mutasi QRIS (seperti tokodigi)
                        const verifyResponse = await verifyPayment(this.paymentId);
                        console.log('🔍 Verify response:', verifyResponse);
                        
                        // Jika verify berhasil (pembayaran ditemukan)
                        if (verifyResponse.success && ['berhasil', 'success', 'sukses'].includes(verifyResponse.status?.toLowerCase())) {
                            console.log('✅ Verify found payment successful!');
                            this.setPaymentActivated();
                            return;
                        }
                        
                        // Step 2: Get payment data dari database (sama seperti tokodigi getPayment)
                        const response = await getPaymentStatus(this.paymentId);
                        
                        // Response is array, get first item
                        const rawData = Array.isArray(response) ? response[0] : (response.data || response);
                        const data = rawData;
                        const status = (data.status || data.payment_status || '').toLowerCase();
                        
                        console.log('🔍 Auto-verify - API Status:', status);
                        
                        if (data && data.id) {
                            // Update QRIS if available
                            if (data.qris && !this.qrisString) {
                                this.qrisString = data.qris;
                                this.qrisStaticString = data.qris_static || null;
                                this.$nextTick(() => this.generateQRCode());
                            }
                            
                            // Handle status sukses -> step 4 (paket aktif)
                            if (status === 'success' || status === 'sukses' || status === 'paid' || status === 'berhasil' || status === 'completed') {
                                this.setPaymentActivated();
                            }
                            // Handle status expired/failed
                            else if (status === 'expired' || status === 'failed') {
                                this.paymentStatus = 'expired';
                                localStorage.removeItem('pendingOrder');
                            }
                            // PENTING: Abaikan status pending dari API saat page load/refresh
                            // Status sudah di-set ke 'verifying' di init(), jangan override ke pending
                            // Ini memastikan step indicator tetap di step 3 (verifying)
                            else if (status === 'pending' || status === 'unpaid' || status.includes('menunggu')) {
                                console.log('⚠️ API returned pending, keeping current status:', this.paymentStatus);
                                // Tidak mengubah this.paymentStatus, biarkan tetap 'verifying'
                            }
                        }
                    } catch (error) {
                        console.error('❌ Auto-verify failed:', error);
                    }
                },
                
                handleDownloadQR() {
                    // Cari element QR Code yang sebenarnya (di dalam #qrcode-inner)
                    // Menggunakan ID spesifik agar tidak mengambil gambar template background
                    const qrInner = document.getElementById('qrcode-inner');
                    
                    if (!qrInner) {
                        this.showToast('Gagal', 'QR Code belum dimuat');
                        return;
                    }

                    const img = qrInner.querySelector('img');
                    const canvas = qrInner.querySelector('canvas');
                    
                    let dataUrl = '';
                    
                    if (img) {
                        dataUrl = img.src;
                    } else if (canvas) {
                        dataUrl = canvas.toDataURL("image/png");
                    } else {
                        this.showToast('Gagal', 'Element QR Code belum generate');
                        return;
                    }
                    
                    if (!dataUrl || dataUrl === '') {
                        this.showToast('Gagal', 'Tidak dapat mengunduh QR Code');
                        return;
                    }
                    
                    // Create download link
                    const link = document.createElement('a');
                    link.download = 'QRIS-Payment-KuotaUmroh.png';
                    link.href = dataUrl;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    this.showToast('Berhasil', 'QR Code sedang diunduh');
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

                // Computed: Can access invoice (only when payment is successful)
                get canAccessInvoice() {
                    return this.paymentStatus === 'activated';
                },
                
                // Handle redirect berdasarkan source type
                redirectToHome() {
                    if (this.sourceType === 'store') {
                        // Dari store (public user) -> kembali ke store dengan referral yang dipakai
                        const linkReferral = this.orderData.linkReferral || this.orderData.refCode || 'kuotaumroh';
                        window.location.href = `/u/${linkReferral}`;
                    } else {
                        // Dari order (agent/affiliate/freelance) -> kembali ke halaman order
                        // Perlu check refCode untuk tentukan dashboard yang mana
                        const refCode = this.orderData.refCode;
                        if (refCode && refCode.startsWith('AGT')) {
                            window.location.href = '/agent/order';
                        } else if (refCode && refCode.startsWith('AFT')) {
                            // Get link referral from localStorage or URL
                            const linkReferral = this.getLinkReferral();
                            window.location.href = `/dash/${linkReferral}/order`;
                        } else if (refCode && refCode.startsWith('FRL')) {
                            // Get link referral from localStorage or URL
                            const linkReferral = this.getLinkReferral();
                            window.location.href = `/dash/${linkReferral}/order`;
                        } else {
                            // Fallback ke welcome page
                            window.location.href = '{{ route('welcome') }}';
                        }
                    }
                },
                
                redirectToInvoice() {
                    // Pass sourceType and refCode as query parameters
                    const params = new URLSearchParams({
                        source: this.sourceType,
                        refCode: this.orderData.refCode || '',
                        linkReferral: this.getLinkReferral()
                    });
                    const invoiceUrl = `/invoice/${this.paymentId}?${params.toString()}`;
                    window.location.href = invoiceUrl;
                },
                
                getLinkReferral() {
                    // Try to get from localStorage
                    const savedOrder = localStorage.getItem('pendingOrder');
                    if (savedOrder) {
                        try {
                            const parsed = JSON.parse(savedOrder);
                            // Check both linkReferral and linkReferal (typo in some places)
                            if (parsed.linkReferral) return parsed.linkReferral;
                            if (parsed.linkReferal) return parsed.linkReferal;
                        } catch (e) {
                            console.error('Failed to parse saved order:', e);
                        }
                    }
                    // Fallback
                    return 'kuotaumroh';
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
                        // Create wrapper for template with QR overlay (ukuran diperbesar)
                        const wrapper = document.createElement('div');
                        wrapper.style.position = 'relative';
                        wrapper.style.width = '260px';  // Diperbesar dari 220px
                        wrapper.style.height = '300px';  // Diperbesar dari 260px
                        
                        // Add template background image
                        const templateImg = document.createElement('img');
                        templateImg.src = '{{ asset("images/template_qris.png") }}';
                        templateImg.style.width = '100%';
                        templateImg.style.height = '100%';
                        templateImg.style.objectFit = 'contain';
                        templateImg.style.position = 'absolute';
                        templateImg.style.top = '0';
                        templateImg.style.left = '0';
                        wrapper.appendChild(templateImg);
                        
                        // Create QR code container (positioned in center-bottom area where template has space)
                        const qrDiv = document.createElement('div');
                        qrDiv.id = 'qrcode-inner';
                        qrDiv.style.position = 'absolute';
                        qrDiv.style.top = '55%';
                        qrDiv.style.left = '50%';
                        qrDiv.style.transform = 'translate(-50%, -50%)';
                        qrDiv.style.background = 'white';
                        qrDiv.style.padding = '4px';
                        qrDiv.style.borderRadius = '4px';
                        wrapper.appendChild(qrDiv);
                        
                        container.appendChild(wrapper);
                        
                        // Generate QR code inside the overlay div (ukuran diperbesar)
                        this.qrCodeInstance = new QRCode(qrDiv, {
                            text: qrisData,
                            width: 170,   // Diperbesar dari 140
                            height: 170,  // Diperbesar dari 140
                            colorDark: '#000000',
                            colorLight: '#ffffff',
                            correctLevel: QRCode.CorrectLevel.M
                        });
                        console.log('✅ QR Code with template generated successfully');
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
                                // Hide loading after QR generated
                                setTimeout(() => {
                                    this.isLoading = false;
                                }, 500);
                            });
                        }
                        
                        // Update payment amount if available
                        if (data && data.payment_amount) {
                            this.paymentAmount = parseInt(data.payment_amount) || 0;
                            this.orderData.uniqueCode = parseInt(data.payment_unique) || 0;
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
                            
                            // Jika status sudah activated, jangan ubah jadi expired
                            // Cukup hapus localStorage agar jika direfresh kembali ke home
                            if (this.paymentStatus === 'activated') {
                                localStorage.removeItem('pendingOrder');
                                console.log('🗑️ Time up for activated order. Storage cleared.');
                            } else {
                                this.paymentStatus = 'expired';
                                this.timeRemaining = 0;
                                localStorage.removeItem('pendingOrder');
                            }
                        } else {
                            this.timeRemaining--;
                        }
                    }, 1000);
                },

                async createPayment() {
                    // Prevent double submission
                    if (this.isCreatingPayment || this.paymentId) {
                        console.log('⚠️ Payment creation already in progress or payment already exists. Skipping.');
                        return;
                    }

                    this.isCreatingPayment = true;

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
                                agent_id: this.orderData.agent_id || null, // Pass agent_id/affiliate_id for role detection
                                affiliate_id: this.orderData.affiliate_id || null,
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
                                price: item.price || 0, // String/Number
                                agent_id: this.orderData.agent_id || null, // Pass agent_id/affiliate_id for role detection
                                affiliate_id: this.orderData.affiliate_id || null,
                            };

                            console.log('📤 Sending INDIVIDUAL payment request:', requestData);
                            response = await createIndividualPayment(requestData);
                        }
                        
                        console.log('📥 Payment response:', response);

                        // Handle both response formats:
                        // Format 1: { success: true, data: {...} }
                        // Format 2: Direct data { id: '...', qris: {...}, ... }
                        
                        // Check for error response first
                        if (response.success === false || response.error) {
                            const errorMessage = response.error || response.message || 'Gagal membuat transaksi';
                            console.error('❌ API returned error:', errorMessage);
                            throw new Error(errorMessage);
                        }
                        
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

                            // OPTIMISASI: Generate QR Code langsung dari response createPayment
                            // Tanpa perlu fetchQrisData() lagi yang bikin lama
                            if (data.qris) {
                                // Handle if qris is object or string
                                if (typeof data.qris === 'object' && data.qris !== null) {
                                     this.qrisString = data.qris.qris_string || data.qris.string || null;
                                     // Fallback jika qris_string ada di root object
                                     if (!this.qrisString && data.qris_string) {
                                         this.qrisString = data.qris_string;
                                     }
                                } else {
                                     this.qrisString = data.qris;
                                }
                                
                                this.qrisStaticString = data.qris_static || null; 
                                
                                if (this.qrisString) {
                                    console.log('✅ QRIS data set immediately from creation response');
                                    this.$nextTick(() => {
                                        this.generateQRCode();
                                        // Langsung sembunyikan loading
                                        this.isLoading = false; 
                                    });
                                }
                            } else if (data.qris_string) {
                                this.qrisString = data.qris_string;
                                this.$nextTick(() => {
                                    this.generateQRCode();
                                    this.isLoading = false; 
                                });
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
                            // OPTIMISASI: Skip fetchQrisData jika QR string sudah didapat
                            if (!this.qrisString) {
                                await this.fetchQrisData();
                            }
                            
                            // Auto-scroll ke ringkasan pembayaran setelah QR berhasil di-generate
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    const summaryElement = document.getElementById('payment-summary');
                                    if (summaryElement) {
                                        summaryElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                        console.log('📤 Auto-scrolled to payment summary');
                                    }
                                }, 500);
                            });
                        } else {
                            throw new Error(response.message || 'Gagal membuat transaksi');
                        }
                    } catch (error) {
                        console.error('❌ Failed to create payment:', error);
                        
                        // Check if error is about invalid/unregistered phone number
                        const errorMessage = error.message || '';
                        const errorStr = JSON.stringify(error).toLowerCase();
                        
                        if (errorMessage.includes('tidak terdaftar') || errorMessage.includes('not registered') || 
                            errorMessage.includes('bukan nomor') || errorMessage.includes('tidak dapat diproses') ||
                            errorMessage.includes('invalid') || errorMessage.toLowerCase().includes('msisdn') ||
                            errorStr.includes('tidak terdaftar') || errorStr.includes('bukan nomor')) {
                            
                            // Extract nomor from error message if available
                            const numberMatch = errorMessage.match(/(\d{10,15})/);
                            const invalidNumber = numberMatch ? numberMatch[1] : '';
                            
                            this.showErrorModal(
                                'Nomor Tidak Terdaftar', 
                                errorMessage || 'Terdapat nomor telepon yang tidak terdaftar atau tidak valid. Silakan periksa kembali nomor telepon yang Anda masukkan dan pastikan nomor tersebut aktif.'
                            );
                        } else {
                            this.showErrorModal(
                                'Error',
                                this.withCsInfo(error.message || 'Gagal membuat transaksi pembayaran. Silakan coba lagi.')
                            );
                        }
                    }
                },

                startPaymentPolling() {
                    if (!this.paymentId) return;
                    
                    // Jika sudah activated, tidak perlu polling
                    if (this.paymentStatus === 'activated') {
                        console.log('✅ Status sudah activated, skip polling');
                        return;
                    }

                    // Check payment status every 5 seconds
                    // Alur sama seperti tokodigi: verifyPayment (cek mutasi) → getPayment (baca DB)
                    this.paymentCheckInterval = setInterval(async () => {
                        try {
                            // Step 1: Trigger verifyPayment untuk cek mutasi QRIS
                            const verifyResponse = await verifyPayment(this.paymentId);
                            console.log('🔄 Polling verify response:', verifyResponse);
                            
                            // Jika verify berhasil (pembayaran ditemukan)
                            if (verifyResponse.success && ['berhasil', 'success', 'sukses'].includes(verifyResponse.status?.toLowerCase())) {
                                console.log('✅ Polling: Payment successful!');
                                this.setPaymentActivated();
                                return;
                            }
                            
                            // Step 2: Get payment data dari database
                            const response = await getPaymentStatus(this.paymentId);
                            
                            // Response is array, get first item
                            const rawData = Array.isArray(response) ? response[0] : (response.data || response);
                            const data = rawData;
                            const status = (data.status || data.payment_status || '').toLowerCase();
                            
                            console.log('🔍 Manual check - Status:', status, 'Data:', data);

                            if (data && data.id) {
                                // Update QRIS strings if available
                                if (data.qris && !this.qrisString) {
                                    this.qrisString = data.qris;
                                    this.qrisStaticString = data.qris_static || null;
                                    console.log('✅ QRIS data updated from polling');
                                    this.$nextTick(() => this.generateQRCode());
                                }
                                
                                // Update indikator berdasarkan status dari API (sama seperti manual check)
                                if (status === 'success' || status === 'sukses' || status === 'paid' || status === 'berhasil' || status === 'completed') {
                                    this.setPaymentActivated();
                                } 
                                else if (status.includes('verifikasi') || status === 'verify' || status === 'verifying') {
                                    if (this.paymentStatus !== 'activated') {
                                        this.paymentStatus = 'verifying';
                                        console.log('📊 Status dari API: verifying');
                                    }
                                }
                                // Status pending/waiting -> tetap di verifying
                                // else if (status === 'pending' || status === 'unpaid' || status === 'menunggu pembayaran') {
                                //     if (this.paymentStatus !== 'activated' && this.paymentStatus !== 'verifying') {
                                //         this.paymentStatus = 'pending';
                                //     }
                                // }
                                else if (status === 'expired' || status === 'failed') {
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
                        'Mengecek pembayaran',
                        'Mohon tunggu, kami sedang mengecek status pembayaran Anda...'
                    );

                    // Verify payment via internal API (this will update local DB if payment is successful)
                    try {
                        console.log('🔍 Verifying payment:', this.paymentId);
                        const verifyResponse = await verifyPayment(this.paymentId);
                        console.log('🔍 Verify response:', verifyResponse);
                        
                        // Check if verification found payment as successful
                        if (verifyResponse.success && (verifyResponse.status === 'berhasil' || verifyResponse.status === 'success')) {
                            // Set to activated (paket aktif)
                            this.setPaymentActivated();
                            return;
                        }
                        
                        // If not successful yet, also get current status for display
                        const response = await getPaymentStatus(this.paymentId);
                        
                        // Response is array, get first item
                        const rawData = Array.isArray(response) ? response[0] : (response.data || response);
                        const data = rawData;
                        const status = (data.status || data.payment_status || '').toLowerCase();
                        
                        console.log('🔍 Manual check - Status:', status, 'Data:', data);

                        if (data && data.id) {
                            // Update QRIS if available
                            if (data.qris && !this.qrisString) {
                                this.qrisString = data.qris;
                                this.qrisStaticString = data.qris_static || null;
                                this.$nextTick(() => this.generateQRCode());
                            }
                            
                            // Handle status sukses -> step 4 (paket aktif)
                            if (status === 'success' || status === 'sukses' || status === 'paid' || status === 'berhasil' || status === 'completed') {
                                this.setPaymentActivated();
                            }
                            // Handle status: verifikasi pembayaran -> pindah ke step 3
                            else if (status.includes('verifikasi') || status === 'verify' || status === 'verifying') {
                                // Set ke step 3 (verifying) berdasarkan status API
                                if (this.paymentStatus !== 'activated') {
                                    this.paymentStatus = 'verifying';
                                    console.log('📊 Status dari API: verifying');
                                }
                                this.showToast(
                                    'Verifikasi Pembayaran',
                                    'Pembayaran sedang diverifikasi oleh sistem...'
                                );
                            }
                            // Status pending/waiting -> tetap di verifying
                            // else if (status === 'pending' || status === 'unpaid' || status === 'menunggu pembayaran') {
                            //     if (this.paymentStatus !== 'activated') {
                            //         this.paymentStatus = 'pending';
                            //         console.log('📊 Status dari API: pending');
                            //     }
                            //     this.showToast(
                            //         'Menunggu Pembayaran',
                            //         'Pembayaran belum diterima. Silakan selesaikan pembayaran dan klik cek status lagi.'
                            //     );
                            // } 
                            else if (status === 'expired' || status === 'failed') {
                                this.paymentStatus = 'expired';
                                clearInterval(this.timerInterval);
                                if (this.paymentCheckInterval) {
                                    clearInterval(this.paymentCheckInterval);
                                }
                                localStorage.removeItem('pendingOrder');
                            } else {
                                // Status lain yang tidak dikenal - anggap sebagai verifying (sudah bayar)
                                if (this.paymentStatus === 'pending') {
                                    this.paymentStatus = 'verifying';
                                    console.log('📊 Unknown status, assuming verifying:', status);
                                }
                                this.showToast(
                                    'Status Pembayaran',
                                    'Status: ' + (data.status || data.payment_status)
                                );
                            }
                        } else {
                            this.showErrorModal(
                                'Error',
                                this.withCsInfo(response.message || 'Gagal mengecek status pembayaran.')
                            );
                        }
                    } catch (error) {
                        console.error('Failed to check payment:', error);
                        this.showErrorModal(
                            'Error',
                            this.withCsInfo('Gagal mengecek status pembayaran. Silakan coba lagi.')
                        );
                    }
                },

                async handleViewInvoice() {
                    if (!this.paymentId) {
                        this.showErrorModal('Error', 'Payment ID tidak ditemukan. Silakan refresh halaman.');
                        return;
                    }

                    // Check if payment is successful before allowing invoice access
                    if (!this.canAccessInvoice) {
                        // Trigger status check first
                        this.showToast('Info', 'Mengecek status pembayaran...');
                        await this.handleCheckPayment();
                        
                        // Check again after status update
                        if (!this.canAccessInvoice) {
                            this.showToast('Menunggu Pembayaran', 'Invoice hanya dapat diakses setelah pembayaran berhasil. Silakan selesaikan pembayaran terlebih dahulu.');
                            return;
                        }
                    }

                    // Buka invoice di tab baru dengan payment ID
                    const invoiceUrl = `/invoice/${this.paymentId}`;
                    window.open(invoiceUrl, '_blank');
                },

                // Set payment to activated state (paket aktif) - called when status is sukses
                setPaymentActivated() {
                    console.log('🎉 Payment successful! Setting to activated...');
                    
                    // Show success modal FIRST (sebelum check status, agar tidak ter-skip)
                    if (!this.modalShown) {
                        console.log('🎊 Showing success modal...');
                        this.showSuccessModal = true;
                        this.modalShown = true;
                        console.log('🎊 showSuccessModal set to:', this.showSuccessModal);
                    }
                    
                    // Check if already activated (after showing modal)
                    if (this.paymentStatus === 'activated') {
                        console.log('⚠️ Already activated, skipping status update...');
                        return;
                    }
                    
                    // Directly set to activated (step 4 - paket aktif)
                    this.paymentStatus = 'activated';
                    console.log('📊 Status: activated (paket aktif)');
                    
                    // Save final state before cleanup (untuk akses invoice jika refresh)
                    this.savePaymentState();
                    
                    // Clean up intervals
                    // NOTE: Timer interval tidak di-clear agar tetap berjalan sampai habis (untuk cleanup localStorage)
                    // clearInterval(this.timerInterval); 
                    
                    if (this.paymentCheckInterval) {
                        clearInterval(this.paymentCheckInterval);
                    }
                    
                    // REMOVED: Clear localStorage timeout.
                    // Biarkan timer yang handle cleanup saat waktu habis (agar user bisa refresh selama masih ada waktu)
                },

                showToast(title, message) {
                    this.toastTitle = title;
                    this.toastMessage = message;
                    this.toastVisible = true;
                    setTimeout(() => {
                        this.toastVisible = false;
                    }, 3000);
                },

                withCsInfo(message) {
                    const csInfo = 'Jika ada kendala, hubungi CS Kuotaumroh via WhatsApp +62 8112-994-499.';
                    const detail = (message || 'Terjadi kendala pada proses.').toString().trim();
                    return detail.includes('CS Kuotaumroh') ? detail : `${detail} ${csInfo}`;
                },
                showErrorModal(title, message) {
                    this.errorModalTitle = 'Terjadi Kendala';
                    this.errorModalMessage = this.withCsInfo(message);
                    this.errorModalVisible = true;
                    this.errorModalCountdown = 5;
                    
                    const countdownInterval = setInterval(() => {
                        this.errorModalCountdown--;
                        if (this.errorModalCountdown <= 0) {
                            clearInterval(countdownInterval);
                            this.redirectAfterError();
                        }
                    }, 1000);
                },
                
                // Redirect after error - untuk tombol Tutup atau countdown habis
                redirectAfterError() {
                    this.errorModalVisible = false;
                    this.isForceExit = true; // Bypass beforeunload confirmation
                    // Redirect berdasarkan source type
                    if (this.sourceType === 'store') {
                        // Dari store (public user) -> kembali ke store dengan referral yang dipakai
                        const linkReferral = this.orderData.linkReferral || this.orderData.refCode || 'kuotaumroh';
                        window.location.href = `/u/${linkReferral}`;
                    } else {
                        // Dari order (agent/affiliate/freelance) -> kembali ke halaman order
                        const refCode = this.orderData.refCode;
                        if (refCode && refCode.startsWith('AGT')) {
                            window.location.href = '/agent/order';
                        } else if (refCode && refCode.startsWith('AFT')) {
                            const linkReferral = this.getLinkReferral();
                            window.location.href = `/dash/${linkReferral}/order`;
                        } else if (refCode && refCode.startsWith('FRL')) {
                            const linkReferral = this.getLinkReferral();
                            window.location.href = `/dash/${linkReferral}/order`;
                        } else {
                            // Fallback ke store jika ada linkReferral
                            const linkReferral = this.orderData.linkReferral || this.orderData.refCode;
                            if (linkReferral) {
                                window.location.href = `/u/${linkReferral}`;
                            } else {
                                window.location.href = '{{ route("welcome") }}';
                            }
                        }
                    }
                },
                
                // Format number tanpa Rp (untuk formula)
                formatNumber(num) {
                    if (!num) return '0';
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                // Format number dengan Rp
                formatRupiah(amount) {
                    if (!amount) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount);
                }
            }
        }
    </script>

</body>

</html>

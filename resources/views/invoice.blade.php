<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Kuotaumroh.id</title>

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
    <meta name="msapplication-TileColor" content="#10b981">
    <meta name="theme-color" content="#10b981">

    <!-- Google Fonts - Figtree -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Shared Scripts -->
    <script src="{{ asset('shared/config.js') }}?v={{ time() }}"></script>
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
                },
            },
        }
    </script>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-shadow { box-shadow: none !important; }
            /* Show all items when printing */
            .print-show { display: table-row !important; }
            .print-hide { display: none !important; }
        }
        @media screen {
            .screen-hide { display: none; }
        }
        
        /* Smooth expand/collapse animation */
        .expand-wrapper {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.4s ease-out;
        }
        .expand-wrapper.expanded {
            grid-template-rows: 1fr;
        }
        .expand-content {
            overflow: hidden;
        }
        
        /* Row fade-in animation */
        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-row {
            animation: fadeSlideIn 0.3s ease-out forwards;
        }
        .animate-row:nth-child(1) { animation-delay: 0.05s; }
        .animate-row:nth-child(2) { animation-delay: 0.1s; }
        .animate-row:nth-child(3) { animation-delay: 0.15s; }
        .animate-row:nth-child(4) { animation-delay: 0.2s; }
        .animate-row:nth-child(5) { animation-delay: 0.25s; }
    </style>
</head>

<body class="min-h-screen bg-gray-100">
    <div x-data="invoiceApp()" x-init="init()">

        <!-- Header (No Print) -->
        <header class="sticky top-0 z-50 w-full border-b bg-white/95 backdrop-blur no-print">
            <div class="container mx-auto flex h-16 items-center justify-between px-4">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-9 w-9 object-contain">
                    <span class="text-xl font-semibold">Kuotaumroh.id</span>
                </a>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <button @click="printInvoice()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak
                    </button>
                    <button @click="downloadPDF()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PDF
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto py-8 px-4">
            <div class="max-w-4xl mx-auto">

                <!-- Loading State -->
                <div x-show="loading" class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                    <p class="text-gray-600">Memuat data invoice...</p>
                </div>

                <!-- Error State -->
                <div x-show="error && !loading" class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                    <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-red-900 mb-2">Gagal Memuat Invoice</h3>
                    <p class="text-red-700 mb-4" x-text="error"></p>
                    <button @click="fetchInvoiceData()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Coba Lagi
                    </button>
                </div>

                <!-- Invoice Card -->
                <div x-show="!loading && !error" class="bg-white rounded-xl shadow-lg print-shadow overflow-hidden">
                    
                    <!-- Invoice Header -->
                    <div class="bg-emerald-600 px-8 py-6 text-white">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/LOGO.png') }}" alt="Logo" class="h-12 w-12 bg-white rounded-lg p-1">
                                <div>
                                    <h1 class="text-2xl font-bold">INVOICE</h1>
                                    <p class="text-emerald-100 text-sm">Kuotaumroh.id</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-lg font-bold text-white mb-1" x-text="invoice.paymentId"></p>
                                <p class="text-emerald-100 text-sm" x-text="'Tanggal: ' + invoice.date"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="px-8 py-4 bg-gray-50 border-b flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600">Status Pembayaran:</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-semibold"
                                :class="{
                                    'bg-green-100 text-green-700': invoice.status === 'paid',
                                    'bg-yellow-100 text-yellow-700': invoice.status === 'pending',
                                    'bg-red-100 text-red-700': invoice.status === 'expired'
                                }">
                                <span class="w-2 h-2 rounded-full"
                                    :class="{
                                        'bg-green-500': invoice.status === 'paid',
                                        'bg-yellow-500': invoice.status === 'pending',
                                        'bg-red-500': invoice.status === 'expired'
                                    }"></span>
                                <span x-text="invoice.status === 'paid' ? 'Lunas' : (invoice.status === 'pending' ? 'Menunggu Pembayaran' : 'Kedaluwarsa')"></span>
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span>Metode: </span>
                            <span class="font-semibold" x-text="invoice.paymentMethod"></span>
                        </div>
                    </div>

                    <!-- Batch Info (Only for bulk payments) -->
                    <div x-show="invoice.batchId" class="px-8 py-4 bg-blue-50 border-b">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">Batch ID</p>
                                    <p class="font-mono font-semibold text-blue-900" x-text="invoice.batchId"></p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs text-blue-600 font-medium">Nama Batch</p>
                                <p class="font-semibold text-blue-900" x-text="invoice.batchName"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Agent, Customer & Payment Info -->
                    <div class="px-8 py-6 grid sm:grid-cols-3 gap-6 border-b">
                        <!-- Agent Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Travel Agent</h3>
                            <div class="space-y-2">
                                <p class="font-semibold text-lg" x-text="invoice.agent.name"></p>
                                <div class="flex items-center gap-2 text-gray-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>PIC: <span x-text="invoice.agent.pic"></span></span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span x-text="invoice.agent.phone"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Affiliate/Freelance Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Affiliate/Freelance</h3>
                            <div class="space-y-2">
                                <p class="font-semibold text-lg" x-text="invoice.orderedBy.name"></p>
                                <div class="flex items-center gap-2 text-gray-600 text-sm">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium" 
                                        :class="invoice.orderedBy.type === 'affiliate' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700'"
                                        x-text="invoice.orderedBy.type === 'affiliate' ? 'Affiliate' : 'Freelance'"></span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span x-text="invoice.orderedBy.phone"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Pembayaran</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Metode Bayar</span>
                                    <span class="font-semibold" x-text="invoice.paymentMethod"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Bayar</span>
                                    <span class="font-semibold" x-text="invoice.paidAt || '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jatuh Tempo</span>
                                    <span class="font-semibold" x-text="invoice.dueDate"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="px-8 py-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Detail Pesanan</h3>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="py-3 text-left font-semibold text-gray-700">No</th>
                                        <th class="py-3 text-left font-semibold text-gray-700">Nomor HP</th>
                                        <th class="py-3 text-left font-semibold text-gray-700">Paket</th>
                                        <th class="py-3 text-left font-semibold text-gray-700">Tipe</th>
                                        <th class="py-3 text-left font-semibold text-gray-700">Kuota</th>
                                        <th class="py-3 text-left font-semibold text-gray-700">Masa Aktif</th>
                                        <th class="py-3 text-left font-semibold text-gray-700">Jadwal Aktivasi</th>
                                        <th class="py-3 text-center font-semibold text-gray-700">Status</th>
                                        <th class="py-3 text-right font-semibold text-gray-700">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- First 5 items (always visible) -->
                                    <template x-for="(item, index) in invoice.items.slice(0, 5)" :key="'first-'+index">
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-4 text-gray-600" x-text="index + 1"></td>
                                            <td class="py-4">
                                                <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs" x-text="item.msisdn"></span>
                                            </td>
                                            <td class="py-4 font-medium" x-text="item.packageName"></td>
                                            <td class="py-4">
                                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary" x-text="item.packageType"></span>
                                            </td>
                                            <td class="py-4 text-gray-600" x-text="item.quota"></td>
                                            <td class="py-4 text-gray-600" x-text="item.validity"></td>
                                            <td class="py-4 text-gray-600 text-xs" x-text="item.scheduledAt"></td>
                                            <td class="py-4 text-center">
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium"
                                                    :class="getStatusClass(item.status)"
                                                    x-text="getStatusLabel(item.status)"></span>
                                            </td>
                                            <td class="py-4 text-right font-semibold" x-text="formatRupiah(item.price)"></td>
                                        </tr>
                                    </template>
                                    
                                    <!-- Remaining items (with animation) - Screen -->
                                    <template x-if="invoice.items.length > 5">
                                        <template x-for="(item, index) in invoice.items.slice(5)" :key="'rest-'+index">
                                            <tr class="border-b border-gray-100 hover:bg-gray-50 print-show"
                                                x-show="showAllItems"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 -translate-y-2"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 -translate-y-2"
                                                :style="{ transitionDelay: (index * 50) + 'ms' }">
                                                <td class="py-4 text-gray-600" x-text="index + 6"></td>
                                                <td class="py-4">
                                                    <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs" x-text="item.msisdn"></span>
                                                </td>
                                                <td class="py-4 font-medium" x-text="item.packageName"></td>
                                                <td class="py-4">
                                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary" x-text="item.packageType"></span>
                                                </td>
                                                <td class="py-4 text-gray-600" x-text="item.quota"></td>
                                                <td class="py-4 text-gray-600" x-text="item.validity"></td>
                                                <td class="py-4 text-gray-600 text-xs" x-text="item.scheduledAt"></td>
                                                <td class="py-4 text-center">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium"
                                                        :class="getStatusClass(item.status)"
                                                        x-text="getStatusLabel(item.status)"></span>
                                                </td>
                                                <td class="py-4 text-right font-semibold" x-text="formatRupiah(item.price)"></td>
                                            </tr>
                                        </template>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Show More Button -->
                        <div x-show="invoice.items.length > 5" class="mt-4 text-center no-print">
                            <button 
                                x-show="!showAllItems"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                @click="showAllItems = true"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary hover:bg-primary/10 rounded-lg transition-all duration-200 hover:gap-3">
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                Tampilkan <span x-text="invoice.items.length - 5"></span> item lainnya
                            </button>
                            <button 
                                x-show="showAllItems"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                @click="showAllItems = false"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                </svg>
                                Sembunyikan
                            </button>
                            <!-- Info: semua item akan tercetak -->
                            <p x-show="!showAllItems" class="mt-2 text-xs text-gray-400">
                                <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Saat mencetak, semua <span x-text="invoice.items.length"></span> item akan ditampilkan
                            </p>
                        </div>

                        <!-- Summary -->
                        <div class="mt-6 flex justify-end">
                            <div class="w-full sm:w-72 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium" x-text="formatRupiah(invoice.subtotal)"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Biaya Platform</span>
                                    <span class="font-medium" x-text="formatRupiah(invoice.platformFee)"></span>
                                </div>
                                <template x-if="invoice.discount > 0">
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>Diskon</span>
                                        <span class="font-medium" x-text="'-' + formatRupiah(invoice.discount)"></span>
                                    </div>
                                </template>
                                <div class="border-t-2 border-gray-200 pt-2 mt-2">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-bold">Total</span>
                                        <span class="text-lg font-bold text-primary" x-text="formatRupiah(invoice.total)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Notes -->
                    <div class="px-8 py-6 bg-gray-50 border-t">
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">Catatan</h4>
                                <p class="text-sm text-gray-600" x-text="invoice.notes"></p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2">Hubungi Kami</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <p>📧 support@kuotaumroh.id</p>
                                    <p>📱 +62 812-3456-7890</p>
                                    <p>🌐 www.kuotaumroh.id</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="px-8 py-4 bg-white border-t">
                        <p class="text-xs text-gray-400 text-center">
                            Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.<br>
                            Dicetak pada: <span x-text="new Date().toLocaleString('id-ID')"></span>
                        </p>
                    </div>
                </div>

                <!-- Back Button (No Print) -->
                <div class="mt-6 text-center no-print">
                    <a href="{{ route('checkout') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Halaman Checkout
                    </a>
                </div>
            </div>
        </main>

        <!-- Footer (No Print) -->
        <footer class="border-t mt-16 py-8 no-print">
            <div class="container mx-auto px-4 text-center text-sm text-gray-500">
                <p>© 2026 Kuotaumroh.id. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Page Script -->
    <script>
        function invoiceApp() {
            return {
                // State
                showAllItems: false,
                loading: true,
                error: null,
                paymentId: '{{ $invoiceId ?? "" }}',  // From route parameter
                
                // Invoice Data
                invoice: {
                    date: '',
                    dueDate: '',
                    status: 'pending',
                    paymentMethod: 'QRIS',
                    paymentId: '',
                    paidAt: '',
                    batchId: '',
                    batchName: '',
                    agent: {
                        name: '',
                        pic: '',
                        phone: '',
                        email: ''
                    },
                    orderedBy: {
                        name: '',
                        type: '',
                        phone: '',
                        email: ''
                    },
                    items: [],
                    subtotal: 0,
                    platformFee: 0,
                    discount: 0,
                    total: 0,
                    notes: ''
                },

                // Init
                async init() {
                    if (!this.paymentId) {
                        this.error = 'Payment ID tidak ditemukan';
                        this.loading = false;
                        return;
                    }

                    await this.fetchInvoiceData();
                },

                // Fetch Invoice Data
                async fetchInvoiceData() {
                    try {
                        this.loading = true;
                        this.error = null;

                        // Get agent_id from URL or localStorage
                        const urlParams = new URLSearchParams(window.location.search);
                        const agentId = urlParams.get('agent_id') || this.getAgentIdFromStorage();

                        console.log('📄 Fetching invoice for payment:', this.paymentId, 'agent:', agentId);

                        const response = await getInvoiceDetail(this.paymentId, agentId);

                        if (!response.success) {
                            throw new Error(response.message || 'Failed to fetch invoice');
                        }

                        console.log('✅ Invoice data received:', response);

                        // Parse data berdasarkan type (bulk atau individual)
                        if (response.type === 'bulk') {
                            this.parseBulkInvoiceData(response.data);
                        } else {
                            this.parseIndividualInvoiceData(response.data);
                        }

                        this.loading = false;
                    } catch (error) {
                        console.error('❌ Error fetching invoice:', error);
                        this.error = error.message || 'Gagal memuat data invoice';
                        this.loading = false;
                    }
                },

                // Parse Bulk Invoice Data
                parseBulkInvoiceData(data) {
                    // data is array of items
                    const items = Array.isArray(data) ? data : [data];
                    
                    if (items.length === 0) return;

                    const firstItem = items[0];
                    
                    // Set invoice metadata
                    this.invoice.paymentId = firstItem.payment_id || firstItem.id || this.paymentId;
                    this.invoice.batchId = firstItem.batch_id || firstItem.location_id || '';
                    this.invoice.batchName = firstItem.batch_name || '';
                    this.invoice.date = firstItem.created_at ? this.formatDate(firstItem.created_at) : '';
                    this.invoice.status = this.mapStatus(firstItem.status);
                    this.invoice.paidAt = firstItem.payment_date ? this.formatDate(firstItem.payment_date) : '';
                    
                    // Jatuh Tempo (Created + 15 mins)
                    if (firstItem.created_at) {
                        const createdDate = new Date(firstItem.created_at);
                        const dueDate = new Date(createdDate.getTime() + 15 * 60000); // Add 15 minutes
                        this.invoice.dueDate = this.formatDate(dueDate.toISOString());
                    } else {
                        this.invoice.dueDate = '';
                    }

                    this.invoice.paymentMethod = firstItem.payment_method || 'QRIS';

                    // Agent info from travel data
                    this.invoice.agent.name = firstItem.agent_name || 'Kuotaumroh.id';
                    this.invoice.agent.pic = 'Kuotaumroh.id'; // PIC diisi nama travel
                    this.invoice.agent.phone = firstItem.agent_phone || '+62 812-3456-7890'; // Telp travel agent

                    // Parse items
                    this.invoice.items = items.map(item => ({
                        msisdn: item.msisdn || '',
                        packageName: item.package_name || item.name || '',
                        packageType: item.package_type || 'UMROH',
                        quota: item.quota || '',
                        validity: item.days ? `${item.days} Hari` : '',
                        scheduledAt: item.schedule_date ? this.formatDate(item.schedule_date) : '',
                        status: item.status || 'pending',
                        price: parseInt(item.price || item.payment_amount || 0)
                    }));

                    // Calculate totals
                    this.invoice.subtotal = this.invoice.items.reduce((sum, item) => sum + item.price, 0);
                    this.invoice.platformFee = parseInt(firstItem.platform_fee || 0);
                    this.invoice.total = parseInt(firstItem.total_payment || firstItem.payment_amount || this.invoice.subtotal);
                    
                    // Agent info (Original was overwriting, now handled above)
                    // this.invoice.agent.name = firstItem.agent_name || ''; 
                    this.invoice.notes = 'Terima kasih telah menggunakan layanan Kuotaumroh.id';
                },

                // Parse Individual Invoice Data
                parseIndividualInvoiceData(data) {
                    // data is single object or array with one item
                    const item = Array.isArray(data) ? data[0] : data;
                    
                    if (!item) {
                        this.error = 'Data invoice tidak ditemukan';
                        return;
                    }

                    console.log('📝 Parsing individual invoice:', item);

                    // Set invoice metadata
                    this.invoice.paymentId = item.payment_id || item.id || this.paymentId;
                    this.invoice.date = item.created_at ? this.formatDate(item.created_at) : new Date().toISOString();
                    this.invoice.status = this.mapStatus(item.status);
                    
                    // Tanggal Bayar (kosong jika belum bayar)
                    this.invoice.paidAt = item.payment_date ? this.formatDate(item.payment_date) : '';
                    
                    // Jatuh Tempo (Created + 15 mins)
                    if (item.created_at) {
                        const createdDate = new Date(item.created_at);
                        const dueDate = new Date(createdDate.getTime() + 15 * 60000); // Add 15 minutes
                        this.invoice.dueDate = this.formatDate(dueDate.toISOString());
                    } else {
                        this.invoice.dueDate = '';
                    }

                    this.invoice.paymentMethod = item.payment_method || 'QRIS';
                    
                    // Agent info from travel data
                    this.invoice.agent.name = item.agent_name || 'Kuotaumroh.id';
                    this.invoice.agent.pic = 'Kuotaumroh.id'; // PIC diisi nama travel
                    this.invoice.agent.phone = item.agent_phone || '+62 812-3456-7890'; // Telp travel agent
                    
                    // Ordered by info
                    this.invoice.orderedBy.name = item.customer_name || item.name || 'Customer';
                    this.invoice.orderedBy.type = 'Individual';
                    this.invoice.orderedBy.phone = item.msisdn || '';

                    // Single item
                    this.invoice.items = [{
                        msisdn: item.msisdn || '',
                        packageName: item.package_name || item.name || '',
                        packageType: item.package_type || 'UMROH',
                        quota: item.quota || '',
                        validity: item.days ? `${item.days} Hari` : '',
                        scheduledAt: item.schedule_date ? this.formatDate(item.schedule_date) : '',
                        status: this.mapStatus(item.status),
                        price: parseInt(item.price || item.payment_amount || 0)
                    }];

                    // Totals
                    this.invoice.total = parseInt(item.payment_amount || item.total_payment || 0);
                    this.invoice.subtotal = this.invoice.total;
                    this.invoice.platformFee = 0;
                    this.invoice.notes = 'Terima kasih telah menggunakan layanan Kuotaumroh.id';
                    
                    console.log('✅ Invoice parsed:', this.invoice);
                },

                // Get Agent ID from Storage
                getAgentIdFromStorage() {
                    try {
                        // Check URL params first
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('agent_id')) {
                            return urlParams.get('agent_id');
                        }
                        
                        // Then check localStorage
                        const orderData = localStorage.getItem('pendingOrder');
                        if (orderData) {
                            const parsed = JSON.parse(orderData);
                            return parsed.refCode || parsed.linkReferal || null;
                        }
                        
                        // Check if logged in user
                        const user = JSON.parse(localStorage.getItem('user') || '{}');
                        if (user && user.agent_id) {
                            return user.agent_id;
                        }
                    } catch (e) {
                        console.error('Error parsing storage data:', e);
                    }
                    return null;
                },

                // Map Status
                mapStatus(status) {
                    const statusMap = {
                        'PAID': 'paid',
                        'UNPAID': 'pending',
                        'PENDING': 'pending',
                        'VERIFY': 'pending',
                        'EXPIRED': 'expired',
                        'FAILED': 'expired',
                        'success': 'paid',
                        'pending': 'pending',
                        'expired': 'expired'
                    };
                    return statusMap[status] || 'pending';
                },

                // Format Date
                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return new Intl.DateTimeFormat('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }).format(date);
                },

                // Get Agent ID from Storage
                getAgentIdFromStorage() {
                    try {
                        const orderData = localStorage.getItem('pendingOrder');
                        if (orderData) {
                            const parsed = JSON.parse(orderData);
                            return parsed.refCode || null;
                        }
                    } catch (e) {
                        console.error('Error parsing order data:', e);
                    }
                    return null;
                },

                // Map Status from API to UI
                mapStatus(status) {
                    const statusMap = {
                        'PAID': 'paid',
                        'UNPAID': 'pending',
                        'PENDING': 'pending',
                        'VERIFY': 'pending',
                        'EXPIRED': 'expired',
                        'FAILED': 'expired',
                        'success': 'paid',
                        'pending': 'pending',
                        'expired': 'expired'
                    };
                    return statusMap[status] || 'pending';
                },

                // Format Rupiah
                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount);
                },

                // Get Status Badge Class
                getStatusClass(status) {
                    const lowerStatus = (status || '').toLowerCase();
                    if (lowerStatus.includes('berhasil') || lowerStatus.includes('success') || lowerStatus.includes('paid')) {
                        return 'bg-green-100 text-green-700';
                    } else if (lowerStatus.includes('proses') || lowerStatus.includes('pending') || lowerStatus.includes('verify')) {
                        return 'bg-yellow-100 text-yellow-700';
                    } else if (lowerStatus.includes('gagal') || lowerStatus.includes('failed') || lowerStatus.includes('expired')) {
                        return 'bg-red-100 text-red-700';
                    }
                    return 'bg-gray-100 text-gray-700';
                },
                
                // Get Status Label
                getStatusLabel(status) {
                    const lowerStatus = (status || '').toLowerCase();
                    if (lowerStatus.includes('berhasil') || lowerStatus.includes('success') || lowerStatus.includes('paid')) {
                        return 'Berhasil';
                    } else if (lowerStatus.includes('proses') || lowerStatus.includes('pending') || lowerStatus.includes('verify')) {
                        return 'Proses';
                    } else if (lowerStatus.includes('gagal') || lowerStatus.includes('failed') || lowerStatus.includes('expired')) {
                        return 'Gagal';
                    }
                    return status || 'Unknown';
                },

                // Print Invoice
                printInvoice() {
                    window.print();
                },

                // Download PDF (placeholder)
                downloadPDF() {
                    alert('Fitur download PDF akan segera tersedia.');
                    // In production, this would generate and download a PDF
                }
            }
        }
    </script>
</body>
</html>

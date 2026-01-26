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
        <!-- Agent Info Banner -->
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-blue-800">
                    Anda akan diarahkan ke toko <strong>{{ session('pending_agent_link') }}</strong> setelah memilih paket
                </p>
            </div>
        </div>
        @endif

        <!-- Provider Selection Section -->
        <div id="pilih-provider" class="mb-16 md:mb-20 scroll-mt-24">
            <div class="mb-6 md:mb-10">
                <h2 class="text-2xl md:text-4xl font-bold mb-2 md:mb-3 text-gray-900">Pilih Provider</h2>
                <p class="text-sm md:text-xl text-gray-600">
                    Silakan pilih provider paket internet Anda di bawah ini:
                </p>
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
                                <p>Wa: +62 811-3995-599</p>
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

</body>

</html>

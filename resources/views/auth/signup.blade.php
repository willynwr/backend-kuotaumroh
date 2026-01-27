<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Travel Agent - Kuotaumroh.id</title>

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
  <link rel="stylesheet" href="{{ asset('frontend/shared/styles.css') }}">

  <!-- ⚠️ PENTING: API Configuration inline -->
  <script>
    // API Configuration
    const API_BASE_URL = '{{ url("/") }}';
    const API_URL = `${API_BASE_URL}/api`;
    const STORAGE_URL = `${API_BASE_URL}/storage`;

    function apiUrl(endpoint) {
      const cleanEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
      return `${API_URL}/${cleanEndpoint}`;
    }

    function storageUrl(path) {
      const cleanPath = path.startsWith('/') ? path.slice(1) : path;
      return `${STORAGE_URL}/${cleanPath}`;
    }
  </script>

  <script src="{{ asset('frontend/shared/utils.js') }}"></script>

  <!-- Google Maps JavaScript API -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initGoogleMaps" async defer></script>
  
  <script>
    // Google Maps callback
    function initGoogleMaps() {
      console.log('Google Maps API loaded');
      window.googleMapsLoaded = true;
    }
  </script>

  <!-- Custom Styles for Map Lock -->
  <style>
    /* Prevent map interaction when locked */
    #map.map-locked {
      cursor: default !important;
      pointer-events: none;
    }
    
    #map.map-locked * {
      cursor: default !important;
    }

    /* Smooth transitions for map lock overlay */
    [x-cloak] {
      display: none !important;
    }

    /* Toast notification animation */
    @keyframes slide-in {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .animate-slide-in {
      animation: slide-in 0.3s ease-out;
    }

    /* Ensure error messages are visible above map */
    [class*="border-destructive"] {
      position: relative;
      z-index: 100;
    }

    .text-destructive {
      position: relative;
      z-index: 100;
    }
  </style>

  <!-- Tailwind Config -->
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
  <div x-data="signupApp()">

    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
      <div class="container mx-auto flex h-16 items-center justify-between px-4">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
          <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-9 w-9 object-contain">
          <span class="text-xl font-semibold">Kuotaumroh.id</span>
        </a>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto py-8 px-4 max-w-5xl animate-fade-in">

      <!-- Back Button -->
      <div class="mb-6">
        <a href="{{ url('/') }}"
          class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back
        </a>
      </div>

      <!-- Page Header -->
      <div class="mb-8 text-center md:text-left">
        <h1 class="text-3xl font-bold tracking-tight mb-2">Daftar Travel Agent</h1>
        <p class="text-muted-foreground">Bergabunglah dengan kami sebagai Travel Agent Kuotaumroh.id</p>
      </div>

      <!-- Registration Form -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <form @submit.prevent="handleSubmit" class="space-y-8" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <!-- Left Column: Contact & Basic Info -->
              <div class="space-y-6">
                <!-- Contact Information Section -->
                <div class="space-y-4">
                  <h2 class="text-lg font-semibold border-b pb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Informasi Kontak
                  </h2>

                  <!-- Email -->
                  <div class="space-y-2">
                    <label for="email" class="text-sm font-medium">Email <span class="text-destructive">*</span></label>
                    <input id="email" type="email" x-model="formData.email" placeholder="Email" disabled
                      class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm opacity-60 cursor-not-allowed">
                  </div>
                  <!-- Full Name -->
                  <div class="space-y-2">
                    <label for="full_name" class="text-sm font-medium">Nama PIC <span class="text-destructive">*</span></label>
                    <input id="full_name" type="text" x-model="formData.full_name"
                      class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                      :class="errors.nama_pic && 'border-destructive focus:ring-destructive'">
                    <p x-show="errors.nama_pic" class="text-xs text-destructive" x-text="errors.nama_pic"></p>
                  </div>


                  <!-- Phone Number -->
                  <div class="space-y-2">
                    <label for="phone" class="text-sm font-medium">No. HP (+62) <span class="text-destructive">*</span></label>
                    <div class="flex gap-0">
                      <!-- Prefix +62 with background -->
                      <div class="flex items-center justify-center px-3 rounded-l-md border border-r-0 border-input bg-gray-100">
                        <span class="text-sm font-bold text-gray-800">+62</span>
                      </div>
                      <!-- Phone Input -->
                      <input id="phone" type="tel" x-model="formData.phone"
                        @input="formData.phone = formData.phone.replace(/[^0-9]/g, '')" placeholder="81xxx"
                        class="flex h-10 flex-1 rounded-r-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                        :class="errors.no_hp && 'border-destructive focus:ring-destructive'">
                    </div>
                    <p x-show="errors.no_hp" class="text-xs text-destructive" x-text="errors.no_hp"></p>
                    <p x-show="!errors.no_hp" class="text-xs text-muted-foreground">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
                  </div>
                </div>


              </div>

              <!-- Right Column: Agent Details (Conditional) -->
              <div class="space-y-6">
                <!-- Agent Information Section -->
                <div class="space-y-4">
                  <h2 class="text-lg font-semibold border-b pb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Detail Travel
                  </h2>

                  <!-- Travel Name -->
                  <div class="space-y-2">
                    <label for="travel_name" class="text-sm font-medium">Nama Travel <span
                        class="text-destructive">*</span></label>
                    <input id="travel_name" type="text" x-model="formData.travel_name"
                      class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                      :class="errors.travel_name && 'border-destructive focus:ring-destructive'">
                    <p x-show="errors.travel_name" class="text-xs text-destructive" x-text="errors.travel_name"></p>
                  </div>

                  <!-- Travel Type -->
                  <div class="space-y-2" x-data="{ travelTypeOpen: false }">
                    <label class="text-sm font-medium">Jenis Travel <span class="text-destructive">*</span></label>
                    <div class="relative" @click.away="travelTypeOpen = false">
                      <button type="button" @click="travelTypeOpen = !travelTypeOpen"
                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                        :class="errors.travel_type && 'border-destructive focus:ring-destructive'">
                        <span :class="!formData.travel_type && 'text-muted-foreground'"
                          x-text="formData.travel_type || 'Pilih jenis travel'"></span>
                        <svg class="h-4 w-4 transition-transform" :class="travelTypeOpen && 'rotate-180'" fill="none"
                          stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </button>
                      <div x-show="travelTypeOpen" x-transition
                        class="absolute z-40 mt-1 w-full rounded-md border border-input bg-white shadow-lg"
                        style="display: none;">
                        <div class="py-1">
                          <button type="button" @click="formData.travel_type = 'UMROH'; travelTypeOpen = false"
                            class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors"
                            :class="formData.travel_type === 'UMROH' && 'bg-primary/10 text-primary'">UMROH</button>
                          <button type="button" @click="formData.travel_type = 'LEISURE'; travelTypeOpen = false"
                            class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors"
                            :class="formData.travel_type === 'LEISURE' && 'bg-primary/10 text-primary'">LEISURE</button>
                          <button type="button" @click="formData.travel_type = 'UMROH LEISURE'; travelTypeOpen = false"
                            class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors"
                            :class="formData.travel_type === 'UMROH LEISURE' && 'bg-primary/10 text-primary'">UMROH &
                            LEISURE</button>
                        </div>
                      </div>
                    </div>
                    <p x-show="errors.travel_type" class="text-xs text-destructive" x-text="errors.travel_type"></p>
                  </div>

                  <!-- Travel Member -->
                  <div class="space-y-2">
                    <label for="travel_member" class="text-sm font-medium">Total Traveller per Bulan <span
                        class="text-destructive">*</span></label>
                    <input id="travel_member" type="number" x-model="formData.travel_member" min="0"
                      class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                      :class="errors.travel_member && 'border-destructive focus:ring-destructive'">
                    <p x-show="errors.travel_member" class="text-xs text-destructive" x-text="errors.travel_member"></p>
                  </div>
                </div>


              </div>
            </div>

            <!-- Address Information Section (Full Width) -->
            <div class="space-y-4 pt-4 border-t">
              <h2 class="text-lg font-semibold border-b pb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Informasi Alamat
              </h2>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Province (Searchable) -->
                <div class="space-y-2" x-data="{ provinceDropdownOpen: false, provinceSearch: '' }"
                  x-init="$watch('provinceDropdownOpen', value => { if (value) $nextTick(() => $refs.provinceSearchInput.focus()) })">
                  <label for="province" class="text-sm font-medium">Pilih Provinsi <span class="text-destructive">*</span></label>

                  <!-- Custom Searchable Dropdown -->
                  <div class="relative" @click.away="provinceDropdownOpen = false">
                    <!-- Trigger Button -->
                    <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
                      class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                      :class="errors.provinsi && 'border-destructive focus:ring-destructive'">
                      <span :class="!formData.province && 'text-muted-foreground'"
                        x-text="formData.province || 'Pilih provinsi'"></span>
                      <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="provinceDropdownOpen" x-transition
                      class="absolute z-40 mt-1 w-full rounded-md border border-input bg-white shadow-lg">
                      <!-- Search Input -->
                      <div class="p-2 border-b">
                        <input type="text" x-ref="provinceSearchInput" x-model="provinceSearch" @click.stop
                          placeholder="Cari provinsi..."
                          class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                      </div>

                      <!-- Options List -->
                      <div class="max-h-60 overflow-y-auto">
                        <template
                          x-for="province in provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase()))"
                          :key="province">
                          <button type="button"
                            @click="formData.province = province; handleProvinceChange(); provinceDropdownOpen = false; provinceSearch = ''"
                            class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors"
                            :class="formData.province === province && 'bg-muted'" x-text="province"></button>
                        </template>
                        <!-- No results -->
                        <div
                          x-show="provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase())).length === 0"
                          class="px-3 py-2 text-sm text-muted-foreground">
                          Tidak ada provinsi ditemukan
                        </div>
                      </div>
                    </div>
                  </div>
                  <p x-show="errors.provinsi" class="text-xs text-destructive" x-text="errors.provinsi"></p>
                </div>

                <!-- City (Searchable) -->
                <div class="space-y-2" x-data="{ cityDropdownOpen: false, citySearch: '' }"
                  x-init="$watch('cityDropdownOpen', value => { if (value) $nextTick(() => $refs.citySearchInput.focus()) })">
                  <label for="city" class="text-sm font-medium">Pilih Kota/Kab <span class="text-destructive">*</span></label>

                  <!-- Custom Searchable Dropdown -->
                  <div class="relative" @click.away="cityDropdownOpen = false">
                    <!-- Trigger Button -->
                    <button type="button" @click="cityDropdownOpen = !cityDropdownOpen"
                      class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                      :class="errors.kabupaten_kota && 'border-destructive focus:ring-destructive'">
                      <span :class="!formData.city && 'text-muted-foreground'"
                        x-text="formData.city || 'Pilih kota/kabupaten'"></span>
                      <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="cityDropdownOpen" x-transition
                      class="absolute z-40 mt-1 w-full rounded-md border border-input bg-white shadow-lg">
                      <!-- Search Input -->
                      <div class="p-2 border-b">
                        <input type="text" x-ref="citySearchInput" x-model="citySearch" @click.stop
                          placeholder="Cari kota/kabupaten..."
                          class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                      </div>

                      <!-- Options List -->
                      <div class="max-h-60 overflow-y-auto p-1">
                        <template x-for="city in filteredCities(citySearch)" :key="city">
                          <button type="button"
                            @click="formData.city = city; handleCityChange(); cityDropdownOpen = false; citySearch = ''"
                            :class="formData.city === city && 'bg-primary/10 text-primary'"
                            class="w-full text-left px-3 py-2 text-sm rounded hover:bg-accent transition-colors"
                            x-text="city"></button>
                        </template>

                        <!-- No Results -->
                        <div x-show="filteredCities(citySearch).length === 0"
                          class="px-3 py-6 text-center text-sm text-muted-foreground">
                          Tidak ada hasil ditemukan
                        </div>
                      </div>
                    </div>
                  </div>
                  <p x-show="errors.kabupaten_kota" class="text-xs text-destructive" x-text="errors.kabupaten_kota"></p>
                </div>
              </div>

              <!-- Full Address -->
              <div class="space-y-2">
                <div class="flex justify-between items-center">
                  <label for="address" class="text-sm font-medium">Alamat Lengkap <span class="text-destructive">*</span></label>
                  <span x-show="isGeocodingAddress" x-transition
                    class="text-xs text-muted-foreground flex items-center gap-1">
                    <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                      </path>
                    </svg>
                    Mencari lokasi...
                  </span>
                </div>
                <textarea id="address" x-model="formData.address" @blur="geocodeAddress()" rows="5"
                  class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                  :class="errors.alamat_lengkap && 'border-destructive focus:ring-destructive'"></textarea>
                <p x-show="errors.alamat_lengkap" class="text-xs text-destructive" x-text="errors.alamat_lengkap"></p>
              </div>

              <!-- Map Section -->
              <div class="space-y-2" x-show="formData.city">
                <div class="flex items-center justify-between">
                  <div>
                    <label class="text-sm font-medium">Tandai Lokasi di Peta</label>
                    <p class="text-xs text-muted-foreground">Aktifkan peta lalu klik pada peta untuk menandai lokasi</p>
                  </div>
                  <!-- Map Lock/Unlock Button -->
                  <button type="button" @click="toggleMapLock()" 
                    class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-md border transition-colors"
                    :class="mapLocked ? 'bg-muted text-muted-foreground border-input hover:bg-muted/80' : 'bg-primary text-primary-foreground border-primary hover:bg-primary/90'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mapLocked">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mapLocked">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    <span x-text="mapLocked ? 'Klik untuk Geser Peta' : 'Kunci Peta'"></span>
                  </button>
                </div>

                <!-- Map Search -->
                <div class="relative mb-2">
                  <div class="relative">
                    <input type="text" x-model="mapSearchQuery" @input="handleMapSearch()"
                      @keydown.enter.prevent="handleMapEnter()"
                      placeholder="Cari lokasi (contoh: Monas, Jalan Sudirman)"
                      class="flex h-10 w-full rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    <div
                      class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-muted-foreground">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3" x-show="isSearchingMap">
                      <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                      </svg>
                    </div>
                  </div>

                  <!-- Search Results Dropdown -->
                  <div x-show="mapSearchResults.length > 0" @click.away="mapSearchResults = []"
                    class="absolute z-40 w-full mt-1 bg-white rounded-lg border border-gray-200 shadow-xl max-h-80 overflow-y-auto"
                    style="display: none;">
                    <div class="py-1">
                      <template x-for="(result, index) in mapSearchResults" :key="index">
                        <button type="button" @click="selectMapLocation(result)"
                          class="w-full text-left px-4 py-3 hover:bg-green-50 transition-colors border-b border-gray-100 last:border-0 group">
                          <div class="flex items-start gap-3">
                            <!-- Location Icon -->
                            <div class="flex-shrink-0 mt-0.5">
                              <svg class="h-5 w-5 text-green-600 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                              </svg>
                            </div>
                            <!-- Text Content -->
                            <div class="flex-1 min-w-0">
                              <div class="font-medium text-gray-900 group-hover:text-green-700 mb-0.5" 
                                x-text="result.name || (result.description ? result.description.split(',')[0] : result.display_name)"></div>
                              <div class="text-xs text-gray-500 truncate leading-relaxed" 
                                x-text="result.description || result.display_name"></div>
                            </div>
                            <!-- Arrow Icon -->
                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                              <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                              </svg>
                            </div>
                          </div>
                        </button>
                      </template>
                    </div>
                  </div>
                </div>

                <!-- Map Container with Overlay -->
                <div class="relative">
                  <div id="map" class="w-full h-80 rounded-md border border-input overflow-hidden bg-gray-100"></div>
                  
                  <!-- Locked Overlay -->
                  <div x-show="mapLocked" 
                    @click="toggleMapLock()"
                    class="absolute inset-0 bg-black/10 backdrop-blur-[1px] rounded-md cursor-pointer flex items-center justify-center transition-opacity hover:bg-black/20 group">
                    <div class="bg-white/95 px-6 py-4 rounded-lg shadow-lg border border-primary/20 text-center max-w-xs">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                      </svg>
                      <p class="text-sm font-semibold text-foreground mb-1">Peta Dikunci</p>
                      <p class="text-xs text-muted-foreground leading-relaxed">Peta tidak akan bergerak saat Anda scroll halaman. Klik di sini untuk mengaktifkan peta agar bisa digeser dan di-zoom.</p>
                    </div>
                  </div>
                </div>

                <!-- Coordinates Manual Input -->
                <div x-show="formData.city" class="grid grid-cols-2 gap-4 pt-2">
                  <div class="space-y-2">
                    <label for="latitude" class="text-sm font-medium">Latitude</label>
                    <input id="latitude" type="number" step="any" x-model.number="formData.latitude"
                      @input="updateMapFromCoordinates()" placeholder="-6.xxxxx"
                      class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                  </div>
                  <div class="space-y-2">
                    <label for="longitude" class="text-sm font-medium">Longitude</label>
                    <input id="longitude" type="number" step="any" x-model.number="formData.longitude"
                      @input="updateMapFromCoordinates()" placeholder="106.xxxxx"
                      class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                  </div>
                </div>

                <!-- Logo Travel & Surat PPIU -->
                <div x-show="formData.city" class="space-y-4 pt-4">
                  <!-- Logo Upload Section -->
                  <div class="space-y-2">
                    <label class="text-sm font-medium">Logo Travel (Opsional)</label>
                    <div class="flex items-center gap-4">
                      <input type="file" @change="handleFileUpload($event)"
                        accept="image/png,image/jpeg,image/jpg,image/gif"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium file:cursor-pointer hover:file:text-primary transition-colors cursor-pointer">
                    </div>
                    <p class="text-xs text-muted-foreground">Format: PNG, JPG, GIF. Maksimal 2MB</p>

                    <!-- Logo Preview -->
                    <div x-show="logoPreview" class="mt-2 relative inline-block">
                      <img :src="logoPreview" alt="Logo preview" class="h-24 w-auto object-contain border rounded-md shadow-sm">
                      <button type="button" @click="logoPreview = null; logoFile = null; $el.closest('.space-y-2').querySelector('input[type=file]').value = ''"
                        class="absolute -top-2 -right-2 bg-destructive text-white rounded-full p-1.5 hover:bg-destructive/90 transition-colors shadow-md">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </div>

                  <!-- Kerjasama Section -->
                  <div class="space-y-2">
                    <label class="text-sm font-medium">Surat PPIU <span class="text-destructive">*</span></label>
                    <div class="flex items-center gap-4">
                      <input type="file" @change="handleCooperationLetterUpload($event)"
                        accept="application/pdf,image/png,image/jpeg,image/jpg"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium file:cursor-pointer hover:file:text-primary transition-colors cursor-pointer"
                        :class="errors.cooperationLetterFile && 'border-destructive focus:ring-destructive'">
                    </div>
                    <p x-show="errors.cooperationLetterFile" class="text-xs text-destructive"
                      x-text="errors.cooperationLetterFile"></p>
                    <p class="text-xs text-muted-foreground">Format: PDF, PNG, JPG. Maksimal 5MB</p>

                    <!-- File Preview -->
                    <div x-show="cooperationLetterFile" class="mt-3">
                      <!-- Image Preview -->
                      <div x-show="cooperationLetterType && cooperationLetterType.startsWith('image/')" 
                        class="relative inline-block">
                        <img :src="cooperationLetterPreview" alt="Preview surat PPIU" 
                          class="h-32 w-auto object-contain border rounded-md shadow-sm">
                        <button type="button" @click="removeCooperationLetter()"
                          class="absolute -top-2 -right-2 bg-destructive text-white rounded-full p-1.5 hover:bg-destructive/90 transition-colors shadow-md">
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>

                      <!-- PDF Preview -->
                      <div x-show="cooperationLetterType === 'application/pdf'" 
                        class="flex items-start gap-3 p-3 border rounded-lg bg-white hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                          <div class="flex items-center gap-4">
                          <!-- PDF Icon -->
                          <div class="flex-shrink-0 bg-red-100 rounded p-2">
                            <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                              <path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="2"/>
                            </svg>
                          </div>
                          <!-- File Info -->
                          <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate" 
                              x-text="cooperationLetterFile?.name"></p>
                            <p class="text-xs text-gray-500 mt-0.5" 
                              x-text="formatFileSize(cooperationLetterFile?.size)"></p>
                          </div>
                        </div>
                        
                        <!-- Delete Button -->
                        <button type="button" @click="removeCooperationLetter()"
                          class="flex-shrink-0 text-gray-400 hover:text-red-600 transition-colors">
                          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4 pt-4">
              <button type="submit" :disabled="isSubmitting"
                :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : 'hover:bg-primary/90'"
                class="flex-1 inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-12 px-8 text-lg font-medium transition-colors shadow-sm">
                <span x-show="!isSubmitting">Daftar Sekarang</span>
                <span x-show="isSubmitting" class="flex items-center gap-2">
                  <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                  </svg>
                  Mendaftar...
                </span>
              </button>
            </div>

          </form>
        </div>
      </div>

      <!-- Additional Info -->
      <div class="mt-6 text-center text-sm text-muted-foreground">
        Sudah punya akun?
        <a href="{{ url('/login') }}" class="text-primary font-medium hover:underline">Login di sini</a>
      </div>

    </main>

    <!-- Footer -->
    <footer class="border-t mt-16 py-8">
      <div class="container mx-auto px-4 text-center text-sm text-muted-foreground">
        <p>© 2026 Kuotaumroh.id. All rights reserved.</p>
      </div>
    </footer>

    <!-- Toast Notification -->
    <div x-show="toastVisible" x-transition 
      class="fixed top-4 right-4 max-w-md bg-white border border-input rounded-lg shadow-2xl p-4 z-[9999] animate-slide-in">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" 
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
      style="display: none;">
      <div x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 text-center">
        
        <!-- Success Icon -->
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
          <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>

        <!-- Title -->
        <h3 class="text-2xl font-bold text-gray-900 mb-3">Pendaftaran Berhasil!</h3>
        
        <!-- Message -->
        <p class="text-gray-600 mb-8 leading-relaxed">
          Silakan login untuk masuk dan tunggu approval dari admin.
        </p>

        <!-- Countdown -->
        <div class="inline-flex items-center gap-3 px-5 py-3 bg-gray-50 rounded-lg border border-gray-200">
          <svg class="animate-spin h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="text-sm text-gray-700">
            Mengarahkan dalam <span class="font-bold text-green-600" x-text="successCountdown"></span> detik
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Page Script -->
  <script>
    function signupApp() {
      return {
        // Form data
        formData: {
          email: '',
          phone: '',
          full_name: '',
          travel_name: '',
          travel_type: '',
          travel_member: '',
          province: '',
          city: '',
          address: '',
          latitude: null,
          longitude: null,
        },

        // Logo upload
        logoFile: null,
        logoPreview: null,

        // Cooperation letter upload
        cooperationLetterFile: null,
        cooperationLetterPreview: null,
        cooperationLetterType: null,

        // UI state
        isSubmitting: false,
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',
        errors: {},

        // Regional data from wilayah.id API
        provinces: [],
        provinceCodes: new Map(),
        cities: [],
        cityCodes: new Map(),

        // Map state
        mapInstance: null,
        marker: null,
        mapInitialized: false,
        mapLocked: true, // Lock map by default to prevent accidental scrolling
        placesService: null, // Places Service instance

        // Map Search
        mapSearchQuery: '',
        mapSearchResults: [],
        isSearchingMap: false,
        isGeocodingAddress: false,
        mapSearchDebounce: null,
        referralContext: null,

        // Success Modal
        showSuccessModal: false,
        successCountdown: 3,

        async init() {
          // Get email from URL params (passed after Google auth)
          const urlParams = new URLSearchParams(window.location.search);
          const email = urlParams.get('email');
          const ref = urlParams.get('ref');
          const affiliateId = urlParams.get('affiliate_id');
          const freelanceId = urlParams.get('freelance_id');
          const referrerName = urlParams.get('referrer_name');

          if (email) {
            this.formData.email = email;
          } else {
            // If no email in URL, redirect to login for Google auth first
            // Commented out for development testing
            // this.showToast('Autentikasi Diperlukan', 'Silakan login dengan Google terlebih dahulu');
            // setTimeout(() => {
            //   window.location.href = "{{ url('/login') }}";
            // }, 2000);
          }

          // Load provinces from API
          await this.loadProvinces();

          if (affiliateId) {
            // Support both integer (legacy) and string format (AFT00001, etc.)
            const id = affiliateId.trim();
            if (id) {
              setReferral({ source_type: 'affiliate', id });
              setReferralContext({ source_type: 'affiliate', id, url: window.location.href });
            }
          } else if (freelanceId) {
            // Support both integer (legacy) and string format (FRL00012, etc.)
            const id = freelanceId.trim();
            if (id) {
              setReferral({ source_type: 'freelance', id });
              setReferralContext({ source_type: 'freelance', id, url: window.location.href });
            }
          } else if (ref) {
            const parsed = parseReferralString(ref);
            if (parsed) {
              setReferral(parsed);
              setReferralContext({ ...parsed, url: window.location.href });
            }
          }

          this.referralContext = getReferral();

          // Show referral banner if coming from referral link
          if (this.referralContext && referrerName) {
            const referrerType = this.referralContext.source_type === 'affiliate' ? 'Affiliate' : 'Freelance';
            this.showToast('Referral Aktif', `Anda akan terdaftar sebagai downline ${referrerType}: ${decodeURIComponent(referrerName)}`);
          }

          console.log('Signup form initialized with email:', this.formData.email);
          console.log('Referral context:', this.referralContext);
        },

        async loadProvinces() {
          try {
            const response = await fetch('/wilayah/provinces.json');
            const data = await response.json();

            if (data && data.data) {
              // Store provinces and their codes
              this.provinces = data.data.map(p => p.name).sort();
              data.data.forEach(p => {
                this.provinceCodes.set(p.name, p.code);
              });

              console.log('Loaded', this.provinces.length, 'provinces');
            }
          } catch (error) {
            console.error('Error loading provinces:', error);
            this.showToast('Error', 'Gagal memuat data provinsi');
          }
        },


        async loadCities(provinceCode) {
          try {
            // Try to load from local cache first, then fall back to API with CORS proxy
            let response;
            const localPath = `/wilayah/regencies-${provinceCode}.json`;

            try {
              // Try local cache first
              response = await fetch(localPath);
              if (!response.ok) throw new Error('Not in cache');
            } catch {
              // If not in cache, try API directly (may fail due to CORS)
              // Note: In production, this should work from a proper domain
              response = await fetch(`https://wilayah.id/api/regencies/${provinceCode}.json`);
            }

            const data = await response.json();

            if (data && data.data) {
              // Store cities and their codes
              this.cities = data.data.map(c => c.name).sort();
              this.cityCodes.clear();
              data.data.forEach(c => {
                this.cityCodes.set(c.name, c.code);
              });

              console.log('Loaded', this.cities.length, 'cities for province', provinceCode);
            }
          } catch (error) {
            console.error('Error loading cities:', error);
            this.showToast('Error', 'Gagal memuat data kota/kabupaten. Silakan coba lagi.');
            this.cities = [];
          }
        },

        filteredCities(search) {
          if (!search) return this.cities;
          const searchLower = search.toLowerCase();
          return this.cities.filter(city =>
            city.toLowerCase().includes(searchLower)
          );
        },

        async handleProvinceChange() {
          // Reset city when province changes
          this.formData.city = '';
          this.cities = [];

          const provinceCode = this.provinceCodes.get(this.formData.province);
          if (provinceCode) {
            await this.loadCities(provinceCode);
            console.log('Province changed to:', this.formData.province, `(${provinceCode})`);
          }
        },

        async handleCityChange() {
          console.log('City changed to:', this.formData.city);
          
          // Initialize map if not already initialized
          this.$nextTick(() => {
            // Wait a bit for the x-show to render the map container
            setTimeout(async () => {
              if (!this.mapInitialized) {
                console.log('Initializing map for city:', this.formData.city);
                await this.initializeMap();
              } else if (this.formData.city) {
                // If map is already initialized, re-center to new city
                console.log('Re-centering map to city:', this.formData.city);
                await this.recenterMapToCity();
              }
            }, 300); // Give time for x-show to render
          });
        },

        toggleMapLock() {
          this.mapLocked = !this.mapLocked;
          
          if (this.mapInstance) {
            const mapContainer = document.getElementById('map');
            
            if (this.mapLocked) {
              // Lock the map
              this.mapInstance.setOptions({ 
                draggable: false,
                zoomControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true
              });
              if (mapContainer) mapContainer.classList.add('map-locked');
              
              this.showToast('Peta Dikunci', 'Peta tidak akan bergerak saat Anda scroll. Lokasi Anda aman!');
            } else {
              // Unlock the map
              this.mapInstance.setOptions({ 
                draggable: true,
                zoomControl: true,
                scrollwheel: true,
                disableDoubleClickZoom: false
              });
              if (mapContainer) mapContainer.classList.remove('map-locked');
              
              this.showToast('Peta Aktif', 'Sekarang Anda bisa menggeser dan zoom peta untuk memilih lokasi.');
            }
          }
        },

        async recenterMapToCity() {
          if (!this.mapInstance || !this.formData.city) return;

          try {
            // Use Google Maps Geocoding API
            const searchQuery = this.formData.province
              ? `${this.formData.city}, ${this.formData.province}, Indonesia`
              : `${this.formData.city}, Indonesia`;
            
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: searchQuery }, (results, status) => {
              if (status === 'OK' && results[0]) {
                const location = results[0].geometry.location;
                
                // Pan and zoom to the new city location
                this.mapInstance.panTo(location);
                this.mapInstance.setZoom(11);
                
                console.log('Map re-centered to:', this.formData.city, 'at', location.lat(), location.lng());
              }
            });
          } catch (error) {
            console.warn('Failed to re-center map:', error);
          }
        },

        async initializeMap() {
          // Prevent multiple initializations
          if (this.mapInitialized) {
            console.log('Map already initialized');
            return;
          }

          console.log('Starting map initialization...');

          // Wait for Google Maps API to load
          if (typeof google === 'undefined' || !google.maps) {
            console.log('Waiting for Google Maps API to load...');
            setTimeout(() => this.initializeMap(), 500);
            return;
          }

          console.log('Google Maps API loaded, creating map...');

          // Default center: Indonesia (approximate center)
          let centerLat = -2.5;
          let centerLng = 118.0;
          let zoomLevel = 5;

          try {
            // If we have a selected city, geocode it to get coordinates
            if (this.formData.city) {
              try {
                const searchQuery = this.formData.province
                  ? `${this.formData.city}, ${this.formData.province}, Indonesia`
                  : `${this.formData.city}, Indonesia`;
                
                console.log('Geocoding city:', searchQuery);
                
                const geocoder = new google.maps.Geocoder();
                const result = await new Promise((resolve, reject) => {
                  geocoder.geocode({ address: searchQuery }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                      resolve(results[0]);
                    } else {
                      reject(new Error('Geocoding failed: ' + status));
                    }
                  });
                });

                centerLat = result.geometry.location.lat();
                centerLng = result.geometry.location.lng();
                zoomLevel = 11;
                console.log('Geocoded city:', this.formData.city, 'to', centerLat, centerLng);
              } catch (geocodeError) {
                console.warn('Geocoding failed, using default center:', geocodeError);
              }
            }

            // Get map container
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
              console.error('Map container not found!');
              return;
            }

            console.log('Creating Google Map instance...');

            // Initialize the map
            this.mapInstance = new google.maps.Map(mapContainer, {
              center: { lat: centerLat, lng: centerLng },
              zoom: zoomLevel,
              draggable: !this.mapLocked,
              zoomControl: !this.mapLocked,
              scrollwheel: false,
              disableDoubleClickZoom: this.mapLocked,
              mapTypeControl: false,
              streetViewControl: false,
              fullscreenControl: true,
            });

            console.log('Map instance created successfully');

            // Add initial marker if coordinates exist
            if (this.formData.latitude && this.formData.longitude) {
              this.updateMarker(this.formData.latitude, this.formData.longitude);
            }

            // Add click event handler
            this.mapInstance.addListener('click', (e) => {
              const lat = e.latLng.lat();
              const lng = e.latLng.lng();
              console.log('Map clicked at:', lat, lng);
              this.updateMarker(lat, lng);
            });

            this.mapInitialized = true;
            console.log('Map initialization complete');
            
            // Initialize Places Service once map is ready
            if (google.maps.places) {
              this.placesService = new google.maps.places.PlacesService(this.mapInstance);
              console.log('Places Service initialized successfully');
            } else {
              console.warn('Places library not available - search will use Geocoding only');
            }

            // Add map-locked class if map is locked by default
            if (this.mapLocked && mapContainer) {
              mapContainer.classList.add('map-locked');
            }

          } catch (error) {
            console.error('Error initializing map:', error);
            this.showToast('Error', 'Gagal memuat peta: ' + error.message);
          }
        },

        handleMapSearch() {
          if (!this.mapSearchQuery || this.mapSearchQuery.length < 3) {
            this.mapSearchResults = [];
            return;
          }

          // Debounce search
          if (this.mapSearchDebounce) clearTimeout(this.mapSearchDebounce);

          this.mapSearchDebounce = setTimeout(() => {
            if (typeof google === 'undefined' || !google.maps) {
              console.error('Google Maps not loaded');
              return;
            }
            
            // Wait for map to be initialized
            if (!this.mapInstance) {
              console.warn('Map not initialized yet, waiting...');
              setTimeout(() => this.handleMapSearch(), 500);
              return;
            }
            
            this.isSearchingMap = true;
            
            // Build search query with city and province context
            let searchQuery = this.mapSearchQuery;
            if (this.formData.city) {
              searchQuery += `, ${this.formData.city}`;
            }
            if (this.formData.province) {
              searchQuery += `, ${this.formData.province}`;
            }
            searchQuery += ', Indonesia';
            
            console.log('Searching for:', searchQuery);
            
            // Use Places Service if available
            if (this.placesService) {
              console.log('Using Places API');
              
              const request = {
                query: searchQuery,
                fields: ['name', 'formatted_address', 'geometry', 'place_id']
              };
              
              this.placesService.findPlaceFromQuery(request, (results, status) => {
                console.log('findPlaceFromQuery status:', status);
                
                if (status === google.maps.places.PlacesServiceStatus.OK && results && results.length > 0) {
                  // Success with Places API!
                  this.mapSearchResults = results.map(r => ({
                    place_id: r.place_id,
                    description: r.formatted_address,
                    display_name: r.formatted_address,
                    geometry: r.geometry,
                    name: r.name || r.formatted_address.split(',')[0]
                  }));
                  console.log('Places API success:', this.mapSearchResults.length, 'results');
                  this.isSearchingMap = false;
                } else if (status === google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
                  // Try textSearch for broader results
                  console.log('findPlaceFromQuery zero results, trying textSearch');
                  this.placesService.textSearch(request, (results, status) => {
                    if (status === google.maps.places.PlacesServiceStatus.OK && results && results.length > 0) {
                      this.mapSearchResults = results.slice(0, 10).map(r => ({
                        place_id: r.place_id,
                        description: r.formatted_address,
                        display_name: r.formatted_address,
                        geometry: r.geometry,
                        name: r.name || r.formatted_address.split(',')[0]
                      }));
                      console.log('textSearch success:', this.mapSearchResults.length);
                      this.isSearchingMap = false;
                    } else {
                      console.log('textSearch failed, using Geocoding');
                      this.useGeocodingSearch(searchQuery);
                    }
                  });
                } else {
                  console.log('Places API error:', status, '- using Geocoding fallback');
                  this.useGeocodingSearch(searchQuery);
                }
              });
            } else {
              // Places Service not available
              console.log('Places Service not initialized, using Geocoding API');
              this.useGeocodingSearch(searchQuery);
            }
          }, 500);
        },
        
        useGeocodingSearch(searchQuery) {
          const geocoder = new google.maps.Geocoder();
          
          geocoder.geocode({ 
            address: searchQuery,
            region: 'id'
          }, (results, status) => {
            console.log('Geocoding status:', status, 'Results:', results?.length || 0);
            this.isSearchingMap = false;
            
            if (status === 'OK' && results && results.length > 0) {
              this.mapSearchResults = results.slice(0, 10).map(r => {
                // Extract better name from address components
                let name = '';
                
                // Try to get POI, premise, or street address as name
                const nameComponent = r.address_components?.find(c => 
                  c.types.includes('point_of_interest') ||
                  c.types.includes('premise') ||
                  c.types.includes('street_address') ||
                  c.types.includes('route')
                );
                
                if (nameComponent) {
                  name = nameComponent.long_name;
                } else {
                  // Fallback: use first part of formatted address
                  name = r.formatted_address.split(',')[0];
                }
                
                return {
                  place_id: r.place_id,
                  description: r.formatted_address,
                  display_name: r.formatted_address,
                  geometry: r.geometry,
                  name: name
                };
              });
              
              console.log('Geocoding results:', this.mapSearchResults.length);
            } else {
              console.log('No geocoding results');
              this.mapSearchResults = [];
            }
          });
        },

        async handleMapEnter() {
          // If results are already showing, select the first one
          if (this.mapSearchResults.length > 0) {
            this.selectMapLocation(this.mapSearchResults[0]);
            return;
          }

          // If no results yet, perform immediate search and select first result
          if (!this.mapSearchQuery || this.mapSearchQuery.length < 3) return;
          if (typeof google === 'undefined' || !google.maps) return;

          if (this.mapSearchDebounce) clearTimeout(this.mapSearchDebounce);
          this.isSearchingMap = true;

          try {
            const geocoder = new google.maps.Geocoder();
            const searchQuery = `${this.mapSearchQuery}, Indonesia`;
            
            geocoder.geocode({ 
              address: searchQuery,
              componentRestrictions: { country: 'ID' }
            }, (results, status) => {
              this.isSearchingMap = false;
              if (status === 'OK' && results && results.length > 0) {
                const result = {
                  place_id: results[0].place_id,
                  description: results[0].formatted_address,
                  display_name: results[0].formatted_address,
                  geometry: results[0].geometry
                };
                this.selectMapLocation(result);
              } else {
                this.showToast('Info', 'Lokasi tidak ditemukan');
              }
            });
          } catch (error) {
            console.error('Instant search error:', error);
            this.isSearchingMap = false;
          }
        },

        selectMapLocation(result) {
          if (typeof google === 'undefined' || !google.maps) return;

          // Check if we have geometry directly (from Geocoder results)
          if (result.geometry && result.geometry.location) {
            const location = result.geometry.location;
            const lat = typeof location.lat === 'function' ? location.lat() : location.lat;
            const lng = typeof location.lng === 'function' ? location.lng() : location.lng;

            // Update Form Data
            this.formData.latitude = lat;
            this.formData.longitude = lng;

            // Update Map View
            const position = new google.maps.LatLng(lat, lng);
            this.mapInstance.panTo(position);
            this.mapInstance.setZoom(16);
            this.updateMarker(lat, lng);

            // Clear Search
            this.mapSearchResults = [];
            this.mapSearchQuery = result.display_name || result.description;
            return;
          }

          // Fallback: Use Geocoder to get lat/lng from place_id
          const geocoder = new google.maps.Geocoder();
          geocoder.geocode({ placeId: result.place_id }, (results, status) => {
            if (status === 'OK' && results[0]) {
              const location = results[0].geometry.location;
              const lat = location.lat();
              const lng = location.lng();

              // Update Form Data
              this.formData.latitude = lat;
              this.formData.longitude = lng;

              // Update Address field if empty
              if (!this.formData.address && results[0].address_components) {
                // Try to extract street address
                const streetNumber = results[0].address_components.find(c => c.types.includes('street_number'));
                const route = results[0].address_components.find(c => c.types.includes('route'));
                
                if (route) {
                  this.formData.address = streetNumber 
                    ? `${route.long_name} ${streetNumber.long_name}` 
                    : route.long_name;
                }
              }

              // Update Map View
              this.mapInstance.panTo(location);
              this.mapInstance.setZoom(16);
              this.updateMarker(lat, lng);

              // Clear Search
              this.mapSearchResults = [];
              this.mapSearchQuery = result.display_name || result.description;
            }
          });
        },

        async geocodeAddress() {
          if (!this.formData.address || this.formData.address.length < 5) return;
          if (typeof google === 'undefined') return;

          this.isGeocodingAddress = true;

          // Construct query with full hierarchy for better accuracy
          let queryItems = [this.formData.address];
          if (this.formData.city) queryItems.push(this.formData.city);
          if (this.formData.province) queryItems.push(this.formData.province);
          queryItems.push('Indonesia');

          const query = queryItems.join(', ');

          try {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: query }, (results, status) => {
              this.isGeocodingAddress = false;
              
              if (status === 'OK' && results[0]) {
                const location = results[0].geometry.location;
                const lat = location.lat();
                const lng = location.lng();

                // Pan and zoom to location
                this.mapInstance.panTo(location);
                this.mapInstance.setZoom(18); // Higher zoom for specific address
                this.updateMarker(lat, lng);
                console.log('Map updated from address:', query);
              }
            });
          } catch (error) {
            console.error('Address geocoding failed:', error);
            this.isGeocodingAddress = false;
          }
        },

        updateMapFromCoordinates() {
          const lat = parseFloat(this.formData.latitude);
          const lng = parseFloat(this.formData.longitude);

          if (!isNaN(lat) && !isNaN(lng) && this.mapInstance && typeof google !== 'undefined') {
            const position = new google.maps.LatLng(lat, lng);
            
            // Remove existing marker
            if (this.marker) {
              this.marker.setMap(null);
            }

            // Add new marker
            this.marker = new google.maps.Marker({
              position: position,
              map: this.mapInstance,
              draggable: true,
              animation: google.maps.Animation.DROP
            });

            // Update coordinates when marker is dragged
            this.marker.addListener('dragend', (e) => {
              this.formData.latitude = e.latLng.lat();
              this.formData.longitude = e.latLng.lng();
            });

            // Pan and zoom to marker
            this.mapInstance.panTo(position);
            this.mapInstance.setZoom(16);
          }
        },

        updateMarker(lat, lng) {
          if (typeof google === 'undefined') return;

          // Remove existing marker
          if (this.marker) {
            this.marker.setMap(null);
          }

          const position = new google.maps.LatLng(lat, lng);

          // Add new marker
          this.marker = new google.maps.Marker({
            position: position,
            map: this.mapInstance,
            draggable: true,
            animation: google.maps.Animation.DROP
          });

          // Update coordinates when marker is dragged
          this.marker.addListener('dragend', (e) => {
            this.formData.latitude = e.latLng.lat();
            this.formData.longitude = e.latLng.lng();
          });

          // Update form data
          this.formData.latitude = lat;
          this.formData.longitude = lng;

          console.log('Location set to:', lat, lng);
          
          // Auto-lock map after setting location to prevent accidental changes
          if (!this.mapLocked) {
            setTimeout(() => {
              this.mapLocked = true;
              this.mapInstance.setOptions({ 
                draggable: false,
                zoomControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true
              });
              const mapContainer = document.getElementById('map');
              if (mapContainer) mapContainer.classList.add('map-locked');
            }, 1000); // Lock after 1 second
          }
        },

        handleFileUpload(event) {
          const file = event.target.files[0];
          if (!file) return;

          // Validate file size (max 2MB)
          if (file.size > 2 * 1024 * 1024) {
            this.showToast('Error', 'Ukuran file maksimal 2MB');
            event.target.value = '';
            return;
          }

          // Validate file type
          const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
          if (!validTypes.includes(file.type)) {
            this.showToast('Error', 'Format file harus PNG, JPG, atau GIF');
            event.target.value = '';
            return;
          }

          this.logoFile = file;

          // Create preview
          const reader = new FileReader();
          reader.onload = (e) => {
            this.logoPreview = e.target.result;
          };
          reader.readAsDataURL(file);
        },

        handleCooperationLetterUpload(event) {
          const file = event.target.files[0];
          if (!file) return;

          // Validate file size (max 5MB)
          if (file.size > 5 * 1024 * 1024) {
            this.showToast('Error', 'Ukuran file maksimal 5MB');
            event.target.value = '';
            return;
          }

          // Validate file type
          const validTypes = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
          if (!validTypes.includes(file.type)) {
            this.showToast('Error', 'Format file harus PDF, PNG, atau JPG');
            event.target.value = '';
            return;
          }

          this.cooperationLetterFile = file;
          this.cooperationLetterType = file.type;
          
          // Create preview for images
          if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
              this.cooperationLetterPreview = e.target.result;
            };
            reader.readAsDataURL(file);
          } else {
            // For PDF, just store the filename
            this.cooperationLetterPreview = null;
          }
          
          console.log('Cooperation letter uploaded:', file.name, file.type);
        },

        removeCooperationLetter() {
          this.cooperationLetterFile = null;
          this.cooperationLetterPreview = null;
          this.cooperationLetterType = null;
          // Reset the file input
          const fileInput = document.querySelector('input[type="file"][accept*="application/pdf"]');
          if (fileInput) fileInput.value = '';
        },

        formatFileSize(bytes) {
          if (!bytes) return '0 Bytes';
          const k = 1024;
          const sizes = ['Bytes', 'KB', 'MB'];
          const i = Math.floor(Math.log(bytes) / Math.log(k));
          return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        },

        async handleSubmit() {
          this.errors = {};

          // Frontend validation
          let hasError = false;
          
          if (!this.formData.full_name || this.formData.full_name.trim() === '') {
            this.errors.nama_pic = 'Nama PIC wajib diisi';
            hasError = true;
          }
          
          if (!this.formData.phone || this.formData.phone.trim() === '') {
            this.errors.no_hp = 'Nomor HP wajib diisi';
            hasError = true;
          } else if (!/^8/.test(this.formData.phone)) {
            this.errors.no_hp = 'Nomor HP harus dimulai dengan angka 8 (contoh: 81234567890)';
            hasError = true;
          } else if (this.formData.phone.length < 9 || this.formData.phone.length > 13) {
            this.errors.no_hp = 'Nomor HP harus 9-13 digit (contoh: 81234567890)';
            hasError = true;
          } else if (!/^\d+$/.test(this.formData.phone)) {
            this.errors.no_hp = 'Nomor HP hanya boleh berisi angka';
            hasError = true;
          }
          
          if (!this.formData.travel_name || this.formData.travel_name.trim() === '') {
            this.errors.travel_name = 'Nama Travel wajib diisi';
            hasError = true;
          }
          
          if (!this.formData.travel_type) {
            this.errors.travel_type = 'Jenis Travel wajib dipilih';
            hasError = true;
          }
          
          if (!this.formData.travel_member) {
            this.errors.travel_member = 'Total Traveller per Bulan wajib diisi';
            hasError = true;
          } else if (this.formData.travel_member < 1) {
            this.errors.travel_member = 'Total Traveller minimal 1';
            hasError = true;
          }
          
          if (!this.formData.province || this.formData.province.trim() === '') {
            this.errors.provinsi = 'Provinsi wajib dipilih';
            hasError = true;
          }
          
          if (!this.formData.city || this.formData.city.trim() === '') {
            this.errors.kabupaten_kota = 'Kabupaten/Kota wajib dipilih';
            hasError = true;
          }
          
          if (!this.formData.address || this.formData.address.trim() === '') {
            this.errors.alamat_lengkap = 'Alamat lengkap wajib diisi';
            hasError = true;
          }
          
          if (!this.cooperationLetterFile) {
            this.errors.cooperationLetterFile = 'Surat PPIU wajib diupload';
            hasError = true;
          }

          if (hasError) {
            this.showToast('Validasi Gagal', 'Mohon lengkapi semua data yang bertanda bintang (*)');
            
            // Scroll to first error with offset
            await this.$nextTick();
            const firstErrorElement = document.querySelector('[class*="border-destructive"]');
            if (firstErrorElement) {
              const elementPosition = firstErrorElement.getBoundingClientRect().top;
              const offsetPosition = elementPosition + window.pageYOffset - 100;
              
              window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
              });
              
              // Focus field after scroll
              setTimeout(() => {
                firstErrorElement.focus();
              }, 500);
            }
            return;
          }

          this.isSubmitting = true;

          try {
            const formDataToSend = new FormData();
            
            // Get CSRF token
            const csrfToken = document.querySelector('input[name="_token"]').value;
            formDataToSend.append('_token', csrfToken);

            // Map keys according to backend validation rules
            formDataToSend.append('email', this.formData.email);

            const ref = this.referralContext;
            // Support both integer (legacy) and string format (AFT00001, FRL00012, etc.)
            if (ref && (ref.source_type === 'affiliate' || ref.source_type === 'freelance') && ref.id) {
              formDataToSend.append('kategori_agent', 'Referral');
              if (ref.source_type === 'affiliate') formDataToSend.append('affiliate_id', String(ref.id));
              if (ref.source_type === 'freelance') formDataToSend.append('freelance_id', String(ref.id));
            } else {
              formDataToSend.append('kategori_agent', 'Referral');
              // Don't set affiliate_id here - let backend use the default
            }

            formDataToSend.append('nama_pic', this.formData.full_name);

            // Phone number
            let phone = this.formData.phone;
            if (phone.startsWith('0')) phone = phone.substring(1);
            if (phone.startsWith('62')) phone = phone.substring(2);
            formDataToSend.append('no_hp', '62' + phone);

            formDataToSend.append('nama_travel', this.formData.travel_name);
            formDataToSend.append('jenis_travel', this.formData.travel_type);

            if (this.formData.travel_member) formDataToSend.append('total_traveller', this.formData.travel_member);
            formDataToSend.append('provinsi', this.formData.province);
            formDataToSend.append('kabupaten_kota', this.formData.city);
            formDataToSend.append('alamat_lengkap', this.formData.address);

            if (this.formData.latitude) formDataToSend.append('lat', this.formData.latitude);
            if (this.formData.longitude) formDataToSend.append('long', this.formData.longitude);

            if (this.formData.latitude && this.formData.longitude) {
              const link = `https://www.google.com/maps?q=${this.formData.latitude},${this.formData.longitude}`;
              formDataToSend.append('link_gmaps', link);
            }

            if (this.logoFile) {
              formDataToSend.append('logo', this.logoFile);
            }

            if (this.cooperationLetterFile) {
              formDataToSend.append('surat_ppiu', this.cooperationLetterFile);
            }

            // Submit to Laravel route (not API)
            let response;
            let result = null;
            
            try {
              response = await fetch('{{ route("agent.store") }}', {
                method: 'POST',
                body: formDataToSend,
                credentials: 'same-origin'
              });
            } catch (error) {
              console.error('Network error:', error);
              throw new Error('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
            }

            // Parse response
            const contentType = response.headers.get('content-type') || '';
            
            try {
              if (contentType.includes('application/json')) {
                result = await response.json();
              } else {
                // Non-JSON response (HTML error page, etc.)
                const text = await response.text();
                
                // Don't show raw HTML to user
                if (!response.ok) {
                  console.error('Server returned non-JSON response:', text.substring(0, 500));
                  
                  if (response.status === 413) {
                    throw new Error('Ukuran file terlalu besar. Maksimal 5MB untuk setiap file.');
                  } else if (response.status === 500) {
                    throw new Error('Terjadi kesalahan pada server. Silakan coba lagi atau hubungi administrator.');
                  } else if (response.status === 419) {
                    throw new Error('Sesi Anda telah berakhir. Silakan refresh halaman dan coba lagi.');
                  } else {
                    throw new Error('Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
                  }
                }
              }
            } catch (parseError) {
              if (parseError.message.includes('Ukuran file') || parseError.message.includes('Terjadi kesalahan')) {
                throw parseError;
              }
              console.error('Failed to parse response:', parseError);
              throw new Error('Server mengembalikan response yang tidak valid. Silakan coba lagi.');
            }

            if (!response.ok) {
              if (response.status === 422 && result?.errors) {
                // Map backend errors to form fields
                this.errors = {};
                for (const [key, messages] of Object.entries(result.errors)) {
                  this.errors[key] = Array.isArray(messages) ? messages[0] : messages;
                }
                
                // Get first error message
                const firstError = Object.values(result.errors).flat()[0];
                
                // Scroll to first error field
                await this.$nextTick();
                const firstErrorElement = document.querySelector('[class*="border-destructive"]');
                if (firstErrorElement) {
                  // Scroll dengan offset agar tidak tertutup elemen lain
                  const elementPosition = firstErrorElement.getBoundingClientRect().top;
                  const offsetPosition = elementPosition + window.pageYOffset - 100;
                  
                  window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                  });
                  
                  // Focus pada field error
                  setTimeout(() => {
                    firstErrorElement.focus();
                  }, 500);
                }
                
                throw new Error(firstError);
              }
              throw new Error(result?.message || 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
            }

            console.log('Success:', result);
            clearReferral();
            
            // Show success modal with countdown
            this.showSuccessModal = true;
            this.successCountdown = 3;
            
            const countdownInterval = setInterval(() => {
              this.successCountdown--;
              if (this.successCountdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = '{{ route("login") }}';
              }
            }, 1000);

          } catch (error) {
            console.error('Submission error:', error);
            this.showToast('Error', error.message || 'Gagal menghubungi server');
          } finally {
            this.isSubmitting = false;
          }
        },

        showToast(title, message) {
          this.toastTitle = title;
          this.toastMessage = message;
          this.toastVisible = true;
          setTimeout(() => { this.toastVisible = false; }, 3000);
        }
      }
    }
  </script>

</body>

</html>

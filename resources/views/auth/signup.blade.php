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

  <!-- Leaflet.js CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <!-- Leaflet.js JavaScript -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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
        <div class="flex items-center gap-2">
          <!-- Desktop Logo -->
          <div class="hidden md:flex items-center gap-2">
            <img src="{{ asset('images/kabah.png') }}" alt="Kuotaumroh.id" class="h-9 w-9 object-contain">
            <span class="text-xl font-semibold">Kuotaumroh.id</span>
          </div>
          <!-- Mobile Banner Logo -->
          <div class="md:hidden">
            <img src="{{ asset('images/bannermobile.png') }}" alt="Kuotaumroh.id" class="h-10 object-contain">
          </div>
        </div>
        <a href="{{ url('/login') }}"
          class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-9 px-4 text-sm font-medium hover:bg-primary/90 transition-colors">
          Login
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
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input id="email" type="email" x-model="formData.email" placeholder="Email" disabled
                      class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm opacity-60 cursor-not-allowed">
                  </div>
                  <!-- Full Name -->
                  <div class="space-y-2">
                    <label for="full_name" class="text-sm font-medium">Nama PIC</label>
                    <input id="full_name" type="text" x-model="formData.full_name"
                      class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                  </div>


                  <!-- Phone Number -->
                  <div class="space-y-2">
                    <label for="phone" class="text-sm font-medium">No. HP (+62)</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <span class="text-sm text-muted-foreground">+62</span>
                      </div>
                      <input id="phone" type="tel" x-model="formData.phone"
                        @input="formData.phone = formData.phone.replace(/[^0-9]/g, '')" placeholder="81xxx"
                        class="flex h-10 w-full rounded-md border border-input bg-background pl-12 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <p class="text-xs text-muted-foreground">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
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
                        class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg"
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
                    <div x-show="logoPreview" class="mt-2">
                      <img :src="logoPreview" alt="Logo preview" class="h-24 w-24 object-contain border rounded-md">
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
                    <div x-show="cooperationLetterFile" class="mt-2 p-3 border rounded-md bg-muted/30">
                      <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                          <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                          </svg>
                          <div class="overflow-hidden">
                            <p class="text-sm font-medium truncate" x-text="cooperationLetterFile?.name"></p>
                            <p class="text-xs text-muted-foreground"
                              x-text="formatFileSize(cooperationLetterFile?.size)"></p>
                          </div>
                        </div>
                        <button type="button" @click="removeCooperationLetter()"
                          class="text-destructive hover:text-destructive/80 transition-colors">
                          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </div>
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
                  <label for="province" class="text-sm font-medium">Pilih Provinsi</label>

                  <!-- Custom Searchable Dropdown -->
                  <div class="relative" @click.away="provinceDropdownOpen = false">
                    <!-- Trigger Button -->
                    <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
                      class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                      <span :class="!formData.province && 'text-muted-foreground'"
                        x-text="formData.province || 'Pilih provinsi'"></span>
                      <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="provinceDropdownOpen" x-transition
                      class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg">
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
                </div>

                <!-- City (Searchable) -->
                <div class="space-y-2" x-data="{ cityDropdownOpen: false, citySearch: '' }"
                  x-init="$watch('cityDropdownOpen', value => { if (value) $nextTick(() => $refs.citySearchInput.focus()) })">
                  <label for="city" class="text-sm font-medium">Pilih Kota/Kab</label>

                  <!-- Custom Searchable Dropdown -->
                  <div class="relative" @click.away="cityDropdownOpen = false">
                    <!-- Trigger Button -->
                    <button type="button" @click="cityDropdownOpen = !cityDropdownOpen"
                      class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                      <span :class="!formData.city && 'text-muted-foreground'"
                        x-text="formData.city || 'Pilih kota/kabupaten'"></span>
                      <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="cityDropdownOpen" x-transition
                      class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg">
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
                </div>
              </div>

              <!-- Full Address -->
              <div class="space-y-2">
                <div class="flex justify-between items-center">
                  <label for="address" class="text-sm font-medium">Alamat Lengkap</label>
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
                  class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
              </div>

              <!-- Map Section -->
              <div class="space-y-2" x-show="formData.city">
                <label class="text-sm font-medium">Tandai Lokasi di Peta</label>
                <p class="text-xs text-muted-foreground">Klik pada peta untuk menandai lokasi Anda</p>

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
                    class="absolute z-10 w-full mt-1 bg-popover text-popover-foreground rounded-md border shadow-md max-h-60 overflow-y-auto"
                    style="display: none;">
                    <ul>
                      <template x-for="(result, index) in mapSearchResults" :key="index">
                        <li @click="selectMapLocation(result)"
                          class="px-3 py-2 text-sm hover:bg-accent hover:text-accent-foreground cursor-pointer border-b last:border-0">
                          <div class="font-medium" x-text="result.display_name.split(',')[0]"></div>
                          <div class="text-xs text-muted-foreground truncate" x-text="result.display_name"></div>
                        </li>
                      </template>
                    </ul>
                  </div>
                </div>

                <!-- Map Container -->
                <div id="map" class="w-full h-80 rounded-md border border-input overflow-hidden"
                  x-init="$nextTick(() => { if (formData.city && !mapInitialized) initializeMap(); })"></div>

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
    <div x-show="toastVisible" x-transition class="toast">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
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

        // Map Search
        mapSearchQuery: '',
        mapSearchResults: [],
        isSearchingMap: false,
        isGeocodingAddress: false,
        mapSearchDebounce: null,
        referralContext: null,

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
            const id = parseInt(affiliateId, 10);
            if (Number.isFinite(id)) {
              setReferral({ source_type: 'affiliate', id });
              setReferralContext({ source_type: 'affiliate', id, url: window.location.href });
            }
          } else if (freelanceId) {
            const id = parseInt(freelanceId, 10);
            if (Number.isFinite(id)) {
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
          // Initialize map if not already initialized
          this.$nextTick(async () => {
            if (!this.mapInitialized) {
              await this.initializeMap();
            } else if (this.formData.city) {
              // If map is already initialized, re-center to new city
              await this.recenterMapToCity();
            }
          });
        },

        async recenterMapToCity() {
          if (!this.mapInstance || !this.formData.city) return;

          try {
            // Use Nominatim API to geocode the city with province for better accuracy
            const searchQuery = this.formData.province
              ? `${this.formData.city}, ${this.formData.province}, Indonesia`
              : `${this.formData.city}, Indonesia`;
            const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1`;

            const response = await fetch(geocodeUrl);
            const data = await response.json();

            if (data && data.length > 0) {
              const centerLat = parseFloat(data[0].lat);
              const centerLng = parseFloat(data[0].lon);

              // Fly to the new city location
              this.mapInstance.flyTo([centerLat, centerLng], 11, {
                duration: 1.5 // Animation duration in seconds
              });

              console.log('Map re-centered to:', this.formData.city, 'at', centerLat, centerLng);
            }
          } catch (error) {
            console.warn('Failed to re-center map:', error);
          }
        },

        async initializeMap() {
          // Prevent multiple initializations
          if (this.mapInitialized) return;

          // Default center: Indonesia (approximate center)
          let centerLat = -2.5;
          let centerLng = 118.0;
          let zoomLevel = 5;

          try {
            // If we have a selected city, geocode it to get coordinates
            if (this.formData.city) {
              try {
                // Use Nominatim API to geocode the city with province for better accuracy
                const searchQuery = this.formData.province
                  ? `${this.formData.city}, ${this.formData.province}, Indonesia`
                  : `${this.formData.city}, Indonesia`;
                const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1`;

                const response = await fetch(geocodeUrl);
                const data = await response.json();

                if (data && data.length > 0) {
                  centerLat = parseFloat(data[0].lat);
                  centerLng = parseFloat(data[0].lon);
                  zoomLevel = 11; // Zoom to city level
                  console.log('Geocoded city:', this.formData.city, 'to', centerLat, centerLng);
                }
              } catch (geocodeError) {
                console.warn('Geocoding failed, using default center:', geocodeError);
              }
            }

            // Initialize the map
            this.mapInstance = L.map('map').setView([centerLat, centerLng], zoomLevel);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '© OpenStreetMap contributors',
              maxZoom: 19
            }).addTo(this.mapInstance);

            // Add initial marker if coordinates exist
            if (this.formData.latitude && this.formData.longitude) {
              this.updateMarker(this.formData.latitude, this.formData.longitude);
            }

            // Add click event handler
            this.mapInstance.on('click', (e) => {
              const { lat, lng } = e.latlng;
              this.updateMarker(lat, lng);
            });

            this.mapInitialized = true;

            // Fix map size check after render
            setTimeout(() => {
              this.mapInstance.invalidateSize();
            }, 100);

          } catch (error) {
            console.error('Error initializing map:', error);
            this.showToast('Error', 'Gagal memuat peta');
          }
        },

        handleMapSearch() {
          if (!this.mapSearchQuery || this.mapSearchQuery.length < 3) {
            this.mapSearchResults = [];
            return;
          }

          // Debounce search
          if (this.mapSearchDebounce) clearTimeout(this.mapSearchDebounce);

          this.mapSearchDebounce = setTimeout(async () => {
            this.isSearchingMap = true;
            try {
              // Search predominantly within Indonesia
              const query = `${this.mapSearchQuery}, Indonesia`;
              const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`);

              if (response.ok) {
                this.mapSearchResults = await response.json();
              }
            } catch (error) {
              console.error('Map search error:', error);
            } finally {
              this.isSearchingMap = false;
            }
          }, 500); // 500ms debounce
        },

        async handleMapEnter() {
          // If results are already showing, select the first one
          if (this.mapSearchResults.length > 0) {
            this.selectMapLocation(this.mapSearchResults[0]);
            return;
          }

          // If no results yet, perform immediate search and select first result
          if (!this.mapSearchQuery || this.mapSearchQuery.length < 3) return;

          if (this.mapSearchDebounce) clearTimeout(this.mapSearchDebounce);
          this.isSearchingMap = true;

          try {
            const query = `${this.mapSearchQuery}, Indonesia`;
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`);

            if (response.ok) {
              const data = await response.json();
              if (data && data.length > 0) {
                // Select the first result immediately
                this.selectMapLocation(data[0]);
              } else {
                this.showToast('Info', 'Lokasi tidak ditemukan');
              }
            }
          } catch (error) {
            console.error('Instant search error:', error);
          } finally {
            this.isSearchingMap = false;
          }
        },

        selectMapLocation(result) {
          const lat = parseFloat(result.lat);
          const lon = parseFloat(result.lon);

          // Update Form Data
          this.formData.latitude = lat;
          this.formData.longitude = lon;

          // Update Address field if empty or user wants to replace
          // Ideally, we might append or confirm, but for now let's auto-fill if empty
          // or just append street name if available
          if (result.address && (result.address.road || result.address.village)) {
            const street = result.address.road || result.address.village || result.display_name.split(',')[0];
            if (!this.formData.address) {
              this.formData.address = street;
            }
          }

          // Update Map View
          this.mapInstance.flyTo([lat, lon], 16);
          this.updateMarker(lat, lon);

          // Clear Search
          this.mapSearchResults = [];
          this.mapSearchQuery = result.display_name;
        },

        async geocodeAddress() {
          if (!this.formData.address || this.formData.address.length < 5) return;

          this.isGeocodingAddress = true;

          // Construct query with full hierarchy for better accuracy
          let queryItems = [this.formData.address];

          if (this.formData.city) queryItems.push(this.formData.city);
          if (this.formData.province) queryItems.push(this.formData.province);

          const query = queryItems.join(', ');

          try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
            const data = await response.json();

            if (data && data.length > 0) {
              const lat = parseFloat(data[0].lat);
              const lon = parseFloat(data[0].lon);

              // Fly to location
              this.mapInstance.flyTo([lat, lon], 18); // Higher zoom for specific address
              this.updateMarker(lat, lon);
              console.log('Map updated from address:', query);
            }
          } catch (error) {
            console.error('Address geocoding failed:', error);
          } finally {
            this.isGeocodingAddress = false;
          }
        },

        updateMapFromCoordinates() {
          const lat = parseFloat(this.formData.latitude);
          const lng = parseFloat(this.formData.longitude);

          if (!isNaN(lat) && !isNaN(lng) && this.mapInstance) {
            // Remove existing marker
            if (this.marker) {
              this.mapInstance.removeLayer(this.marker);
            }

            // Define custom green icon
            const greenIcon = L.icon({
              iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
              shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34],
              shadowSize: [41, 41]
            });

            this.marker = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapInstance);
            this.mapInstance.flyTo([lat, lng], 16);
          }
        },

        updateMarker(lat, lng) {
          // Remove existing marker
          if (this.marker) {
            this.mapInstance.removeLayer(this.marker);
          }

          // Define custom green icon
          const greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
          });

          // Add new marker
          this.marker = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapInstance);

          // Update form data
          this.formData.latitude = lat;
          this.formData.longitude = lng;

          console.log('Location set to:', lat, lng);
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
          console.log('Cooperation letter uploaded:', file.name);
        },

        removeCooperationLetter() {
          this.cooperationLetterFile = null;
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

          let hasError = false;
          if (!this.formData.travel_name) {
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
          }
          if (!this.cooperationLetterFile) {
            this.errors.cooperationLetterFile = 'Surat PPIU wajib diupload';
            hasError = true;
          }

          if (hasError) {
            this.showToast('Validasi Gagal', 'Mohon lengkapi data travel yang bertanda bintang');
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
            if (ref && (ref.source_type === 'affiliate' || ref.source_type === 'freelance') && Number.isFinite(ref.id)) {
              formDataToSend.append('kategori_agent', 'Referral');
              if (ref.source_type === 'affiliate') formDataToSend.append('affiliate_id', String(ref.id));
              if (ref.source_type === 'freelance') formDataToSend.append('freelance_id', String(ref.id));
            } else {
              formDataToSend.append('kategori_agent', 'Referral');
              formDataToSend.append('affiliate_id', '1');
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
            let response = await fetch('{{ route("agent.store") }}', {
              method: 'POST',
              body: formDataToSend,
              credentials: 'same-origin'
            });

            let result = await response.json();

            if (!response.ok) {
              if (response.status === 422 && result.errors) {
                const errorMessages = Object.values(result.errors).flat().join(', ');
                throw new Error(errorMessages);
              }
              throw new Error(result.message || 'Terjadi kesalahan saat mendaftar');
            }

            console.log('Success:', result);
            this.showToast('Berhasil!', 'Pendaftaran Travel Agent berhasil. Mengarahkan ke login...');
            clearReferral();

            setTimeout(() => {
              window.location.href = '{{ route("login") }}';
            }, 800);

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

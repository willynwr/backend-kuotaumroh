<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Kuotaumroh.id</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
  <meta name="theme-color" content="#10b981">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Alpine.js for reactivity -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Shared CSS -->
  <link rel="stylesheet" href="{{ asset('frontend/shared/styles.css') }}">

  <!-- Tailwind Config -->
  <script>
    // API Configuration
    const API_BASE_URL = '{{ url('/') }}';
    const API_URL = `${API_BASE_URL}/api`;

    function apiUrl(endpoint) {
      const cleanEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
      return `${API_URL}/${cleanEndpoint}`;
    }

    tailwind.config = {
      theme: {
        extend: {
          colors: {
            border: "hsl(var(--border))",
            input: "hsl(var(--input))",
            background: "hsl(var(--background))",
            foreground: "hsl(var(--foreground))",
            primary: {
              DEFAULT: "hsl(var(--primary))",
              foreground: "hsl(var(--primary-foreground))",
            },
            muted: {
              DEFAULT: "hsl(var(--muted))",
              foreground: "hsl(var(--muted-foreground))",
            },
            destructive: {
              DEFAULT: "hsl(var(--destructive))",
              foreground: "hsl(var(--destructive-foreground))",
            },
            card: {
              DEFAULT: "hsl(var(--card))",
              foreground: "hsl(var(--card-foreground))",
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

<body class="h-screen overflow-hidden">
  <!-- Alpine.js App -->
  <div x-data="adminLoginApp()" x-init="init()" class="relative h-screen overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
      <img src="{{ asset('images/image.png') }}" alt="Background" class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-br from-slate-900/45 via-white/10 to-primary/30"></div>
    </div>

    <!-- Logo - Top Left -->
    <div class="absolute top-4 left-4 sm:top-6 sm:left-6 lg:top-8 lg:left-8 z-20">
      <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-16 w-auto sm:h-24 sm:w-auto lg:h-32 lg:w-auto object-contain drop-shadow-2xl">
    </div>

    <!-- Main Content -->
    <div class="relative z-10 h-full flex items-center justify-center px-3 sm:px-4 py-20 sm:py-8 md:py-4">
      <div class="w-full max-w-6xl">
        
        <!-- Hero Copy - Visible on all screens -->
        <div class="text-white drop-shadow-lg mb-6 sm:mb-8 md:hidden text-center px-2">
          <h1 class="text-2xl sm:text-3xl font-black leading-tight tracking-tight">
            Portal Administrator Kuotaumroh.id
          </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 lg:gap-12 items-center">
          <!-- Hero Copy - Desktop -->
          <div class="hidden md:block text-white drop-shadow-lg space-y-6">
            <div class="space-y-5">
              <h1 class="text-3xl lg:text-5xl xl:text-6xl font-black leading-[1.1] tracking-tight">
                Portal Administrator Kuotaumroh.id
              </h1>
              <p class="text-lg lg:text-xl text-white/90 font-medium">
                Kelola sistem dan pengguna dengan mudah
              </p>
            </div>
          </div>

          <!-- Login Card -->
          <div class="w-full max-w-md mx-auto md:max-w-none">
            <div class="rounded-2xl bg-white/50 backdrop-blur shadow-2xl border border-white/40 p-4 sm:p-5 lg:p-7 space-y-4 sm:space-y-5">
              <div class="space-y-3 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Admin Access</p>
                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900">Login Administrator</h3>
                <p class="text-xs sm:text-sm text-slate-600">
                  Gunakan akun Google yang terdaftar sebagai admin
                </p>
              </div>

            @if(session('error'))
            <div class="p-3 rounded-md bg-red-50 border border-red-200">
              <p class="text-sm text-red-600">{{ session('error') }}</p>
            </div>
            @endif

            <div class="space-y-3">
              <button @click="handleGoogleLogin()" :disabled="loading"
                class="w-full inline-flex items-center justify-center h-11 sm:h-12 rounded-full bg-white border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all gap-3 text-sm sm:text-base text-slate-800 font-semibold disabled:opacity-60 disabled:cursor-not-allowed">
                <svg class="h-5 w-5" viewBox="0 0 24 24">
                  <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                  <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                  <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                  <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                </svg>
                <span x-show="!loading">Masuk dengan Google</span>
                <span x-show="loading">Memuat...</span>
              </button>
            </div>

            <p class="text-center text-xs text-slate-500">Hanya admin terdaftar yang dapat mengakses sistem</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Shared Scripts -->
  <script src="{{ asset('frontend/shared/utils.js') }}"></script>

  <!-- Page Script -->
  <script>
    function adminLoginApp() {
      return {
        loading: false,

        init() {
          // If already logged in as admin, redirect to dashboard
          if (isLoggedIn() && getUserRole() === 'admin') {
            window.location.href = '{{ url('/admin/dashboard') }}';
          }
        },

        async handleGoogleLogin() {
          this.loading = true;

          try {
            // Set intent for admin login
            sessionStorage.setItem('auth_intent', 'admin_login');

            // Get Google URL from backend
            const response = await fetch(apiUrl('/auth/google/url'));
            const data = await response.json();

            if (data && data.url) {
              window.location.href = data.url;
            } else {
              throw new Error('Gagal mendapatkan URL login Google');
            }
          } catch (error) {
            alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
            this.loading = false;
          }
        }
      }
    }
  </script>

</body>
</html>

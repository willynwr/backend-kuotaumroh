<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Kuotaumroh.id</title>

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
    const STORAGE_URL = `${API_BASE_URL}/storage`;

    function apiUrl(endpoint) {
      const cleanEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
      return `${API_URL}/${cleanEndpoint}`;
    }

    function storageUrl(path) {
      const cleanPath = path.startsWith('/') ? path.slice(1) : path;
      return `${STORAGE_URL}/${cleanPath}`;
    }

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

<body class="h-screen overflow-hidden">
  <!-- Alpine.js App -->
  <div x-data="loginApp()" x-init="init()" class="relative h-screen overflow-hidden">
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
            Tambah Cuan Jual Paket Roaming Umroh & Haji Anti Ribet
          </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 lg:gap-12 items-center">
          <!-- Hero Copy - Desktop -->
          <div class="hidden md:block text-white drop-shadow-lg space-y-6">
            <div class="space-y-5">
              <h1 class="text-3xl lg:text-5xl xl:text-6xl font-black leading-[1.1] tracking-tight">
                Tambah Cuan Jual Paket Roaming Umroh & Haji Anti Ribet
              </h1>
            </div>
          </div>

          <!-- Login Card -->
          <div class="w-full max-w-md mx-auto md:max-w-none">
            <div class="rounded-2xl bg-white/95 backdrop-blur shadow-2xl border border-white/40 p-4 sm:p-5 lg:p-7 space-y-4 sm:space-y-5">
              <div class="space-y-3 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Masuk Agen</p>
                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900">Lanjutkan dengan Google</h3>
                <p class="text-xs sm:text-sm text-slate-600" x-show="!referrerName">
                  Gunakan akun Google yang sudah terdaftar sebagai agen.
                </p>
                <!-- Improved Referral Banner -->
                <div x-show="referrerName" class="mt-4 p-3 sm:p-4 rounded-xl bg-gradient-to-br from-primary/10 to-primary/5 border-2 border-primary/30 shadow-sm">
                  <div class="flex items-center justify-center gap-2 mb-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                      <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm font-bold text-primary uppercase tracking-wide">Undangan dari</p>
                  </div>
                  <p class="text-lg sm:text-xl lg:text-2xl font-black text-primary mb-1" x-text="referrerName"></p>
                  <p class="text-xs text-slate-600">Login untuk melanjutkan pendaftaran</p>
                </div>
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

              <div class="text-center text-xs sm:text-sm text-slate-500">atau</div>

              <button @click="handleGoogleSignup()" :disabled="loading"
                class="w-full inline-flex items-center justify-center h-10 sm:h-11 rounded-full bg-primary text-white text-sm sm:text-base font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                <span x-show="!loading">Daftar sebagai Agen</span>
                <span x-show="loading">Memproses...</span>
              </button>
            </div>

            <p class="text-center text-xs text-slate-500">Dengan melanjutkan, Anda setuju dengan syarat & kebijakan privasi.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div x-data="{ toast: { visible: false, title: '', message: '' } }" x-show="toast.visible" x-transition
    class="fixed bottom-4 right-4 bg-white border rounded-lg shadow-lg p-4 max-w-sm z-50" style="display: none;">
    <div class="font-semibold" x-text="toast.title"></div>
    <div class="text-sm text-muted-foreground" x-text="toast.message"></div>
  </div>

  <!-- Shared Scripts -->
  <script src="{{ asset('frontend/shared/utils.js') }}"></script>
  <script src="{{ asset('frontend/shared/components.js') }}"></script>

  <!-- Page Script -->
  <script>
    function loginApp() {
      return {
        loading: false,
        referrerName: '',
        toast: {
          visible: false,
          title: '',
          message: '',
          show(title, message) {
            this.title = title;
            this.message = message;
            this.visible = true;
            setTimeout(() => {
              this.visible = false;
            }, 3000);
          },
          error(title, message) {
            this.show(title, message);
          }
        },

        init() {
          try {
            const params = new URLSearchParams(window.location.search);
            const ref = params.get('ref');
            const affiliateId = params.get('affiliate_id');
            const freelanceId = params.get('freelance_id');
            const referrerName = params.get('referrer_name');

            // Display referrer name if present
            if (referrerName) {
              this.referrerName = decodeURIComponent(referrerName);
            }

            if (affiliateId) {
              setReferral({ source_type: 'affiliate', id: parseInt(affiliateId, 10) });
              setReferralContext({ source_type: 'affiliate', id: parseInt(affiliateId, 10), url: window.location.href });
              return;
            }
            if (freelanceId) {
              setReferral({ source_type: 'freelance', id: parseInt(freelanceId, 10) });
              setReferralContext({ source_type: 'freelance', id: parseInt(freelanceId, 10), url: window.location.href });
              return;
            }
            if (ref) {
              const parts = ref.split(':');
              if (parts.length === 2) {
                const type = parts[0];
                const id = parseInt(parts[1], 10);
                if ((type === 'affiliate' || type === 'freelance') && Number.isFinite(id)) {
                  setReferral({ source_type: type, id });
                  setReferralContext({ source_type: type, id, url: window.location.href });
                }
              }
            }
          } catch {
          }
        },

        async handleGoogleLogin() {
          this.loading = true;

          try {
            // Set intent so callback knows if we are logging in or signing up
            sessionStorage.setItem('auth_intent', 'login');

            // Get Google URL from backend
            const response = await fetch(apiUrl('/auth/google/url'));
            const data = await response.json();

            if (data && data.url) {
              window.location.href = data.url;
            } else {
              throw new Error('Gagal mendapatkan URL login Google');
            }
          } catch (error) {
            this.loading = false;
            this.toast.error('Login Gagal', error.message || 'Silakan coba lagi atau hubungi admin.');
            console.error('Login error:', error);
          }
        },

        async handleGoogleSignup() {
          this.loading = true;

          try {
            // Set intent
            sessionStorage.setItem('auth_intent', 'signup');

            // Get Google URL from backend
            const response = await fetch(apiUrl('/auth/google/url'));
            const data = await response.json();

            if (data && data.url) {
              window.location.href = data.url;
            } else {
              throw new Error('Gagal mendapatkan URL login Google');
            }
          } catch (error) {
            this.loading = false;
            this.toast.error('Gagal', error.message || 'Silakan coba lagi.');
            console.error('Google signup error:', error);
          }
        }
      }
    }
  </script>

</body>

</html>

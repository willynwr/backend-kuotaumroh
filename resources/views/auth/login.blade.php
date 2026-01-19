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

<body class="min-h-screen">
  <!-- Alpine.js App -->
  <div x-data="loginApp()" x-init="init()">
    <div
      class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-primary/5 to-background p-4">
      <div class="w-full max-w-md space-y-8 animate-fade-in">

        <!-- Logo -->
        <div class="flex flex-col items-center space-y-2">
          <img src="{{ asset('images/kabah.png') }}" alt="Kuotaumroh.id Logo" class="h-24 w-24 object-contain" />
          <h1 class="text-xl font-bold">Kuotaumroh.id</h1>
          <p class="text-muted-foreground text-center">
            Kelola kuota internet untuk umroh dan haji dengan mudah
          </p>
        </div>

        <!-- Login Card -->
        <div class="rounded-lg bg-card text-card-foreground border-0 shadow-lg">
          <div class="p-6 text-center">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Portal Agen</h3>
            <p class="text-sm text-muted-foreground mt-2">
              Gunakan akun Google yang terdaftar sebagai agen
            </p>
          </div>
          <div class="p-6 pt-0 space-y-4">
            <!-- Google Login Button -->
            <button @click="handleGoogleLogin()" :disabled="loading"
              class="inline-flex items-center justify-center rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground w-full h-12 text-base gap-3 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
              <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path
                  d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                  fill="#4285F4" />
                <path
                  d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                  fill="#34A853" />
                <path
                  d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                  fill="#FBBC05" />
                <path
                  d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                  fill="#EA4335" />
              </svg>
              <span x-show="!loading">Masuk dengan Google</span>
              <span x-show="loading">Loading...</span>
            </button>

            <!-- Divider -->
            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t"></span>
              </div>
              <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-card px-2 text-muted-foreground">
                  Belum terdaftar?
                </span>
              </div>
            </div>

            <!-- Registration Button -->
            <button @click="handleGoogleSignup()" :disabled="loading"
              class="inline-flex items-center justify-center rounded-md bg-secondary text-secondary-foreground hover:bg-secondary/80 w-full h-11 text-sm font-medium transition-colors gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
              <span x-show="!loading">Daftar sebagai Agen</span>
              <span x-show="loading">Memproses...</span>
            </button>
          </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-muted-foreground">
          © 2026 Kuotaumroh.id. All rights reserved.
        </p>
      </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toast.visible" x-transition
      class="fixed bottom-4 right-4 bg-white border rounded-lg shadow-lg p-4 max-w-sm z-50"
      style="display: none;">
      <div class="font-semibold" x-text="toast.title"></div>
      <div class="text-sm text-muted-foreground" x-text="toast.message"></div>
    </div>
  </div>

  <!-- Shared Scripts -->
  <script src="{{ asset('frontend/shared/utils.js') }}"></script>
  <script src="{{ asset('frontend/shared/components.js') }}"></script>

  <!-- Page Script -->
  <script>
    function loginApp() {
      return {
        loading: false,
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

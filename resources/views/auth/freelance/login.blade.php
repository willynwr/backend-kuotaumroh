<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Affiliate - Kuotaumroh.id</title>

  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
  <meta name="theme-color" content="#10b981">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="{{ asset('frontend/shared/styles.css') }}">
  <script src="{{ asset('frontend/shared/utils.js') }}"></script>

  <script>
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
            primary: { DEFAULT: "hsl(var(--primary))", foreground: "hsl(var(--primary-foreground))" },
            muted: { DEFAULT: "hsl(var(--muted))", foreground: "hsl(var(--muted-foreground))" },
            destructive: { DEFAULT: "hsl(var(--destructive))", foreground: "hsl(var(--destructive-foreground))" },
            card: { DEFAULT: "hsl(var(--card))", foreground: "hsl(var(--card-foreground))" },
          },
          fontFamily: { sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
        },
      },
    }
  </script>
</head>

<body class="min-h-screen">
  <div x-data="affiliateLoginApp()">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-primary/5 to-background p-4">
      <div class="w-full max-w-md space-y-8 animate-fade-in">

        <!-- Logo -->
        <div class="flex flex-col items-center space-y-2">
          <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-24 w-24 object-contain">
          <h1 class="text-xl font-bold">Kuotaumroh.id</h1>
          <p class="text-muted-foreground text-center">Portal Affiliate</p>
        </div>

        <!-- Login Card -->
        <div class="rounded-lg bg-card text-card-foreground border-0 shadow-lg">
          <div class="p-6 text-center">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Login Affiliate</h3>
            <p class="text-sm text-muted-foreground mt-2">Masuk dengan kredensial affiliate</p>
          </div>
          <div class="p-6 pt-0 space-y-4">
            <!-- Email Input -->
            <div class="space-y-2">
              <label for="email" class="text-sm font-medium">Email</label>
              <input id="email" type="email" x-model="email" placeholder="affiliate@example.com" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <!-- Password Input -->
            <div class="space-y-2">
              <label for="password" class="text-sm font-medium">Password</label>
              <input id="password" type="password" x-model="password" placeholder="••••••••" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <!-- Login Button -->
            <button @click="handleLogin()" :disabled="loading" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 w-full h-12 text-base font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
              <span x-show="!loading">Masuk</span>
              <span x-show="loading">Loading...</span>
            </button>

            <!-- Error Message -->
            <p x-show="errorMessage" class="text-sm text-destructive text-center" x-text="errorMessage"></p>

            <!-- Divider -->
            <div class="relative">
              <div class="absolute inset-0 flex items-center"><span class="w-full border-t"></span></div>
              <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-card px-2 text-muted-foreground">Belum punya akun?</span>
              </div>
            </div>

            <!-- Register Link -->
            <a href="{{ url('/signup') }}" class="inline-flex items-center justify-center rounded-md border border-input bg-background hover:bg-muted w-full h-11 text-sm font-medium transition-colors">
              Daftar sebagai Affiliate
            </a>
          </div>
        </div>

        <p class="text-center text-xs text-muted-foreground">© 2026 Kuotaumroh.id. All rights reserved.</p>
      </div>
    </div>
  </div>

  <script>
    function affiliateLoginApp() {
      return {
        email: '', password: '', loading: false, errorMessage: '',

        async handleLogin() {
          this.loading = true;
          this.errorMessage = '';

          try {
            await new Promise(resolve => setTimeout(resolve, 500));

            // Mock affiliate login - in production this would call API
            if (this.email && this.password) {
              saveUser({ name: 'Affiliate User', email: this.email, role: 'freelance', referralCode: 'AFF-DEMO' });
              window.location.href = '{{ url('/') }}/freelance/dashboard';
            } else {
              this.errorMessage = 'Email dan password wajib diisi';
              this.loading = false;
            }
          } catch (error) {
            this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
            this.loading = false;
          }
        },

        init() {
          // If already logged in as freelance, redirect to dashboard
          if (isLoggedIn() && getUserRole() === 'freelance') {
            window.location.href = '{{ url('/') }}/freelance/dashboard';
          }
        }
      }
    }
  </script>

</body>
</html>

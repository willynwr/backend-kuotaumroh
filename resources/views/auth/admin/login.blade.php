<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Kuotaumroh.id</title>

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
  <div x-data="adminLoginApp()">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-primary/5 to-background p-4">
      <div class="w-full max-w-md space-y-8 animate-fade-in">

        <!-- Logo -->
        <div class="flex flex-col items-center space-y-2">
          <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-24 w-24 object-contain">
          <h1 class="text-xl font-bold">Kuotaumroh.id</h1>
          <p class="text-muted-foreground text-center">Portal Administrator</p>
        </div>

        <!-- Login Card -->
        <div class="rounded-lg bg-card text-card-foreground border-0 shadow-lg">
          <div class="p-6 text-center">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Login Admin</h3>
            <p class="text-sm text-muted-foreground mt-2">Masuk menggunakan nomor HP</p>
          </div>
          <div class="p-6 pt-0 space-y-4">
            <!-- Phone Input -->
            <div class="space-y-2">
              <label for="phone" class="text-sm font-medium">Nomor HP</label>
              <input id="phone" type="text" x-model="phone" placeholder="Contoh: 081234567890" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <!-- Request OTP Button -->
            <button @click="handleRequestOtp()" :disabled="loading" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 w-full h-12 text-base font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
              <span x-show="!loading">Kirim OTP</span>
              <span x-show="loading">Loading...</span>
            </button>

            <!-- Error Message -->
            <p x-show="errorMessage" class="text-sm text-destructive text-center" x-text="errorMessage"></p>
          </div>
        </div>

        <p class="text-center text-xs text-muted-foreground">© 2026 Kuotaumroh.id. All rights reserved.</p>
      </div>
    </div>

    <!-- OTP Modal -->
    <div x-show="otpModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" style="display: none;">
      <div @click.outside="closeOtpModal()" class="w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
        <div class="mb-4">
          <h3 class="text-lg font-semibold">Masukkan OTP</h3>
          <p class="text-sm text-muted-foreground mt-1" x-text="`Kode dikirim ke ${phone || '-'}`"></p>
        </div>

        <div class="space-y-2">
          <label for="otp" class="text-sm font-medium">Kode OTP</label>
          <input id="otp" type="text" x-model="otp" placeholder="6 digit kode" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
        </div>

        <p x-show="otpError" class="text-sm text-destructive mt-3" x-text="otpError"></p>

        <div class="mt-6 flex gap-3">
          <button @click="verifyOtp()" class="inline-flex flex-1 items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-11 text-sm font-medium transition-colors">
            Verifikasi
          </button>
          <button @click="closeOtpModal()" class="inline-flex flex-1 items-center justify-center rounded-md border border-input bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 h-11 transition-colors">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function adminLoginApp() {
      return {
        phone: '',
        otp: '',
        loading: false,
        otpModalOpen: false,
        errorMessage: '',
        otpError: '',

        async handleRequestOtp() {
          if (!this.phone) {
            this.errorMessage = 'Nomor HP wajib diisi';
            return;
          }

          this.loading = true;
          this.errorMessage = '';

          try {
            // Call API to request OTP
            const response = await fetch(apiUrl('/admin/request-otp'), {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
              },
              body: JSON.stringify({ phone: this.phone })
            });

            const data = await response.json();

            if (response.ok) {
              this.loading = false;
              this.otpModalOpen = true;
            } else {
              throw new Error(data.message || 'Gagal mengirim OTP');
            }
          } catch (error) {
            this.errorMessage = error.message || 'Terjadi kesalahan. Silakan coba lagi.';
            this.loading = false;
          }
        },

        async verifyOtp() {
          this.otpError = '';

          if (!this.otp) {
            this.otpError = 'Kode OTP wajib diisi';
            return;
          }

          try {
            // Call API to verify OTP
            const response = await fetch(apiUrl('/admin/verify-otp'), {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                phone: this.phone,
                otp: this.otp
              })
            });

            const data = await response.json();

            if (response.ok && data.success) {
              // Save user and redirect
              saveUser({
                id: data.user.id,
                name: data.user.name || 'Admin',
                email: data.user.email || '',
                role: 'admin',
                token: data.token
              });

              window.location.href = '{{ url('/') }}/admin/dashboard';
            } else {
              this.otpError = data.message || 'Kode OTP salah';
            }
          } catch (error) {
            this.otpError = 'Terjadi kesalahan. Silakan coba lagi.';
          }
        },

        closeOtpModal() {
          this.otpModalOpen = false;
          this.otp = '';
          this.otpError = '';
        },

        init() {
          // If already logged in as admin, redirect to dashboard
          if (isLoggedIn() && getUserRole() === 'admin') {
            window.location.href = '{{ url('/') }}/admin/dashboard';
          }
        }
      }
    }
  </script>

</body>
</html>

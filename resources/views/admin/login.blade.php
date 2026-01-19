<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login Admin - Kuotaumroh.id</title>

  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/kabah.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/kabah.png') }}">
  <meta name="theme-color" content="#10b981">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
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

  <style>
    :root {
      --background: 0 0% 100%;
      --foreground: 222.2 84% 4.9%;
      --card: 0 0% 100%;
      --card-foreground: 222.2 84% 4.9%;
      --primary: 142.1 76.2% 36.3%;
      --primary-foreground: 355.7 100% 97.3%;
      --muted: 210 40% 96.1%;
      --muted-foreground: 215.4 16.3% 46.9%;
      --destructive: 0 84.2% 60.2%;
      --destructive-foreground: 210 40% 98%;
      --border: 214.3 31.8% 91.4%;
      --input: 214.3 31.8% 91.4%;
      --radius: 0.5rem;
    }

    .animate-fade-in {
      animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body class="min-h-screen">
  <div x-data="adminLoginApp()">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-primary/5 to-background p-4">
      <div class="w-full max-w-md space-y-8 animate-fade-in">

        <!-- Logo -->
        <div class="flex flex-col items-center space-y-2">
          <img src="{{ asset('images/kabah.png') }}" alt="Kuotaumroh.id Logo" class="h-24 w-24 object-contain">
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
            <form @submit.prevent="handleRequestOtp()">
              <!-- Phone Input -->
              <div class="space-y-2">
                <label for="phone" class="text-sm font-medium">Nomor HP</label>
                <input id="phone" type="text" x-model="phone" placeholder="Contoh: 081234567890" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
              </div>

              <!-- Request OTP Button -->
              <button type="submit" :disabled="loading" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 w-full h-12 text-base font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-4">
                <span x-show="!loading">Kirim OTP</span>
                <span x-show="loading">Loading...</span>
              </button>

              <!-- Error Message -->
              <p x-show="errorMessage" class="text-sm text-destructive text-center mt-3" x-text="errorMessage"></p>
            </form>
          </div>
        </div>

        <p class="text-center text-xs text-muted-foreground">© {{ date('Y') }} Kuotaumroh.id. All rights reserved.</p>
      </div>
    </div>

    <!-- OTP Modal -->
    <div x-show="otpModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" style="display: none;">
      <div @click.outside="closeOtpModal()" class="w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
        <div class="mb-4">
          <h3 class="text-lg font-semibold">Masukkan OTP</h3>
          <p class="text-sm text-muted-foreground mt-1" x-text="`Kode dikirim ke ${phone || '-'}`"></p>
        </div>

        <form @submit.prevent="verifyOtp()">
          <div class="space-y-2">
            <label for="otp" class="text-sm font-medium">Kode OTP</label>
            <input id="otp" type="text" x-model="otp" placeholder="6 digit kode" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm" required>
          </div>

          <p x-show="otpError" class="text-sm text-destructive mt-3" x-text="otpError"></p>

          <div class="mt-6 flex gap-3">
            <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-11 text-sm font-medium transition-colors">
              Verifikasi
            </button>
            <button type="button" @click="closeOtpModal()" class="inline-flex flex-1 items-center justify-center rounded-md border border-input bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 h-11 transition-colors">
              Batal
            </button>
          </div>
        </form>
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
            const response = await fetch('{{ route("admin.login.otp") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                phone: this.phone
              })
            });

            const data = await response.json();

            if (response.ok) {
              this.loading = false;
              this.otpModalOpen = true;
              // In production, OTP will be sent via SMS
              console.log('OTP:', data.otp); // For testing only
            } else {
              this.errorMessage = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
              this.loading = false;
            }
          } catch (error) {
            console.error('Error:', error);
            this.errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
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
            const response = await fetch('{{ route("admin.login.verify") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                phone: this.phone,
                otp: this.otp
              })
            });

            const data = await response.json();

            if (response.ok) {
              window.location.href = '{{ route("admin.dashboard") }}';
            } else {
              this.otpError = data.message || 'Kode OTP salah';
            }
          } catch (error) {
            console.error('Error:', error);
            this.otpError = 'Terjadi kesalahan. Silakan coba lagi.';
          }
        },

        closeOtpModal() {
          this.otpModalOpen = false;
          this.otp = '';
          this.otpError = '';
        },

        init() {
          // Redirect if already logged in
          @if(Auth::check() && Auth::user()->role === 'admin')
            window.location.href = '{{ route("admin.dashboard") }}';
          @endif
        }
      }
    }
  </script>

</body>
</html>

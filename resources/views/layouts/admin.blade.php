<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') - Kuotaumroh.id</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/LOGO.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/LOGO.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/LOGO.png') }}">
  <meta name="theme-color" content="#10b981">

  <!-- Google Fonts - Figtree -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Shared Utilities -->
  <script src="{{ asset('frontend/shared/utils.js') }}"></script>

  <!-- Session Timeout Manager -->
  <script src="{{ asset('shared/session-timeout.js') }}?v={{ time() }}"></script>

  <!-- API Configuration -->
  <script>
    const API_BASE_URL = '{{ url('/') }}';
    const API_URL = `${API_BASE_URL}/api`;
    
    function apiUrl(endpoint) {
      const cleanEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
      return `${API_URL}/${cleanEndpoint}`;
    }
  </script>

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
            muted: {
              DEFAULT: "hsl(var(--muted))",
              foreground: "hsl(var(--muted-foreground))",
            },
            destructive: {
              DEFAULT: "hsl(var(--destructive))",
              foreground: "hsl(var(--destructive-foreground))",
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
          container: {
            center: true,
            padding: "2rem",
            screens: {
              "2xl": "1400px",
            },
          },
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
      --popover: 0 0% 100%;
      --popover-foreground: 222.2 84% 4.9%;
      --primary: 160 84% 39%;
      --primary-foreground: 0 0% 100%;
      --secondary: 210 40% 96.1%;
      --secondary-foreground: 222.2 47.4% 11.2%;
      --muted: 210 40% 96.1%;
      --muted-foreground: 215.4 16.3% 46.9%;
      --accent: 210 40% 96.1%;
      --accent-foreground: 222.2 47.4% 11.2%;
      --destructive: 0 84.2% 60.2%;
      --destructive-foreground: 210 40% 98%;
      --border: 214.3 31.8% 91.4%;
      --input: 214.3 31.8% 91.4%;
      --ring: 160 84% 39%;
      --radius: 0.5rem;
    }

    .animate-fade-in {
      animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .toast {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: white;
      border: 1px solid hsl(var(--border));
      padding: 1rem 1.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      z-index: 100;
      min-width: 300px;
    }
  </style>

  @stack('styles')
</head>

<body class="min-h-screen bg-slate-50">
  @yield('content')

  <!-- Toast Notification Component -->
  <div x-data="{ 
    show: false, 
    title: '', 
    message: '',
    showToast(title, message) {
      this.title = title;
      this.message = message;
      this.show = true;
      setTimeout(() => { this.show = false; }, 3000);
    }
  }" 
  x-show="show" 
  x-transition 
  class="toast"
  style="display: none;">
    <div class="font-semibold mb-1" x-text="title"></div>
    <div class="text-sm text-muted-foreground" x-text="message"></div>
  </div>

  @stack('scripts')
</body>
</html>

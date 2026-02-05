<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agent - Kuotaumroh.id')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/LOGO.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/LOGO.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/LOGO.png') }}">
    <meta name="theme-color" content="#10b981">

    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Shared Styles & Scripts -->
    <link rel="stylesheet" href="{{ asset('shared/styles.css') }}">
    <!-- Config MUST be loaded first -->
    <script src="{{ asset('shared/config.js') }}"></script>
    <script src="{{ asset('shared/utils.js') }}"></script>
    <script src="{{ asset('shared/api.js') }}"></script>
    <!-- QR Code Generator Library -->
    <script src="{{ asset('shared/qrcode.min.js') }}"></script>
    <!-- Session Timeout Manager -->
    <script src="{{ asset('shared/session-timeout.js') }}?v={{ time() }}"></script>

    <!-- Tailwind Config (Matching Affiliate Theme) -->
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
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50">
    <!-- Navbar -->
    @include('partials.agent-header')

    <!-- Main Content -->
    <!-- Note: Content should handle its own container due to varying layout requirements -->
    @yield('content')

    <!-- Simple Toast Notification (Global Fallback) -->
    <div x-data="{ show: false, title: '', message: '' }"
         x-show="show" 
         x-transition
         @toast.window="show = true; title = $event.detail.title; message = $event.detail.message; setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 z-50 bg-white border border-gray-200 shadow-lg rounded-lg p-4 min-w-[300px]"
         style="display: none;">
        <div class="font-semibold mb-1" x-text="title"></div>
        <div class="text-sm text-gray-600" x-text="message"></div>
    </div>

    @yield('scripts')
    @stack('scripts')
</body>
</html>

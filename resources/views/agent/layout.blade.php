<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Agent - Kuotaumroh.id')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
          },
          colors: {
            primary: {
              DEFAULT: '#10b981',
              foreground: '#ffffff',
            },
            muted: {
              DEFAULT: '#f1f5f9',
              foreground: '#64748b',
            },
            border: '#e2e8f0',
            background: '#f8fafc',
            foreground: '#0f172a',
          },
        },
      },
    }
  </script>
  <style>
    [x-cloak] { display: none !important; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fadeIn .2s ease-out both; }
    .spinner { width: 20px; height: 20px; border: 2px solid rgba(15, 23, 42, 0.15); border-top-color: #10b981; border-radius: 50%; animation: spin .8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .badge { display: inline-flex; align-items: center; justify-content: center; border-radius: 9999px; padding: 2px 10px; font-size: 12px; line-height: 18px; font-weight: 600; }
    .badge-primary { background: rgba(16,185,129,.12); color: #047857; }
    .badge-secondary { background: rgba(100,116,139,.12); color: #334155; }
    .badge-outline { background: transparent; border: 1px solid #e2e8f0; color: #334155; }
    .badge-destructive { background: rgba(239,68,68,.12); color: #b91c1c; }
    .toast { position: fixed; right: 16px; bottom: 16px; z-index: 50; width: min(420px, calc(100vw - 32px)); border: 1px solid #e2e8f0; background: #fff; border-radius: 12px; padding: 14px 16px; box-shadow: 0 10px 25px rgba(0,0,0,.12); }
  </style>
  @yield('head')
</head>
<body class="min-h-screen bg-background text-foreground">
  <main class="container mx-auto px-4 py-6">
    @yield('content')
  </main>
  @yield('scripts')
</body>
</html>

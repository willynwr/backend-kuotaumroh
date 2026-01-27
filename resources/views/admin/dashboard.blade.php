@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div x-data="adminDashboard()" x-init="checkAdminAuth()">
  <!-- Header Component -->
  @include('components.admin.header')

  <!-- Main Content -->
  <main class="container mx-auto py-10 animate-fade-in px-4">
    <!-- Stats Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-12 mb-12">
      <!-- Revenue Card (Primary) -->
      <div class="lg:col-span-7">
        <div class="relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm h-full">
          <!-- Background Decoration -->
          <div class="pointer-events-none absolute right-0 top-0 h-40 w-40 -translate-y-1/3 translate-x-1/3 rounded-full bg-primary/5"></div>

          <!-- Header -->
          <div class="relative z-10 flex flex-row items-center justify-between p-6 pb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">
              Revenue Performance
            </h3>
            <div class="rounded-lg p-2 bg-primary/10 text-primary">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>

          <!-- Content -->
          <div class="relative z-10 p-6 pt-0">
            <div class="mb-2">
              <p class="text-xs font-bold uppercase text-slate-400 mb-1" x-text="`Revenue ${getCurrentMonth()} ${getCurrentYear()}`"></p>
              <div class="text-3xl font-extrabold text-primary tracking-tight" x-text="formatRupiah(stats.revenueMTD)"></div>
            </div>

            <!-- Summary Bar -->
            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
              <div>
                <p class="text-xs font-bold uppercase text-slate-400" x-text="`Revenue Sepanjang ${getCurrentYear()}`"></p>
                <p class="text-xl font-extrabold text-primary" x-text="formatRupiah(stats.revenueYTD)"></p>
              </div>
              <!-- Mini Chart -->
              <div class="flex items-end gap-1 opacity-70">
                <div class="h-4 w-2 rounded-t-sm bg-primary/20"></div>
                <div class="h-6 w-2 rounded-t-sm bg-primary/30"></div>
                <div class="h-5 w-2 rounded-t-sm bg-primary/40"></div>
                <div class="h-8 w-2 rounded-t-sm bg-primary/60"></div>
                <div class="h-10 w-2 rounded-t-sm bg-primary/80"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Users Card -->
      <div class="lg:col-span-5">
        <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full">
          <!-- Header -->
          <div class="flex flex-row items-center justify-between p-6 pb-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">
              Total Pengguna
            </h3>
          </div>

          <!-- Content -->
          <div class="p-6 pt-0">
            <!-- Three Column Grid -->
            <div class="grid grid-cols-3 gap-0 border-b border-slate-100 pb-6 mb-6 relative">
              <!-- Dividers -->
              <div class="absolute top-0 bottom-6 left-1/3 w-px bg-slate-200"></div>
              <div class="absolute top-0 bottom-6 right-1/3 w-px bg-slate-200"></div>
              
              <!-- Agen Column -->
              <div class="px-2 text-center md:text-left md:pl-4">
                <div class="text-3xl font-bold text-slate-900 tracking-tight mb-1" x-text="stats.totalAgents"></div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Agen</div>
                <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                  <span x-text="stats.activeAgents"></span> Active
                </div>
              </div>

              <!-- Affiliate Column -->
              <div class="px-2 text-center md:text-left md:pl-8">
                <div class="text-3xl font-bold text-slate-900 tracking-tight mb-1" x-text="stats.totalAffiliates"></div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Affiliate</div>
                <div class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                  <span x-text="stats.activeAffiliates"></span> Active
                </div>
              </div>

              <!-- Freelance Column -->
              <div class="px-2 text-center md:text-left md:pl-8">
                <div class="text-3xl font-bold text-slate-900 tracking-tight mb-1" x-text="stats.totalFreelancers"></div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Freelance</div>
                <div class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2 py-0.5 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20">
                  <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                  <span x-text="stats.activeFreelancers"></span> Active
                </div>
              </div>
            </div>

            <!-- Pending Requests -->
            <div class="space-y-3 mt-4">
              <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-3 group text-slate-700 hover:text-red-600 transition-colors">
                <div class="h-2 w-2 rounded-full bg-red-500"></div>
                <div class="flex items-baseline gap-2">
                  <span class="text-xl font-bold font-mono" x-text="stats.pendingWithdrawals"></span>
                  <span class="text-sm font-medium">Request penarikan dana tertunda</span>
                </div>
              </a>

              <a href="{{ route('admin.reward-claims') }}" class="flex items-center gap-3 group text-slate-700 hover:text-red-600 transition-colors">
                <div class="h-2 w-2 rounded-full bg-red-500"></div>
                <div class="flex items-baseline gap-2">
                  <span class="text-xl font-bold font-mono" x-text="stats.pendingClaims"></span>
                  <span class="text-sm font-medium">Request klaim hadiah tertunda</span>
                </div>
              </a>
            </div>

            <!-- Action Button removed -->
          </div>
        </div>
      </div>
    </div>

    <!-- Menu Grid -->
    <div class="space-y-8">
      <div class="flex items-center gap-4">
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Menu Admin</h2>
        <div class="h-px flex-1 bg-slate-200"></div>
      </div>

      <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
        <template x-for="item in menuItems" :key="item.id">
          <a :href="item.href">
            <div
              class="group flex h-40 cursor-pointer items-center justify-center rounded-2xl border shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md relative"
              :class="item.variant === 'primary'
                ? 'border-primary bg-primary text-primary-foreground hover:bg-primary/90'
                : 'border-slate-200 bg-white'"
            >
              <!-- Badge for pending items -->
              <div x-show="item.badge > 0" class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white text-xs font-bold flex items-center justify-center" x-text="item.badge"></div>
              
              <div class="flex flex-col items-center justify-center gap-3 p-4 text-center">
                <img
                  :src="`{{ asset('images') }}/${item.icon}.png`"
                  :alt="item.title"
                  class="h-20 w-20 object-contain transition-transform group-hover:scale-110"
                  onerror="this.src='{{ asset('images/LOGO.png') }}'"
                />
                <h3
                  class="text-xs font-bold uppercase tracking-wide leading-tight"
                  :class="item.variant === 'primary' ? 'text-primary-foreground' : 'text-slate-700'"
                  x-text="item.title"
                ></h3>
              </div>
            </div>
          </a>
        </template>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function adminDashboard() {
    return {
      stats: {
        totalAgents: {{ $stats['totalAgents'] ?? 0 }},
        activeAgents: {{ $stats['activeAgents'] ?? 0 }},
        totalAffiliates: {{ $stats['totalAffiliates'] ?? 0 }},
        activeAffiliates: {{ $stats['activeAffiliates'] ?? 0 }},
        totalFreelancers: {{ $stats['totalFreelancers'] ?? 0 }},
        activeFreelancers: {{ $stats['activeFreelancers'] ?? 0 }},
        revenueMTD: {{ $stats['revenueMTD'] ?? 0 }},
        revenueYTD: {{ $stats['revenueYTD'] ?? 0 }},
        pendingWithdrawals: {{ $stats['pendingWithdrawals'] ?? 0 }},
        pendingClaims: {{ $stats['pendingClaims'] ?? 0 }}
      },
      menuItems: [],

      getCurrentMonth() {
        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return months[new Date().getMonth()];
      },

      getCurrentYear() {
        return new Date().getFullYear();
      },

      formatRupiah(amount) {
        return `Rp ${amount.toLocaleString('id-ID')}`;
      },

      checkAdminAuth() {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || getUserRole() !== 'admin') {
          window.location.href = '{{ url('/maha') }}';
          return;
        }
      },

      init() {
        // Build menu items with badges
        this.menuItems = [
          {
            id: 'users',
            title: 'Kelola Pengguna',
            href: '{{ route("admin.users") }}',
            variant: 'primary',
            badge: 0,
            icon: 'users'
          },
          {
            id: 'transactions',
            title: 'Cek Transaksi',
            href: '{{ route("admin.transactions") }}',
            variant: 'default',
            badge: 0,
            icon: 'transaction'
          },
          {
            id: 'orders',
            title: 'Pesanan Baru',
            href: '{{ route("admin.orders") }}',
            variant: 'default',
            badge: 0,
            icon: 'order'
          },
          {
            id: 'analytics',
            title: 'Analitik Operasional',
            href: '{{ route("admin.analytics") }}',
            variant: 'default',
            badge: 0,
            icon: 'analytics'
          },
          {
            id: 'withdrawals',
            title: 'Penarikan Dana',
            href: '{{ route("admin.withdrawals") }}',
            variant: 'default',
            badge: this.stats.pendingWithdrawals,
            icon: 'withdraw'
          },
          {
            id: 'claims',
            title: 'Klaim Hadiah',
            href: '{{ route("admin.reward-claims") }}',
            variant: 'default',
            badge: this.stats.pendingClaims,
            icon: 'claim'
          },
          {
            id: 'rewards',
            title: 'Daftar Hadiah',
            href: '{{ route("admin.rewards") }}',
            variant: 'default',
            badge: 0,
            icon: 'reward'
          },
          {
            id: 'packages',
            title: 'Kelola Paket',
            href: '{{ route("admin.packages") }}',
            variant: 'default',
            badge: 0,
            icon: 'catalog'
          }
        ];
      }
    }
  }
</script>
@endpush

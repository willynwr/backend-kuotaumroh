@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div x-data="adminDashboard()">
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
              Total Revenue
            </h3>
            <div class="rounded-lg p-2 bg-primary/10 text-primary">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>

          <!-- Content -->
          <div class="relative z-10 p-6 pt-0">
            <div class="text-4xl font-extrabold text-primary tracking-tight" x-text="formatRupiah(stats.totalRevenue)"></div>

            <!-- Summary Bar -->
            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
              <div>
                <p class="text-xs font-bold uppercase text-slate-400">Total Orders</p>
                <p class="text-xl font-extrabold text-primary" x-text="stats.totalOrders.toLocaleString('id-ID')"></p>
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
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
              Total Pengguna
            </h3>
          </div>

          <!-- Content -->
          <div class="p-6 pt-0">
            <div class="flex items-center gap-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-slate-900" x-text="stats.totalAgents"></div>
                <p class="text-xs text-slate-500 mt-1">Agen</p>
              </div>
              <div class="h-10 w-px bg-slate-200"></div>
              <div class="text-center">
                <div class="text-3xl font-bold text-slate-900" x-text="stats.totalAffiliates"></div>
                <p class="text-xs text-slate-500 mt-1">Freelance</p>
              </div>
            </div>

            <div class="mt-4 flex gap-4 text-sm text-slate-500">
              <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                <span>Pending WD: <strong class="text-slate-900" x-text="stats.pendingWithdrawals"></strong></span>
              </div>
              <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                <span>Pending Claims: <strong class="text-slate-900" x-text="stats.pendingClaims"></strong></span>
              </div>
            </div>

            <!-- Action Button -->
            <a href="{{ route('admin.users') }}" class="mt-6 inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 w-full h-11 text-base font-medium transition-colors">
              Kelola Pengguna
            </a>
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
                  onerror="this.src='{{ asset('images/kabah.png') }}'"
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
        totalAffiliates: {{ $stats['totalAffiliates'] ?? 0 }},
        totalOrders: {{ $stats['totalOrders'] ?? 0 }},
        totalRevenue: {{ $stats['totalRevenue'] ?? 0 }},
        pendingWithdrawals: {{ $stats['pendingWithdrawals'] ?? 0 }},
        pendingClaims: {{ $stats['pendingClaims'] ?? 0 }}
      },
      menuItems: [],

      formatRupiah(amount) {
        return `Rp ${amount.toLocaleString('id-ID')}`;
      },

      init() {
        // Build menu items with badges
        this.menuItems = [
          {
            id: 'users',
            title: 'Users',
            href: '{{ route("admin.users") }}',
            variant: 'primary',
            badge: 0,
            icon: 'users'
          },
          {
            id: 'transactions',
            title: 'Transaksi',
            href: '{{ route("admin.transactions") }}',
            variant: 'default',
            badge: 0,
            icon: 'transaction'
          },
          {
            id: 'analytics',
            title: 'Analytics',
            href: '{{ route("admin.analytics") }}',
            variant: 'default',
            badge: 0,
            icon: 'analytics'
          },
          {
            id: 'withdrawals',
            title: 'Withdrawals',
            href: '{{ route("admin.withdrawals") }}',
            variant: 'default',
            badge: this.stats.pendingWithdrawals,
            icon: 'withdraw'
          },
          {
            id: 'claims',
            title: 'Reward Claims',
            href: '{{ route("admin.reward-claims") }}',
            variant: 'default',
            badge: this.stats.pendingClaims,
            icon: 'claim'
          },
          {
            id: 'rewards',
            title: 'Rewards',
            href: '{{ route("admin.rewards") }}',
            variant: 'default',
            badge: 0,
            icon: 'reward'
          },
          {
            id: 'packages',
            title: 'Paket',
            href: '{{ route("admin.packages") }}',
            variant: 'default',
            badge: 0,
            icon: 'package'
          }
        ];
      }
    }
  }
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div x-data="analyticsPage()">
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <div class="mb-6 flex items-start gap-4">
      <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Analytics</h1>
        <p class="text-muted-foreground mt-1">Analisis dan laporan platform</p>
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <p class="text-sm text-muted-foreground">Total Revenue</p>
        <p class="text-3xl font-bold mt-2" x-text="formatRupiah(stats.totalRevenue)"></p>
      </div>
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <p class="text-sm text-muted-foreground">Total Orders</p>
        <p class="text-3xl font-bold mt-2" x-text="stats.totalOrders.toLocaleString('id-ID')"></p>
      </div>
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <p class="text-sm text-muted-foreground">Active Users</p>
        <p class="text-3xl font-bold mt-2" x-text="stats.activeUsers.toLocaleString('id-ID')"></p>
      </div>
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <p class="text-sm text-muted-foreground">Conversion Rate</p>
        <p class="text-3xl font-bold mt-2" x-text="stats.conversionRate + '%'"></p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid gap-6 md:grid-cols-2 mb-8">
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Revenue Trend</h3>
        <div class="h-64 flex items-center justify-center text-muted-foreground">
          Chart akan ditampilkan di sini
        </div>
      </div>
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">User Growth</h3>
        <div class="h-64 flex items-center justify-center text-muted-foreground">
          Chart akan ditampilkan di sini
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="space-y-4">
          <template x-for="activity in recentActivity" :key="activity.id">
            <div class="flex items-start gap-4 pb-4 border-b last:border-0">
              <div class="flex-1">
                <p class="font-medium" x-text="activity.description"></p>
                <p class="text-sm text-muted-foreground" x-text="formatDate(activity.created_at)"></p>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function analyticsPage() {
    return {
      stats: {
        totalRevenue: {{ $stats['totalRevenue'] ?? 0 }},
        totalOrders: {{ $stats['totalOrders'] ?? 0 }},
        activeUsers: {{ $stats['activeUsers'] ?? 0 }},
        conversionRate: {{ $stats['conversionRate'] ?? 0 }}
      },
      recentActivity: @json($recentActivity ?? []),

      formatRupiah(amount) {
        return `Rp ${parseInt(amount).toLocaleString('id-ID')}`;
      },

      formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
          year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });
      },

      init() {}
    }
  }
</script>
@endpush

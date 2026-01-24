@extends('layouts.admin')

@section('title', 'Analytics')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/style.css">
<style>
  .flatpickr-calendar.hasMonthSelectorPlugin { max-width: 400px !important; width: 400px !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important; }
  .flatpickr-calendar.hasMonthSelectorPlugin .flatpickr-days { display: none !important; }
  .flatpickr-calendar.hasMonthSelectorPlugin .flatpickr-months { max-width: 400px !important; }
  .flatpickr-monthSelect-months { max-width: 400px !important; width: 100% !important; display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 10px !important; padding: 16px !important; box-sizing: border-box !important; }
  .flatpickr-monthSelect-month { background: white; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 8px; margin: 0 !important; cursor: pointer; transition: all 0.2s; text-align: center; font-size: 13px; min-height: 40px; display: flex !important; align-items: center; justify-content: center; width: 100% !important; box-sizing: border-box !important; }
  .flatpickr-monthSelect-month:hover { background: #f3f4f6; border-color: #10b981; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
  .flatpickr-monthSelect-month.selected { background: #10b981 !important; color: white !important; border-color: #10b981 !important; font-weight: 600; }
  .flatpickr-monthSelect-theme { max-width: 400px !important; width: 400px !important; }
  .flatpickr-innerContainer { max-width: 400px !important; }
  .overflow-x-auto::-webkit-scrollbar { height: 6px; }
  .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
  .overflow-x-auto::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
  .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #059669; }
</style>
@endpush

@section('content')
<div x-data="analyticsPage()">
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <div class="mb-6">
      <div class="flex items-stretch gap-3">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center w-12 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali ke Dashboard">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </a>
        <div>
          <h1 class="text-3xl font-bold tracking-tight">Analytics</h1>
          <p class="text-muted-foreground mt-1">Insights dan statistik platform</p>
        </div>
      </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5 mb-6">
      <!-- Revenue (MTD & YTD) -->
      <div class="rounded-lg border bg-white shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm font-medium text-muted-foreground">Revenue</span>
          <div class="h-9 w-9 rounded-full bg-emerald-100 flex items-center justify-center">
            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="space-y-1">
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Januari 2026</span>
            <span class="text-lg font-bold" x-text="formatRupiah(metrics.revenueMTD)"></span>
          </div>
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Sepanjang 2026</span>
            <span class="text-base font-semibold text-muted-foreground" x-text="formatRupiah(metrics.revenueYTD)"></span>
          </div>
        </div>
      </div>

      <!-- Total Order (MTD & YTD) -->
      <div class="rounded-lg border bg-white shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm font-medium text-muted-foreground">Total Order</span>
          <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center">
            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
          </div>
        </div>
        <div class="space-y-1">
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Januari 2026</span>
            <span class="text-lg font-bold" x-text="metrics.totalOrderMTD"></span>
          </div>
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Sepanjang 2026</span>
            <span class="text-base font-semibold text-muted-foreground" x-text="metrics.totalOrderYTD"></span>
          </div>
        </div>
      </div>

      <!-- Rata-rata Order/Hari -->
      <div class="rounded-lg border bg-white shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm font-medium text-muted-foreground">Rata-rata Order/Hari</span>
          <div class="h-9 w-9 rounded-full bg-rose-100 flex items-center justify-center">
            <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
        <div class="space-y-1">
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Januari 2026</span>
            <span class="text-lg font-bold" x-text="metrics.avgOrdersPerDay + ' order/hari'"></span>
          </div>
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Bulan Ini</span>
            <span class="text-base font-semibold text-muted-foreground" x-text="metrics.currentMonthDays + ' hari'"></span>
          </div>
        </div>
      </div>

      <!-- Profit from Margin (MTD & YTD) -->
      <div class="rounded-lg border bg-white shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm font-medium text-muted-foreground">Profit Margin</span>
          <div class="h-9 w-9 rounded-full bg-amber-100 flex items-center justify-center">
            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
          </div>
        </div>
        <div class="space-y-1">
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Januari 2026</span>
            <span class="text-lg font-bold" x-text="formatRupiah(metrics.profitMarginMTD)"></span>
          </div>
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Sepanjang 2026</span>
            <span class="text-base font-semibold text-muted-foreground" x-text="formatRupiah(metrics.profitMarginYTD)"></span>
          </div>
        </div>
      </div>

      <!-- Pengguna Aktif -->
      <div class="rounded-lg border bg-white shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm font-medium text-muted-foreground">Pengguna Aktif</span>
          <div class="h-9 w-9 rounded-full bg-purple-100 flex items-center justify-center">
            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
        </div>
        <div class="space-y-1">
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Agent Travel</span>
            <span class="text-lg font-bold" x-text="metrics.activeAgents"></span>
          </div>
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Affiliate</span>
            <span class="text-base font-semibold text-muted-foreground" x-text="metrics.activeAffiliates"></span>
          </div>
          <div class="flex items-baseline justify-between">
            <span class="text-xs text-muted-foreground">Freelance</span>
            <span class="text-base font-semibold text-muted-foreground" x-text="metrics.activeFreelancers"></span>
          </div>
        </div>
      </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
      <!-- Top Agents -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <h3 class="text-lg font-semibold">Top 10 Agen Travel Bulanan</h3>
            <div class="relative w-full sm:w-auto">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <input type="text" id="topAgentsDatePicker" placeholder="Pilih Bulan" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]" readonly>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">#</th>
                  <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Agen</th>
                  <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Travel</th>
                  <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Orders</th>
                  <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Revenue</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="(agent, idx) in filteredTopAgents" :key="agent.id">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-medium" x-text="(currentAgentPage - 1) * agentsPerPage + idx + 1"></td>
                    <td class="p-4 align-middle" x-text="agent.name"></td>
                    <td class="p-4 align-middle" x-text="agent.travelName"></td>
                    <td class="p-4 align-middle text-center" x-text="agent.orders"></td>
                    <td class="p-4 align-middle text-right font-semibold text-primary" x-text="formatRupiah(agent.revenue)"></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
          <!-- Pagination Controls -->
          <div class="flex items-center justify-between px-4 py-3 border-t">
            <div class="text-sm text-muted-foreground">
              <span>Halaman <span class="font-medium" x-text="currentAgentPage"></span> dari <span class="font-medium" x-text="totalAgentPages"></span></span>
            </div>
            <div class="flex items-center gap-2">
              <button @click="prevAgentPage()" :disabled="currentAgentPage === 1" :class="currentAgentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md text-sm font-medium h-9 px-4 py-2 border border-input bg-background transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Sebelumnya
              </button>
              <button @click="nextAgentPage()" :disabled="currentAgentPage === totalAgentPages" :class="currentAgentPage === totalAgentPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md text-sm font-medium h-9 px-4 py-2 border border-input bg-background transition-colors">
                Selanjutnya
                <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Monthly Trends -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <h3 class="text-lg font-semibold">Tren Bulanan</h3>
            <div class="relative w-full sm:w-auto">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <select x-model.number="selectedYear" @change="selectedYear = Number($event.target.value); updateMonthlyData(); $nextTick(() => initMonthlyChart())" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-8 py-2 text-sm w-full sm:w-[180px] appearance-none cursor-pointer">
                <option value="2025">2025</option>
                <option value="2026" selected>2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2029</option>
                <option value="2030">2030</option>
              </select>
              <svg class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>
          <div class="relative h-[400px]">
            <canvas id="monthlyTrendChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Provider Breakdown -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <h3 class="text-lg font-semibold">Breakdown per Provider</h3>
            <div class="relative w-full sm:w-auto">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <input type="text" id="providerDatePicker" placeholder="Pilih Bulan" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]" readonly>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Provider</th>
                  <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Orders</th>
                  <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Revenue MTD</th>
                  <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Revenue YTD</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="p in filteredProviderBreakdown" :key="p.provider">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-medium" x-text="p.provider"></td>
                    <td class="p-4 align-middle text-center" x-text="p.orders"></td>
                    <td class="p-4 align-middle text-right font-semibold text-primary" x-text="formatRupiah(p.revenueMTD)"></td>
                    <td class="p-4 align-middle text-right font-semibold text-muted-foreground" x-text="formatRupiah(p.revenueYTD)"></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Daily Trends -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <h3 class="text-lg font-semibold">Trend Harian</h3>
            <div class="relative w-full sm:w-auto">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <input type="text" id="dailyTrendDatePicker" placeholder="Pilih Bulan" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]" readonly>
            </div>
          </div>
          <div class="relative h-[320px]">
            <canvas id="dailyTrendChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
function analyticsPage() {
  return {
    metrics: { revenueMTD: 0, revenueYTD: 0, totalOrderMTD: 0, totalOrderYTD: 0, profitMarginMTD: 0, profitMarginYTD: 0, activeAgents: 0, activeAffiliates: 0, activeFreelancers: 0, avgOrdersPerDay: 0, currentMonthDays: 0, maxMonthlyRevenue: 0 },
    topAgents: [], topAgentsRaw: {}, monthlyData: [], monthlyDataRaw: {}, providerBreakdownRaw: {}, dailyDataRaw: {}, maxDailyRevenue: 0,
    selectedYear: 2026, selectedAgentMonth: '2026-01', selectedProviderMonth: '2026-01', selectedDailyMonth: '2026-01',
    dailyTrendChart: null, monthlyTrendChart: null, currentAgentPage: 1, agentsPerPage: 5,

    formatRupiah(amount) { return `Rp ${Number(amount).toLocaleString('id-ID')}`; },

    get filteredTopAgents() {
      const allAgents = this.topAgentsRaw[this.selectedAgentMonth] || [];
      const startIndex = (this.currentAgentPage - 1) * this.agentsPerPage;
      return allAgents.slice(startIndex, startIndex + this.agentsPerPage);
    },

    get totalAgentPages() {
      return Math.ceil((this.topAgentsRaw[this.selectedAgentMonth] || []).length / this.agentsPerPage);
    },

    get filteredProviderBreakdown() { return this.providerBreakdownRaw[this.selectedProviderMonth] || []; },
    get filteredDailyData() { return this.dailyDataRaw[this.selectedDailyMonth] || []; },

    updateTopAgentsFilter(dateStr) { this.selectedAgentMonth = dateStr; this.currentAgentPage = 1; },
    updateProviderFilter(dateStr) { this.selectedProviderMonth = dateStr; },
    updateDailyTrendsFilter(dateStr) { this.selectedDailyMonth = dateStr; this.$nextTick(() => this.initDailyChart()); },
    nextAgentPage() { if (this.currentAgentPage < this.totalAgentPages) this.currentAgentPage++; },
    prevAgentPage() { if (this.currentAgentPage > 1) this.currentAgentPage--; },

    initMonthlyChart() {
      const ctx = document.getElementById('monthlyTrendChart');
      if (!ctx) return;
      if (this.monthlyTrendChart) this.monthlyTrendChart.destroy();
      this.monthlyTrendChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: this.monthlyData.map(d => d.name.split(' ')[0]),
          datasets: [{ label: 'Revenue', data: this.monthlyData.map(d => d.revenue), backgroundColor: this.monthlyData.map(d => d.isFuture ? 'rgba(156, 163, 175, 0.3)' : 'rgba(16, 185, 129, 0.8)'), hoverBackgroundColor: this.monthlyData.map(d => d.isFuture ? 'rgba(156, 163, 175, 0.5)' : 'rgba(5, 150, 105, 0.9)'), borderRadius: 4, borderSkipped: false }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(17, 24, 39, 0.95)', titleColor: '#fff', bodyColor: '#10b981', padding: 12, borderColor: 'rgba(16, 185, 129, 0.2)', borderWidth: 1, displayColors: false, callbacks: { title: (items) => this.monthlyData[items[0].dataIndex].name, label: (context) => this.formatRupiah(this.monthlyData[context.dataIndex].revenue) } } },
          scales: { x: { grid: { display: false }, ticks: { font: { family: 'Figtree', size: 10 }, color: '#6b7280' } }, y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' }, ticks: { font: { family: 'Figtree', size: 11 }, color: '#6b7280', callback: (value) => 'Rp ' + (value / 1000000).toFixed(1) + ' JT' } } },
          interaction: { intersect: false, mode: 'index' }
        }
      });
    },

    initDailyChart() {
      const ctx = document.getElementById('dailyTrendChart');
      if (!ctx) return;
      if (this.dailyTrendChart) this.dailyTrendChart.destroy();
      const data = this.filteredDailyData;
      this.dailyTrendChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.map(d => d.date.split('-')[2]),
          datasets: [{ label: 'Revenue', data: data.map(d => d.revenue), backgroundColor: 'rgba(16, 185, 129, 0.8)', hoverBackgroundColor: 'rgba(5, 150, 105, 0.9)', borderRadius: 4, borderSkipped: false }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(17, 24, 39, 0.95)', titleColor: '#fff', bodyColor: '#10b981', padding: 12, borderColor: 'rgba(16, 185, 129, 0.2)', borderWidth: 1, displayColors: false, callbacks: { title: (items) => data[items[0].dataIndex].label, label: (context) => this.formatRupiah(data[context.dataIndex].revenue), afterLabel: (context) => data[context.dataIndex].orders + ' order' } } },
          scales: { x: { grid: { display: false }, ticks: { font: { family: 'Figtree', size: 10 }, color: '#6b7280' } }, y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' }, ticks: { font: { family: 'Figtree', size: 11 }, color: '#6b7280', callback: (value) => 'Rp ' + (value / 1000000).toFixed(1) + ' JT' } } },
          interaction: { intersect: false, mode: 'index' }
        }
      });
    },

    updateMonthlyData() {
      const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
      this.monthlyData = [];
      for (let month = 1; month <= 12; month++) {
        const dateKey = `${this.selectedYear}-${String(month).padStart(2, '0')}`;
        const isFuture = (this.selectedYear > 2026) || (this.selectedYear === 2026 && month > 1);
        this.monthlyData.push({ date: dateKey, name: `${monthNames[month - 1]} ${this.selectedYear}`, revenue: isFuture ? 0 : (this.monthlyDataRaw[dateKey] || 0), isFuture });
      }
    },

    init() {
      const now = new Date();
      const currentDay = now.getDate();
      this.metrics = { revenueMTD: 85750000, revenueYTD: 85750000, totalOrderMTD: 1843, totalOrderYTD: 1843, profitMarginMTD: 8575000, profitMarginYTD: 8575000, activeAgents: 156, activeAffiliates: 89, activeFreelancers: 42, avgOrdersPerDay: Math.ceil(1843 / currentDay), currentMonthDays: currentDay };
      this.topAgentsRaw = {
        '2026-01': [{ id: 1, name: 'Ahmad Fauzi', travelName: 'PT Amanah Umroh', orders: 45, revenue: 11250000 }, { id: 2, name: 'Siti Rahmah', travelName: 'PT Barokah Travel', orders: 38, revenue: 9500000 }, { id: 3, name: 'Budi Santoso', travelName: 'PT Baitullah Nusantara', orders: 32, revenue: 8000000 }, { id: 4, name: 'Dewi Lestari', travelName: 'PT Safar Mandiri', orders: 28, revenue: 7000000 }, { id: 5, name: 'Rudi Hartono', travelName: 'PT Amanah Umroh', orders: 25, revenue: 6250000 }, { id: 6, name: 'Eko Prasetyo', travelName: 'PT Hidayah Travel', orders: 22, revenue: 5500000 }, { id: 7, name: 'Fitri Handayani', travelName: 'PT Barokah Travel', orders: 20, revenue: 5000000 }, { id: 8, name: 'Hadi Wijaya', travelName: 'PT Safar Mandiri', orders: 18, revenue: 4500000 }, { id: 9, name: 'Indah Permata', travelName: 'PT Baitullah Nusantara', orders: 16, revenue: 4000000 }, { id: 10, name: 'Joko Susilo', travelName: 'PT Amanah Umroh', orders: 15, revenue: 3750000 }],
        '2025-12': [{ id: 1, name: 'Siti Rahmah', travelName: 'PT Barokah Travel', orders: 52, revenue: 13000000 }, { id: 2, name: 'Ahmad Fauzi', travelName: 'PT Amanah Umroh', orders: 48, revenue: 12000000 }]
      };
      this.monthlyDataRaw = { '2025-01': 45000000, '2025-02': 52000000, '2025-03': 58000000, '2025-04': 61000000, '2025-05': 55000000, '2025-06': 63000000, '2025-07': 68000000, '2025-08': 72000000, '2025-09': 59000000, '2025-10': 65000000, '2025-11': 78000000, '2025-12': 92000000, '2026-01': 85750000 };
      this.updateMonthlyData();

      const genProvider = (m, y) => {
        const base = { 'Telkomsel': 35000000, 'Indosat': 23000000, 'XL': 17000000, 'Smartfren': 9000000, 'Tri': 6000000 };
        return Object.keys(base).map(p => {
          const rev = Math.floor(base[p] * (0.8 + Math.random() * 0.4));
          let ytd = 0;
          if (y === 2025) for (let i = 1; i <= m; i++) ytd += Math.floor(base[p] * (0.8 + Math.sin(i) * 0.2));
          else if (y === 2026 && m === 1) ytd = rev;
          return { provider: p, orders: Math.floor(rev / 50000), revenueMTD: rev, revenueYTD: ytd };
        });
      };
      this.providerBreakdownRaw = { '2026-01': genProvider(1, 2026), '2025-12': genProvider(12, 2025), '2025-11': genProvider(11, 2025) };

      const genDaily = (y, m) => {
        const days = (y === 2026 && m === 1) ? currentDay : new Date(y, m, 0).getDate();
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return Array.from({ length: days }, (_, i) => {
          const day = i + 1;
          const date = new Date(y, m - 1, day);
          const factor = (date.getDay() === 0 || date.getDay() === 6) ? 0.85 : 1.1;
          const rev = Math.floor((2500000 + m * 100000) * (Math.random() * 0.6 + 0.7) * factor);
          return { date: `${y}-${String(m).padStart(2, '0')}-${String(day).padStart(2, '0')}`, label: `${day} ${monthNames[m - 1]} ${y}`, revenue: rev, orders: Math.floor((60 + m * 2) * (Math.random() * 0.6 + 0.7) * factor) };
        });
      };
      this.dailyDataRaw = { '2026-01': genDaily(2026, 1), '2025-12': genDaily(2025, 12), '2025-11': genDaily(2025, 11) };

      this.$nextTick(() => {
        const indonesian = { months: { shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] } };
        const fpOpts = (sel, cb) => flatpickr(sel, {
          dateFormat: 'F Y', defaultDate: '2026-01-01', locale: indonesian,
          plugins: [new monthSelectPlugin({ shorthand: false, dateFormat: 'F Y', altFormat: 'F Y' })],
          onChange: (dates) => { if (dates[0]) { const d = dates[0]; cb.call(this, `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`); } }
        });
        fpOpts('#topAgentsDatePicker', this.updateTopAgentsFilter);
        fpOpts('#providerDatePicker', this.updateProviderFilter);
        fpOpts('#dailyTrendDatePicker', this.updateDailyTrendsFilter);
        this.initMonthlyChart();
        this.initDailyChart();
      });
    }
  }
}
</script>
@endpush

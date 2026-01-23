@extends('agent.layout')

@section('title', 'History Profit - Kuotaumroh.id')

@section('content')
  <div x-data="historyProfitApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/wallet') : route('agent.wallet') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">History Profit</h1>
            <p class="text-muted-foreground mt-2">Tracking profit bulanan dan tahunan</p>
          </div>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="grid gap-4 md:grid-cols-3 mb-6">
        <!-- Saldo Total -->
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-primary/10 p-3">
              <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Saldo Total</p>
              <h3 class="text-xl font-bold" x-text="formatRupiah(profitData.current_balance)"></h3>
            </div>
          </div>
        </div>

        <!-- Profit Bulan Ini -->
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-blue-100 p-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Profit Bulan Ini</p>
              <h3 class="text-xl font-bold" x-text="formatRupiah(profitData.monthly_profit)"></h3>
            </div>
          </div>
        </div>

        <!-- Profit Tahun Ini -->
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-green-100 p-3">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Profit Tahun Ini</p>
              <h3 class="text-xl font-bold" x-text="formatRupiah(profitData.yearly_profit)"></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Year Selector -->
      <div class="mb-6 rounded-lg border bg-white shadow-sm p-4">
        <div class="flex items-center gap-4">
          <label for="yearSelect" class="text-sm font-medium text-muted-foreground">Pilih Tahun:</label>
          <select id="yearSelect" x-model="selectedYear" class="inline-flex items-center justify-center rounded-md border bg-white px-4 h-10 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary">
            <template x-for="year in availableYears" :key="year">
              <option :value="year" x-text="year"></option>
            </template>
          </select>
          <div class="flex-1 text-right">
            <span class="text-sm text-muted-foreground">Total Bulan: </span>
            <span class="text-sm font-semibold" x-text="filteredMonthlyHistory.length"></span>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">Riwayat Profit Per Bulan</h3>
              <div class="overflow-auto">
                <table class="w-full caption-bottom text-sm">
                  <thead class="border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50">
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Bulan</th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total Transaksi</th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total Profit</th>
                      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="item in filteredMonthlyHistory" :key="item.month">
                      <tr class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle font-medium" x-text="formatMonth(item.month)"></td>
                        <td class="p-4 align-middle text-right" x-text="item.total_transactions"></td>
                        <td class="p-4 align-middle text-right font-semibold text-primary" x-text="formatRupiah(item.total_profit)"></td>
                        <td class="p-4 align-middle text-center">
                          <button type="button" @click="showDetail(item)" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-primary text-primary hover:bg-primary/10 h-8 px-3 transition-colors cursor-pointer">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            Lihat Detail
                          </button>
                        </td>
                      </tr>
                    </template>
                    <template x-if="filteredMonthlyHistory.length === 0">
                      <tr>
                        <td colspan="4" class="p-8 text-center text-muted-foreground">
                          <div class="flex flex-col items-center gap-2">
                            <svg class="h-12 w-12 text-muted-foreground/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                            <p>Belum ada history profit di tahun <span x-text="selectedYear"></span></p>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Note -->
      <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
        <div class="flex gap-3">
          <svg class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          <div class="text-sm text-blue-900">
            <p class="font-medium mb-1">Informasi Penting:</p>
            <ul class="list-disc list-inside space-y-1 text-blue-800">
              <li><strong>Saldo Total:</strong> Akumulasi profit keseluruhan (tidak pernah reset)</li>
              <li><strong>Profit Bulan Ini:</strong> Profit yang didapat bulan ini (reset setiap awal bulan)</li>
              <li><strong>Profit Tahun Ini:</strong> Akumulasi profit tahun ini (reset setiap awal tahun)</li>
            </ul>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Detail Transaksi -->
    <div 
      x-show="showModal" 
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      @click.self="showModal = false" 
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
      style="display: none;"
    >
      <div 
        @click.stop 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b">
          <h3 class="text-xl font-semibold" x-text="'Detail Transaksi ' + formatMonth(selectedMonth?.month)"></h3>
          <button @click="showModal = false" class="rounded-md p-2 hover:bg-muted transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(80vh-140px)]">
          <div class="overflow-auto">
            <table class="w-full caption-bottom text-sm">
              <thead class="border-b">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Produk</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Profit</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="(detail, index) in selectedMonth?.details" :key="index">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle" x-text="detail.date"></td>
                    <td class="p-4 align-middle" x-text="detail.product_name"></td>
                    <td class="p-4 align-middle text-right font-semibold text-primary" x-text="formatRupiah(detail.profit)"></td>
                  </tr>
                </template>
                <template x-if="!selectedMonth?.details || selectedMonth.details.length === 0">
                  <tr>
                    <td colspan="3" class="p-8 text-center text-muted-foreground">
                      <div class="flex flex-col items-center gap-2">
                        <svg class="h-12 w-12 text-muted-foreground/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p>Tidak ada detail transaksi</p>
                      </div>
                    </td>
                  </tr>
                </template>
              </tbody>
              <tfoot class="border-t bg-muted/30">
                <tr>
                  <td colspan="2" class="p-4 align-middle font-semibold">Total</td>
                  <td class="p-4 align-middle text-right font-bold text-primary" x-text="formatRupiah(selectedMonth?.total_profit)"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="flex justify-end gap-2 p-6 border-t">
          <button @click="showModal = false" class="inline-flex items-center justify-center rounded-md border bg-white hover:bg-muted h-10 px-4 text-sm font-medium transition-colors">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function historyProfitApp() {
      const data = {
        profitData: @json($profitData),
        monthlyHistory: @json($profitData['monthly_history'] ?? []),
        yearlyHistory: @json($profitData['yearly_history'] ?? []),
        showModal: false,
        selectedMonth: null,
        selectedYear: new Date().getFullYear(),
        
        init() {
          console.log('App initialized');
          console.log('Monthly History:', this.monthlyHistory);
          console.log('Initial showModal:', this.showModal);
          // Pastikan modal tidak muncul saat load
          this.showModal = false;
        },
        
        get availableYears() {
          // Ambil semua tahun unik dari monthlyHistory
          const years = [...new Set(this.monthlyHistory.map(item => {
            const [year] = item.month.split('-');
            return parseInt(year);
          }))];
          // Urutkan descending
          return years.sort((a, b) => b - a);
        },
        
        get filteredMonthlyHistory() {
          // Filter monthlyHistory berdasarkan tahun yang dipilih
          return this.monthlyHistory.filter(item => {
            const [year] = item.month.split('-');
            return parseInt(year) === parseInt(this.selectedYear);
          });
        },
        
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        
        formatMonth(monthString) {
          if (!monthString) return '';
          // Format: 2024-01 -> Januari 2024
          const [year, month] = monthString.split('-');
          const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          return `${months[parseInt(month) - 1]} ${year}`;
        },
        
        showDetail(monthData) {
          console.log('=== showDetail called ===');
          console.log('Raw monthData:', JSON.stringify(monthData, null, 2));
          console.log('Current showModal value:', this.showModal);
          
          // Extract data from proxy
          const details = Array.isArray(monthData.details) ? monthData.details : [];
          
          console.log('Extracted details:', details);
          console.log('Details count:', details.length);
          
          this.selectedMonth = {
            month: monthData.month,
            total_profit: monthData.total_profit,
            total_transactions: monthData.total_transactions,
            details: details
          };
          
          console.log('selectedMonth after set:', JSON.stringify(this.selectedMonth, null, 2));
          
          // Set modal to show
          this.showModal = true;
          
          console.log('showModal after set:', this.showModal);
          
          // Force a small delay to ensure Alpine has processed the change
          this.$nextTick(() => {
            console.log('NextTick - showModal:', this.showModal);
          });
        }
      };
      
      return data;
    }
  </script>
@endsection

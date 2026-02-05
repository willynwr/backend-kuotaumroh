@extends('agent.layout')

@section('title', 'Detail Profit - Kuotaumroh.id')

@section('head')
  <style>
    [x-cloak] { display: none !important; }
    
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
      width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #10b981;
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #059669;
    }
  </style>
@endsection

@section('content')
  <div x-data="detailApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/referrals') : route('agent.dashboard') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Detail Transaksi</h1>
            <p class="text-muted-foreground mt-2" x-text="formatMonth('{{ $month }}')"></p>
          </div>
        </div>
      </div>

      <!-- Summary Card -->
      <div class="grid gap-4 md:grid-cols-2 mb-6">
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-blue-100 p-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Total Transaksi</p>
              <h3 class="text-2xl font-bold">{{ $totalTransactions }}</h3>
            </div>
          </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-green-100 p-3">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Total Profit</p>
              <h3 class="text-2xl font-bold text-primary" x-text="formatRupiah({{ $totalProfit }})"></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Table Detail -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="mb-6 flex gap-2 border-b">
            <button @click="filterType = 'all'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="filterType === 'all' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Semua</button>
            <button @click="filterType = 'bulk'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="filterType === 'bulk' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Bulk Order</button>
            <button @click="filterType = 'store'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="filterType === 'store' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Store</button>
          </div>
          <h3 class="text-lg font-semibold mb-4">Detail Transaksi</h3>
          <div class="overflow-auto">
            <table class="w-full caption-bottom text-sm">
              <thead class="border-b">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Produk</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nomor HP</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Profit</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="detail in filteredDetails" :key="detail.date + detail.batch_name">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle" x-text="detail.date"></td>
                    <td class="p-4 align-middle">
                      <div x-text="detail.product_name"></div>
                      <div class="text-xs text-muted-foreground" x-text="detail.batch_name"></div>
                    </td>
                    <td class="p-4 align-middle">
                      <template x-if="detail.batch_id && detail.batch_id.startsWith('IND-')">
                        <span class="font-mono text-sm" x-text="detail.msisdn || '-'"></span>
                      </template>
                      <template x-if="detail.batch_id && detail.batch_id.startsWith('BATCH_')">
                        <button @click="showMsisdnModal(detail)" class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-primary border border-primary rounded hover:bg-primary/10 transition-colors">
                          <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                          Lihat <span x-text="detail.msisdn_count"></span> Nomor
                        </button>
                      </template>
                    </td>
                    <td class="p-4 align-middle text-right font-semibold text-primary" x-text="formatRupiah(detail.profit)"></td>
                  </tr>
                </template>
                <template x-if="filteredDetails.length === 0">
                  <tr>
                    <td colspan="4" class="p-8 text-center text-muted-foreground">
                      <div class="flex flex-col items-center gap-2">
                        <svg class="h-12 w-12 text-muted-foreground/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p>Tidak ada detail transaksi</p>
                      </div>
                    </td>
                  </tr>
                </template>
              </tbody>
              <tfoot class="border-t bg-muted/30" x-show="filteredDetails.length > 0">
                <tr>
                  <td colspan="3" class="p-4 align-middle font-semibold">Total</td>
                  <td class="p-4 align-middle text-right font-bold text-primary" x-text="formatRupiah(filteredTotal)"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Nomor HP -->
      <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="modalOpen = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
          <!-- Background overlay -->
          <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="modalOpen = false"></div>

          <!-- Modal panel -->
          <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-3xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary to-primary/80 px-6 py-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                  </div>
                  <div>
                    <h3 class="text-xl font-bold text-white">Daftar Nomor HP</h3>
                    <p class="text-sm text-white/80 mt-0.5" x-text="selectedDetail?.batch_name"></p>
                  </div>
                </div>
                <button @click="modalOpen = false" class="text-white/80 hover:text-white transition-colors">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>
            </div>
            
            <!-- Body (Scrollable) -->
            <div class="px-6 py-4 bg-gray-50">
              <div class="bg-white rounded-lg border p-4">
                <div class="flex items-center justify-between mb-3 pb-3 border-b">
                  <span class="text-sm font-medium text-gray-700">Total Nomor HP:</span>
                  <span class="text-lg font-bold text-primary" x-text="selectedDetail?.msisdn_count || 0"></span>
                </div>
                <div class="max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    <template x-for="(msisdn, index) in selectedDetail?.msisdn_list" :key="index">
                      <div class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition-all group">
                        <div class="flex items-center justify-center w-6 h-6 bg-primary/10 text-primary rounded-full text-xs font-semibold group-hover:bg-primary group-hover:text-white transition-colors">
                          <span x-text="index + 1"></span>
                        </div>
                        <div class="flex items-center gap-1.5 flex-1">
                          <svg class="h-4 w-4 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                          <span class="font-mono text-sm font-medium text-gray-900" x-text="msisdn"></span>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-100 px-6 py-4 border-t">
              <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                  <span class="font-medium">Produk:</span> <span x-text="selectedDetail?.product_name"></span>
                </p>
                <button @click="modalOpen = false" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary/90 transition-colors shadow-sm hover:shadow-md">
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
@endsection

@section('scripts')
  <script>
    function detailApp() {
      return {
        filterType: 'all',
        details: @json($details ?? []),
        modalOpen: false,
        selectedDetail: null,
        
        get filteredDetails() {
          if (this.filterType === 'all') return this.details;
          if (this.filterType === 'bulk') {
            return this.details.filter(d => d.batch_id && d.batch_id.startsWith('BATCH_'));
          }
          if (this.filterType === 'store') {
            return this.details.filter(d => d.batch_id && d.batch_id.startsWith('IND-'));
          }
          return this.details;
        },
        
        get filteredTotal() {
          return this.filteredDetails.reduce((sum, d) => sum + (d.profit || 0), 0);
        },
        
        showMsisdnModal(detail) {
          this.selectedDetail = detail;
          this.modalOpen = true;
        },
        
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        
        formatMonth(monthString) {
          if (!monthString) return '';
          const [year, month] = monthString.split('-');
          const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          return `${months[parseInt(month) - 1]} ${year}`;
        }
      };
    }
  </script>
@endsection

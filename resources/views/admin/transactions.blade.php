@extends('layouts.admin')

@section('title', 'Transaksi')

@section('content')
<div x-data="transactionsPage()">
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <div class="mb-6 flex items-start gap-4">
      <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Transaksi</h1>
        <p class="text-muted-foreground mt-1">Lihat semua transaksi platform</p>
      </div>
    </div>

    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
          <h3 class="text-lg font-semibold">Daftar Transaksi</h3>
          <div class="flex gap-2 w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input type="text" x-model="search" placeholder="Cari transaksi..." class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
            </div>
            <select x-model="statusFilter" class="h-10 rounded-md border border-input bg-background px-3 text-sm">
              <option value="all">Semua Status</option>
              <option value="success">Success</option>
              <option value="pending">Pending</option>
              <option value="failed">Failed</option>
            </select>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b">
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">ID</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Paket</th>
                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="trx in paginatedTransactions" :key="trx.id">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <td class="p-4 align-middle text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
                      'bg-green-100 text-green-800': trx.status === 'success',
                      'bg-yellow-100 text-yellow-800': trx.status === 'pending',
                      'bg-red-100 text-red-800': trx.status === 'failed'
                    }" x-text="trx.status"></span>
                  </td>
                  <td class="p-4 align-middle text-center">
                    <button @click="viewDetail(trx)" class="text-sm text-primary hover:underline">Detail</button>
                  </td>
                  <td class="p-4 align-middle font-mono text-sm" x-text="trx.id"></td>
                  <td class="p-4 align-middle">
                    <div>
                      <div class="font-medium" x-text="trx.user_name"></div>
                      <div class="text-xs text-muted-foreground" x-text="trx.user_email"></div>
                    </div>
                  </td>
                  <td class="p-4 align-middle" x-text="trx.package_name"></td>
                  <td class="p-4 align-middle text-right font-semibold" x-text="formatRupiah(trx.total)"></td>
                  <td class="p-4 align-middle" x-text="formatDate(trx.created_at)"></td>
                </tr>
              </template>
              <tr x-show="paginatedTransactions.length === 0">
                <td colspan="7" class="p-8 text-center text-muted-foreground">Tidak ada transaksi</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex items-center justify-between mt-4">
          <div class="text-sm text-muted-foreground">
            Menampilkan <span x-text="(currentPage - 1) * itemsPerPage + 1"></span> - <span x-text="Math.min(currentPage * itemsPerPage, filteredTransactions.length)"></span> dari <span x-text="filteredTransactions.length"></span> data
          </div>
          <div class="flex gap-2">
            <button @click="currentPage--" :disabled="currentPage === 1" class="h-9 px-4 rounded-md border border-input bg-background text-sm font-medium disabled:opacity-50">Previous</button>
            <button @click="currentPage++" :disabled="currentPage * itemsPerPage >= filteredTransactions.length" class="h-9 px-4 rounded-md border border-input bg-background text-sm font-medium disabled:opacity-50">Next</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail Transaksi -->
    <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="showDetailModal = false" style="display: none;">
      <!-- Overlay -->
      <div x-show="showDetailModal" 
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           class="fixed inset-0 bg-black/50" 
           @click="showDetailModal = false"></div>
      
      <!-- Modal Content -->
      <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="showDetailModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl" 
             @click.away="showDetailModal = false">
          <!-- Header -->
          <div class="flex items-center justify-between p-6 border-b">
            <div>
              <h3 class="text-xl font-semibold">Detail Transaksi</h3>
              <p class="text-sm text-muted-foreground mt-1">Informasi lengkap transaksi pembayaran</p>
            </div>
            <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="p-6 max-h-[70vh] overflow-y-auto" x-show="selectedTransaction">
            <!-- Status Badge -->
            <div class="mb-6">
              <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold" :class="{
                'bg-green-100 text-green-800': selectedTransaction?.status === 'success',
                'bg-yellow-100 text-yellow-800': selectedTransaction?.status === 'pending',
                'bg-red-100 text-red-800': selectedTransaction?.status === 'failed'
              }">
                <span class="w-2 h-2 rounded-full mr-2" :class="{
                  'bg-green-500': selectedTransaction?.status === 'success',
                  'bg-yellow-500': selectedTransaction?.status === 'pending',
                  'bg-red-500': selectedTransaction?.status === 'failed'
                }"></span>
                <span x-text="selectedTransaction?.status?.toUpperCase()"></span>
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Informasi Transaksi -->
              <div class="space-y-4">
                <div class="bg-blue-50 rounded-lg p-4">
                  <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informasi Transaksi
                  </h4>
                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-sm text-blue-700">ID Transaksi:</span>
                      <span class="text-sm font-mono font-semibold text-blue-900" x-text="selectedTransaction?.id"></span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-blue-700">Tanggal:</span>
                      <span class="text-sm font-medium text-blue-900" x-text="formatDate(selectedTransaction?.created_at)"></span>
                    </div>
                  </div>
                </div>

                <!-- Informasi User -->
                <div class="bg-purple-50 rounded-lg p-4">
                  <h4 class="font-semibold text-purple-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi User
                  </h4>
                  <div class="space-y-2">
                    <div>
                      <span class="text-sm text-purple-700">Nama:</span>
                      <p class="text-sm font-medium text-purple-900" x-text="selectedTransaction?.user_name"></p>
                    </div>
                    <div>
                      <span class="text-sm text-purple-700">Email:</span>
                      <p class="text-sm font-medium text-purple-900" x-text="selectedTransaction?.user_email"></p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Informasi Paket & Pembayaran -->
              <div class="space-y-4">
                <div class="bg-green-50 rounded-lg p-4">
                  <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Informasi Paket
                  </h4>
                  <div class="space-y-2">
                    <div>
                      <span class="text-sm text-green-700">Nama Paket:</span>
                      <p class="text-sm font-medium text-green-900" x-text="selectedTransaction?.package_name"></p>
                    </div>
                  </div>
                </div>

                <div class="bg-orange-50 rounded-lg p-4">
                  <h4 class="font-semibold text-orange-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pembayaran
                  </h4>
                  <div class="space-y-2">
                    <div class="flex justify-between items-center pt-2 border-t-2 border-orange-200">
                      <span class="text-base font-semibold text-orange-900">Total Pembayaran:</span>
                      <span class="text-lg font-bold text-orange-900" x-text="formatRupiah(selectedTransaction?.total)"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
            <button @click="showDetailModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function transactionsPage() {
    return {
      transactions: @json($transactions ?? []),
      search: '',
      statusFilter: 'all',
      currentPage: 1,
      itemsPerPage: 10,
      showDetailModal: false,
      selectedTransaction: null,

      get filteredTransactions() {
        return this.transactions.filter(trx => {
          const matchesStatus = this.statusFilter === 'all' || trx.status === this.statusFilter;
          const matchesSearch = !this.search || 
            trx.user_name.toLowerCase().includes(this.search.toLowerCase()) ||
            trx.id.toString().includes(this.search);
          return matchesStatus && matchesSearch;
        });
      },

      get paginatedTransactions() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        return this.filteredTransactions.slice(start, start + this.itemsPerPage);
      },

      formatRupiah(amount) {
        return `Rp ${parseInt(amount).toLocaleString('id-ID')}`;
      },

      formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
          year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });
      },

      viewDetail(trx) {
        this.selectedTransaction = trx;
        this.showDetailModal = true;
      },

      init() {}
    }
  }
</script>
@endpush

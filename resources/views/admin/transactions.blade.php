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
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">ID</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Paket</th>
                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="trx in paginatedTransactions" :key="trx.id">
                <tr class="border-b transition-colors hover:bg-muted/50">
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
        window.location.href = `/admin/transactions/${trx.id}`;
      },

      init() {}
    }
  }
</script>
@endpush

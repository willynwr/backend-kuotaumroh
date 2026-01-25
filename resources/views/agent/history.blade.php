@extends('agent.layout')

@section('title', 'Riwayat Transaksi - Kuotaumroh.id')

@section('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <style>
    .flatpickr-calendar { border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; }
    .flatpickr-months { border-radius: 0.5rem 0.5rem 0 0; }
    .flatpickr-current-month .flatpickr-monthDropdown-months, .flatpickr-current-month .numInputWrapper { font-weight: 600; }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay { background: #10b981; border-color: #10b981; }
    .flatpickr-day.inRange { background: rgba(16, 185, 129, 0.15); border-color: transparent; }
    .flatpickr-day:hover:not(.selected):not(.startRange):not(.endRange) { background: #f1f5f9; border-color: #e2e8f0; }
    .flatpickr-day.today { border-color: #10b981; }
    .flatpickr-day.today:hover, .flatpickr-day.today:focus { background: rgba(16, 185, 129, 0.1); border-color: #10b981; }
    .flatpickr-months .flatpickr-prev-month:hover svg, .flatpickr-months .flatpickr-next-month:hover svg { fill: #10b981; }
    .flatpickr-weekdays { background: #f1f5f9; }
    .flatpickr-weekday { color: #64748b; font-weight: 600; }
  </style>
@endsection

@section('content')
  <div x-data="historyApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral) : route('agent.dashboard') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Riwayat Transaksi</h1>
            <p class="text-muted-foreground mt-2">Lihat semua transaksi yang telah Anda buat</p>
          </div>
        </div>
      </div>

      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="mb-6 flex gap-2 border-b">
            <button @click="viewMode = 'batch'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="viewMode === 'batch' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Per Batch</button>
            <button @click="viewMode = 'number'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="viewMode === 'number' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Per Nomor</button>
          </div>
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-semibold">Daftar Transaksi</h3>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
              <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <input type="text" id="dateRangePicker" placeholder="Pilih Rentang Tanggal" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-8 py-2 text-sm w-full sm:w-[250px]" readonly>
                <button type="button" x-show="dateFrom || dateTo" @click="clearDateFilter()" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-muted-foreground hover:text-foreground rounded-full hover:bg-muted transition-colors">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" :placeholder="viewMode === 'batch' ? 'Cari batch/tanggal/nominal' : 'Cari nomor/provider/paket/harga'" x-model="search" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
              </div>
              <select x-show="viewMode === 'batch'" x-model="statusFilter" class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm w-full sm:w-[150px]">
                <option value="all">Semua Status</option>
                <option value="pending">Menunggu Pembayaran</option>
                <option value="processing">Menunggu Pembayaran</option>
                <option value="completed">Order Selesai</option>
              </select>
              <select x-show="viewMode === 'number'" x-model="statusFilter" x-cloak class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm w-full sm:w-[150px]">
                <option value="all">Semua Status</option>
                <option value="completed">Sukses</option>
                <option value="processing">Proses</option>
                <option value="pending">Pending</option>
                <option value="failed">Gagal</option>
              </select>
            </div>
          </div>

          <div class="overflow-x-auto" x-show="viewMode === 'batch'" x-transition>
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('batchId')" class="inline-flex items-center gap-2">
                      Batch ID
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('batchName')" class="inline-flex items-center gap-2">
                      Nama Batch
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('createdAt')" class="inline-flex items-center gap-2">
                      Tanggal
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('msisdnCount')" class="inline-flex items-center gap-2">
                      Jumlah Nomor HP
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Proses Paket</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('totalAmount')" class="inline-flex items-center gap-2">
                      Total
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('status')" class="inline-flex items-center gap-2">
                      Status
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="tx in paginatedTransactions" :key="tx.id">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-mono text-sm" x-text="tx.batchId"></td>
                    <td class="p-4 align-middle font-medium" x-text="tx.batchName"></td>
                    <td class="p-4 align-middle" x-text="formatDateTime(tx.createdAt)"></td>
                    <td class="p-4 align-middle text-center" x-text="tx.items?.length || 0"></td>
                    <td class="p-4 align-middle text-center"><span class="text-xs" x-html="getPackageStatusSummary(tx)"></span></td>
                    <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(tx.totalAmount)"></td>
                    <td class="p-4 align-middle text-center"><span class="badge" :class="getStatusBadgeClass(tx.status)" x-text="getStatusLabel(tx.status)"></span></td>
                    <td class="p-4 align-middle text-center">
                      <button @click="viewDetails(tx)" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 w-8 hover:bg-muted transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                      </button>
                    </td>
                  </tr>
                </template>
                <template x-if="sortedTransactions.length === 0">
                  <tr><td colspan="8" class="p-8 text-center text-muted-foreground">Tidak ada transaksi yang ditemukan</td></tr>
                </template>
              </tbody>
            </table>

            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 p-4 border-t" x-show="viewMode === 'batch'">
              <div class="text-sm text-muted-foreground">
                Menampilkan item
                <span x-text="Math.min((currentPage - 1) * itemsPerPage + 1, sortedTransactions.length)"></span>
                -
                <span x-text="Math.min(currentPage * itemsPerPage, sortedTransactions.length)"></span>
                dari
                <span x-text="sortedTransactions.length"></span>
                item.
              </div>
              <div class="flex items-center gap-2">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 text-sm transition-colors">Previous</button>
                <div class="flex gap-1">
                  <template x-for="page in totalPages" :key="page">
                    <button x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)" @click="changePage(page)" :class="page === currentPage ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border h-8 w-8 text-sm transition-colors" x-text="page"></button>
                  </template>
                </div>
                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 text-sm transition-colors">Next</button>
              </div>
              <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <span>Menampilkan</span>
                <select @change="setItemsPerPage($event.target.value)" :value="itemsPerPage" class="h-8 rounded-md border border-input bg-background px-2 py-1 text-sm">
                  <option value="50">50</option>
                  <option value="80">80</option>
                  <option value="100">100</option>
                  <option value="120">120</option>
                  <option value="150">150</option>
                </select>
                <span>item per halaman</span>
              </div>
            </div>
          </div>

          <div class="overflow-x-auto" x-show="viewMode === 'number'" x-cloak x-transition>
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nomor HP</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Provider</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Paket</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Harga</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal & Waktu</th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="(item, idx) in paginatedItems" :key="idx">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-mono text-sm" x-text="item.msisdn"></td>
                    <td class="p-4 align-middle" x-text="item.provider"></td>
                    <td class="p-4 align-middle" x-text="item.packageName"></td>
                    <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(item.price)"></td>
                    <td class="p-4 align-middle text-sm text-muted-foreground" x-text="formatDateTime(item.createdAt)"></td>
                    <td class="p-4 align-middle text-center"><span class="badge" :class="getStatusBadgeClass(item.status)" x-text="item.status === 'completed' ? 'Sukses' : (item.status === 'processing' ? 'Proses' : 'Gagal')"></span></td>
                  </tr>
                </template>
                <template x-if="sortedItems.length === 0">
                  <tr><td colspan="6" class="p-8 text-center text-muted-foreground">Tidak ada data yang ditemukan</td></tr>
                </template>
              </tbody>
            </table>

            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 p-4 border-t" x-show="viewMode === 'number'">
              <div class="text-sm text-muted-foreground">
                Menampilkan item
                <span x-text="Math.min((currentPage - 1) * itemsPerPage + 1, sortedItems.length)"></span>
                -
                <span x-text="Math.min(currentPage * itemsPerPage, sortedItems.length)"></span>
                dari
                <span x-text="sortedItems.length"></span>
                item.
              </div>
              <div class="flex items-center gap-2">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 text-sm transition-colors">Previous</button>
                <div class="flex gap-1">
                  <template x-for="page in totalPages" :key="page">
                    <button x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)" @click="changePage(page)" :class="page === currentPage ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border h-8 w-8 text-sm transition-colors" x-text="page"></button>
                  </template>
                </div>
                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 text-sm transition-colors">Next</button>
              </div>
              <div class="flex items-center gap-2 text-sm text-muted-foreground">
                <span>Menampilkan</span>
                <select @change="setItemsPerPage($event.target.value)" :value="itemsPerPage" class="h-8 rounded-md border border-input bg-background px-2 py-1 text-sm">
                  <option value="50">50</option>
                  <option value="80">80</option>
                  <option value="100">100</option>
                  <option value="120">120</option>
                  <option value="150">150</option>
                </select>
                <span>item per halaman</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div x-show="selectedTransaction" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeDetails()">
      <div class="relative bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden animate-fade-in">
        <div class="p-6 border-b">
          <div class="flex items-start justify-between">
            <div>
              <h2 class="text-lg font-semibold">Detail Transaksi</h2>
              <p class="text-sm text-muted-foreground mt-1" x-show="selectedTransaction">
                <span x-text="selectedTransaction?.batchId"></span> - <span x-text="selectedTransaction?.batchName"></span>
              </p>
            </div>
            <button @click="closeDetails()" class="rounded-md p-2 hover:bg-muted transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>
        </div>

        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]" x-show="selectedTransaction">
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <p class="text-muted-foreground">Tanggal</p>
                <p class="font-medium" x-text="selectedTransaction ? formatDateTimeFull(selectedTransaction.createdAt) : ''"></p>
              </div>
              <div>
                <p class="text-muted-foreground">Status</p>
                <p><span class="badge" :class="selectedTransaction ? getStatusBadgeClass(selectedTransaction.status) : ''" x-text="selectedTransaction ? getStatusLabel(selectedTransaction.status) : ''"></span></p>
              </div>
              <div>
                <p class="text-muted-foreground">Jumlah Nomor HP</p>
                <p class="font-medium"><span x-text="selectedTransaction?.items?.length || 0"></span> nomor</p>
              </div>
              <div>
                <p class="text-muted-foreground">Total Pembayaran</p>
                <p class="font-medium" x-text="selectedTransaction ? formatRupiah(selectedTransaction.totalAmount) : ''"></p>
              </div>
            </div>

            <div class="border-t pt-4">
              <p class="font-medium mb-3 text-sm">Proses Paket</p>
              <div class="grid grid-cols-3 gap-3" x-show="selectedTransaction">
                <div class="p-3 rounded-md border bg-muted/30">
                  <p class="text-xs text-muted-foreground mb-1">Berhasil</p>
                  <p class="text-2xl font-bold text-primary" x-text="getItemStatusCount(selectedTransaction, 'completed')">0</p>
                </div>
                <div class="p-3 rounded-md border bg-muted/30">
                  <p class="text-xs text-muted-foreground mb-1">Diproses</p>
                  <p class="text-2xl font-bold text-muted-foreground" x-text="getItemStatusCount(selectedTransaction, 'processing')">0</p>
                </div>
                <div class="p-3 rounded-md border bg-muted/30">
                  <p class="text-xs text-muted-foreground mb-1">Gagal</p>
                  <p class="text-2xl font-bold text-destructive" x-text="getItemStatusCount(selectedTransaction, 'pending')">0</p>
                </div>
              </div>
            </div>

            <div class="border-t pt-4">
              <p class="font-medium mb-2">Detail Nomor HP</p>
              <div class="relative mb-3">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" placeholder="Cari nomor/provider/paket/harga" x-model="detailSearch" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full">
              </div>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b">
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('msisdn')" class="inline-flex items-center gap-2">Nomor HP <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg></button>
                      </th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('provider')" class="inline-flex items-center gap-2">Provider <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg></button>
                      </th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('packageName')" class="inline-flex items-center gap-2">Paket <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg></button>
                      </th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('price')" class="inline-flex items-center gap-2">Harga <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg></button>
                      </th>
                      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('status')" class="inline-flex items-center gap-2">Status <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg></button>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="(item, idx) in sortedDetails" :key="idx">
                      <tr class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle font-mono text-sm" x-text="item.msisdn"></td>
                        <td class="p-4 align-middle" x-text="item.provider"></td>
                        <td class="p-4 align-middle" x-text="item.packageName"></td>
                        <td class="p-4 align-middle text-right" x-text="formatRupiah(item.price)"></td>
                        <td class="p-4 align-middle text-center"><span class="badge" :class="getItemStatusBadgeClass(item.status)" x-text="getItemStatusLabel(item.status)"></span></td>
                      </tr>
                    </template>
                    <template x-if="selectedTransaction && selectedTransaction.items.length === 0">
                      <tr><td colspan="5" class="p-6 text-center text-muted-foreground">Detail nomor belum tersedia.</td></tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div x-show="toastVisible" x-transition class="toast">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function formatRupiah(value) {
      const n = Number(value || 0);
      return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
    }
    function formatDateTime(date) {
      const d = new Date(date);
      return d.toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }
    function formatDateTimeFull(date) {
      const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
      const d = new Date(date);
      const hours = String(d.getHours()).padStart(2, '0');
      const minutes = String(d.getMinutes()).padStart(2, '0');
      return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}, ${hours}:${minutes} WIB`;
    }
  </script>
  <script>
    function historyApp() {
      return {
        viewMode: 'batch',
        search: '',
        statusFilter: 'all',
        dateFrom: '',
        dateTo: '',
        sortKey: 'createdAt',
        sortDirection: 'desc',
        selectedTransaction: null,
        detailSearch: '',
        detailSortKey: 'msisdn',
        detailSortDirection: 'asc',
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',
        currentPage: 1,
        itemsPerPage: 50,
        transactions: @json($transactions ?? []).map(tx => ({
          ...tx,
          createdAt: new Date(tx.createdAt)
        })),
        init() {
          this.$nextTick(() => {
            flatpickr('#dateRangePicker', {
              mode: 'range',
              dateFormat: 'd M Y',
              locale: { rangeSeparator: ' - ' },
              onChange: (selectedDates) => {
                if (selectedDates.length === 2) {
                  this.dateFrom = selectedDates[0].toISOString().split('T')[0];
                  this.dateTo = selectedDates[1].toISOString().split('T')[0];
                } else if (selectedDates.length === 0) {
                  this.dateFrom = '';
                  this.dateTo = '';
                }
              },
              onClose: (selectedDates, _dateStr, instance) => {
                if (selectedDates.length === 1) {
                  instance.clear();
                  this.dateFrom = '';
                  this.dateTo = '';
                }
              }
            });
          });
        },
        changePage(page) {
          if (page >= 1 && page <= this.totalPages) this.currentPage = page;
        },
        setItemsPerPage(value) {
          this.itemsPerPage = parseInt(value);
          this.currentPage = 1;
        },
        clearDateFilter() {
          this.dateFrom = '';
          this.dateTo = '';
          const input = document.querySelector('#dateRangePicker');
          const picker = input ? input._flatpickr : null;
          if (picker) picker.clear();
          else if (input) input.value = '';
        },
        get sortedTransactions() {
          return [...this.filteredTransactions].sort((a, b) => {
            let comparison = 0;
            switch (this.sortKey) {
              case 'batchId':
                comparison = a.batchId.localeCompare(b.batchId);
                break;
              case 'batchName':
                comparison = a.batchName.localeCompare(b.batchName);
                break;
              case 'createdAt':
                comparison = a.createdAt.getTime() - b.createdAt.getTime();
                break;
              case 'msisdnCount':
                comparison = (a.items?.length || 0) - (b.items?.length || 0);
                break;
              case 'totalAmount':
                comparison = a.totalAmount - b.totalAmount;
                break;
              case 'status':
                comparison = this.getStatusLabel(a.status).localeCompare(this.getStatusLabel(b.status));
                break;
              default:
                comparison = 0;
            }
            return this.sortDirection === 'asc' ? comparison : -comparison;
          });
        },
        get filteredTransactions() {
          return this.transactions.filter(tx => {
            if (this.statusFilter !== 'all' && tx.status !== this.statusFilter) return false;
            if (this.dateFrom || this.dateTo) {
              const txDate = new Date(tx.createdAt);
              txDate.setHours(0, 0, 0, 0);
              if (this.dateFrom) {
                const fromDate = new Date(this.dateFrom);
                fromDate.setHours(0, 0, 0, 0);
                if (txDate < fromDate) return false;
              }
              if (this.dateTo) {
                const toDate = new Date(this.dateTo);
                toDate.setHours(23, 59, 59, 999);
                if (txDate > toDate) return false;
              }
            }
            if (!this.search.trim()) return true;
            const query = this.search.toLowerCase();
            const searchable = [tx.batchId, tx.batchName, formatDateTime(tx.createdAt), String(tx.items?.length || 0), formatRupiah(tx.totalAmount), String(tx.totalAmount)].join(' ').toLowerCase();
            return searchable.includes(query);
          });
        },
        get sortedItems() {
          return [...this.filteredItems].sort((a, b) => b.createdAt - a.createdAt);
        },
        get filteredItems() {
          const allItems = this.transactions.flatMap(tx => (tx.items || []).map(item => ({ ...item, batchId: tx.batchId, createdAt: tx.createdAt })));
          return allItems.filter(item => {
            if (this.statusFilter !== 'all' && item.status !== this.statusFilter) return false;
            if (this.dateFrom || this.dateTo) {
              const itemDate = new Date(item.createdAt);
              itemDate.setHours(0, 0, 0, 0);
              if (this.dateFrom) {
                const from = new Date(this.dateFrom);
                from.setHours(0, 0, 0, 0);
                if (itemDate < from) return false;
              }
              if (this.dateTo) {
                const to = new Date(this.dateTo);
                to.setHours(23, 59, 59, 999);
                if (itemDate > to) return false;
              }
            }
            if (!this.search.trim()) return true;
            const query = this.search.toLowerCase();
            const searchable = [item.msisdn, item.provider, item.packageName, formatRupiah(item.price)].join(' ').toLowerCase();
            return searchable.includes(query);
          });
        },
        get totalPages() {
          const itemCount = this.viewMode === 'batch' ? this.sortedTransactions.length : this.sortedItems.length;
          return Math.ceil(itemCount / this.itemsPerPage) || 1;
        },
        get paginatedTransactions() {
          const start = (this.currentPage - 1) * this.itemsPerPage;
          const end = start + this.itemsPerPage;
          return this.sortedTransactions.slice(start, end);
        },
        get paginatedItems() {
          const start = (this.currentPage - 1) * this.itemsPerPage;
          const end = start + this.itemsPerPage;
          return this.sortedItems.slice(start, end);
        },
        get sortedDetails() {
          if (!this.selectedTransaction) return [];

          const filtered = this.selectedTransaction.items.filter(item => {
            if (!this.detailSearch.trim()) return true;
            const query = this.detailSearch.toLowerCase();
            const searchable = [item.msisdn, item.provider, item.packageName, formatRupiah(item.price), String(item.price)].join(' ').toLowerCase();
            return searchable.includes(query);
          });

          return [...filtered].sort((a, b) => {
            let comparison = 0;
            switch (this.detailSortKey) {
              case 'msisdn': comparison = a.msisdn.localeCompare(b.msisdn); break;
              case 'provider': comparison = a.provider.localeCompare(b.provider); break;
              case 'packageName': comparison = a.packageName.localeCompare(b.packageName); break;
              case 'price': comparison = a.price - b.price; break;
              case 'status': comparison = this.getStatusLabel(a.status).localeCompare(this.getStatusLabel(b.status)); break;
              default: comparison = 0;
            }
            return this.detailSortDirection === 'asc' ? comparison : -comparison;
          });
        },
        handleSort(key) {
          this.currentPage = 1;
          if (this.sortKey === key) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            return;
          }
          this.sortKey = key;
          this.sortDirection = key === 'createdAt' ? 'desc' : 'asc';
        },
        handleDetailSort(key) {
          if (this.detailSortKey === key) {
            this.detailSortDirection = this.detailSortDirection === 'asc' ? 'desc' : 'asc';
            return;
          }
          this.detailSortKey = key;
          this.detailSortDirection = 'asc';
        },
        viewDetails(tx) {
          this.selectedTransaction = tx;
          this.detailSearch = '';
          this.detailSortKey = 'msisdn';
          this.detailSortDirection = 'asc';
        },
        closeDetails() {
          this.selectedTransaction = null;
          this.detailSearch = '';
        },
        getStatusLabel(status) {
          switch (status) {
            case 'pending': return 'Menunggu Pembayaran';
            case 'processing': return 'Menunggu Pembayaran';
            case 'completed': return 'Order Selesai';
            default: return status;
          }
        },
        getStatusBadgeClass(status) {
          switch (status) {
            case 'pending': return 'badge-secondary';
            case 'processing': return 'badge-secondary';
            case 'completed': return 'badge-primary';
            default: return 'badge-outline';
          }
        },
        getPackageStatusSummary(transaction) {
          if (!transaction.items || transaction.items.length === 0) {
            return '<span class="text-muted-foreground">0 Berhasil | 0 Diproses | 0 Gagal</span>';
          }

          const counts = { completed: 0, processing: 0, pending: 0 };
          transaction.items.forEach(item => {
            if (item.status === 'completed') counts.completed++;
            else if (item.status === 'processing') counts.processing++;
            else counts.pending++;
          });

          const parts = [];
          parts.push(`<span class="${counts.completed > 0 ? 'text-primary font-medium' : 'text-muted-foreground'}">${counts.completed} Berhasil</span>`);
          parts.push(`<span class="text-muted-foreground">${counts.processing} Diproses</span>`);
          parts.push(`<span class="${counts.pending > 0 ? 'text-destructive' : 'text-muted-foreground'}">${counts.pending} Gagal</span>`);
          return parts.join(' <span class="text-muted-foreground">|</span> ');
        },
        getItemStatusCount(transaction, status) {
          if (!transaction || !transaction.items) return 0;
          if (status === 'pending') return transaction.items.filter(item => item.status === 'pending' || item.status === 'failed').length;
          return transaction.items.filter(item => item.status === status).length;
        },
        getItemStatusLabel(status) {
          switch (status) {
            case 'completed': return 'Berhasil';
            case 'processing': return 'Diproses';
            case 'pending':
            case 'failed':
              return 'Gagal';
            default: return status;
          }
        },
        getItemStatusBadgeClass(status) {
          switch (status) {
            case 'completed': return 'badge-primary';
            case 'processing': return 'badge-secondary';
            case 'pending':
            case 'failed':
              return 'badge-destructive';
            default: return 'badge-outline';
          }
        },
        showToast(title, message) {
          this.toastTitle = title;
          this.toastMessage = message;
          this.toastVisible = true;
          setTimeout(() => { this.toastVisible = false; }, 3000);
        },
        formatRupiah,
        formatDateTime,
        formatDateTimeFull,
      };
    }
  </script>
@endsection

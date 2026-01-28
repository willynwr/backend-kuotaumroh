@extends('layouts.affiliate')

@section('title', 'Riwayat Transaksi')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .sticky-pagination {
      position: sticky;
      bottom: 0;
      background: white;
      border-top: 1px solid hsl(var(--border));
      z-index: 10;
    }
    .sticky-column {
      position: sticky;
      left: 0;
      background: white;
      z-index: 1;
    }
    .sticky-column::after {
      content: "";
      position: absolute;
      right: 0;
      top: 0;
      bottom: 0;
      width: 1px;
      background: hsl(var(--border));
    }
    .table-content tbody tr:hover .sticky-column {
      background: hsl(var(--muted) / 0.5);
    }
    .badge {
      display: inline-flex;
      align-items: center;
      border-radius: 9999px;
      padding: 0.25rem 0.625rem;
      font-size: 0.75rem;
      font-weight: 600;
      border: 1px solid transparent;
    }
    .badge-primary {
      background: hsl(var(--primary));
      color: hsl(var(--primary-foreground));
    }
    .badge-secondary {
      background: hsl(var(--secondary));
      color: hsl(var(--secondary-foreground));
    }
    .badge-destructive {
      background: hsl(var(--destructive));
      color: hsl(var(--destructive-foreground));
    }
    .badge-outline {
      background: transparent;
      border-color: hsl(var(--border));
      color: hsl(var(--foreground));
    }
    .package-status {
      text-align: left;
      font-size: 14px;
      line-height: 1.6;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .animate-fade-in {
      animation: fadeIn 0.2s ease-out;
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="transactionsPage()">
    <main class="container mx-auto py-6 px-4">
      <div class="mb-6">
        <div class="flex items-stretch gap-3">
          <a href="{{ url('/dash/' . $linkReferral) }}" class="inline-flex items-center justify-center w-12 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali ke Dashboard">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Riwayat Transaksi</h1>
            <p class="text-muted-foreground mt-1">Lihat semua transaksi Anda</p>
          </div>
        </div>
      </div>

      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <!-- Tabs -->
          <div class="mb-6 flex gap-2 border-b">
            <button @click="viewMode = 'batch'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="viewMode === 'batch' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Per Batch</button>
            <button @click="viewMode = 'number'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="viewMode === 'number' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Per Nomor</button>
          </div>

          <!-- Filters -->
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg font-semibold">Daftar Transaksi</h3>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
              <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <input type="text" id="dateRangePicker" placeholder="Pilih Rentang Tanggal" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-8 py-2 text-sm w-full sm:w-[250px]" readonly>
                <button type="button" x-show="dateFrom || dateTo" @click="clearDateFilter()" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-muted-foreground hover:text-foreground rounded-full hover:bg-muted transition-colors">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" :placeholder="viewMode === 'batch' ? 'Cari batch/nama travel/agen/tanggal/nominal' : 'Cari nomor/provider/paket/harga/travel/agen'" x-model="search" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[300px]">
              </div>
              <select x-show="viewMode === 'batch'" x-model="statusFilter" class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm w-full sm:w-[150px]">
                <option value="all">Semua Status</option>
                <option value="pending">Menunggu Pembayaran</option>
                <option value="processing">Proses Inject</option>
                <option value="completed">Order Selesai</option>
              </select>
              <select x-show="viewMode === 'number'" x-cloak x-model="statusFilter" class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm w-full sm:w-[150px]">
                <option value="all">Semua Status</option>
                <option value="completed">Sukses</option>
                <option value="processing">Diproses</option>
                <option value="failed">Gagal</option>
              </select>
            </div>
          </div>

          <!-- Batch View -->
          <div x-show="viewMode === 'batch'">
            <div class="overflow-x-auto" x-transition>
              <table class="w-full table-content">
                <thead>
                  <tr class="border-b">
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleSort('batchId')" class="inline-flex items-center gap-2">Batch ID
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground sticky-column whitespace-nowrap min-w-[200px]">
                      <button @click="handleSort('batchName')" class="inline-flex items-center gap-2">Nama Batch
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleSort('status')" class="inline-flex items-center gap-2">Status
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleSort('msisdnCount')" class="inline-flex items-center gap-2">Jumlah Nomor
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground whitespace-nowrap min-w-[180px]">Proses Paket</th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleSort('createdAt')" class="inline-flex items-center gap-2">Tanggal
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap min-w-[180px]">
                      <button @click="handleSort('travelName')" class="inline-flex items-center gap-2">Nama Travel
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleSort('territory')" class="inline-flex items-center gap-2">Teritori
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap min-w-[180px]">
                      <button @click="handleSort('agentName')" class="inline-flex items-center gap-2">Nomor Agen
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleSort('totalAmount')" class="inline-flex items-center gap-2">Total
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleSort('marginTotal')" class="inline-flex items-center gap-2">Margin
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground whitespace-nowrap">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="tx in paginatedTransactions" :key="tx.id">
                    <tr class="border-b transition-colors hover:bg-muted/50">
                      <td class="p-4 align-middle font-mono whitespace-nowrap" x-text="tx.batchId"></td>
                      <td class="p-4 align-middle font-medium whitespace-nowrap sticky-column" x-text="tx.batchName"></td>
                      <td class="p-4 align-middle text-center whitespace-nowrap">
                        <span class="badge" :class="getStatusBadgeClass(tx.status)" x-text="getStatusLabel(tx.status)"></span>
                      </td>
                      <td class="p-4 align-middle text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-1">
                          <span x-text="tx.items?.length || 0"></span>
                          <button @click="viewDetails(tx)" class="inline-flex items-center justify-center rounded-md border bg-transparent h-7 w-7 hover:bg-muted transition-colors" title="Lihat Detail">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                          </button>
                        </div>
                      </td>
                      <td class="p-4 align-middle whitespace-nowrap min-w-[180px]">
                        <div class="package-status" x-html="getPackageStatusSummary(tx)"></div>
                      </td>
                      <td class="p-4 align-middle whitespace-nowrap" x-text="formatDateTime(tx.createdAt)"></td>
                      <td class="p-4 align-middle whitespace-nowrap min-w-[180px]" x-text="tx.travelName"></td>
                      <td class="p-4 align-middle whitespace-nowrap" x-text="tx.territory || '-'"></td>
                      <td class="p-4 align-middle whitespace-nowrap min-w-[180px]">
                        <div class="flex flex-col">
                          <span class="font-medium" x-text="tx.agentName || '-'"></span>
                          <span class="text-muted-foreground" x-text="tx.agentPhone || ''"></span>
                        </div>
                      </td>
                      <td class="p-4 align-middle text-right font-medium whitespace-nowrap" x-text="formatRupiah(tx.totalAmount)"></td>
                      <td class="p-4 align-middle text-right font-medium whitespace-nowrap" x-text="formatRupiah(tx.marginTotal || 0)"></td>
                      <td class="p-4 align-middle text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-1">
                          <a :href="'tel:' + (tx.agentPhone || '')" class="inline-flex items-center justify-center rounded-md bg-transparent h-8 w-8 hover:bg-green-50 hover:text-green-600 transition-colors" title="Hubungi Agen" x-show="tx.agentPhone">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                          </a>
                          <a :href="'https://wa.me/' + (tx.agentPhone || '').replace(/\D/g, '')" target="_blank" class="inline-flex items-center justify-center rounded-md bg-transparent h-8 w-8 hover:bg-green-50 hover:text-green-600 transition-colors" title="WhatsApp Agen" x-show="tx.agentPhone">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                          </a>
                        </div>
                      </td>
                    </tr>
                  </template>
                  <template x-if="sortedTransactions.length === 0">
                    <tr>
                      <td colspan="12" class="p-8 text-center text-muted-foreground">Tidak ada transaksi yang ditemukan</td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>

            <!-- Pagination - Batch View -->
            <div class="sticky-pagination flex flex-col lg:flex-row items-center justify-between gap-4 p-4" x-show="viewMode === 'batch'">
              <div class="text-muted-foreground">
                Menampilkan item <span x-text="Math.min((currentPage - 1) * itemsPerPage + 1, sortedTransactions.length)"></span> - <span x-text="Math.min(currentPage * itemsPerPage, sortedTransactions.length)"></span> dari <span x-text="sortedTransactions.length"></span> item.
              </div>
              <div class="flex items-center gap-2">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 transition-colors">Previous</button>
                <div class="flex gap-1">
                  <template x-for="page in totalPages" :key="page">
                    <button x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)" @click="changePage(page)" :class="page === currentPage ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border h-8 w-8 transition-colors" x-text="page"></button>
                  </template>
                </div>
                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 transition-colors">Next</button>
              </div>
              <div class="flex items-center gap-2 text-muted-foreground">
                <span>Menampilkan</span>
                <select @change="setItemsPerPage($event.target.value)" :value="itemsPerPage" class="h-8 rounded-md border border-input bg-background px-2 py-1">
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

          <!-- Number View (similar structure) -->
          <div x-show="viewMode === 'number'" x-cloak>
            <div class="overflow-x-auto" x-transition>
              <table class="w-full table-content">
                <thead>
                  <tr class="border-b">
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap sticky-column min-w-[180px]">
                      <button @click="handleItemSort('msisdn')" class="inline-flex items-center gap-2">Nomor HP
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap min-w-[150px]">
                      <button @click="handleItemSort('provider')" class="inline-flex items-center gap-2">Provider
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleItemSort('packageName')" class="inline-flex items-center gap-2">Paket
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground whitespace-nowrap min-w-[120px]">
                      <button @click="handleItemSort('status')" class="inline-flex items-center gap-2">Status
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleItemSort('price')" class="inline-flex items-center gap-2">Harga
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleItemSort('margin')" class="inline-flex items-center gap-2">Margin
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap min-w-[200px]">
                      <button @click="handleItemSort('travelName')" class="inline-flex items-center gap-2">Nama Travel
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleItemSort('territory')" class="inline-flex items-center gap-2">Teritori
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap min-w-[180px]">
                      <button @click="handleItemSort('agentName')" class="inline-flex items-center gap-2">Nomor Agen
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground whitespace-nowrap">
                      <button @click="handleItemSort('createdAt')" class="inline-flex items-center gap-2">Tanggal & Waktu
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                      </button>
                    </th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground whitespace-nowrap">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="(item, idx) in paginatedItems" :key="idx">
                    <tr class="border-b transition-colors hover:bg-muted/50">
                      <td class="p-4 align-middle font-mono whitespace-nowrap sticky-column" x-text="item.msisdn"></td>
                      <td class="p-4 align-middle whitespace-nowrap min-w-[150px]" x-text="item.provider"></td>
                      <td class="p-4 align-middle whitespace-nowrap" x-text="item.packageName"></td>
                      <td class="p-4 align-middle text-center whitespace-nowrap min-w-[120px]">
                        <span class="badge" :class="getItemStatusBadgeClass(item.status)" x-text="getItemStatusLabel(item.status)"></span>
                      </td>
                      <td class="p-4 align-middle text-right font-medium whitespace-nowrap" x-text="formatRupiah(item.price)"></td>
                      <td class="p-4 align-middle text-right font-medium whitespace-nowrap" x-text="formatRupiah(item.margin || 0)"></td>
                      <td class="p-4 align-middle whitespace-nowrap min-w-[200px]" x-text="item.travelName"></td>
                      <td class="p-4 align-middle whitespace-nowrap" x-text="item.territory || '-'"></td>
                      <td class="p-4 align-middle whitespace-nowrap min-w-[180px]">
                        <div class="flex flex-col">
                          <span class="font-medium" x-text="item.agentName || '-'"></span>
                          <span class="text-muted-foreground" x-text="item.agentPhone || ''"></span>
                        </div>
                      </td>
                      <td class="p-4 align-middle text-muted-foreground whitespace-nowrap" x-text="formatDateTime(item.createdAt)"></td>
                      <td class="p-4 align-middle text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-1">
                          <a :href="'tel:' + (item.agentPhone || '')" class="inline-flex items-center justify-center rounded-md bg-transparent h-8 w-8 hover:bg-green-50 hover:text-green-600 transition-colors" title="Hubungi Agen" x-show="item.agentPhone">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                          </a>
                          <a :href="'https://wa.me/' + (item.agentPhone || '').replace(/\D/g, '')" target="_blank" class="inline-flex items-center justify-center rounded-md bg-transparent h-8 w-8 hover:bg-green-50 hover:text-green-600 transition-colors" title="WhatsApp Agen" x-show="item.agentPhone">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                          </a>
                        </div>
                      </td>
                    </tr>
                  </template>
                  <template x-if="sortedItems.length === 0">
                    <tr>
                      <td colspan="11" class="p-8 text-center text-muted-foreground">Tidak ada data yang ditemukan</td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>

            <!-- Pagination - Number View -->
            <div class="sticky-pagination flex flex-col lg:flex-row items-center justify-between gap-4 p-4">
              <div class="text-muted-foreground">
                Menampilkan item <span x-text="Math.min((currentPage - 1) * itemsPerPage + 1, sortedItems.length)"></span> - <span x-text="Math.min(currentPage * itemsPerPage, sortedItems.length)"></span> dari <span x-text="sortedItems.length"></span> item.
              </div>
              <div class="flex items-center gap-2">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 transition-colors">Previous</button>
                <div class="flex gap-1">
                  <template x-for="page in totalPages" :key="page">
                    <button x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)" @click="changePage(page)" :class="page === currentPage ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border h-8 w-8 transition-colors" x-text="page"></button>
                  </template>
                </div>
                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-muted'" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 transition-colors">Next</button>
              </div>
              <div class="flex items-center gap-2 text-muted-foreground">
                <span>Menampilkan</span>
                <select @change="setItemsPerPage($event.target.value)" :value="itemsPerPage" class="h-8 rounded-md border border-input bg-background px-2 py-1">
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

    <!-- Detail Modal -->
    <div x-show="selectedTransaction" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeDetails()">
      <div class="relative bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden animate-fade-in mx-4">
        <div class="p-6 border-b">
          <div class="flex items-start justify-between">
            <div>
              <h2 class="text-lg font-semibold">Detail Transaksi</h2>
              <p class="text-sm text-muted-foreground mt-1" x-show="selectedTransaction">
                <span x-text="selectedTransaction?.batchId"></span> - <span x-text="selectedTransaction?.batchName"></span>
              </p>
            </div>
            <button @click="closeDetails()" class="rounded-md p-2 hover:bg-muted transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
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
                <p>
                  <span class="badge" :class="selectedTransaction ? getStatusBadgeClass(selectedTransaction.status) : ''" x-text="selectedTransaction ? getStatusLabel(selectedTransaction.status) : ''"></span>
                </p>
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
                  <p class="text-2xl font-bold text-destructive" x-text="getItemStatusCount(selectedTransaction, 'failed') + getItemStatusCount(selectedTransaction, 'pending')">0</p>
                </div>
              </div>
            </div>
            <div class="border-t pt-4">
              <p class="font-medium mb-2">Detail Nomor HP</p>
              <div class="relative mb-3">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Cari nomor/provider/paket/harga" x-model="detailSearch" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full">
              </div>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b">
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('msisdn')" class="inline-flex items-center gap-2">Nomor HP
                          <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                        </button>
                      </th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('provider')" class="inline-flex items-center gap-2">Provider
                          <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                        </button>
                      </th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('packageName')" class="inline-flex items-center gap-2">Paket
                          <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                        </button>
                      </th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('price')" class="inline-flex items-center gap-2">Harga
                          <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                        </button>
                      </th>
                      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground text-sm">
                        <button @click="handleDetailSort('status')" class="inline-flex items-center gap-2">Status
                          <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                        </button>
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
                        <td class="p-4 align-middle text-center">
                          <span class="badge" :class="selectedTransaction.status === 'pending' ? '' : getItemStatusBadgeClass(item.status)" x-text="selectedTransaction.status === 'pending' ? '' : getItemStatusLabel(item.status)"></span>
                        </td>
                      </tr>
                    </template>
                    <template x-if="selectedTransaction && selectedTransaction.items.length === 0">
                      <tr>
                        <td colspan="5" class="p-6 text-center text-muted-foreground">Detail nomor belum tersedia.</td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function transactionsPage() {
  return {
    viewMode: 'batch',
    search: '',
    statusFilter: 'all',
    dateFrom: '',
    dateTo: '',
    sortKey: 'createdAt',
    sortDirection: 'desc',
    itemSortKey: 'createdAt',
    itemSortDirection: 'desc',
    currentPage: 1,
    itemsPerPage: 50,
    transactions: [],
    selectedTransaction: null,
    detailSearch: '',
    detailSortKey: 'msisdn',
    detailSortDirection: 'asc',

    formatDate(dateStr) { return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }); },
    formatDateTime(date) { return new Date(date).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }); },
    formatDateTimeFull(date) { return new Date(date).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' }); },
    formatRupiah(amount) { return `Rp ${amount.toLocaleString('id-ID')}`; },

    clearDateFilter() {
      this.dateFrom = '';
      this.dateTo = '';
      const input = document.querySelector('#dateRangePicker');
      const picker = input ? input._flatpickr : null;
      if (picker) picker.clear();
      else if (input) input.value = '';
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
        const searchable = [tx.batchId, tx.batchName, tx.travelName, tx.agentName || '', tx.agentPhone || '', tx.territory || '', this.formatDateTime(tx.createdAt), String(tx.items?.length || 0), this.formatRupiah(tx.totalAmount), String(tx.totalAmount)].join(' ').toLowerCase();
        return searchable.includes(query);
      });
    },

    get sortedTransactions() {
      return [...this.filteredTransactions].sort((a, b) => {
        let comparison = 0;
        switch (this.sortKey) {
          case 'batchId': comparison = a.batchId.localeCompare(b.batchId); break;
          case 'batchName': comparison = a.batchName.localeCompare(b.batchName); break;
          case 'createdAt': comparison = new Date(a.createdAt) - new Date(b.createdAt); break;
          case 'travelName': comparison = a.travelName.localeCompare(b.travelName); break;
          case 'territory': comparison = (a.territory || '').localeCompare(b.territory || ''); break;
          case 'agentName': comparison = (a.agentName || '').localeCompare(b.agentName || ''); break;
          case 'msisdnCount': comparison = (a.items?.length || 0) - (b.items?.length || 0); break;
          case 'totalAmount': comparison = a.totalAmount - b.totalAmount; break;
          case 'marginTotal': comparison = (a.marginTotal || 0) - (b.marginTotal || 0); break;
          case 'status': comparison = this.getStatusLabel(a.status).localeCompare(this.getStatusLabel(b.status)); break;
        }
        return this.sortDirection === 'asc' ? comparison : -comparison;
      });
    },

    get filteredItems() {
      const allItems = this.transactions.flatMap(tx => (tx.items || []).map(item => ({ ...item, batchId: tx.batchId, batchName: tx.batchName, createdAt: tx.createdAt, travelName: tx.travelName, territory: tx.territory || '', agentName: tx.agentName || '', agentPhone: tx.agentPhone || '' })));
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
        const searchable = [item.msisdn, item.provider, item.packageName, item.travelName, item.agentName || '', item.agentPhone || '', item.territory || '', item.price.toString(), this.formatRupiah(item.price)].join(' ').toLowerCase();
        return searchable.includes(query);
      });
    },

    get sortedItems() {
      return [...this.filteredItems].sort((a, b) => {
        let comparison = 0;
        switch (this.itemSortKey) {
          case 'msisdn': comparison = a.msisdn.localeCompare(b.msisdn); break;
          case 'provider': comparison = a.provider.localeCompare(b.provider); break;
          case 'packageName': comparison = a.packageName.localeCompare(b.packageName); break;
          case 'price': comparison = a.price - b.price; break;
          case 'margin': comparison = (a.margin || 0) - (b.margin || 0); break;
          case 'status': comparison = this.getItemStatusLabel(a.status).localeCompare(this.getItemStatusLabel(b.status)); break;
          case 'travelName': comparison = a.travelName.localeCompare(b.travelName); break;
          case 'territory': comparison = (a.territory || '').localeCompare(b.territory || ''); break;
          case 'agentName': comparison = (a.agentName || '').localeCompare(b.agentName || ''); break;
          case 'createdAt': comparison = new Date(a.createdAt) - new Date(b.createdAt); break;
        }
        return this.itemSortDirection === 'asc' ? comparison : -comparison;
      });
    },

    get sortedDetails() {
      if (!this.selectedTransaction) return [];
      const filtered = this.selectedTransaction.items.filter(item => {
        if (!this.detailSearch.trim()) return true;
        const query = this.detailSearch.toLowerCase();
        const searchable = [item.msisdn, item.provider, item.packageName, this.formatRupiah(item.price), String(item.price)].join(' ').toLowerCase();
        return searchable.includes(query);
      });
      return [...filtered].sort((a, b) => {
        let comparison = 0;
        switch (this.detailSortKey) {
          case 'msisdn': comparison = a.msisdn.localeCompare(b.msisdn); break;
          case 'provider': comparison = a.provider.localeCompare(b.provider); break;
          case 'packageName': comparison = a.packageName.localeCompare(b.packageName); break;
          case 'price': comparison = a.price - b.price; break;
          case 'status': comparison = this.getItemStatusLabel(a.status).localeCompare(this.getItemStatusLabel(b.status)); break;
        }
        return this.detailSortDirection === 'asc' ? comparison : -comparison;
      });
    },

    get totalPages() {
      const count = this.viewMode === 'batch' ? this.sortedTransactions.length : this.sortedItems.length;
      return Math.ceil(count / this.itemsPerPage) || 1;
    },

    get paginatedTransactions() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.sortedTransactions.slice(start, start + this.itemsPerPage);
    },

    get paginatedItems() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.sortedItems.slice(start, start + this.itemsPerPage);
    },

    handleSort(key) {
      if (this.sortKey === key) this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      else { this.sortKey = key; this.sortDirection = key === 'createdAt' ? 'desc' : 'asc'; }
    },

    handleItemSort(key) {
      if (this.itemSortKey === key) this.itemSortDirection = this.itemSortDirection === 'asc' ? 'desc' : 'asc';
      else { this.itemSortKey = key; this.itemSortDirection = key === 'createdAt' ? 'desc' : 'asc'; }
    },

    handleDetailSort(key) {
      if (this.detailSortKey === key) this.detailSortDirection = this.detailSortDirection === 'asc' ? 'desc' : 'asc';
      else { this.detailSortKey = key; this.detailSortDirection = key === 'msisdn' ? 'asc' : 'desc'; }
    },

    changePage(page) {
      if (page >= 1 && page <= this.totalPages) this.currentPage = page;
    },

    setItemsPerPage(value) {
      this.itemsPerPage = parseInt(value, 10);
      this.currentPage = 1;
    },

    getStatusLabel(status) {
      switch (status) {
        case 'pending': return 'Menunggu Pembayaran';
        case 'processing': return 'Proses Inject';
        case 'completed': return 'Order Selesai';
        case 'failed': return 'Gagal';
        default: return status;
      }
    },

    getStatusBadgeClass(status) {
      switch (status) {
        case 'pending': return 'badge-secondary';
        case 'processing': return 'bg-yellow-500 text-white border-yellow-500';
        case 'completed': return 'badge-primary';
        case 'failed': return 'badge-destructive';
        default: return 'badge-outline';
      }
    },

    getPackageStatusSummary(transaction) {
      if (transaction.status === 'pending' || !transaction.items || transaction.items.length === 0) {
        return '<div>0 Berhasil<br>0 Diproses<br>0 Gagal</div>';
      }
      const counts = { completed: 0, processing: 0, failed: 0, pending: 0 };
      transaction.items.forEach(item => { if (counts.hasOwnProperty(item.status)) counts[item.status]++; });
      const gagal = counts.failed + counts.pending;
      const parts = [];
      parts.push(`<span class="${counts.completed > 0 ? 'text-primary font-medium' : 'text-muted-foreground'}">${counts.completed} Berhasil</span>`);
      parts.push(`<span class="text-muted-foreground">${counts.processing} Diproses</span>`);
      parts.push(`<span class="${gagal > 0 ? 'text-destructive' : 'text-muted-foreground'}">${gagal} Gagal</span>`);
      return '<div>' + parts.join('<br>') + '</div>';
    },

    viewDetails(tx) {
      this.selectedTransaction = tx;
      this.detailSearch = '';
    },

    closeDetails() {
      this.selectedTransaction = null;
      this.detailSearch = '';
    },

    getItemStatusCount(transaction, status) {
      if (!transaction || !transaction.items || transaction.status === 'pending') return 0;
      return transaction.items.filter(item => item.status === status).length;
    },

    getItemStatusLabel(status) {
      switch (status) {
        case 'completed': return 'Sukses';
        case 'processing': return 'Diproses';
        case 'pending': return 'Gagal';
        case 'failed': return 'Gagal';
        default: return status;
      }
    },

    getItemStatusBadgeClass(status) {
      switch (status) {
        case 'completed': return 'badge-primary';
        case 'processing': return 'bg-yellow-500 text-white border-yellow-500';
        case 'pending': return 'badge-destructive';
        case 'failed': return 'badge-destructive';
        default: return 'badge-outline';
      }
    },

    init() {
      // Mock data - replace with API call
      this.transactions = @json($transactions ?? []);

      this.$nextTick(() => {
        flatpickr('#dateRangePicker', {
          mode: 'range',
          dateFormat: 'd M Y',
          onChange: (selectedDates) => {
            if (selectedDates.length === 2) {
              this.dateFrom = selectedDates[0].toISOString().split('T')[0];
              this.dateTo = selectedDates[1].toISOString().split('T')[0];
            } else if (selectedDates.length === 0) {
              this.dateFrom = '';
              this.dateTo = '';
            }
          },
          onClose: (selectedDates, dateStr, instance) => {
            if (selectedDates.length === 1) {
              instance.clear();
              this.dateFrom = '';
              this.dateTo = '';
            }
          }
        });
      });
    }
  }
}
</script>
@endpush

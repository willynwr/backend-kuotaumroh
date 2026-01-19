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
        <h1 class="text-3xl font-bold tracking-tight">Riwayat Transaksi</h1>
        <p class="text-muted-foreground mt-2">Lihat semua transaksi yang telah Anda buat</p>
      </div>

      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="mb-6 flex gap-2 border-b">
            <button @click="viewMode = 'batch'; search = ''" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="viewMode === 'batch' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Per Batch</button>
            <button @click="viewMode = 'number'; search = ''" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="viewMode === 'number' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Per Nomor</button>
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
                <option value="processing">Proses Inject</option>
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
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Batch ID</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Batch</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Jumlah Nomor HP</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total</th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="tx in sortedTransactions" :key="tx.id">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-mono text-sm" x-text="tx.batchId"></td>
                    <td class="p-4 align-middle font-medium" x-text="tx.batchName"></td>
                    <td class="p-4 align-middle" x-text="formatDateTime(tx.createdAt)"></td>
                    <td class="p-4 align-middle text-center" x-text="tx.items?.length || 0"></td>
                    <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(tx.totalAmount)"></td>
                    <td class="p-4 align-middle text-center"><span class="badge" :class="getStatusBadgeClass(tx.status)" x-text="getStatusLabel(tx.status)"></span></td>
                  </tr>
                </template>
                <template x-if="sortedTransactions.length === 0">
                  <tr><td colspan="6" class="p-8 text-center text-muted-foreground">Tidak ada transaksi yang ditemukan</td></tr>
                </template>
              </tbody>
            </table>
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
                <template x-for="(item, idx) in sortedItems" :key="idx">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-mono text-sm" x-text="item.msisdn"></td>
                    <td class="p-4 align-middle" x-text="item.provider"></td>
                    <td class="p-4 align-middle" x-text="item.packageName"></td>
                    <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(item.price)"></td>
                    <td class="p-4 align-middle text-sm text-muted-foreground" x-text="formatDateTime(item.createdAt)"></td>
                    <td class="p-4 align-middle text-center"><span class="badge" :class="getItemStatusBadgeClass(item.status)" x-text="getItemStatusLabel(item.status)"></span></td>
                  </tr>
                </template>
                <template x-if="sortedItems.length === 0">
                  <tr><td colspan="6" class="p-8 text-center text-muted-foreground">Tidak ada data yang ditemukan</td></tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
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
  </script>
  <script>
    function historyApp() {
      return {
        viewMode: 'batch',
        search: '',
        statusFilter: 'all',
        dateFrom: '',
        dateTo: '',
        transactions: [
          {
            id: '1',
            batchId: 'ORD-2026-0115',
            batchName: 'Batch Januari Group A',
            createdAt: new Date('2026-01-15T10:30:00'),
            totalAmount: 3250000,
            status: 'completed',
            items: [
              { msisdn: '081234567890', provider: 'Telkomsel', packageName: 'Umroh Gold 10GB', price: 250000, status: 'completed', createdAt: new Date('2026-01-15T10:30:00') },
              { msisdn: '085234567892', provider: 'Indosat', packageName: 'Umroh Silver 5GB', price: 150000, status: 'completed', createdAt: new Date('2026-01-15T10:30:00') },
            ]
          },
          {
            id: '2',
            batchId: 'ORD-2026-0112',
            batchName: 'Pesanan Group B',
            createdAt: new Date('2026-01-12T14:15:00'),
            totalAmount: 2150000,
            status: 'processing',
            items: [
              { msisdn: '081234567894', provider: 'Telkomsel', packageName: 'Umroh Gold 10GB', price: 250000, status: 'processing', createdAt: new Date('2026-01-12T14:15:00') },
              { msisdn: '085234567895', provider: 'Indosat', packageName: 'Umroh Silver 5GB', price: 150000, status: 'processing', createdAt: new Date('2026-01-12T14:15:00') },
            ]
          },
        ],
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
        clearDateFilter() {
          this.dateFrom = '';
          this.dateTo = '';
          const input = document.querySelector('#dateRangePicker');
          const picker = input ? input._flatpickr : null;
          if (picker) picker.clear();
          else if (input) input.value = '';
        },
        get sortedTransactions() {
          return this.filteredTransactions;
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
            const searchable = [tx.batchId, tx.batchName, formatDateTime(tx.createdAt), formatRupiah(tx.totalAmount)].join(' ').toLowerCase();
            return searchable.includes(query);
          });
        },
        get sortedItems() {
          const allItems = this.transactions.flatMap(tx => (tx.items || []).map(item => ({ ...item })));
          const filtered = allItems.filter(item => {
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
          return filtered.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
        },
        getStatusLabel(status) {
          switch (status) {
            case 'pending': return 'Menunggu Pembayaran';
            case 'processing': return 'Proses Inject';
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
        getItemStatusLabel(status) {
          switch (status) {
            case 'completed': return 'Sukses';
            case 'processing': return 'Proses';
            case 'pending': return 'Pending';
            case 'failed': return 'Gagal';
            default: return status;
          }
        },
        getItemStatusBadgeClass(status) {
          switch (status) {
            case 'completed': return 'badge-primary';
            case 'processing': return 'badge-secondary';
            case 'pending': return 'badge-secondary';
            case 'failed': return 'badge-destructive';
            default: return 'badge-outline';
          }
        },
        formatRupiah,
        formatDateTime,
      };
    }
  </script>
@endsection

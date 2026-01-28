@extends('layouts.affiliate')

@section('title', 'Dompet Saya')

@push('styles')
<style>
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
    .badge-outline {
      background: transparent;
      border-color: hsl(var(--border));
      color: hsl(var(--foreground));
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="walletApp()">
    <main class="container mx-auto py-6 px-4">
      <div class="mb-6">
        <div class="flex items-stretch gap-3">
          <a href="{{ url('/dash/' . $linkReferral) }}" class="inline-flex items-center justify-center w-12 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali ke Dashboard">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Dompet Saya</h1>
            <p class="text-muted-foreground mt-1">Kelola saldo dan riwayat transaksi</p>
          </div>
        </div>
      </div>

      <!-- Balance Card -->
      <div class="relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm mb-6">
        <div class="pointer-events-none absolute right-0 top-0 h-32 w-32 -translate-y-1/3 translate-x-1/3 rounded-full bg-primary/5"></div>
        <div class="relative z-10 flex flex-row items-center justify-between p-4 pb-3">
          <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Saldo Komisi Tersedia</h3>
          <div class="rounded-lg p-2 bg-primary/10 text-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="relative z-10 p-4 pt-0">
          <div class="flex items-center gap-3">
            <img src="{{ asset('images/wallet.png') }}" alt="Wallet" class="h-10 w-10 object-contain" onerror="this.style.display='none'">
            <div class="text-2xl font-extrabold text-primary tracking-tight" x-text="'Rp ' + walletBalance.balance.toLocaleString('id-ID')"></div>
          </div>
          <div class="flex items-center justify-between border-t border-slate-100 pt-3 mt-3">
            <div>
              <p class="text-xs font-bold uppercase text-slate-400">Saldo Diproses (Penarikan)</p>
              <p class="text-lg font-extrabold text-primary" x-text="'Rp ' + walletBalance.pendingWithdrawal.toLocaleString('id-ID')"></p>
            </div>
            <a href="{{ url('/dash/' . $linkReferral . '/withdraw') }}" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 text-sm font-medium transition-colors">
              <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
              </svg>
              Tarik Saldo
            </a>
          </div>
        </div>
      </div>

      <!-- History Card with Tabs -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">Riwayat</h3>

          <div x-data="{ activeTab: 'income' }">
            <!-- Tab List -->
            <div class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground mb-4">
              <button @click="activeTab = 'income'" :class="activeTab === 'income' ? 'bg-background text-foreground shadow-sm' : ''" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all">
                Pemasukan
              </button>
              <button @click="activeTab = 'withdrawal'" :class="activeTab === 'withdrawal' ? 'bg-background text-foreground shadow-sm' : ''" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all">
                Penarikan
              </button>
            </div>

            <!-- Income Tab -->
            <div x-show="activeTab === 'income'" x-cloak>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b">
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tipe</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Keterangan</th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="item in incomeHistory" :key="item.id">
                      <tr class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle" x-text="formatDate(item.date)"></td>
                        <td class="p-4 align-middle">
                          <span class="badge badge-outline" x-text="getTypeLabel(item.type)"></span>
                        </td>
                        <td class="p-4 align-middle" x-text="item.description"></td>
                        <td class="p-4 align-middle text-right font-medium text-primary" x-text="'+' + formatRupiah(item.amount)"></td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Withdrawal Tab -->
            <div x-show="activeTab === 'withdrawal'" x-cloak>
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b">
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Bank</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Rekening</th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Jumlah</th>
                      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="item in withdrawalHistory" :key="item.id">
                      <tr class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle" x-text="formatDate(item.date)"></td>
                        <td class="p-4 align-middle" x-text="item.bankName"></td>
                        <td class="p-4 align-middle" x-text="item.accountNumber"></td>
                        <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(item.amount)"></td>
                        <td class="p-4 align-middle text-center">
                          <span class="badge" :class="{ 'badge-secondary': item.status === 'pending', 'badge-primary': item.status === 'completed', 'bg-destructive text-destructive-foreground': item.status === 'rejected' }" x-text="getStatusLabel(item.status)"></span>
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
    </main>
</div>
@endsection

@push('scripts')
<script>
function walletApp() {
  return {
    walletBalance: {
      balance: 3250000,
      pendingWithdrawal: 500000,
    },
    incomeHistory: [
      { id: '1', date: new Date('2024-01-15'), type: 'commission', description: 'Komisi Batch #ORD-2024-0115', amount: 450000 },
      { id: '2', date: new Date('2024-01-12'), type: 'refund', description: 'Refund gagal aktivasi', amount: 75000 },
      { id: '3', date: new Date('2024-01-10'), type: 'commission', description: 'Komisi Batch #ORD-2024-0110', amount: 320000 },
      { id: '4', date: new Date('2024-01-08'), type: 'bonus', description: 'Bonus target bulanan', amount: 500000 },
    ],
    withdrawalHistory: [
      { id: '1', date: new Date('2024-01-14'), amount: 500000, bankName: 'BCA', accountNumber: '****4567', status: 'pending' },
      { id: '2', date: new Date('2024-01-10'), amount: 1000000, bankName: 'BCA', accountNumber: '****4567', status: 'completed' },
      { id: '3', date: new Date('2024-01-05'), amount: 750000, bankName: 'Mandiri', accountNumber: '****8901', status: 'completed' },
    ],

    formatDate(date) {
      return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    },

    formatRupiah(amount) {
      return `Rp ${amount.toLocaleString('id-ID')}`;
    },

    getTypeLabel(type) {
      const labels = { 'commission': 'Komisi', 'refund': 'Refund', 'bonus': 'Bonus' };
      return labels[type] || type;
    },

    getStatusLabel(status) {
      const labels = { 'pending': 'Diproses', 'completed': 'Selesai', 'rejected': 'Ditolak' };
      return labels[status] || status;
    },

    init() {
      // Replace with API call: this.walletBalance = @json($walletBalance ?? null);
      // this.incomeHistory = @json($incomeHistory ?? []);
      // this.withdrawalHistory = @json($withdrawalHistory ?? []);
    }
  }
}
</script>
@endpush

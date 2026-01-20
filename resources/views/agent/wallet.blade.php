@extends('agent.layout')

@section('title', 'Dompet - Kuotaumroh.id')

@section('content')
  <div x-data="walletApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral) : route('agent.dashboard') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Dompet Saya</h1>
            <p class="text-muted-foreground mt-2">Kelola saldo dan riwayat transaksi</p>
          </div>
        </div>
      </div>

      <div class="rounded-2xl border-slate-200 bg-white shadow-sm mb-6 relative overflow-hidden">
        <div class="relative z-10 flex flex-row items-center justify-between p-6 pb-4">
          <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Saldo Tersedia</h3>
        </div>
        <div class="relative z-10 p-6 pt-0">
          <div class="flex items-center gap-3">
            <img :src="imageBase + '/wallet.png'" alt="Wallet" class="h-12 w-12 object-contain" onerror="this.style.display='none'">
            <div class="text-3xl font-bold text-slate-900 tracking-tight" x-text="formatRupiah(walletBalance.balance)"></div>
          </div>
          <div class="mt-2 text-sm text-slate-500">
            <span>Pending penarikan</span>
            <span class="ml-2 font-semibold text-slate-900" x-text="formatRupiah(walletBalance.pendingWithdrawal)"></span>
          </div>
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/withdraw') : route('agent.withdraw') }}" class="mt-6 inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 w-full h-11 text-base font-medium transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
            </svg>
            Tarik Saldo
          </a>
        </div>
      </div>

      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">Riwayat</h3>
          <div x-data="{ activeTab: 'income' }">
            <div class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground mb-4">
              <button @click="activeTab = 'income'" :class="activeTab === 'income' ? 'bg-background text-foreground shadow-sm' : ''" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all">Pemasukan</button>
              <button @click="activeTab = 'withdrawal'" :class="activeTab === 'withdrawal' ? 'bg-background text-foreground shadow-sm' : ''" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all">Penarikan</button>
            </div>

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
                        <td class="p-4 align-middle"><span class="badge badge-outline" x-text="getTypeLabel(item.type)"></span></td>
                        <td class="p-4 align-middle" x-text="item.description"></td>
                        <td class="p-4 align-middle text-right font-medium text-primary" x-text="'+' + formatRupiah(item.amount)"></td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>

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
                          <span class="badge" :class="{ 'badge-secondary': item.status === 'pending', 'badge-primary': item.status === 'completed', 'badge-destructive': item.status === 'rejected' }" x-text="getStatusLabel(item.status)"></span>
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

@section('scripts')
  <script>
    function walletApp() {
      return {
        imageBase: @json(asset('images')),
        walletBalance: { balance: 3250000, pendingWithdrawal: 500000 },
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
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        formatDate(date) {
          const d = new Date(date);
          return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        },
        getTypeLabel(type) {
          const labels = { commission: 'Komisi', refund: 'Refund', bonus: 'Bonus' };
          return labels[type] || type;
        },
        getStatusLabel(status) {
          const labels = { pending: 'Diproses', completed: 'Selesai', rejected: 'Ditolak' };
          return labels[status] || status;
        },
      };
    }
  </script>
@endsection

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

      <div class="grid gap-4 md:grid-cols-2 mb-6">
        <!-- Saldo Tersedia -->
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-primary/10 p-3">
              <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-muted-foreground">Saldo Profit Tersedia</p>
              <h3 class="text-2xl font-bold" x-text="formatRupiah(walletBalance.balance)"></h3>
            </div>
            <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/history-profit') : route('agent.history-profit') }}" class="rounded-md p-2 hover:bg-primary/10 transition-colors" title="Lihat History Profit">
              <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </a>
          </div>
        </div>

        <!-- Saldo Diproses -->
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-muted p-3">
              <svg class="h-6 w-6 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Saldo Diproses</p>
              <h3 class="text-2xl font-bold" x-text="formatRupiah(walletBalance.pendingWithdrawal)"></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-6">
        <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/withdraw') : route('agent.withdraw') }}" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 w-full h-12 text-lg font-medium transition-colors shadow-sm">
          <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
          Tarik Saldo
        </a>
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
        walletBalance: @json($walletBalance ?? ['balance' => 0, 'pendingWithdrawal' => 0]),
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

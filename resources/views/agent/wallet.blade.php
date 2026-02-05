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

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Komisi Toko -->
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-primary/10 p-3">
              <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-muted-foreground">Komisi Toko</p>
              <h3 class="text-2xl font-bold" x-text="formatRupiah(walletBalance.balance)"></h3>
            </div>
          </div>
        </div>

        <!-- Total Komisi -->
        <div class="rounded-lg border bg-slate-50 border-slate-200 shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-primary/10 p-3">
              <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Total Komisi Toko</p>
              <p class="text-2xl font-bold" x-text="formatRupiah(referralData.totalStoreCommission)"></p>
            </div>
          </div>
        </div>

        <!-- Total Tarik Saldo -->
        <div class="rounded-lg border bg-slate-50 border-slate-200 shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-muted p-3">
              <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Total Tarik Saldo</p>
              <p class="text-2xl font-bold" x-text="formatRupiah(referralData.totalWithdrawn)"></p>
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
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nomor HP</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Provider</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Paket</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Harga Jual</th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Profit</th>
                      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="row in referralHistory" :key="row.id">
                      <tr class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle font-mono text-sm" x-text="row.msisdn"></td>
                        <td class="p-4 align-middle">
                          <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800" x-text="row.provider"></span>
                        </td>
                        <td class="p-4 align-middle" x-text="row.packageName"></td>
                        <td class="p-4 align-middle text-muted-foreground text-sm" x-text="formatDate(row.orderDate)"></td>
                        <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(row.sellPrice)"></td>
                        <td class="p-4 align-middle text-right font-bold text-primary" x-text="'+' + formatRupiah(row.commission)"></td>
                        <td class="p-4 align-middle text-center">
                          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold" 
                            :class="{
                              'bg-green-100 text-green-800': row.status === 'sukses',
                              'bg-yellow-100 text-yellow-800': row.status === 'proses',
                              'bg-red-100 text-red-800': row.status === 'batal'
                            }" 
                            x-text="row.status.charAt(0).toUpperCase() + row.status.slice(1)">
                          </span>
                        </td>
                      </tr>
                    </template>
                    <template x-if="referralHistory.length === 0">
                      <tr><td colspan="7" class="p-8 text-center text-muted-foreground">Tidak ada data referral</td></tr>
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
                      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Bank</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Rekening</th>
                      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Keterangan</th>
                      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="item in withdrawalHistory" :key="item.id">
                      <tr class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle">
                          <div class="flex flex-col items-center justify-center gap-2">
                            <span class="badge" :class="{ 'badge-secondary': item.status === 'pending', 'badge-primary': item.status === 'approve', 'badge-destructive': item.status === 'reject' }" x-text="getStatusLabel(item.status)"></span>
                            <button 
                              x-show="item.status === 'reject' && item.alasan_reject" 
                              @click="showRejectReason(item)" 
                              class="text-xs text-red-600 hover:text-red-700 font-medium underline"
                              title="Lihat alasan penolakan"
                            >
                              Detail
                            </button>
                          </div>
                        </td>
                        <td class="p-4 align-middle" x-text="formatDate(item.date)"></td>
                        <td class="p-4 align-middle" x-text="item.bankName"></td>
                        <td class="p-4 align-middle" x-text="item.accountNumber"></td>
                        <td class="p-4 align-middle">
                          <span class="text-sm text-muted-foreground" x-text="item.keterangan || '-'"></span>
                        </td>
                        <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(item.amount)"></td>
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

    <!-- Modal Alasan Reject -->
    <div x-show="rejectReasonModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" @click.self="closeRejectReason()">
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md animate-fade-in overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-white">Penarikan Ditolak</h2>
              <p class="text-red-50 text-sm">Alasan penolakan penarikan</p>
            </div>
          </div>
        </div>
        <div class="p-6 space-y-4" x-show="selectedReject">
          <div class="bg-gray-50 rounded-xl p-4 space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Tanggal</span>
              <span class="text-sm font-medium" x-text="formatDate(selectedReject?.date)"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Jumlah</span>
              <span class="text-base font-bold text-red-600" x-text="formatRupiah(selectedReject?.amount)"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Bank</span>
              <span class="text-sm" x-text="selectedReject?.bankName"></span>
            </div>
          </div>
          <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-start gap-2 mb-2">
              <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div class="flex-1">
                <p class="text-sm font-semibold text-red-900 mb-1">Alasan Penolakan:</p>
                <p class="text-sm text-red-800" x-text="selectedReject?.alasan_reject"></p>
              </div>
            </div>
          </div>
        </div>
        <div class="p-6 bg-gray-50 border-t">
          <button @click="closeRejectReason()" class="w-full inline-flex items-center justify-center rounded-lg h-11 px-4 font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function walletApp() {
      return {
        imageBase: @json(asset('images')),
        walletBalance: @json($walletBalance ?? ['balance' => 0, 'pendingWithdrawal' => 0]),
        referralData: @json($referralData ?? ['totalStoreCommission' => 0, 'totalWithdrawn' => 0]),
        referralHistory: @json($referralHistory ?? []),
        withdrawalHistory: @json($withdrawalHistory ?? []),
        rejectReasonModal: false,
        selectedReject: null,
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        formatDate(date) {
          const d = new Date(date);
          return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        },
        getStatusLabel(status) {
          const labels = { pending: 'Diproses', approve: 'Selesai', reject: 'Ditolak' };
          return labels[status] || status;
        },
        showRejectReason(withdrawal) {
          this.selectedReject = withdrawal;
          this.rejectReasonModal = true;
        },
        closeRejectReason() {
          this.rejectReasonModal = false;
          this.selectedReject = null;
        },
      };
    }
  </script>
@endsection

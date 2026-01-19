@extends('agent.layout')

@section('title', 'Dashboard - Kuotaumroh.id')

@section('content')
  <div x-data="dashboardApp()">
    <main class="container mx-auto py-10 animate-fade-in px-4">
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">
        <div>
          <div class="relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm h-full">
            <div class="pointer-events-none absolute right-0 top-0 h-40 w-40 -translate-y-1/3 translate-x-1/3 rounded-full bg-primary/5"></div>
            <div class="relative z-10 flex flex-row items-center justify-between p-6 pb-4">
              <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Profit Bulan Ini</h3>
              <div class="rounded-lg p-2 bg-primary/10 text-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
            </div>
            <div class="relative z-10 p-6 pt-0">
              <div class="text-4xl font-extrabold text-primary tracking-tight" x-text="formatRupiah(stats.monthlyProfit)"></div>
              <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                <div>
                  <p class="text-xs font-bold uppercase text-slate-400">Total akumulasi</p>
                  <p class="text-xl font-extrabold text-primary" x-text="formatRupiah(stats.totalProfit)"></p>
                </div>
                <div class="flex items-end gap-1 opacity-70">
                  <div class="h-4 w-2 rounded-t-sm bg-primary/20"></div>
                  <div class="h-6 w-2 rounded-t-sm bg-primary/30"></div>
                  <div class="h-5 w-2 rounded-t-sm bg-primary/40"></div>
                  <div class="h-8 w-2 rounded-t-sm bg-primary/60"></div>
                  <div class="h-10 w-2 rounded-t-sm bg-primary/80"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div>
          <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full">
            <div class="flex flex-row items-center justify-between p-6 pb-4">
              <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Link Referral</h3>
            </div>
            <div class="p-6 pt-0">
              <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="sm:w-24 sm:shrink-0">
                  <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(referralLink)" alt="QR Referral" class="w-full aspect-square rounded-lg border bg-white object-contain p-2">
                </div>
                <div class="space-y-2 sm:flex-1">
                  <label class="text-xs font-medium text-muted-foreground">Link Pendaftaran</label>
                  <div class="flex flex-col gap-2 sm:flex-row">
                    <input type="text" readonly :value="referralLink" class="flex h-9 w-full min-w-0 rounded-md border border-input bg-muted px-3 py-2 text-xs">
                    <button @click="copyReferralLink()" class="h-9 w-full px-3 bg-primary text-white rounded-md text-xs font-medium hover:bg-primary/90 transition-colors sm:w-auto">
                      Salin
                    </button>
                  </div>
                  <p class="text-xs text-slate-500">Bagikan link ini untuk dapatkan bonus referral.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div>
          <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full">
            <div class="flex flex-row items-center justify-between p-6 pb-4">
              <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Saldo Dompet Saat Ini</h3>
            </div>
            <div class="p-6 pt-0">
              <div class="flex items-center gap-3">
                <img :src="imageBase + '/wallet.png'" alt="Wallet" class="h-12 w-12 object-contain" onerror="this.style.display='none'" />
                <div class="text-3xl font-bold text-slate-900 tracking-tight" x-text="formatRupiah(stats.walletBalance)"></div>
              </div>
              <div class="mt-2 text-sm text-slate-500">
                <span>Pending penarikan</span>
                <span class="ml-2 font-semibold text-slate-900" x-text="formatRupiah(stats.pendingWithdrawal)"></span>
              </div>
              <a href="{{ route('agent.withdraw') }}" class="mt-6 inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 w-full h-11 text-base font-medium transition-colors">
                Tarik Saldo
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-8">
        <div class="flex items-center gap-4">
          <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Menu Utama</h2>
          <div class="h-px flex-1 bg-slate-200"></div>
        </div>
        <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-5">
          <template x-for="item in menuItems" :key="item.id">
            <a :href="item.href">
              <div class="group flex h-48 cursor-pointer items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:border-primary hover:bg-primary hover:text-primary-foreground hover:shadow-md">
                <div class="flex flex-col items-center justify-center gap-3 p-6 text-center">
                  <img :src="imageBase + '/' + item.icon + '.png'" :alt="item.title" class="h-24 w-24 object-contain transition-transform group-hover:scale-110" onerror="this.src = imageBase + '/kabah.png'" />
                  <h3 class="text-xs font-bold uppercase tracking-wide leading-tight text-slate-700 group-hover:text-primary-foreground" x-text="item.title"></h3>
                </div>
              </div>
            </a>
          </template>
        </div>
      </div>
    </main>
  </div>
@endsection

@section('scripts')
  <script>
    function dashboardApp() {
      return {
        imageBase: @json(url('/agent/assets')),
        linkReferral: '{{ $linkReferral ?? "" }}',
        referralLink: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral) : "" }}',
        stats: {
          monthlyProfit: 2450000,
          totalProfit: 15750000,
          walletBalance: 3250000,
          pendingWithdrawal: 500000,
        },
        menuItems: [
          { id: 'new-order', title: 'Pesanan Baru', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/order") : route("agent.order") }}', icon: 'order' },
          { id: 'history', title: 'Riwayat Transaksi', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/history") : route("agent.history") }}', icon: 'history' },
          { id: 'wallet', title: 'Dompet Saya', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/wallet") : route("agent.wallet") }}', icon: 'wallet' },
          { id: 'referrals', title: 'Program Referral', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/referrals") : route("agent.referrals") }}', icon: 'referral' },
          { id: 'catalog', title: 'Katalog Harga', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/catalog") : route("agent.catalog") }}', icon: 'catalog' },
        ],
        init() {
          // Link referral sudah diset dari controller
        },
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        copyReferralLink() {
          if (!this.referralLink) return;
          navigator.clipboard.writeText(this.referralLink);
        },
      };
    }
  </script>
@endsection

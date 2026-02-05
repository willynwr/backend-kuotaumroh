@extends('agent.layout')

@section('title', 'Dashboard - Kuotaumroh.id')

@section('content')
  <div x-data="dashboardApp()">
    <main class="container mx-auto py-10 animate-fade-in px-4">
      
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">
        <!-- Box 1: Profit Bulan Ini -->
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
              
              <!-- Breakdown Komisi Toko dan Margin Bulk -->
              <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                  <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <span class="text-slate-600">Komisi Toko</span>
                  </div>
                  <span class="font-semibold text-blue-600" x-text="formatRupiah(stats.monthlyStoreProfit)"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                  <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <span class="text-slate-600">Margin Bulk</span>
                  </div>
                  <span class="font-semibold text-green-600" x-text="formatRupiah(stats.monthlyBulkProfit)"></span>
                </div>
              </div>
              
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

        <!-- Dynamic Link Toko Box(es) based on jenis_travel -->
        <!-- Dynamic Link Toko Box(es) based on jenis_travel -->
        <template x-if="hasUmroh">
          <div>
            <div class="rounded-2xl border-slate-200 bg-white shadow-sm">
              <div class="flex flex-row items-center justify-between p-6 pb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Link Toko: Kuotaumroh.id</h3>
              </div>
              <div class="p-6 pt-0">
                <div class="flex flex-row gap-4 items-start">
                  <div class="w-24 sm:w-24 shrink-0">
                    <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(referralLinkUmroh)" alt="QR Kuotaumroh" class="w-full aspect-square rounded-lg border bg-white object-contain p-2">
                  </div>
                  <div class="space-y-2 sm:flex-1">
                    <!-- <label class="text-xs font-medium text-muted-foreground">Link Toko Umroh</label> -->
                    <input type="text" readonly :value="referralLinkUmroh" class="flex h-9 w-full rounded-md border border-input bg-muted px-3 py-2 text-xs">
                    <div class="flex gap-2">
                      <button @click="copyLink(referralLinkUmroh)" class="h-9 flex-1 px-3 bg-primary text-white rounded-md text-xs font-medium hover:bg-primary/90 transition-colors">
                        Salin Link
                      </button>
                      <button @click="downloadQR(referralLinkUmroh, 'QR-Kuotaumroh')" class="h-9 flex-1 px-3 bg-white border border-slate-300 text-slate-700 rounded-md text-xs font-medium hover:bg-slate-50 transition-colors">
                        Download QR
                      </button>
                    </div>
                    <p class="text-xs text-slate-500">Bagikan link ini untuk dapatkan bonus referral.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
        
        <template x-if="hasLeisure">
          <div>
            <div class="rounded-2xl border-slate-200 bg-white shadow-sm">
              <div class="flex flex-row items-center justify-between p-6 pb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Link Toko: Roamer.id</h3>
              </div>
              <div class="p-6 pt-0">
                <div class="flex flex-row gap-4 items-start">
                  <div class="w-24 sm:w-24 shrink-0">
                    <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(referralLinkLeisure)" alt="QR Roamer" class="w-full aspect-square rounded-lg border bg-white object-contain p-2">
                  </div>
                  <div class="space-y-2 sm:flex-1">
                    <!-- <label class="text-xs font-medium text-muted-foreground">Link Toko Leisure</label> -->
                    <input type="text" readonly :value="referralLinkLeisure" class="flex h-9 w-full rounded-md border border-input bg-muted px-3 py-2 text-xs">
                    <div class="flex gap-2">
                      <button @click="copyLink(referralLinkLeisure)" class="h-9 flex-1 px-3 bg-primary text-white rounded-md text-xs font-medium hover:bg-primary/90 transition-colors">
                        Salin Link
                      </button>
                      <button @click="downloadQR(referralLinkLeisure, 'QR-Roamer')" class="h-9 flex-1 px-3 bg-white border border-slate-300 text-slate-700 rounded-md text-xs font-medium hover:bg-slate-50 transition-colors">
                        Download QR
                      </button>
                    </div>
                    <p class="text-xs text-slate-500">Bagikan link ini untuk dapatkan bonus referral.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- Jika tidak ada QR code yang ditampilkan, tampilkan placeholder kosong -->
        <template x-if="!hasUmroh && !hasLeisure">
          <div></div>
        </template>
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
                  <img :src="imageBase + '/' + item.icon + '.png'" :alt="item.title" class="h-24 w-24 object-contain transition-transform group-hover:scale-110" onerror="this.src = imageBase + '/LOGO.png'" />
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
        imageBase: @json(asset('images')),
        linkReferral: '{{ $linkReferral ?? "" }}',
        jenisTravel: '{{ $jenisTravelAgent ?? "" }}', // UMROH, LEISURE, or UMROH,LEISURE
        linkReferalAgent: '{{ $linkReferalAgent ?? "" }}', // Link toko: /u/xxx
        referralLink: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral) : "" }}',
        referralLinkUmroh: '',
        referralLinkLeisure: '',
        hasUmroh: false,
        hasLeisure: false,
        stats: {
          monthlyProfit: 0,
          monthlyStoreProfit: 0,
          monthlyBulkProfit: 0,
          totalProfit: 0,
          walletBalance: 0,
          pendingWithdrawal: 0,
        },
        loading: true,
        menuItems: [
          { id: 'new-order', title: 'Pesanan Baru', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/order") : route("agent.order") }}', icon: 'order' },
          { id: 'history', title: 'Riwayat Transaksi', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/history") : route("agent.history") }}', icon: 'history' },
          { id: 'wallet', title: 'Dompet Saya', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/wallet") : route("agent.wallet") }}', icon: 'wallet' },
          { id: 'referrals', title: 'Program Referral', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/referrals") : route("agent.referrals") }}', icon: 'referral' },
          { id: 'catalog', title: 'Katalog Harga', href: '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/catalog") : route("agent.catalog") }}', icon: 'catalog' },
        ],
        async init() {
          console.log('Dashboard Init - jenisTravel:', this.jenisTravel);
          console.log('linkReferalAgent:', this.linkReferalAgent);
          
          // Load stats dari API
          await this.loadStats();
          
          // Determine which travel types the agent has
          // Use more robust parsing with includes check
          if (this.jenisTravel) {
            const jenisStr = String(this.jenisTravel).toUpperCase();
            console.log('jenisStr:', jenisStr);
            
            // Check if string contains UMROH or LEISURE
            this.hasUmroh = jenisStr.includes('UMROH');
            this.hasLeisure = jenisStr.includes('LEISURE');
            
            // Also try split with multiple delimiters for debugging
            const travelTypes = jenisStr.split(/[,;|\/\s]+/).map(t => t.trim()).filter(t => t);
            console.log('Split result:', travelTypes);
          }
          
          console.log('hasUmroh:', this.hasUmroh);
          console.log('hasLeisure:', this.hasLeisure);
          
          // Generate store links using link_referal (format: /u/{link_referal})
          if (this.linkReferalAgent) {
            const storeBaseUrl = `${window.location.origin}/u/${this.linkReferalAgent}`;
            this.referralLinkUmroh = storeBaseUrl;
            this.referralLinkLeisure = storeBaseUrl;
            console.log('Store URL generated:', storeBaseUrl);
          } else {
            console.warn('linkReferalAgent is empty!');
          }
        },
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        copyReferralLink() {
          if (!this.referralLink) return;
          navigator.clipboard.writeText(this.referralLink);
        },
        copyLink(link) {
          if (!link) return;
          navigator.clipboard.writeText(link);
        },
        async downloadQR(link, filename = 'QR-Code') {
          if (!link) return;
          
          try {
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=${encodeURIComponent(link)}`;
            
            // Fetch the QR code image as blob
            const response = await fetch(qrUrl);
            const blob = await response.blob();
            
            // Create a temporary URL for the blob
            const blobUrl = window.URL.createObjectURL(blob);
            
            // Create a temporary link element and trigger download
            const a = document.createElement('a');
            a.href = blobUrl;
            a.download = `${filename}.png`;
            document.body.appendChild(a);
            a.click();
            
            // Cleanup
            document.body.removeChild(a);
            window.URL.revokeObjectURL(blobUrl);
            
            console.log('QR Code downloaded successfully:', filename);
          } catch (error) {
            console.error('Error downloading QR code:', error);
            alert('Gagal mendownload QR code. Silakan coba lagi.');
          }
        },
        async loadStats() {
          try {
            this.loading = true;
            const agentId = '{{ $user->id ?? "" }}';
            
            if (!agentId) {
              console.error('Agent ID not found');
              return;
            }
            
            const response = await fetch(`/api/agent/stats?agent_id=${agentId}`);
            const result = await response.json();
            
            if (result.success) {
              this.stats.monthlyProfit = parseInt(result.data.monthly_profit) || 0;
              this.stats.monthlyStoreProfit = parseInt(result.data.monthly_store_profit) || 0;
              this.stats.monthlyBulkProfit = parseInt(result.data.monthly_bulk_profit) || 0;
              this.stats.totalProfit = parseInt(result.data.total_profit) || 0;
              this.stats.walletBalance = parseInt(result.data.wallet_balance) || 0;
              this.stats.pendingWithdrawal = parseInt(result.data.pending_withdrawal) || 0;
              console.log('Stats loaded:', this.stats);
            } else {
              console.error('Failed to load stats:', result.message);
            }
          } catch (error) {
            console.error('Error loading stats:', error);
          } finally {
            this.loading = false;
          }
        },
      };
    }
  </script>
@endsection

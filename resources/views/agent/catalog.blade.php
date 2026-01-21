@extends('agent.layout')

@section('title', 'Katalog Harga - Kuotaumroh.id')

@section('content')
  <div x-data="catalogApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral) : route('agent.dashboard') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Katalog Harga</h1>
            <p class="text-muted-foreground mt-2">Daftar paket kuota umroh yang tersedia</p>
          </div>
        </div>
      </div>

      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Daftar Paket</h3>
            
            <!-- Provider Filter Pills -->
            <div class="flex flex-wrap items-center gap-2 mb-4">
              <button 
                @click="selectedProvider = ''" 
                :class="selectedProvider === '' ? 'bg-primary text-white' : 'bg-muted text-muted-foreground hover:bg-muted/80'"
                class="rounded-full px-4 py-2 text-sm font-medium transition-colors">
                Semua
              </button>
              <template x-for="provider in providerList" :key="provider">
                <button 
                  @click="selectedProvider = provider" 
                  :class="selectedProvider === provider ? 'bg-primary text-white' : 'bg-muted text-muted-foreground hover:bg-muted/80'"
                  class="rounded-full px-4 py-2 text-sm font-medium transition-colors"
                  x-text="provider">
                </button>
              </template>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input type="text" placeholder="Cari paket..." x-model="search" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full">
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('provider')" class="inline-flex items-center gap-2">
                      Provider
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('name')" class="inline-flex items-center gap-2">
                      Nama Paket
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('type')" class="inline-flex items-center gap-2">
                      Tipe Paket
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('duration')" class="inline-flex items-center gap-2">
                      Masa Aktif
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('totalQuota')" class="inline-flex items-center gap-2">
                      Total Kuota
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('mainQuota')" class="inline-flex items-center gap-2">
                      Kuota Utama
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('bonusQuota')" class="inline-flex items-center gap-2">
                      Kuota Bonus
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('call')" class="inline-flex items-center gap-2">
                      Telp
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('sms')" class="inline-flex items-center gap-2">
                      SMS
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('price')" class="inline-flex items-center gap-2">
                      Harga Modal
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('sellPrice')" class="inline-flex items-center gap-2">
                      Harga Jual
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('profit')" class="inline-flex items-center gap-2">
                      Profit
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('pointBonus')" class="inline-flex items-center gap-2">
                      Poin Bonus
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                </tr>
              </thead>
              <tbody>
                <template x-if="loading">
                  <tr>
                    <td colspan="13" class="text-center py-12">
                      <div class="flex items-center justify-center gap-3">
                        <div class="spinner"></div>
                        <span class="text-muted-foreground">Memuat paket...</span>
                      </div>
                    </td>
                  </tr>
                </template>
                <template x-if="!loading">
                  <template x-for="pkg in sortedPackages" :key="pkg.id">
                    <tr class="border-b transition-colors hover:bg-muted/50">
                      <td class="p-4 align-middle">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700" x-text="pkg.provider"></span>
                      </td>
                      <td class="p-4 align-middle">
                        <div class="font-medium" x-text="pkg.name"></div>
                        <template x-if="pkg.isBestPromo">
                          <div class="mt-1">
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold tracking-wide text-primary">PROMO TERBAIK</span>
                          </div>
                        </template>
                      </td>
                      <td class="p-4 align-middle">
                        <span class="text-xs text-muted-foreground" x-text="pkg.type"></span>
                      </td>
                      <td class="p-4 align-middle text-sm" x-text="pkg.duration"></td>
                      <td class="p-4 align-middle font-medium" x-text="pkg.totalQuota"></td>
                      <td class="p-4 align-middle text-sm" x-text="pkg.mainQuota"></td>
                      <td class="p-4 align-middle text-sm text-primary" x-text="pkg.bonusQuota"></td>
                      <td class="p-4 align-middle text-center text-sm" x-text="pkg.call"></td>
                      <td class="p-4 align-middle text-center text-sm" x-text="pkg.sms"></td>
                      <td class="p-4 align-middle text-right" x-text="formatRupiah(pkg.price)"></td>
                      <td class="p-4 align-middle text-right font-semibold" x-text="formatRupiah(pkg.sellPrice)"></td>
                      <td class="p-4 align-middle text-right text-primary font-medium" x-text="'+' + formatRupiah(pkg.profit)"></td>
                      <td class="p-4 align-middle text-right text-primary font-medium" x-text="'+' + pkg.pointBonus"></td>
                    </tr>
                  </template>
                </template>
                <template x-if="!loading && sortedPackages.length === 0">
                  <tr>
                    <td colspan="13" class="p-8 text-center text-muted-foreground">Tidak ada paket yang ditemukan</td>
                  </tr>
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
    function catalogApp() {
      return {
        search: '',
        selectedProvider: '',
        sortKey: 'provider',
        sortDirection: 'asc',
        loading: false,
        packages: [
          // AXIS Packages
          { id: 1, provider: 'AXIS', name: 'Internet 10 Hari', type: 'INTERNET', duration: '10 hari', totalQuota: 'Unlimited', mainQuota: 'Unlimited', bonusQuota: '-', call: '-', sms: '-', price: 224100, sellPrice: 249000, profit: 24900, pointBonus: 0, isBestPromo: true },
          { id: 2, provider: 'AXIS', name: 'Combo 10 Hari', type: 'INTERNET + TELP/SMS', duration: '10 hari', totalQuota: 'Unlimited', mainQuota: 'Unlimited', bonusQuota: '-', call: '50', sms: '50', price: 308700, sellPrice: 343000, profit: 34300, pointBonus: 0, isBestPromo: true },
          { id: 3, provider: 'AXIS', name: 'Combo 20 Hari', type: 'INTERNET + TELP/SMS', duration: '20 hari', totalQuota: 'Unlimited', mainQuota: 'Unlimited', bonusQuota: '-', call: '75', sms: '75', price: 393300, sellPrice: 437000, profit: 43700, pointBonus: 0, isBestPromo: true },
          { id: 4, provider: 'AXIS', name: 'Combo 40 Hari', type: 'INTERNET + TELP/SMS', duration: '40 hari', totalQuota: 'Unlimited', mainQuota: 'Unlimited', bonusQuota: '-', call: 'Unlimited', sms: 'Unlimited', price: 520200, sellPrice: 578000, profit: 57800, pointBonus: 0, isBestPromo: true },
          { id: 5, provider: 'AXIS', name: 'Internet 45 Hari 2GB', type: 'INTERNET', duration: '45 hari', totalQuota: '2 GB', mainQuota: '1 GB', bonusQuota: '1 GB Indo', call: '-', sms: '-', price: 139500, sellPrice: 155000, profit: 15500, pointBonus: 0, isBestPromo: false },
          
          // BYU Packages
          { id: 6, provider: 'BYU', name: 'Internet 10GB', type: 'INTERNET', duration: '30 hari', totalQuota: '10 GB', mainQuota: '10 GB', bonusQuota: '-', call: '-', sms: '-', price: 42000, sellPrice: 48000, profit: 6000, pointBonus: 0, isBestPromo: false },
          { id: 7, provider: 'BYU', name: 'Combo 15GB', type: 'INTERNET + TELP/SMS', duration: '30 hari', totalQuota: '15 GB', mainQuota: '12 GB', bonusQuota: '3 GB Apps', call: '100', sms: '100', price: 58000, sellPrice: 65000, profit: 7000, pointBonus: 0, isBestPromo: false },
          
          // INDOSAT Packages
          { id: 8, provider: 'INDOSAT', name: 'Freedom Internet 3GB', type: 'INTERNET', duration: '30 hari', totalQuota: '3 GB', mainQuota: '3 GB', bonusQuota: '-', call: '-', sms: '-', price: 15000, sellPrice: 18000, profit: 3000, pointBonus: 0, isBestPromo: false },
          { id: 9, provider: 'INDOSAT', name: 'Combo 15GB + BBM', type: 'INTERNET + APPS', duration: '30 hari', totalQuota: '15 GB', mainQuota: '10 GB', bonusQuota: '5 GB BBM', call: '-', sms: '-', price: 45000, sellPrice: 52000, profit: 7000, pointBonus: 0, isBestPromo: false },
          
          // SMARTFREN Packages
          { id: 10, provider: 'SMARTFREN', name: 'Unlimited MAX 30 Hari', type: 'INTERNET', duration: '30 hari', totalQuota: 'Unlimited', mainQuota: 'Unlimited', bonusQuota: '-', call: '-', sms: '-', price: 55000, sellPrice: 62000, profit: 7000, pointBonus: 0, isBestPromo: false },
          { id: 11, provider: 'SMARTFREN', name: 'Combo Unlimited + Voice', type: 'INTERNET + TELP', duration: '30 hari', totalQuota: 'Unlimited', mainQuota: 'Unlimited', bonusQuota: '-', call: 'Unlimited', sms: '-', price: 70000, sellPrice: 78000, profit: 8000, pointBonus: 0, isBestPromo: false },
          
          // TELKOMSEL Packages
          { id: 12, provider: 'TELKOMSEL', name: 'Internet Sakti 11GB', type: 'INTERNET', duration: '30 hari', totalQuota: '11 GB', mainQuota: '8 GB', bonusQuota: '3 GB Lokal', call: '-', sms: '-', price: 25000, sellPrice: 28000, profit: 3000, pointBonus: 0, isBestPromo: true },
          { id: 13, provider: 'TELKOMSEL', name: 'Combo 25GB OMG', type: 'INTERNET + TELP/SMS', duration: '30 hari', totalQuota: '25 GB', mainQuota: '20 GB', bonusQuota: '5 GB Malam', call: '100', sms: '100', price: 85000, sellPrice: 95000, profit: 10000, pointBonus: 0, isBestPromo: true },
          
          // TRI Packages
          { id: 14, provider: 'TRI', name: 'AON 10GB', type: 'INTERNET', duration: '30 hari', totalQuota: '10 GB', mainQuota: '10 GB', bonusQuota: '-', call: '-', sms: '-', price: 32000, sellPrice: 38000, profit: 6000, pointBonus: 0, isBestPromo: false },
          { id: 15, provider: 'TRI', name: 'Combo Sakti 20GB', type: 'INTERNET + TELP/SMS', duration: '30 hari', totalQuota: '20 GB', mainQuota: '15 GB', bonusQuota: '5 GB Malam', call: 'Unlimited', sms: 'Unlimited', price: 58000, sellPrice: 65000, profit: 7000, pointBonus: 0, isBestPromo: false },
          
          // XL Packages
          { id: 16, provider: 'XL', name: 'Xtra Combo Flex M', type: 'INTERNET', duration: '30 hari', totalQuota: '15 GB', mainQuota: '15 GB', bonusQuota: '-', call: '-', sms: '-', price: 45000, sellPrice: 48000, profit: 3000, pointBonus: 0, isBestPromo: false },
          { id: 17, provider: 'XL', name: 'Combo Xtra Lite 7GB', type: 'INTERNET + TELP/SMS', duration: '15 hari', totalQuota: '7 GB', mainQuota: '5 GB', bonusQuota: '2 GB Sosmed', call: '50', sms: '50', price: 28000, sellPrice: 32000, profit: 4000, pointBonus: 0, isBestPromo: false },
        ],
        formatRupiah(value) {
          const n = Number(value || 0);
          return 'Rp ' + n.toLocaleString('id-ID');
        },
        handleSort(key) {
          if (this.sortKey === key) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
          } else {
            this.sortKey = key;
            this.sortDirection = 'asc';
          }
        },
        get providerList() {
          const providers = new Set(this.packages.map(p => p.provider));
          return Array.from(providers).sort();
        },
        get filteredPackages() {
          let pkgs = this.packages;
          
          // Filter by Provider Pills
          if (this.selectedProvider) {
            pkgs = pkgs.filter(p => p.provider === this.selectedProvider);
          }

          // Filter by Search
          if (this.search.trim()) {
            const query = this.search.toLowerCase();
            pkgs = pkgs.filter(pkg =>
              String(pkg.name).toLowerCase().includes(query) ||
              String(pkg.provider).toLowerCase().includes(query) ||
              String(pkg.type).toLowerCase().includes(query) ||
              String(pkg.totalQuota).toLowerCase().includes(query) ||
              String(pkg.mainQuota).toLowerCase().includes(query) ||
              String(pkg.bonusQuota).toLowerCase().includes(query) ||
              String(pkg.duration).toLowerCase().includes(query) ||
              String(pkg.price).includes(query) ||
              String(pkg.sellPrice).includes(query) ||
              String(pkg.profit).includes(query)
            );
          }
          return pkgs;
        },
        get sortedPackages() {
          return [...this.filteredPackages].sort((a, b) => {
            let comparison = 0;
            switch (this.sortKey) {
              case 'provider': comparison = a.provider.localeCompare(b.provider); break;
              case 'name': comparison = a.name.localeCompare(b.name); break;
              case 'type': comparison = String(a.type).localeCompare(String(b.type)); break;
              case 'totalQuota': comparison = String(a.totalQuota).localeCompare(String(b.totalQuota)); break;
              case 'mainQuota': comparison = String(a.mainQuota).localeCompare(String(b.mainQuota)); break;
              case 'bonusQuota': comparison = String(a.bonusQuota).localeCompare(String(b.bonusQuota)); break;
              case 'call': comparison = String(a.call).localeCompare(String(b.call)); break;
              case 'sms': comparison = String(a.sms).localeCompare(String(b.sms)); break;
              case 'duration': comparison = String(a.duration).localeCompare(String(b.duration)); break;
              case 'price': comparison = a.price - b.price; break;
              case 'sellPrice': comparison = a.sellPrice - b.sellPrice; break;
              case 'profit': comparison = a.profit - b.profit; break;
              case 'pointBonus': comparison = a.pointBonus - b.pointBonus; break;
              default: comparison = 0;
            }
            return this.sortDirection === 'asc' ? comparison : -comparison;
          });
        },
      };
    }
  </script>
@endsection

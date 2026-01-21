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
              <tbody class="[&_tr]:border-b">
                <!-- Loading State -->
                <template x-if="loading">
                  <tr>
                    <td colspan="13" class="p-8 text-center">
                      <div class="flex flex-col items-center gap-3">
                        <svg class="h-8 w-8 animate-spin text-primary" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm text-muted-foreground">Memuat data katalog...</p>
                      </div>
                    </td>
                  </tr>
                </template>

                <!-- Empty State -->
                <template x-if="!loading && sortedPackages.length === 0">
                  <tr>
                    <td colspan="13" class="p-8 text-center">
                      <div class="flex flex-col items-center gap-3">
                        <svg class="h-12 w-12 text-muted-foreground/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-sm text-muted-foreground">Tidak ada paket ditemukan</p>
                      </div>
                    </td>
                  </tr>
                </template>

                <!-- Package Rows -->
                <template x-for="pkg in sortedPackages" :key="pkg.id">
                  <tr x-show="!loading" class="border-b transition-colors hover:bg-muted/50">
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
      const rawPackages = @json($packages ?? []);
      console.log('Raw packages from server:', rawPackages);
      console.log('Total raw packages:', rawPackages.length);
      
      return {
        search: '',
        selectedProvider: '',
        sortKey: 'provider',
        sortDirection: 'asc',
        loading: false,
        packages: rawPackages.map(pkg => {
          console.log('Processing package:', pkg);
          // Transform database fields to catalog format
          return {
            id: pkg.id,
            provider: pkg.provider || '-',
            name: pkg.nama_paket || '-',
            type: pkg.tipe_paket || '-',
            duration: pkg.masa_aktif ? pkg.masa_aktif + ' hari' : '-',
            totalQuota: pkg.total_kuota || '-',
            mainQuota: pkg.kuota_utama || '-',
            bonusQuota: pkg.kuota_bonus || '-',
            call: pkg.telp || '-',
            sms: pkg.sms || '-',
            price: parseInt(pkg.harga_modal) || 0,
            sellPrice: parseInt(pkg.harga_eup) || 0,
            profit: (parseInt(pkg.harga_eup) || 0) - (parseInt(pkg.harga_modal) || 0),
            pointBonus: parseInt(pkg.poin) || 0,
            isBestPromo: false // Could be based on profit or other criteria
          };
        }),
        init() {
          console.log('Catalog initialized');
          console.log('Total packages after transform:', this.packages.length);
          console.log('Transformed packages:', this.packages);
        },
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

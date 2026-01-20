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
          <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
            <h3 class="text-lg font-semibold">Daftar Paket</h3>
            <div class="relative w-full max-w-sm">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input type="text" placeholder="Cari paket/provider/kuota/durasi/harga" x-model="search" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full">
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
                      Tipe
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('quota')" class="inline-flex items-center gap-2">
                      Kuota
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('duration')" class="inline-flex items-center gap-2">
                      Durasi
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
                      Keuntungan
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">
                    <button @click="handleSort('affiliateFee')" class="inline-flex items-center gap-2">
                      Fee Affiliate
                      <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    </button>
                  </th>
                </tr>
              </thead>
              <tbody>
                <template x-if="loading">
                  <tr>
                    <td colspan="9" class="text-center py-12">
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
                        <div class="font-semibold" x-text="pkg.name"></div>
                        <template x-if="pkg.isBestPromo">
                          <div class="mt-1">
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold tracking-wide text-primary">PROMO TERBAIK</span>
                          </div>
                        </template>
                      </td>
                      <td class="p-4 align-middle">
                        <span class="text-xs uppercase tracking-wide text-muted-foreground" x-text="pkg.type"></span>
                      </td>
                      <td class="p-4 align-middle">
                        <div x-text="pkg.quota"></div>
                        <template x-if="pkg.quotaSub">
                          <div class="text-xs text-primary" x-text="pkg.quotaSub"></div>
                        </template>
                      </td>
                      <td class="p-4 align-middle" x-text="pkg.duration"></td>
                      <td class="p-4 align-middle text-right" x-text="formatRupiah(pkg.price)"></td>
                      <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(pkg.sellPrice)"></td>
                      <td class="p-4 align-middle text-right text-primary font-medium" x-text="'+' + formatRupiah(pkg.profit)"></td>
                      <td class="p-4 align-middle text-right text-primary font-medium" x-text="formatRupiah(pkg.affiliateFee)"></td>
                    </tr>
                  </template>
                </template>
                <template x-if="!loading && sortedPackages.length === 0">
                  <tr>
                    <td colspan="9" class="p-8 text-center text-muted-foreground">Tidak ada paket yang ditemukan</td>
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
        sortKey: 'provider',
        sortDirection: 'asc',
        loading: false,
        packages: [
          { id: 1, provider: 'AXIS', name: 'Internet 10 Hari', type: 'INTERNET', quota: 'Unlimited', quotaSub: '', duration: '10 hari', price: 224100, sellPrice: 249000, isBestPromo: true },
          { id: 2, provider: 'AXIS', name: 'Combo 10 Hari', type: 'INTERNET + TELP/SMS', quota: 'Unlimited', quotaSub: '', duration: '10 hari', price: 308700, sellPrice: 343000, isBestPromo: true },
          { id: 3, provider: 'AXIS', name: 'Combo 20 Hari', type: 'INTERNET + TELP/SMS', quota: 'Unlimited', quotaSub: '', duration: '20 hari', price: 393300, sellPrice: 437000, isBestPromo: true },
          { id: 4, provider: 'AXIS', name: 'Combo 40 Hari', type: 'INTERNET + TELP/SMS', quota: 'Unlimited', quotaSub: '', duration: '40 hari', price: 520200, sellPrice: 578000, isBestPromo: true },
          { id: 5, provider: 'AXIS', name: 'Internet 45 Hari 2GB', type: 'INTERNET', quota: '1 GB', quotaSub: '+ 1 GB Indo', duration: '45 hari', price: 139500, sellPrice: 155000, isBestPromo: false },
        ].map(p => {
          const profit = p.sellPrice - p.price;
          const affiliateFee = Math.round(profit * 0.3);
          return { ...p, profit, affiliateFee };
        }),
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
        get filteredPackages() {
          if (!this.search.trim()) return this.packages;
          const query = this.search.toLowerCase();
          return this.packages.filter(pkg =>
            String(pkg.name).toLowerCase().includes(query) ||
            String(pkg.provider).toLowerCase().includes(query) ||
            String(pkg.type).toLowerCase().includes(query) ||
            String(pkg.quota).toLowerCase().includes(query) ||
            String(pkg.duration).toLowerCase().includes(query) ||
            String(pkg.price).includes(query) ||
            String(pkg.sellPrice).includes(query) ||
            String(pkg.affiliateFee).includes(query)
          );
        },
        get sortedPackages() {
          return [...this.filteredPackages].sort((a, b) => {
            let comparison = 0;
            switch (this.sortKey) {
              case 'provider': comparison = a.provider.localeCompare(b.provider); break;
              case 'name': comparison = a.name.localeCompare(b.name); break;
              case 'type': comparison = String(a.type).localeCompare(String(b.type)); break;
              case 'quota': comparison = String(a.quota).localeCompare(String(b.quota)); break;
              case 'duration': comparison = String(a.duration).localeCompare(String(b.duration)); break;
              case 'price': comparison = a.price - b.price; break;
              case 'sellPrice': comparison = a.sellPrice - b.sellPrice; break;
              case 'profit': comparison = a.profit - b.profit; break;
              case 'affiliateFee': comparison = a.affiliateFee - b.affiliateFee; break;
              default: comparison = 0;
            }
            return this.sortDirection === 'asc' ? comparison : -comparison;
          });
        },
      };
    }
  </script>
@endsection

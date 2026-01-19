@extends('agent.layout')

@section('title', 'Katalog Harga - Kuotaumroh.id')

@section('content')
  <div x-data="catalogApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <h1 class="text-3xl font-bold tracking-tight">Katalog Harga</h1>
        <p class="text-muted-foreground mt-2">Daftar paket kuota umroh yang tersedia</p>
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
                </tr>
              </thead>
              <tbody>
                <template x-if="loading">
                  <tr>
                    <td colspan="7" class="text-center py-12">
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
                      <td class="p-4 align-middle"><span class="badge badge-secondary" x-text="pkg.provider"></span></td>
                      <td class="p-4 align-middle font-medium" x-text="pkg.name"></td>
                      <td class="p-4 align-middle" x-text="pkg.quota"></td>
                      <td class="p-4 align-middle" x-text="pkg.duration"></td>
                      <td class="p-4 align-middle text-right" x-text="formatRupiah(pkg.price)"></td>
                      <td class="p-4 align-middle text-right font-medium" x-text="formatRupiah(pkg.sellPrice)"></td>
                      <td class="p-4 align-middle text-right text-primary font-medium">+<span x-text="formatRupiah(pkg.profit)"></span></td>
                    </tr>
                  </template>
                </template>
                <template x-if="!loading && sortedPackages.length === 0">
                  <tr>
                    <td colspan="7" class="p-8 text-center text-muted-foreground">Tidak ada paket yang ditemukan</td>
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
          { id: 1, provider: 'TELKOMSEL', name: 'Internet Umroh 10GB', quota: '10GB', duration: '10 Hari', price: 150000, sellPrice: 175000 },
          { id: 2, provider: 'INDOSAT', name: 'Internet Umroh 15GB', quota: '15GB', duration: '12 Hari', price: 165000, sellPrice: 190000 },
          { id: 3, provider: 'XL', name: 'Internet Umroh 20GB', quota: '20GB', duration: '14 Hari', price: 185000, sellPrice: 215000 },
        ].map(p => ({ ...p, profit: p.sellPrice - p.price })),
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
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
            String(pkg.quota).toLowerCase().includes(query) ||
            String(pkg.duration).toLowerCase().includes(query) ||
            String(pkg.price).includes(query) ||
            String(pkg.sellPrice).includes(query)
          );
        },
        get sortedPackages() {
          return [...this.filteredPackages].sort((a, b) => {
            let comparison = 0;
            switch (this.sortKey) {
              case 'provider': comparison = a.provider.localeCompare(b.provider); break;
              case 'name': comparison = a.name.localeCompare(b.name); break;
              case 'quota': comparison = String(a.quota).localeCompare(String(b.quota)); break;
              case 'duration': comparison = String(a.duration).localeCompare(String(b.duration)); break;
              case 'price': comparison = a.price - b.price; break;
              case 'sellPrice': comparison = a.sellPrice - b.sellPrice; break;
              case 'profit': comparison = a.profit - b.profit; break;
              default: comparison = 0;
            }
            return this.sortDirection === 'asc' ? comparison : -comparison;
          });
        },
      };
    }
  </script>
@endsection

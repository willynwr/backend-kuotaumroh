@extends('layouts.admin')

@section('title', 'Kelola Paket')

@section('content')
<div x-data="packagesPage()">
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <div class="mb-6 flex items-center justify-between">
      <div class="flex items-start gap-4">
        <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h1 class="text-3xl font-bold tracking-tight">Kelola Paket</h1>
          <p class="text-muted-foreground mt-1">Kelola paket kuota umroh dari berbagai provider</p>
        </div>
      </div>
      <button @click="openAddModal()" class="h-10 px-4 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
        + Tambah Paket
      </button>
    </div>

    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
          <div class="relative w-full sm:w-auto">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" x-model="search" placeholder="Cari paket..." class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b">
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Provider</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Paket</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Kuota</th>
                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Harga Modal</th>
                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Harga Jual</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="pkg in filteredPackages" :key="pkg.id">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <td class="p-4 align-middle font-medium" x-text="pkg.provider"></td>
                  <td class="p-4 align-middle" x-text="pkg.nama_produk"></td>
                  <td class="p-4 align-middle text-center" x-text="pkg.kuota"></td>
                  <td class="p-4 align-middle text-right" x-text="formatRupiah(pkg.harga_modal)"></td>
                  <td class="p-4 align-middle text-right font-semibold" x-text="formatRupiah(pkg.harga_jual)"></td>
                  <td class="p-4 align-middle text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="pkg.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" x-text="pkg.status === 'active' ? 'Aktif' : 'Nonaktif'"></span>
                  </td>
                  <td class="p-4 align-middle text-center">
                    <div class="flex justify-center gap-2">
                      <button @click="editPackage(pkg)" class="h-8 px-3 border rounded text-xs hover:bg-muted">Edit</button>
                      <button @click="toggleStatus(pkg)" class="h-8 px-3 border rounded text-xs" :class="pkg.status === 'active' ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50'" x-text="pkg.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'"></button>
                    </div>
                  </td>
                </tr>
              </template>
              <tr x-show="filteredPackages.length === 0">
                <td colspan="7" class="p-8 text-center text-muted-foreground">Tidak ada paket ditemukan</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function packagesPage() {
    return {
      packages: @json($packages ?? []),
      search: '',

      get filteredPackages() {
        if (!this.search) return this.packages;
        return this.packages.filter(pkg => 
          pkg.nama_produk.toLowerCase().includes(this.search.toLowerCase()) ||
          pkg.provider.toLowerCase().includes(this.search.toLowerCase())
        );
      },

      formatRupiah(amount) {
        return `Rp ${parseInt(amount).toLocaleString('id-ID')}`;
      },


      editPackage(pkg) {
        window.location.href = `/admin/packages/${pkg.id}/edit`;
      },

      async toggleStatus(pkg) {
        if (!confirm(`Apakah Anda yakin ingin ${pkg.status === 'active' ? 'menonaktifkan' : 'mengaktifkan'} paket ini?`)) return;
        
        try {
          const response = await fetch(`/admin/packages/${pkg.id}/toggle-status`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          if (response.ok) {
            pkg.status = pkg.status === 'active' ? 'inactive' : 'active';
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan');
        }
      },

      init() {}
    }
  }
</script>
@endpush

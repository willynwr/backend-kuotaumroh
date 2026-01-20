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
      <button @click="showAddModal = true" class="h-10 px-4 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
        + Tambah Paket
      </button>
    </div>

    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <!-- Filters -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
          <div class="flex flex-wrap gap-2">
            <button @click="providerFilter = 'all'" class="h-9 px-4 rounded-md text-sm font-medium" :class="providerFilter === 'all' ? 'bg-primary text-white' : 'border hover:bg-muted'">Semua</button>
            <template x-for="provider in providers" :key="provider">
              <button @click="providerFilter = provider" class="h-9 px-4 rounded-md text-sm font-medium" :class="providerFilter === provider ? 'bg-primary text-white' : 'border hover:bg-muted'" x-text="provider"></button>
            </template>
          </div>
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
              <!-- Loading State -->
              <template x-if="loading">
                <tr>
                  <td colspan="7" class="p-8 text-center text-muted-foreground">
                    <div class="flex items-center justify-center gap-3">
                      <span>Memuat paket...</span>
                    </div>
                  </td>
                </tr>
              </template>
              <!-- Package List -->
              <template x-if="!loading" x-for="pkg in filteredPackages" :key="pkg.id">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <td class="p-4 align-middle font-medium" x-text="pkg.provider"></td>
                  <td class="p-4 align-middle" x-text="pkg.nama_produk"></td>
                  <td class="p-4 align-middle text-center" x-text="pkg.kuota"></td>
                  <td class="p-4 align-middle text-right" x-text="formatRupiah(pkg.harga_modal)"></td>
                  <td class="p-4 align-middle text-right font-semibold" x-text="formatRupiah(pkg.harga_jual)"></td>
                  {{-- <td class="p-4 align-middle text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="pkg.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" x-text="pkg.status === 'active' ? 'Aktif' : 'Nonaktif'"></span>
                  </td> --}}
                </tr>
              </template>
              <!-- Empty State -->
              <template x-if="!loading && filteredPackages.length === 0">
                <tr>
                  <td colspan="7" class="p-8 text-center text-muted-foreground">Tidak ada paket ditemukan</td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <!-- Add/Edit Modal -->
  <div x-show="showAddModal || editingPackage" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeModal()">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h3 class="text-lg font-semibold mb-4" x-text="editingPackage ? 'Edit Paket' : 'Tambah Paket Baru'"></h3>
      <form @submit.prevent="savePackage()">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Provider</label>
            <select x-model="formData.provider" class="w-full h-10 rounded-md border px-3">
              <template x-for="p in providers" :key="p"><option :value="p" x-text="p"></option></template>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Nama Paket</label>
            <input type="text" x-model="formData.nama_produk" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Kuota</label>
            <input type="text" x-model="formData.kuota" placeholder="e.g. 10GB" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Harga Modal</label>
              <input type="number" x-model="formData.harga_modal" class="w-full h-10 rounded-md border px-3" required>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Harga Jual</label>
              <input type="number" x-model="formData.harga_jual" class="w-full h-10 rounded-md border px-3" required>
            </div>
          </div>
        </div>
        <div class="flex justify-end gap-2 mt-6">
          <button type="button" @click="closeModal()" class="h-10 px-4 border rounded-md">Batal</button>
          <button type="submit" class="h-10 px-4 bg-primary text-white rounded-md">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Confirm Modal -->
  <div x-show="confirmModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40" @click="closeConfirmModal()"></div>
    <div x-transition class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6">
      <h3 class="text-lg font-semibold text-slate-900" x-text="confirmModalTitle"></h3>
      <p class="mt-2 text-sm text-muted-foreground" x-text="confirmModalMessage"></p>
      <div class="mt-6 flex items-center justify-end gap-3">
        <button @click="closeConfirmModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">
          Batal
        </button>
        <button
          @click="confirmToggle()"
          :class="selectedPackage?.status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
          class="h-9 px-4 rounded-md text-white text-sm font-medium transition-colors"
        >
          Konfirmasi
        </button>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div x-show="toastVisible" x-transition class="toast">
    <div class="font-semibold mb-1" x-text="toastTitle"></div>
    <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function packagesPage() {
    return {
      packages: @json($packages ?? []),
      providers: [],
      search: '',
      providerFilter: 'all',
      loading: false,
      showAddModal: false,
      editingPackage: null,
      formData: { provider: '', nama_produk: '', kuota: '', harga_modal: '', harga_jual: '' },
      toastVisible: false,
      toastTitle: '',
      toastMessage: '',
      confirmModalOpen: false,
      confirmModalTitle: '',
      confirmModalMessage: '',
      selectedPackage: null,

      get filteredPackages() {
        return this.packages.filter(p => {
          const matchSearch = !this.search || 
            p.nama_produk.toLowerCase().includes(this.search.toLowerCase()) ||
            p.provider.toLowerCase().includes(this.search.toLowerCase());
          const matchProvider = this.providerFilter === 'all' || p.provider === this.providerFilter;
          return matchSearch && matchProvider;
        });
      },

      formatRupiah(amount) {
        return `Rp ${parseInt(amount).toLocaleString('id-ID')}`;
      },

      showToast(title, message) {
        this.toastTitle = title;
        this.toastMessage = message;
        this.toastVisible = true;
        setTimeout(() => {
          this.toastVisible = false;
        }, 3000);
      },

      editPackage(pkg) {
        this.editingPackage = pkg;
        this.formData = { ...pkg };
      },

      openConfirmModal(pkg) {
        this.selectedPackage = pkg;
        if (pkg.status === 'active') {
          this.confirmModalTitle = `Nonaktifkan ${pkg.nama_produk}?`;
          this.confirmModalMessage = 'Paket tidak akan muncul di daftar aktif.';
        } else {
          this.confirmModalTitle = `Aktifkan ${pkg.nama_produk}?`;
          this.confirmModalMessage = 'Paket akan tersedia untuk pemesanan.';
        }
        this.confirmModalOpen = true;
      },

      closeConfirmModal() {
        this.confirmModalOpen = false;
        this.selectedPackage = null;
      },

      confirmToggle() {
        if (!this.selectedPackage) return;
        this.toggleStatus(this.selectedPackage);
        this.closeConfirmModal();
      },

      closeModal() {
        this.showAddModal = false;
        this.editingPackage = null;
        this.formData = { provider: this.providers[0] || '', nama_produk: '', kuota: '', harga_modal: '', harga_jual: '' };
      },

      savePackage() {
        if (this.editingPackage) {
          Object.assign(this.editingPackage, this.formData);
          this.showToast('Updated', this.formData.nama_produk);
        } else {
          this.packages.push({ id: Date.now(), ...this.formData, status: 'active' });
          this.showToast('Added', this.formData.nama_produk);
        }
        this.closeModal();
      },

      async toggleStatus(pkg) {
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
            this.showToast('Berhasil', `${pkg.nama_produk} status diubah`);
          }
        } catch (error) {
          console.error('Error:', error);
          this.showToast('Error', 'Gagal mengubah status paket');
        }
      },

      init() {
        // Extract unique providers
        this.providers = [...new Set(this.packages.map(p => p.provider))];
        if (this.providers.length > 0) {
          this.formData.provider = this.providers[0];
        }
      }
    }
  }
</script>
@endpush

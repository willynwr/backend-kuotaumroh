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
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-end gap-4 mb-6">
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
              <tr class="border-b bg-muted/50">
                <th class="px-4 py-3 text-left text-xs font-semibold text-muted-foreground">Aksi</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-muted-foreground">Nama Paket</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-muted-foreground">Tipe Paket</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-muted-foreground">Masa Aktif</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Total Kuota</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Kuota Utama</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Kuota Bonus</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Telp</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">SMS</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Harga Modal</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loading State -->
              <template x-if="loading">
                <tr>
                  <td colspan="10" class="p-8 text-center text-muted-foreground">
                    <div class="flex items-center justify-center gap-3">
                      <span>Memuat paket...</span>
                    </div>
                  </td>
                </tr>
              </template>
              <!-- Package List -->
              <template x-if="!loading">
                <template x-for="pkg in filteredPackages" :key="pkg.id">
                  <tr class="border-b transition-colors hover:bg-muted/30">
                  <td class="px-4 py-3 text-sm align-middle">
                    <div class="flex items-center gap-2">
                      <button @click="editPackage(pkg)" class="inline-flex items-center justify-center w-8 h-8 rounded hover:bg-blue-50 text-blue-600" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                      <button @click="deletePackage(pkg)" class="inline-flex items-center justify-center w-8 h-8 rounded hover:bg-red-50 text-red-600" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-sm align-middle font-medium" x-text="pkg.nama_paket"></td>
                  <td class="px-4 py-3 text-sm align-middle" x-text="pkg.tipe_paket"></td>
                  <td class="px-4 py-3 text-sm align-middle text-center" x-text="pkg.masa_aktif"></td>
                  <td class="px-4 py-3 text-sm align-middle text-right" x-text="pkg.total_kuota"></td>
                  <td class="px-4 py-3 text-sm align-middle text-right" x-text="pkg.kuota_utama"></td>
                  <td class="px-4 py-3 text-sm align-middle text-right" x-text="pkg.kuota_bonus"></td>
                  <td class="px-4 py-3 text-sm align-middle text-right" x-text="pkg.telp"></td>
                  <td class="px-4 py-3 text-sm align-middle text-right" x-text="pkg.sms"></td>
                  <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(pkg.harga_modal)"></td>
                </tr>                </template>              </template>
              <!-- Empty State -->
              <template x-if="!loading && filteredPackages.length === 0">
                <tr>
                  <td colspan="10" class="p-8 text-center text-muted-foreground">Tidak ada paket ditemukan</td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <!-- Add/Edit Modal -->
  <div x-show="showAddModal || editingPackage" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto" @click.self="closeModal()">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 my-8">
      <h3 class="text-lg font-semibold mb-4" x-text="editingPackage ? 'Edit Paket' : 'Tambah Paket Baru'"></h3>
      <form @submit.prevent="savePackage()">
        <div class="grid grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2">
          <div>
            <label class="block text-sm font-medium mb-1">Nama Paket</label>
            <input type="text" x-model="formData.nama_paket" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Tipe Paket</label>
            <input type="text" x-model="formData.tipe_paket" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Masa Aktif (hari)</label>
            <input type="number" x-model="formData.masa_aktif" placeholder="30" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Total Kuota</label>
            <input type="number" x-model="formData.total_kuota" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Kuota Utama</label>
            <input type="number" x-model="formData.kuota_utama" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Kuota Bonus</label>
            <input type="number" x-model="formData.kuota_bonus" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Telp (menit)</label>
            <input type="number" x-model="formData.telp" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">SMS</label>
            <input type="number" x-model="formData.sms" class="w-full h-10 rounded-md border px-3" required>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Harga Modal</label>
            <input type="number" x-model="formData.harga_modal" class="w-full h-10 rounded-md border px-3" required>
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
      search: '',
      loading: false,
      showAddModal: false,
      editingPackage: null,
      formData: {
        nama_paket: '',
        tipe_paket: '',
        masa_aktif: '',
        total_kuota: '',
        kuota_utama: '',
        kuota_bonus: '',
        telp: '',
        sms: '',
        harga_modal: ''
      },
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
            (p.nama_paket && p.nama_paket.toLowerCase().includes(this.search.toLowerCase())) ||
            (p.tipe_paket && p.tipe_paket.toLowerCase().includes(this.search.toLowerCase()));
          return matchSearch;
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
        this.showAddModal = true;
      },

      async deletePackage(pkg) {
        if (!confirm(`Apakah Anda yakin ingin menghapus paket "${pkg.nama_paket}"?`)) {
          return;
        }

        try {
          const response = await fetch(`/admin/packages/${pkg.id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          if (response.ok) {
            this.packages = this.packages.filter(p => p.id !== pkg.id);
            this.showToast('Berhasil', `Paket "${pkg.nama_paket}" telah dihapus`);
          } else {
            this.showToast('Error', 'Gagal menghapus paket');
          }
        } catch (error) {
          console.error('Error:', error);
          this.showToast('Error', 'Gagal menghapus paket');
        }
      },

      openConfirmModal(pkg) {
        this.selectedPackage = pkg;
        if (pkg.status === 'active') {
          this.confirmModalTitle = `Nonaktifkan ${pkg.nama_paket}?`;
          this.confirmModalMessage = 'Paket tidak akan muncul di daftar aktif.';
        } else {
          this.confirmModalTitle = `Aktifkan ${pkg.nama_paket}?`;
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
        this.formData = {
          nama_paket: '',
          tipe_paket: '',
          masa_aktif: '',
          total_kuota: '',
          kuota_utama: '',
          kuota_bonus: '',
          telp: '',
          sms: '',
          harga_modal: ''
        };
      },

      savePackage() {
        if (this.editingPackage) {
          Object.assign(this.editingPackage, this.formData);
          this.showToast('Updated', this.formData.nama_paket);
        } else {
          this.packages.push({ id: Date.now(), ...this.formData, status: 'active' });
          this.showToast('Added', this.formData.nama_paket);
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
            this.showToast('Berhasil', `${pkg.nama_paket} status diubah`);
          }
        } catch (error) {
          console.error('Error:', error);
          this.showToast('Error', 'Gagal mengubah status paket');
        }
      },

      init() {
        // Initialize
      }
    }
  }
</script>
@endpush

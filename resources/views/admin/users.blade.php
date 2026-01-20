@extends('layouts.admin')

@section('title', 'Kelola Users')

@section('content')
<div x-data="usersPage()">
  <!-- Header Component -->
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <!-- Page Header -->
    <div class="mb-6 flex items-start gap-4">
      <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Kelola Users</h1>
        <p class="text-muted-foreground mt-1">Lihat dan kelola semua pengguna platform</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 md:grid-cols-3 mb-6">
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <p class="text-sm text-muted-foreground">Total Affiliate</p>
        <p class="text-3xl font-bold mt-1" x-text="stats.affiliates"></p>
        <div class="mt-3 grid grid-cols-2 gap-4 text-xs text-muted-foreground">
          <span>Affiliate Aktif</span>
          <span>Banned</span>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm font-semibold">
          <span x-text="stats.affiliatesActive"></span>
          <span class="text-destructive" x-text="stats.affiliatesBanned"></span>
        </div>
      </div>
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <p class="text-sm text-muted-foreground">Total Travel Agent</p>
        <p class="text-3xl font-bold mt-1" x-text="stats.agents"></p>
        <div class="mt-3 grid grid-cols-2 gap-4 text-xs text-muted-foreground">
          <span>Agent Aktif</span>
          <span>Banned</span>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm font-semibold">
          <span x-text="stats.agentsActive"></span>
          <span class="text-destructive" x-text="stats.agentsBanned"></span>
        </div>
      </div>
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <p class="text-sm text-muted-foreground">Total Freelance</p>
        <p class="text-3xl font-bold mt-1" x-text="stats.freelance"></p>
        <div class="mt-3 grid grid-cols-2 gap-4 text-xs text-muted-foreground">
          <span>Freelance Aktif</span>
          <span>Banned</span>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm font-semibold">
          <span x-text="stats.freelanceActive"></span>
          <span class="text-destructive" x-text="stats.freelanceBanned"></span>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <!-- Filters -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
          <div class="flex items-center gap-3">
            <h3 class="text-lg font-semibold">Daftar Users</h3>
            <button x-show="roleFilter === 'affiliate'" @click="openAddModal()" 
              class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors inline-flex items-center gap-2">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Tambah Affiliate
            </button>
            <button x-show="roleFilter === 'freelance'" @click="openAddModal()" 
              class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors inline-flex items-center gap-2">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Tambah Freelance
            </button>
            <button x-show="roleFilter === 'agent'" @click="openAddModal()" 
              class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors inline-flex items-center gap-2">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Tambah Travel Agent
            </button>
          </div>
          <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input type="text" x-model="search" placeholder="Cari nama/email" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
            </div>
            <select x-model="statusFilter" class="h-10 rounded-md border border-input bg-background px-3 text-sm">
              <option value="all">Semua Status</option>
              <option value="active">Aktif</option>
              <option value="reject">Ditolak</option>
            </select>
          </div>
        </div>

        <div class="mb-6 flex gap-2 border-b">
          <button @click="roleFilter = 'affiliate'; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'affiliate' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Affiliate</button>
          <button @click="roleFilter = 'agent'; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'agent' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Travel Agent</button>
          <button @click="roleFilter = 'freelance'; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'freelance' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Freelance</button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b whitespace-nowrap">
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal Daftar</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Link Referral</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="user in paginatedUsers" :key="user.id">
                <tr class="border-b transition-colors hover:bg-muted/50 whitespace-nowrap">
                  <td class="p-4 align-middle font-medium" x-text="user.name"></td>
                  <td class="p-4 align-middle text-muted-foreground" x-text="user.email"></td>
                  <td class="p-4 align-middle" x-text="formatDate(user.created_at)"></td>
                  <td class="p-4 align-middle text-sm">
                    <a x-show="user.referral_code" :href="getReferralLink(user)" target="_blank" x-text="user.referral_code" class="text-primary hover:underline"></a>
                    <span x-show="!user.referral_code" class="text-muted-foreground">-</span>
                  </td>
                  <td class="p-4 align-middle text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
                      'bg-green-100 text-green-800': user.status === 'active',
                      'bg-red-100 text-red-800': user.status === 'reject',
                      'bg-yellow-100 text-yellow-800': user.status === 'pending'
                    }" x-text="user.status === 'active' ? 'Aktif' : (user.status === 'reject' ? 'Ditolak' : 'Pending')"></span>
                  </td>
                  <td class="p-4 align-middle text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button @click="viewUser(user)" class="text-sm text-primary hover:underline">Detail</button>
                      <button x-show="user.status !== 'reject'" @click="toggleBan(user)" class="text-sm text-destructive hover:underline" x-text="user.status === 'active' ? 'Ban' : 'Aktifkan'"></button>
                    </div>
                  </td>
                </tr>
              </template>
              <tr x-show="paginatedUsers.length === 0">
                <td colspan="6" class="p-8 text-center text-muted-foreground">Tidak ada data user</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4">
          <div class="text-sm text-muted-foreground">
            Menampilkan <span x-text="(currentPage - 1) * itemsPerPage + 1"></span> - <span x-text="Math.min(currentPage * itemsPerPage, filteredUsers.length)"></span> dari <span x-text="filteredUsers.length"></span> data
          </div>
          <div class="flex gap-2">
            <button @click="currentPage--" :disabled="currentPage === 1" class="h-9 px-4 rounded-md border border-input bg-background text-sm font-medium disabled:opacity-50">
              Previous
            </button>
            <button @click="currentPage++" :disabled="currentPage * itemsPerPage >= filteredUsers.length" class="h-9 px-4 rounded-md border border-input bg-background text-sm font-medium disabled:opacity-50">
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  @include('partials.form-addaffiliate')
  @include('partials.form-addfreelance')
  @include('partials.form-addtravelagent')
</div>
@endsection

@push('scripts')
<script>
  function usersPage() {
    return {
      users: @json($users ?? []),
      oldInput: @json(old()),
      stats: {
        affiliates: {{ $stats['affiliates'] ?? 0 }},
        affiliatesActive: {{ $stats['affiliatesActive'] ?? 0 }},
        affiliatesBanned: {{ $stats['affiliatesBanned'] ?? 0 }},
        agents: {{ $stats['agents'] ?? 0 }},
        agentsActive: {{ $stats['agentsActive'] ?? 0 }},
        agentsBanned: {{ $stats['agentsBanned'] ?? 0 }},
        freelance: {{ $stats['freelance'] ?? 0 }},
        freelanceActive: {{ $stats['freelanceActive'] ?? 0 }},
        freelanceBanned: {{ $stats['freelanceBanned'] ?? 0 }}
      },
      roleFilter: 'affiliate',
      toastVisible: false,
      toastTitle: '',
      toastMessage: '',
      addAffiliateModalOpen: false,
      addFreelanceModalOpen: false,
      addTravelAgentModalOpen: false,
      confirmAddModalOpen: false,
      confirmAddFreelanceModalOpen: false,
      confirmAddTravelAgentModalOpen: false,
      newAffiliate: { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '' },
      newFreelance: { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '' },
      newTravelAgent: { full_name: '', email: '', phone: '', travel_name: '', travel_type: '', travel_member: '', kategori_agent: '', province: '', city: '', address: '' },
      selectedDownline: null,
      provinces: [],
      provinceCodes: {},
      cities: [],
      citiesFreelance: [],
      citiesTravelAgent: [],
      statusFilter: 'all',
      search: '',
      currentPage: 1,
      itemsPerPage: 10,

      init() {
        const params = new URLSearchParams(window.location.search);
        const tab = params.get('tab');
        if (tab === 'affiliate' || tab === 'agent' || tab === 'freelance') {
          this.roleFilter = tab;
        }

        this.loadProvinces();

        const oldForm = @json(old('_form'));
        if (oldForm === 'affiliate') {
          this.roleFilter = 'affiliate';
          this.openAddAffiliateModal(true);
        }
        if (oldForm === 'freelance') {
          this.roleFilter = 'freelance';
          this.openAddFreelanceModal(true);
        }
        if (oldForm === 'agent') {
          this.roleFilter = 'agent';
          this.openAddTravelAgentModal(true);
        }
      },

      showToast(title, message) {
        this.toastTitle = title;
        this.toastMessage = message;
        this.toastVisible = true;
        setTimeout(() => { this.toastVisible = false; }, 3000);
      },

      async loadProvinces() {
        try {
          const response = await fetch('/wilayah/provinces.json');
          const data = await response.json();
          if (data && data.data) {
            this.provinces = data.data.map(p => p.name).sort();
            const codes = {};
            data.data.forEach(p => { codes[p.name] = p.code; });
            this.provinceCodes = codes;
          }
        } catch (error) {
          console.error('Error loading provinces:', error);
          this.provinces = [];
          this.provinceCodes = {};
        }
      },

      async loadCities(provinceCode) {
        const response = await fetch(`/wilayah/regencies-${provinceCode}.json`);
        const data = await response.json();
        if (data && data.data) {
          return data.data.map(c => c.name).sort();
        }
        return [];
      },

      async handleProvinceChange() {
        this.newAffiliate.kab_kota = '';
        this.cities = [];
        const provinceCode = this.provinceCodes[this.newAffiliate.provinsi];
        if (!provinceCode) return;
        try {
          this.cities = await this.loadCities(provinceCode);
        } catch {
          this.cities = [];
        }
      },

      async handleProvinceChangeFreelance() {
        this.newFreelance.kab_kota = '';
        this.citiesFreelance = [];
        const provinceCode = this.provinceCodes[this.newFreelance.provinsi];
        if (!provinceCode) return;
        try {
          this.citiesFreelance = await this.loadCities(provinceCode);
        } catch {
          this.citiesFreelance = [];
        }
      },

      async handleProvinceChangeTravelAgent() {
        this.newTravelAgent.city = '';
        this.citiesTravelAgent = [];
        const provinceCode = this.provinceCodes[this.newTravelAgent.province];
        if (!provinceCode) return;
        try {
          this.citiesTravelAgent = await this.loadCities(provinceCode);
        } catch {
          this.citiesTravelAgent = [];
        }
      },

      get downlines() {
        return this.users
          .filter(u => u.role === 'affiliate' || u.role === 'freelance')
          .map(u => ({
            id: u.id,
            name: u.name,
            email: u.email,
            type: u.role === 'affiliate' ? 'Affiliate' : 'Freelance',
          }));
      },

      selectDownline(downline) {
        this.selectedDownline = downline;
      },

      get filteredUsers() {
        return this.users.filter(user => {
          const matchesRole = user.role === this.roleFilter;
          const matchesStatus = this.statusFilter === 'all' || user.status === this.statusFilter;
          const matchesSearch = !this.search || 
            user.name.toLowerCase().includes(this.search.toLowerCase()) ||
            (user.email && user.email.toLowerCase().includes(this.search.toLowerCase()));
          return matchesRole && matchesStatus && matchesSearch;
        });
      },

      get paginatedUsers() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.filteredUsers.slice(start, end);
      },

      formatDate(date) {
        if (!date) return '-';
        return new Date(date).toLocaleDateString('id-ID', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        });
      },

      getReferralLink(user) {
        return `{{ url('/') }}?ref=${user.referral_code}`;
      },

      openAddModal() {
        if (this.roleFilter === 'affiliate') return this.openAddAffiliateModal(false);
        if (this.roleFilter === 'freelance') return this.openAddFreelanceModal(false);
        if (this.roleFilter === 'agent') return this.openAddTravelAgentModal(false);
      },

      openAddAffiliateModal(fromOldInput) {
        this.newAffiliate = {
          nama: fromOldInput ? (this.oldInput.nama || '') : '',
          email: fromOldInput ? (this.oldInput.email || '') : '',
          no_wa: fromOldInput ? (String(this.oldInput.no_wa || '').replace(/^62/, '').replace(/^0/, '')) : '',
          provinsi: fromOldInput ? (this.oldInput.provinsi || '') : '',
          kab_kota: fromOldInput ? (this.oldInput.kab_kota || '') : '',
          alamat_lengkap: fromOldInput ? (this.oldInput.alamat_lengkap || '') : '',
          link_referral: fromOldInput ? (this.oldInput.link_referral || '') : '',
        };
        this.addAffiliateModalOpen = true;
        if (this.newAffiliate.provinsi) {
          this.handleProvinceChange();
        }
      },

      closeAddAffiliateModal() {
        this.addAffiliateModalOpen = false;
        this.confirmAddModalOpen = false;
        this.newAffiliate = { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '' };
        this.cities = [];
      },

      openConfirmAddModal() {
        if (!this.newAffiliate.nama || !this.newAffiliate.email || !this.newAffiliate.no_wa ||
          !this.newAffiliate.provinsi || !this.newAffiliate.kab_kota ||
          !this.newAffiliate.alamat_lengkap || !this.newAffiliate.link_referral) {
          this.showToast('Error', 'Mohon lengkapi semua field yang wajib diisi');
          return;
        }
        this.confirmAddModalOpen = true;
      },

      closeConfirmAddModal() {
        this.confirmAddModalOpen = false;
      },

      confirmAddAffiliate() {
        this.confirmAddModalOpen = false;
        if (this.$refs.addAffiliateForm) this.$refs.addAffiliateForm.submit();
      },

      openAddFreelanceModal(fromOldInput) {
        this.newFreelance = {
          nama: fromOldInput ? (this.oldInput.nama || '') : '',
          email: fromOldInput ? (this.oldInput.email || '') : '',
          no_wa: fromOldInput ? (String(this.oldInput.no_wa || '').replace(/^62/, '').replace(/^0/, '')) : '',
          provinsi: fromOldInput ? (this.oldInput.provinsi || '') : '',
          kab_kota: fromOldInput ? (this.oldInput.kab_kota || '') : '',
          alamat_lengkap: fromOldInput ? (this.oldInput.alamat_lengkap || '') : '',
          link_referral: fromOldInput ? (this.oldInput.link_referral || '') : '',
        };
        this.addFreelanceModalOpen = true;
        if (this.newFreelance.provinsi) {
          this.handleProvinceChangeFreelance();
        }
      },

      closeAddFreelanceModal() {
        this.addFreelanceModalOpen = false;
        this.confirmAddFreelanceModalOpen = false;
        this.newFreelance = { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '' };
        this.citiesFreelance = [];
      },

      openConfirmAddFreelanceModal() {
        if (!this.newFreelance.nama || !this.newFreelance.email || !this.newFreelance.no_wa ||
          !this.newFreelance.provinsi || !this.newFreelance.kab_kota ||
          !this.newFreelance.alamat_lengkap || !this.newFreelance.link_referral) {
          this.showToast('Error', 'Mohon lengkapi semua field yang wajib diisi');
          return;
        }
        this.confirmAddFreelanceModalOpen = true;
      },

      closeConfirmAddFreelanceModal() {
        this.confirmAddFreelanceModalOpen = false;
      },

      confirmAddFreelance() {
        this.confirmAddFreelanceModalOpen = false;
        if (this.$refs.addFreelanceForm) this.$refs.addFreelanceForm.submit();
      },

      openAddTravelAgentModal(fromOldInput) {
        this.newTravelAgent = {
          full_name: fromOldInput ? (this.oldInput.nama_pic || '') : '',
          email: fromOldInput ? (this.oldInput.email || '') : '',
          phone: fromOldInput ? (String(this.oldInput.no_hp || '').replace(/^62/, '').replace(/^0/, '')) : '',
          travel_name: fromOldInput ? (this.oldInput.nama_travel || '') : '',
          travel_type: fromOldInput ? (this.oldInput.jenis_travel || '') : '',
          travel_member: fromOldInput ? (this.oldInput.total_traveller || '') : '',
          kategori_agent: fromOldInput ? (this.oldInput.kategori_agent || '') : '',
          province: fromOldInput ? (this.oldInput.provinsi || '') : '',
          city: fromOldInput ? (this.oldInput.kabupaten_kota || '') : '',
          address: fromOldInput ? (this.oldInput.alamat_lengkap || '') : '',
        };
        this.selectedDownline = null;
        this.addTravelAgentModalOpen = true;
        if (this.newTravelAgent.province) {
          this.handleProvinceChangeTravelAgent();
        }
      },

      closeAddTravelAgentModal() {
        this.addTravelAgentModalOpen = false;
        this.confirmAddTravelAgentModalOpen = false;
        this.newTravelAgent = { full_name: '', email: '', phone: '', travel_name: '', travel_type: '', travel_member: '', kategori_agent: '', province: '', city: '', address: '' };
        this.selectedDownline = null;
        this.citiesTravelAgent = [];
      },

      openConfirmAddTravelAgentModal() {
        if (!this.newTravelAgent.full_name || !this.newTravelAgent.email || !this.newTravelAgent.phone ||
          !this.newTravelAgent.travel_name || !this.newTravelAgent.travel_type || !this.newTravelAgent.travel_member ||
          !this.newTravelAgent.kategori_agent || !this.newTravelAgent.province || !this.newTravelAgent.city || !this.newTravelAgent.address) {
          this.showToast('Error', 'Mohon lengkapi semua field yang wajib diisi');
          return;
        }
        this.confirmAddTravelAgentModalOpen = true;
      },

      closeConfirmAddTravelAgentModal() {
        this.confirmAddTravelAgentModalOpen = false;
      },

      confirmAddTravelAgent() {
        this.confirmAddTravelAgentModalOpen = false;
        if (this.$refs.addTravelAgentForm) this.$refs.addTravelAgentForm.submit();
      },

      

      viewUser(user) {
        window.location.href = `/admin/users/${user.id}`;
      },

      async toggleBan(user) {
        if (!confirm(`Apakah Anda yakin ingin ${user.status === 'active' ? 'mem-ban' : 'mengaktifkan'} user ini?`)) {
          return;
        }

        try {
          const response = await fetch(`/admin/users/${user.id}/toggle-status`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              role: user.role
            })
          });

          if (response.ok) {
            const data = await response.json();
            user.status = data.status;
          } else {
            alert('Gagal mengubah status user');
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan');
        }
      }
    }
  }
</script>
@endpush

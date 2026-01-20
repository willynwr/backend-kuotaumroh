@extends('layouts.admin')

@section('title', 'Kelola Users')

@push('styles')
<!-- Leaflet.js CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

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
            <button x-show="roleFilter === 'newusers'" @click="openAddTravelAgentModal(false)" 
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
              <option value="pending">Pending</option>
              <option value="reject">Ditolak</option>
            </select>
          </div>
        </div>

        <div class="mb-6 flex gap-2 border-b">
          <button @click="roleFilter = 'affiliate'; statusFilter = 'all'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'affiliate' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Affiliate</button>
          <button @click="roleFilter = 'agent'; statusFilter = 'all'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'agent' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Travel Agent</button>
          <button @click="roleFilter = 'freelance'; statusFilter = 'all'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'freelance' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Freelance</button>
          <button @click="roleFilter = 'newusers'; statusFilter = 'all'; search = ''; currentPage = 1" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'newusers' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Pengguna Baru</button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <div x-show="roleFilter === 'affiliate'" x-cloak>
            @include('admin.partial-users.users-affiliate')
          </div>

          <div x-show="roleFilter === 'agent'" x-cloak>
            @include('admin.partial-users.users-travel-agent')
          </div>

          <div x-show="roleFilter === 'freelance'" x-cloak>
            @include('admin.partial-users.users-freelance')
          </div>

          <div x-show="roleFilter === 'newusers'" x-cloak>
            @include('admin.partial-users.users-new-users')
          </div>
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

  <div x-show="fileModalOpen" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40" @click="closeFileModal()"></div>
    <div class="relative z-10 w-full max-w-5xl rounded-lg bg-white shadow-lg p-4 max-h-[90vh] overflow-hidden"
      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
      <div class="flex items-center justify-between gap-4 pb-3 border-b">
        <div class="font-semibold text-slate-900">Preview File</div>
        <div class="flex items-center gap-3">
          <a :href="fileModalSrc" target="_blank" class="text-sm text-primary hover:underline">Buka tab baru</a>
          <button @click="closeFileModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Tutup</button>
        </div>
      </div>
      <div class="mt-4 h-[75vh] overflow-auto">
        <template x-if="fileModalType === 'pdf'">
          <iframe :src="fileModalSrc" class="w-full h-[75vh]" frameborder="0"></iframe>
        </template>
        <template x-if="fileModalType === 'image'">
          <img :src="fileModalSrc" class="max-w-full h-auto mx-auto" alt="Preview">
        </template>
      </div>
    </div>
  </div>

  <div x-show="userDetailModalOpen" x-cloak class="fixed inset-0 z-[65] flex items-center justify-center"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40" @click="closeUserDetail()"></div>
    <div class="relative z-10 w-full max-w-3xl rounded-lg bg-white shadow-lg p-6 max-h-[90vh] overflow-y-auto"
      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h3 class="text-lg font-semibold text-slate-900">Detail User</h3>
          <p class="mt-1 text-sm text-muted-foreground" x-text="selectedUser ? (selectedUser.role.toUpperCase() + ' • ' + selectedUser.name) : ''"></p>
        </div>
        <button @click="closeUserDetail()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Tutup</button>
      </div>

      <div class="mt-6 space-y-4" x-show="selectedUser">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <div class="text-xs text-muted-foreground">Email</div>
            <div class="text-sm font-medium" x-text="selectedUser?.email || '-'"> </div>
          </div>
          <div>
            <div class="text-xs text-muted-foreground">No. HP/WA</div>
            <div class="text-sm font-medium" x-text="selectedUser?.phone || '-'"> </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <div class="text-xs text-muted-foreground">Provinsi</div>
            <div class="text-sm font-medium" x-text="selectedUser?.province || '-'"> </div>
          </div>
          <div>
            <div class="text-xs text-muted-foreground">Kota/Kab</div>
            <div class="text-sm font-medium" x-text="selectedUser?.city || '-'"> </div>
          </div>
        </div>

        <div>
          <div class="text-xs text-muted-foreground">Alamat</div>
          <div class="text-sm font-medium" x-text="selectedUser?.address || '-'"> </div>
        </div>

        <template x-if="selectedUser?.role === 'agent'">
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <div class="text-xs text-muted-foreground">Kategori Agent</div>
                <div class="text-sm font-medium" x-text="selectedUser?.agent_category || '-'"> </div>
              </div>
              <div>
                <div class="text-xs text-muted-foreground">Total Travel/Bulan</div>
                <div class="text-sm font-medium" x-text="(selectedUser?.monthly_travellers ?? 0).toLocaleString('id-ID')"></div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <div class="text-xs text-muted-foreground">Nama Travel</div>
                <div class="text-sm font-medium" x-text="selectedUser?.travel_name || '-'"> </div>
              </div>
              <div>
                <div class="text-xs text-muted-foreground">Jenis Travel</div>
                <div class="text-sm font-medium" x-text="selectedUser?.travel_type || '-'"> </div>
              </div>
            </div>

            <div>
              <div class="text-xs text-muted-foreground">Koordinat</div>
              <template x-if="selectedUser?.latitude && selectedUser?.longitude">
                <a :href="`https://www.google.com/maps?q=${selectedUser.latitude},${selectedUser.longitude}`" target="_blank" class="text-sm text-primary hover:underline" x-text="`${selectedUser.latitude}, ${selectedUser.longitude}`"></a>
              </template>
              <div x-show="!selectedUser?.latitude || !selectedUser?.longitude" class="text-sm text-muted-foreground">-</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <div class="text-xs text-muted-foreground">Logo</div>
                <button x-show="selectedUser?.logo" @click="openFileModal(selectedUser.logo)" class="text-sm text-primary hover:underline">Lihat Logo</button>
                <div x-show="!selectedUser?.logo" class="text-sm text-muted-foreground">-</div>
              </div>
              <div>
                <div class="text-xs text-muted-foreground">Surat PPIU</div>
                <button x-show="selectedUser?.ppiu" @click="openFileModal(selectedUser.ppiu)" class="text-sm text-primary hover:underline">Lihat Surat PPIU</button>
                <div x-show="!selectedUser?.ppiu" class="text-sm text-muted-foreground">-</div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  @include('partials.form-addaffiliate')
  @include('partials.form-addfreelance')
  @include('partials.form-addtravelagent')

  <!-- Approve Modal -->
  <div x-show="approveModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40" @click="closeApproveModal()"></div>
    <div class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6"
      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
      
      <h3 class="text-lg font-semibold text-slate-900 mb-2">Approve Travel Agent</h3>
      <p class="text-sm text-muted-foreground mb-4">
        Setujui pendaftaran travel agent <strong><span x-text="approvalUser?.name"></span></strong>.
        Silakan tentukan link referral untuk agent ini.
      </p>

      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1">Referral Link</label>
        <div class="flex rounded-md shadow-sm">
          <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-input bg-muted text-muted-foreground text-sm">
            {{ url('/') }}/u/
          </span>
          <input type="text" x-model="referralSlug" 
            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-input text-sm focus:ring-primary focus:border-primary"
            placeholder="nama-travel">
        </div>
        <p class="mt-1 text-xs text-muted-foreground">Isi bagian belakang link saja. Database akan mencatat bagian ini.</p>
      </div>

      <div class="flex items-center justify-end gap-3 mt-6">
        <button @click="closeApproveModal()" class="px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors">
          Batal
        </button>
        <button @click="submitApprove()" :disabled="!referralSlug" 
          class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          Approve & Aktifkan
        </button>
      </div>
    </div>
  </div>

  <!-- Reject Modal -->
  <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40" @click="closeRejectModal()"></div>
    <div class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6"
      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
      
      <div class="flex items-center gap-3 mb-2 text-red-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-semibold text-slate-900">Tolak Pendaftaran?</h3>
      </div>
      
      <p class="text-sm text-muted-foreground mb-4">
        Apakah Anda yakin ingin menolak pendaftaran travel agent <strong><span x-text="approvalUser?.name"></span></strong>?
        Tindakan ini akan mengubah status pengguna menjadi ditolak/banned.
      </p>

      <div class="flex items-center justify-end gap-3 mt-6">
        <button @click="closeRejectModal()" class="px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-md transition-colors">
          Batal
        </button>
        <button @click="submitReject()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md shadow-sm transition-colors">
          Ya, Tolak
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet.js JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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
      userDetailModalOpen: false,
      selectedUser: null,
      fileModalOpen: false,
      fileModalType: 'image',
      fileModalSrc: '',
      confirmAddModalOpen: false,
      confirmAddFreelanceModalOpen: false,
      confirmAddTravelAgentModalOpen: false,
      approveModalOpen: false,
      rejectModalOpen: false,
      approvalUser: null,
      referralSlug: '',
      newAffiliate: { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '', latitude: null, longitude: null },
      newFreelance: { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '', latitude: null, longitude: null },
      newTravelAgent: { full_name: '', email: '', phone: '', travel_name: '', travel_type: '', travel_member: '', kategori_agent: '', province: '', city: '', address: '', latitude: null, longitude: null },
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

      // Map instances and markers
      mapAffiliateInstance: null,
      mapFreelanceInstance: null,
      mapAgentInstance: null,
      markerAffiliate: null,
      markerFreelance: null,
      markerAgent: null,
      mapAffiliateInitialized: false,
      mapFreelanceInitialized: false,
      mapAgentInitialized: false,

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

      buildStorageUrl(path) {
        if (!path) return '';
        if (String(path).startsWith('http')) return String(path);
        const clean = String(path).replace(/^public\//, '').replace(/^\//, '');
        return `/storage/${clean}`;
      },

      openFileModal(path) {
        const url = this.buildStorageUrl(path);
        if (!url) return;
        const ext = url.split('.').pop().toLowerCase();
        this.fileModalType = ext === 'pdf' ? 'pdf' : 'image';
        this.fileModalSrc = url;
        this.fileModalOpen = true;
      },

      closeFileModal() {
        this.fileModalOpen = false;
        this.fileModalSrc = '';
        this.fileModalType = 'image';
      },

      openUserDetail(user) {
        this.selectedUser = user;
        this.userDetailModalOpen = true;
      },

      closeUserDetail() {
        this.userDetailModalOpen = false;
        this.selectedUser = null;
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

      getReferralLink(user) {
        return '{{ url('/') }}/u/' + user.referral_code;
      },

      get filteredUsers() {
        return this.users.filter(user => {
          // For newusers tab, show only travel agents with pending status
          if (this.roleFilter === 'newusers') {
            const matchesRole = user.role === 'agent';
            const matchesStatus = user.status === 'pending';
            const matchesSearch = !this.search || 
              user.name.toLowerCase().includes(this.search.toLowerCase()) ||
              (user.email && user.email.toLowerCase().includes(this.search.toLowerCase()));
            return matchesRole && matchesStatus && matchesSearch;
          }
          
          // For agent tab, show travel agents with active or reject/banned status (not pending)
          if (this.roleFilter === 'agent') {
            const matchesRole = user.role === 'agent';
            const matchesStatus = user.status !== 'pending';
            const matchesSearch = !this.search || 
              user.name.toLowerCase().includes(this.search.toLowerCase()) ||
              (user.email && user.email.toLowerCase().includes(this.search.toLowerCase()));
            return matchesRole && matchesStatus && matchesSearch;
          }
          
          // For other tabs (affiliate, freelance)
          const matchesRole = user.role === this.roleFilter;
          const matchesStatus = this.statusFilter === 'all' || user.status === this.statusFilter;
          const matchesSearch = !this.search || 
            user.name.toLowerCase().includes(this.search.toLowerCase()) ||
            (user.email && user.email.toLowerCase().includes(this.search.toLowerCase()));
          return matchesRole && matchesStatus && matchesSearch;
        });
      },

      get paginatedNewUsers() {
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.filteredUsers.slice(start, end);
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
        this.openUserDetail(user);
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
      },

      // ========== APPROVAL FUNCTIONS ==========
      openApproveModal(user) {
        this.approvalUser = user;
        const suggestSlug = (user.travel_name || user.name || '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
        this.referralSlug = suggestSlug;
        this.approveModalOpen = true;
      },

      closeApproveModal() {
        this.approveModalOpen = false;
        this.approvalUser = null;
        this.referralSlug = '';
      },

      async submitApprove() {
        if (!this.approvalUser || !this.referralSlug) return;

        try {
            const response = await fetch(`/admin/agents/${this.approvalUser.id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    link_referral: this.referralSlug
                })
            });

            if (response.ok) {
                this.showToast('Sukses', 'Agent berhasil disetujui');
                window.location.reload(); 
            } else {
                const data = await response.json();
                this.showToast('Error', data.message || 'Gagal menyetujui agent');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showToast('Error', 'Terjadi kesalahan');
        } finally {
            this.closeApproveModal();
        }
      },

      openRejectModal(user) {
        this.approvalUser = user;
        this.rejectModalOpen = true;
      },

      closeRejectModal() {
        this.rejectModalOpen = false;
        this.approvalUser = null;
      },

      async submitReject() {
        if (!this.approvalUser) return;

        try {
            const response = await fetch(`/admin/agents/${this.approvalUser.id}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                this.showToast('Sukses', 'Agent berhasil ditolak');
                window.location.reload();
            } else {
                this.showToast('Error', 'Gagal menolak agent');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showToast('Error', 'Terjadi kesalahan');
        } finally {
            this.closeRejectModal();
        }
      },

      // ========== MAP FUNCTIONS FOR AFFILIATE ==========
      async handleCityChangeAffiliate() {
        this.$nextTick(async () => {
          if (!this.mapAffiliateInitialized) {
            await this.initializeMapAffiliate();
          }
        });
      },

      async initializeMapAffiliate() {
        if (this.mapAffiliateInitialized) return;

        let centerLat = -2.5;
        let centerLng = 118.0;
        let zoomLevel = 5;

        try {
          if (this.newAffiliate.kab_kota) {
            const searchQuery = this.newAffiliate.provinsi
              ? `${this.newAffiliate.kab_kota}, ${this.newAffiliate.provinsi}, Indonesia`
              : `${this.newAffiliate.kab_kota}, Indonesia`;
            const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1`;

            const response = await fetch(geocodeUrl);
            const data = await response.json();

            if (data && data.length > 0) {
              centerLat = parseFloat(data[0].lat);
              centerLng = parseFloat(data[0].lon);
              zoomLevel = 11;
            }
          }

          this.mapAffiliateInstance = L.map('map-affiliate').setView([centerLat, centerLng], zoomLevel);

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
          }).addTo(this.mapAffiliateInstance);

          if (this.newAffiliate.latitude && this.newAffiliate.longitude) {
            this.updateMarkerAffiliate(this.newAffiliate.latitude, this.newAffiliate.longitude);
          }

          this.mapAffiliateInstance.on('click', (e) => {
            const { lat, lng } = e.latlng;
            this.updateMarkerAffiliate(lat, lng);
          });

          this.mapAffiliateInitialized = true;

          setTimeout(() => {
            this.mapAffiliateInstance.invalidateSize();
          }, 100);
        } catch (error) {
          console.error('Error initializing affiliate map:', error);
        }
      },

      updateMarkerAffiliate(lat, lng) {
        if (this.markerAffiliate) {
          this.mapAffiliateInstance.removeLayer(this.markerAffiliate);
        }

        const greenIcon = L.icon({
          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });

        this.markerAffiliate = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapAffiliateInstance);
        this.newAffiliate.latitude = lat;
        this.newAffiliate.longitude = lng;
      },

      updateMapFromCoordinatesAffiliate() {
        const lat = parseFloat(this.newAffiliate.latitude);
        const lng = parseFloat(this.newAffiliate.longitude);

        if (!isNaN(lat) && !isNaN(lng) && this.mapAffiliateInstance) {
          if (this.markerAffiliate) {
            this.mapAffiliateInstance.removeLayer(this.markerAffiliate);
          }

          const greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
          });

          this.markerAffiliate = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapAffiliateInstance);
          this.mapAffiliateInstance.flyTo([lat, lng], 16);
        }
      },

      // ========== MAP FUNCTIONS FOR FREELANCE ==========
      async handleCityChangeFreelance() {
        this.$nextTick(async () => {
          if (!this.mapFreelanceInitialized) {
            await this.initializeMapFreelance();
          }
        });
      },

      async initializeMapFreelance() {
        if (this.mapFreelanceInitialized) return;

        let centerLat = -2.5;
        let centerLng = 118.0;
        let zoomLevel = 5;

        try {
          if (this.newFreelance.kab_kota) {
            const searchQuery = this.newFreelance.provinsi
              ? `${this.newFreelance.kab_kota}, ${this.newFreelance.provinsi}, Indonesia`
              : `${this.newFreelance.kab_kota}, Indonesia`;
            const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1`;

            const response = await fetch(geocodeUrl);
            const data = await response.json();

            if (data && data.length > 0) {
              centerLat = parseFloat(data[0].lat);
              centerLng = parseFloat(data[0].lon);
              zoomLevel = 11;
            }
          }

          this.mapFreelanceInstance = L.map('map-freelance').setView([centerLat, centerLng], zoomLevel);

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
          }).addTo(this.mapFreelanceInstance);

          if (this.newFreelance.latitude && this.newFreelance.longitude) {
            this.updateMarkerFreelance(this.newFreelance.latitude, this.newFreelance.longitude);
          }

          this.mapFreelanceInstance.on('click', (e) => {
            const { lat, lng } = e.latlng;
            this.updateMarkerFreelance(lat, lng);
          });

          this.mapFreelanceInitialized = true;

          setTimeout(() => {
            this.mapFreelanceInstance.invalidateSize();
          }, 100);
        } catch (error) {
          console.error('Error initializing freelance map:', error);
        }
      },

      updateMarkerFreelance(lat, lng) {
        if (this.markerFreelance) {
          this.mapFreelanceInstance.removeLayer(this.markerFreelance);
        }

        const greenIcon = L.icon({
          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });

        this.markerFreelance = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapFreelanceInstance);
        this.newFreelance.latitude = lat;
        this.newFreelance.longitude = lng;
      },

      updateMapFromCoordinatesFreelance() {
        const lat = parseFloat(this.newFreelance.latitude);
        const lng = parseFloat(this.newFreelance.longitude);

        if (!isNaN(lat) && !isNaN(lng) && this.mapFreelanceInstance) {
          if (this.markerFreelance) {
            this.mapFreelanceInstance.removeLayer(this.markerFreelance);
          }

          const greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
          });

          this.markerFreelance = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapFreelanceInstance);
          this.mapFreelanceInstance.flyTo([lat, lng], 16);
        }
      },

      // ========== MAP FUNCTIONS FOR TRAVEL AGENT ==========
      async handleCityChangeTravelAgent() {
        this.$nextTick(async () => {
          if (!this.mapAgentInitialized) {
            await this.initializeMapAgent();
          }
        });
      },

      async initializeMapAgent() {
        if (this.mapAgentInitialized) return;

        let centerLat = -2.5;
        let centerLng = 118.0;
        let zoomLevel = 5;

        try {
          if (this.newTravelAgent.city) {
            const searchQuery = this.newTravelAgent.province
              ? `${this.newTravelAgent.city}, ${this.newTravelAgent.province}, Indonesia`
              : `${this.newTravelAgent.city}, Indonesia`;
            const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1`;

            const response = await fetch(geocodeUrl);
            const data = await response.json();

            if (data && data.length > 0) {
              centerLat = parseFloat(data[0].lat);
              centerLng = parseFloat(data[0].lon);
              zoomLevel = 11;
            }
          }

          this.mapAgentInstance = L.map('map-agent').setView([centerLat, centerLng], zoomLevel);

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
          }).addTo(this.mapAgentInstance);

          if (this.newTravelAgent.latitude && this.newTravelAgent.longitude) {
            this.updateMarkerAgent(this.newTravelAgent.latitude, this.newTravelAgent.longitude);
          }

          this.mapAgentInstance.on('click', (e) => {
            const { lat, lng } = e.latlng;
            this.updateMarkerAgent(lat, lng);
          });

          this.mapAgentInitialized = true;

          setTimeout(() => {
            this.mapAgentInstance.invalidateSize();
          }, 100);
        } catch (error) {
          console.error('Error initializing agent map:', error);
        }
      },

      updateMarkerAgent(lat, lng) {
        if (this.markerAgent) {
          this.mapAgentInstance.removeLayer(this.markerAgent);
        }

        const greenIcon = L.icon({
          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });

        this.markerAgent = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapAgentInstance);
        this.newTravelAgent.latitude = lat;
        this.newTravelAgent.longitude = lng;
      },

      updateMapFromCoordinatesAgent() {
        const lat = parseFloat(this.newTravelAgent.latitude);
        const lng = parseFloat(this.newTravelAgent.longitude);

        if (!isNaN(lat) && !isNaN(lng) && this.mapAgentInstance) {
          if (this.markerAgent) {
            this.mapAgentInstance.removeLayer(this.markerAgent);
          }

          const greenIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
          });

          this.markerAgent = L.marker([lat, lng], { icon: greenIcon }).addTo(this.mapAgentInstance);
          this.mapAgentInstance.flyTo([lat, lng], 16);
        }
      }
    }
  }
</script>
@endpush

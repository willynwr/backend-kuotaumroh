@extends('layouts.admin')

@section('title', 'Kelola Users')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<div x-data="usersPage()">
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
            <button @click="openAddUserModal()" 
              class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors inline-flex items-center gap-2">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Tambah User
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
          <button @click="changeTab('agent')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'agent' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Travel Agent</button>
          <button @click="changeTab('affiliate')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'affiliate' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Affiliate</button>
          <button @click="changeTab('freelance')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'freelance' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Freelance</button>
        </div>

        <!-- Table -->
        <!-- Content State -->
        <div x-show="loaded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
          <div x-show="roleFilter === 'agent'" x-cloak>
            @include('admin.partial-users.users-travel-agent')
          </div>

          <div x-show="roleFilter === 'affiliate'" x-cloak>
            @include('admin.partial-users.users-affiliate')
          </div>

          <div x-show="roleFilter === 'freelance'" x-cloak>
            @include('admin.partial-users.users-freelance')
          </div>
        </div>

        <!-- Skeleton Loading State -->
        <div x-show="!loaded" class="space-y-4 p-4 animate-pulse">
          <div class="h-10 bg-slate-100 rounded w-full mb-4"></div>
          <div class="space-y-3">
             <div class="h-12 bg-slate-100 rounded w-full"></div>
             <div class="h-12 bg-slate-50 rounded w-full"></div>
             <div class="h-12 bg-slate-100 rounded w-full"></div>
             <div class="h-12 bg-slate-50 rounded w-full"></div>
             <div class="h-12 bg-slate-100 rounded w-full"></div>
          </div>
        </div>

        <!-- Pagination -->
        <div x-show="loaded" class="mt-4 flex items-center justify-between">
          <p class="text-sm text-muted-foreground">
            Menampilkan <span x-text="(currentPage - 1) * itemsPerPage + 1"></span> - <span x-text="Math.min(currentPage * itemsPerPage, filteredUsers.length)"></span> dari <span x-text="filteredUsers.length"></span> data
          </p>
          <div class="flex gap-2">
            <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 rounded-md border border-input bg-background text-sm font-medium disabled:opacity-50">Previous</button>
            <button @click="currentPage++" :disabled="currentPage >= Math.ceil(filteredUsers.length / itemsPerPage)" class="px-3 py-1 rounded-md border border-input bg-background text-sm font-medium disabled:opacity-50">Next</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Unified Add User Modal -->
  <div x-show="addUserModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40" @click.self="closeAddUserModal()" x-transition.opacity>
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col mx-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
      <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
        <h3 class="text-xl font-bold text-gray-900">
          <span x-show="!selectedUserType">Tambah User Baru</span>
          <span x-show="selectedUserType === 'agent'">Tambah Travel Agent</span>
          <span x-show="selectedUserType === 'affiliate'">Tambah Affiliate</span>
          <span x-show="selectedUserType === 'freelance'">Tambah Freelance</span>
        </h3>
        <button @click="closeAddUserModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      
      <div class="flex-1 overflow-y-auto p-6">
        <!-- Step 1: Select User Type -->
        <div x-show="!selectedUserType" class="space-y-6">
          <p class="text-sm text-muted-foreground mb-6">Pilih jenis user yang ingin ditambahkan:</p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Travel Agent Card -->
            <button @click="selectUserType('agent')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition-all text-left group">
              <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                <svg class="w-6 h-6 text-blue-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <h4 class="font-semibold text-lg mb-2 group-hover:text-primary transition-colors">Travel Agent</h4>
              <p class="text-sm text-muted-foreground">Tambah agen travel dengan detail perusahaan dan dokumen</p>
            </button>
            
            <!-- Affiliate Card -->
            <button @click="selectUserType('affiliate')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition-all text-left group">
              <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                <svg class="w-6 h-6 text-green-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
              <h4 class="font-semibold text-lg mb-2 group-hover:text-primary transition-colors">Affiliate</h4>
              <p class="text-sm text-muted-foreground">Tambah affiliate dengan link referral</p>
            </button>
            
            <!-- Freelance Card -->
            <button @click="selectUserType('freelance')" class="p-6 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition-all text-left group">
              <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-colors">
                <svg class="w-6 h-6 text-purple-600 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <h4 class="font-semibold text-lg mb-2 group-hover:text-primary transition-colors">Freelance</h4>
              <p class="text-sm text-muted-foreground">Tambah freelancer dengan link referral</p>
            </button>
          </div>
        </div>
        
        <!-- Step 2: Show Selected Form -->
        <div x-show="selectedUserType" x-cloak>
          @include('partials.form-addtravelagent')
          @include('partials.form-addaffiliate')
          @include('partials.form-addfreelance')
        </div>
      </div>
      
      <!-- Footer with Back Button when form is shown -->
      <div x-show="selectedUserType" class="px-6 py-4 bg-gray-50 border-t flex justify-start">
        <button @click="backToSelection()" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900 font-medium flex items-center gap-2 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Kembali ke Pilihan
        </button>
      </div>
    </div>
  </div>

  <!-- Approve Modal -->
  <div x-show="approveModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40" x-transition.opacity>
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md" @click.away="closeApproveModal()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
       <h3 class="text-lg font-semibold mb-4">Approve Travel Agent</h3>
       <p class="mb-4 text-sm text-gray-600">Setujui pendaftaran <strong x-text="approvalUser?.name"></strong>. Tentukan link referral:</p>
       <div class="mb-4">
         <div class="flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 bg-gray-50 text-gray-500 text-sm">{{ url('/') }}/u/</span>
            <input type="text" x-model="referralSlug" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm" placeholder="slug-travel">
         </div>
         <p class="mt-1 text-xs text-muted-foreground">Format slug akan otomatis disesuaikan.</p>
       </div>
       <div class="flex justify-end gap-2">
         <button @click="closeApproveModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded transition-colors">Batal</button>
         <button @click="submitApprove()" :disabled="!referralSlug" class="px-4 py-2 text-sm bg-green-600 text-white hover:bg-green-700 rounded disabled:opacity-50 transition-colors">Approve</button>
       </div>
    </div>
  </div>

  <!-- Reject Modal -->
  <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40" x-transition.opacity>
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md" @click.away="closeRejectModal()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
       <h3 class="text-lg font-semibold mb-4 text-red-600 flex items-center gap-2">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
         Tolak Pendaftaran?
       </h3>
       <p class="mb-4 text-sm text-gray-600">Yakin tolak <strong x-text="approvalUser?.name"></strong>? Status akan menjadi ditolak/banned.</p>
       <div class="flex justify-end gap-2">
         <button @click="closeRejectModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded transition-colors">Batal</button>
         <button @click="submitReject()" class="px-4 py-2 text-sm bg-red-600 text-white hover:bg-red-700 rounded transition-colors">Ya, Tolak</button>
       </div>
    </div>
  </div>

  <!-- File Preview -->
  <div x-show="fileModalOpen" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40" @click.self="closeFileModal()" x-transition.opacity>
     <div class="bg-white p-4 rounded-lg shadow-xl max-w-4xl max-h-[90vh] overflow-hidden flex flex-col w-full mx-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="flex justify-between items-center mb-3 pb-2 border-b">
            <h3 class="font-bold text-lg">Preview</h3>
            <button @click="closeFileModal()" class="text-gray-500 hover:text-gray-700">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-auto bg-gray-50 p-2 rounded flex justify-center items-center">
            <template x-if="fileModalType === 'image'">
                <img :src="fileModalSrc" class="max-w-full max-h-full object-contain shadow-sm">
            </template>
            <template x-if="fileModalType === 'pdf'">
                <iframe :src="fileModalSrc" class="w-full h-full min-h-[500px]"></iframe>
            </template>
        </div>
     </div>
  </div>

  <!-- User Detail -->
  <div x-show="userDetailModalOpen" x-cloak class="fixed inset-0 z-[65] flex items-center justify-center bg-black/40" @click.self="closeUserDetail()" x-transition.opacity>
     <div class="bg-white rounded-lg shadow-xl p-0 w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col mx-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
            <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">Detail User</h3>
            <button @click="closeUserDetail()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <div x-show="selectedUser" class="flex-1 overflow-y-auto p-6 space-y-6">
             <!-- User Profile -->
             <div class="flex items-start gap-4">
                 <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl font-bold text-slate-400">
                    <span x-text="selectedUser?.name?.charAt(0) || 'U'"></span>
                 </div>
                 <div class="flex-1">
                    <h4 class="text-lg font-bold text-gray-900" x-text="selectedUser?.name"></h4>
                    <p class="text-sm text-gray-500" x-text="selectedUser?.email"></p>
                    <div class="mt-2 flex gap-2">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 uppercase" x-text="selectedUser?.role"></span>
                        <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800" x-text="selectedUser?.status"></span>
                    </div>
                 </div>
             </div>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg border">
                 <div>
                     <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Phone/WA</p>
                     <p class="mt-1 font-medium" x-text="selectedUser?.phone || '-'"> </p>
                 </div>
                 <div>
                     <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Alamat</p>
                     <p class="mt-1 font-medium" x-text="selectedUser?.address || '-'"> </p>
                 </div>
                  <div>
                     <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Lokasi</p>
                     <p class="mt-1 font-medium" x-text="`${selectedUser?.city || '-'}, ${selectedUser?.province || '-'}`"></p>
                 </div>
                 <div>
                     <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Daftar</p>
                     <p class="mt-1 font-medium" x-text="selectedUser?.created_at?.substring(0,10) || '-'"> </p>
                 </div>
             </div>

             <template x-if="selectedUser?.role === 'agent'">
                <div class="pt-2">
                    <h4 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Informasi Travel
                    </h4>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                         <div>
                             <p class="text-xs text-gray-500 mb-1">Nama Travel</p>
                             <p class="font-medium" x-text="selectedUser?.travel_name"></p>
                         </div>
                         <div>
                             <p class="text-xs text-gray-500 mb-1">Jenis</p>
                             <p class="font-medium" x-text="selectedUser?.travel_type"></p>
                         </div>
                         <div>
                             <p class="text-xs text-gray-500 mb-1">PPIU/Izin</p>
                             <span x-text="selectedUser?.ppiu ? 'Terlampir' : '-'" class="font-medium"></span>
                         </div>
                         <div>
                             <p class="text-xs text-gray-500 mb-1">Kategori Agen</p>
                             <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800" x-text="selectedUser?.agent_category"></span>
                         </div>
                     </div>
                </div>
             </template>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
             <button @click="closeUserDetail()" class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 font-medium text-sm shadow-sm transition-colors">Tutup</button>
        </div>
     </div>
  </div>

  <!-- Custom Notification Modal -->
  <div x-show="notificationModalOpen" x-cloak 
    class="fixed top-4 right-4 z-[100] max-w-md w-full"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full">
    <div class="rounded-lg shadow-2xl overflow-hidden"
      :class="{
        'bg-white border-l-4 border-green-500': notificationType === 'success',
        'bg-white border-l-4 border-red-500': notificationType === 'error',
        'bg-white border-l-4 border-orange-500': notificationType === 'warning'
      }">
      <div class="p-4">
        <div class="flex items-start gap-3">
          <!-- Icon -->
          <div class="flex-shrink-0">
            <template x-if="notificationType === 'success'">
              <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </template>
            <template x-if="notificationType === 'error'">
              <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </template>
            <template x-if="notificationType === 'warning'">
              <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
              </div>
            </template>
          </div>
          
          <!-- Content -->
          <div class="flex-1 pt-0.5">
            <h3 class="text-sm font-semibold" 
              :class="{
                'text-green-900': notificationType === 'success',
                'text-red-900': notificationType === 'error',
                'text-orange-900': notificationType === 'warning'
              }"
              x-text="notificationType === 'success' ? 'Berhasil!' : (notificationType === 'warning' ? 'Perhatian!' : 'Error!')">
            </h3>
            <p class="mt-1 text-sm text-gray-600" x-text="notificationMessage"></p>
          </div>
          
          <!-- Close Button -->
          <button @click="closeNotification()" 
            class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <!-- Progress Bar -->
        <div class="mt-3 h-1 bg-gray-200 rounded-full overflow-hidden">
          <div class="h-full transition-all duration-[3000ms] ease-linear"
            :class="{
              'bg-green-500': notificationType === 'success',
              'bg-red-500': notificationType === 'error',
              'bg-orange-500': notificationType === 'warning'
            }"
            x-init="$nextTick(() => { if(notificationModalOpen) $el.style.width = '0%'; setTimeout(() => $el.style.width = '100%', 10); })">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ban Confirmation Modal -->
  <div x-show="banModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-black/40" @click="closeBanModal()"></div>
    <div class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 transform scale-95"
      x-transition:enter-end="opacity-100 transform scale-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform scale-100"
      x-transition:leave-end="opacity-0 transform scale-95">
      
      <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
          <div class="w-12 h-12 rounded-full flex items-center justify-center"
            :class="banAction === 'deactivate' ? 'bg-red-100' : 'bg-green-100'">
            <svg class="w-6 h-6" :class="banAction === 'deactivate' ? 'text-red-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <template x-if="banAction === 'deactivate'">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </template>
              <template x-if="banAction === 'activate'">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </template>
            </svg>
          </div>
        </div>
        
        <div class="flex-1">
          <h3 class="text-lg font-semibold text-slate-900" x-text="banAction === 'deactivate' ? 'Konfirmasi Ban User' : 'Konfirmasi Aktifkan User'"></h3>
          <p class="mt-2 text-sm text-muted-foreground">
            <span x-show="banAction === 'deactivate'">
              Apakah Anda yakin ingin mem-ban <strong x-text="banUser?.name"></strong>? User akan dinonaktifkan dan tidak dapat mengakses sistem.
            </span>
            <span x-show="banAction === 'activate'">
              Apakah Anda yakin ingin mengaktifkan <strong x-text="banUser?.name"></strong>? User akan dapat mengakses sistem kembali.
            </span>
          </p>
        </div>
      </div>
      
      <div class="mt-6 flex items-center justify-end gap-3">
        <button @click="closeBanModal()" class="h-10 px-4 rounded-md border border-gray-300 text-sm font-medium text-slate-700 hover:bg-gray-50 transition-colors">
          Batal
        </button>
        <button @click="confirmBan()" class="h-10 px-4 rounded-md text-white text-sm font-medium transition-colors"
          :class="banAction === 'deactivate' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'">
          <span x-text="banAction === 'deactivate' ? 'Ya, Ban User' : 'Ya, Aktifkan'"></span>
        </button>
      </div>
    </div>
  </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function usersPage() {
    return {
        loaded: false,
        users: @json($users ?? []),
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
        roleFilter: 'agent',
        statusFilter: 'all',
        search: '',
        currentPage: 1,
        itemsPerPage: 10,
        sortField: 'name',
        sortDirection: 'asc',
        
        // Modals
        approveModalOpen: false,
        rejectModalOpen: false,
        addUserModalOpen: false,
        selectedUserType: null,
        addAffiliateModalOpen: false,
        addFreelanceModalOpen: false,
        addTravelAgentModalOpen: false,
        confirmAddModalOpen: false,
        confirmAddFreelanceModalOpen: false,
        confirmAddTravelAgentModalOpen: false,
        userDetailModalOpen: false,
        fileModalOpen: false,
        notificationModalOpen: false,
        banModalOpen: false,
        
        // Data
        approvalUser: null,
        referralSlug: '',
        selectedUser: null,
        fileModalType: 'image',
        fileModalSrc: '',
        logoFile: null,
        notificationMessage: '',
        notificationType: 'success', // 'success' or 'error'
        banUser: null,
        banAction: '',
        
        // Forms
        newAffiliate: { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '', latitude: null, longitude: null },
        newFreelance: { nama: '', email: '', no_wa: '', provinsi: '', kab_kota: '', alamat_lengkap: '', link_referral: '', latitude: null, longitude: null },
        newTravelAgent: { full_name: '', email: '', phone: '', travel_name: '', travel_type: '', travel_member: '', kategori_agent: '', province: '', city: '', address: '', latitude: null, longitude: null },
        
        // Maps
        mapAffiliateInstance: null,
        mapFreelanceInstance: null,
        mapAgentInstance: null,
        markerAffiliate: null,
        markerFreelance: null,
        markerAgent: null,
        mapAffiliateInitialized: false,
        mapFreelanceInitialized: false,
        mapAgentInitialized: false,
        
        provinces: [],
        provinceCodes: {},
        cities: [],
        citiesFreelance: [],
        citiesTravelAgent: [],
        selectedDownline: null,

        init() {
            this.loadProvinces();
            
            // Tab Persistence: Check URL first, then LocalStorage
            const params = new URLSearchParams(window.location.search);
            const urlTab = params.get('tab');
            
            if(urlTab && ['agent', 'affiliate', 'freelance'].includes(urlTab)) {
                this.roleFilter = urlTab;
            } else {
                const storedTab = localStorage.getItem('admin_users_tab');
                if(storedTab && ['agent', 'affiliate', 'freelance'].includes(storedTab)) {
                    this.roleFilter = storedTab;
                } else {
                    this.roleFilter = 'agent';
                }
            }
            
            // Simulate smooth loading
            setTimeout(() => { this.loaded = true; }, 300);
            
            @if(session('success')) 
                this.showNotification("{{ session('success') }}", 'success');
            @endif
            @if(session('error')) 
                this.showNotification("{{ session('error') }}", 'error');
            @endif

            // Re-open modals on validation errors
            @if($errors->any())
              if(@json(old('nama_travel'))) {
                  this.changeTab('agent');
                  this.$nextTick(() => {
                      this.openAddUserModal();
                      this.selectUserType('agent');
                  });
              } else if(@json(old('nama'))) {
                  const type = this.roleFilter === 'affiliate' ? 'affiliate' : 'freelance';
                  this.$nextTick(() => {
                      this.openAddUserModal();
                      this.selectUserType(type);
                  });
              }
            @endif
        },

        changeTab(role) {
            this.roleFilter = role;
            this.statusFilter = 'all';
            this.search = '';
            this.currentPage = 1;
            
            // Save state
            localStorage.setItem('admin_users_tab', role);
            const url = new URL(window.location);
            url.searchParams.set('tab', role);
            window.history.pushState({}, '', url);
        },

        
        get filteredUsers() {
            let filtered = this.users.filter(user => {
                // Search
                const s = this.search.toLowerCase();
                if (s && !user.name.toLowerCase().includes(s) && !user.email.toLowerCase().includes(s)) return false;

                if (this.roleFilter === 'agent') {
                    if (user.role !== 'agent') return false;
                    if (this.statusFilter !== 'all') return user.status === this.statusFilter;
                    return true; // Show all agents including pending
                }
                
                // Affiliate/Freelance
                if (user.role !== this.roleFilter) return false;
                if (this.statusFilter !== 'all') {
                     // Check mapping for is_active
                     if (this.statusFilter === 'active') return user.status === 'active';
                     if (this.statusFilter === 'reject') return user.status === 'reject';
                }
                return true;
            });
            
            // Apply sorting
            return filtered.sort((a, b) => {
                let aVal = a[this.sortField];
                let bVal = b[this.sortField];
                
                // Handle null/undefined values
                if (aVal === null || aVal === undefined) aVal = '';
                if (bVal === null || bVal === undefined) bVal = '';
                
                // Convert to lowercase for string comparison
                if (typeof aVal === 'string') aVal = aVal.toLowerCase();
                if (typeof bVal === 'string') bVal = bVal.toLowerCase();
                
                let comparison = 0;
                if (aVal > bVal) comparison = 1;
                if (aVal < bVal) comparison = -1;
                
                return this.sortDirection === 'asc' ? comparison : -comparison;
            });
        },
        
        
        get paginatedFilteredUsers() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredUsers.slice(start, start + this.itemsPerPage);  
        },
        
        get paginatedUsers() { return this.paginatedFilteredUsers; },
        
        // Sorting functions
        sort(field) {
            if (this.sortField === field) {
                // Toggle direction if same field
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                // New field, default to ascending
                this.sortField = field;
                this.sortDirection = 'asc';
            }
            this.currentPage = 1; // Reset to first page when sorting
        },
        
        getSortIcon(field) {
            if (this.sortField !== field) return '↕';
            return this.sortDirection === 'asc' ? '↑' : '↓';
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        },

        getReferralLink(user) {
            if (!user.referral_code) return '#';
            const origin = window.location.origin;

            if (user.role === 'affiliate' || user.role === 'freelance') {
                // Affiliate/Freelance refer to Agent Signup
                return `${origin}/agent/${user.referral_code}`;
            }
            
            // Agent refers to their Store
            return `${origin}/u/${user.referral_code}`;
        },

        // Modal Actions
        openAddUserModal() { 
            this.addUserModalOpen = true; 
            this.selectedUserType = null;
        },
        closeAddUserModal() { 
            this.addUserModalOpen = false; 
            this.selectedUserType = null;
            this.addTravelAgentModalOpen = false;
            this.addAffiliateModalOpen = false;
            this.addFreelanceModalOpen = false;
        },
        selectUserType(type) {
            this.selectedUserType = type;
            // Set flag untuk show form sesuai tipe
            if (type === 'agent') {
                this.addTravelAgentModalOpen = true;
            } else if (type === 'affiliate') {
                this.addAffiliateModalOpen = true;
            } else if (type === 'freelance') {
                this.addFreelanceModalOpen = true;
            }
            // Initialize map based on type
            this.$nextTick(() => {
                if (type === 'agent') {
                    this.initializeMapAgent();
                } else if (type === 'affiliate') {
                    this.initializeMapAffiliate();
                } else if (type === 'freelance') {
                    this.initializeMapFreelance();
                }
            });
        },
        backToSelection() {
            this.selectedUserType = null;
            this.addTravelAgentModalOpen = false;
            this.addAffiliateModalOpen = false;
            this.addFreelanceModalOpen = false;
        },
        openAddAffiliateModal() { this.addAffiliateModalOpen = true; },
        closeAddAffiliateModal() { this.addAffiliateModalOpen = false; },
        openAddFreelanceModal() { this.addFreelanceModalOpen = true; },
        closeAddFreelanceModal() { this.addFreelanceModalOpen = false; },
        openAddTravelAgentModal() { this.addTravelAgentModalOpen = true; },
        closeAddTravelAgentModal() { this.addTravelAgentModalOpen = false; },
        openUserDetail(u) { this.selectedUser = u; this.userDetailModalOpen = true; },
        closeUserDetail() { this.userDetailModalOpen = false; },
        
        openApproveModal(u) {
            this.approvalUser = u;
            // Suggest slug
            let base = u.travel_name || u.name;
            this.referralSlug = base.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g,'');
            this.approveModalOpen = true;
        },
        closeApproveModal() { this.approveModalOpen = false; this.approvalUser = null; },
        
        async submitApprove() {
            if(!this.approvalUser) return;
            try {
                const formData = new FormData();
                formData.append('link_referal', this.referralSlug);
                formData.append('_token', '{{ csrf_token() }}');
                
                const res = await fetch(`/admin/agents/${this.approvalUser.id}/approve`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
                const data = await res.json();
                
                if(res.ok) {
                    this.showNotification('Agent berhasil di-approve', 'success');
                    this.closeApproveModal();
                    setTimeout(() => window.location.reload(), 1500);
                } else if(res.status === 422) {
                    // Validation error
                    if(data.errors && data.errors.link_referal) {
                        this.showNotification(data.errors.link_referal[0], 'error');
                    } else {
                        this.showNotification(data.message || 'Validasi gagal', 'error');
                    }
                } else {
                    this.showNotification(data.message || 'Error', 'error');
                }
            } catch(e) { 
                console.error('Error:', e);
                this.showNotification('Error approving user', 'error'); 
            }
        },
        
        openRejectModal(u) { this.approvalUser = u; this.rejectModalOpen = true; },
        closeRejectModal() { this.rejectModalOpen = false; this.approvalUser = null; },
        
        async submitReject() {
            if(!this.approvalUser) return;
             try {
                const res = await fetch(`/admin/agents/${this.approvalUser.id}/reject`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                
                if(res.ok && data.success) {
                    this.showNotification('Agent berhasil ditolak', 'warning');
                    this.closeRejectModal();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    this.showNotification(data.message || 'Error menolak agent', 'error');
                }
            } catch(e) { 
                console.error('Error:', e);
                this.showNotification('Error rejecting user', 'error'); 
            }
        },
        
        openFileModal(path) {
            if(!path) return;
            this.fileModalSrc = path.startsWith('http') ? path : `/storage/${path}`;
            this.fileModalType = path.endsWith('.pdf') ? 'pdf' : 'image';
            this.fileModalOpen = true;
        },
        closeFileModal() { this.fileModalOpen = false; },
        
        openBanModal(user) {
            this.banUser = user;
            this.banAction = user.status === 'active' ? 'deactivate' : 'activate';
            this.banModalOpen = true;
        },
        
        closeBanModal() {
            this.banModalOpen = false;
            this.banUser = null;
            this.banAction = '';
        },
        
        async toggleBan(user) {
            if (!user) return;
            this.openBanModal(user);
        },
        
        async confirmBan() {
            if (!this.banUser) return;
            
            try {
                let url = '';
                if (this.banUser.role === 'agent') {
                    url = `/admin/agents/${this.banUser.id}/${this.banAction}`;
                } else if (this.banUser.role === 'affiliate') {
                    url = `/admin/affiliates/${this.banUser.id}/${this.banAction}`;
                } else if (this.banUser.role === 'freelance') {
                    url = `/admin/freelances/${this.banUser.id}/${this.banAction}`;
                }
                
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await res.json();
                this.closeBanModal();
                
                if (data.success) {
                    this.showNotification(data.message || 'Status berhasil diubah', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(data.message || 'Error', 'error');
                }
            } catch(e) { 
                this.closeBanModal();
                this.showNotification('Error mengubah status user', 'error'); 
            }
        },

        // Province & City
        async loadProvinces() {
            try {
                const res = await fetch('/wilayah/provinces.json');
                const d = await res.json();
                this.provinces = d.data.map(p => p.name).sort();
                d.data.forEach(p => this.provinceCodes[p.name] = p.code);
            } catch(e) {}
        },
        async loadCities(provName, targetList) {
            const code = this.provinceCodes[provName];
            if(!code) return [];
            try {
                const res = await fetch(`/wilayah/regencies-${code}.json`);
                const d = await res.json();
                return d.data.map(c => c.name).sort();
            } catch { return []; }
        },
        async handleProvinceChange() { this.cities = await this.loadCities(this.newAffiliate.provinsi); },
        async handleProvinceChangeFreelance() { this.citiesFreelance = await this.loadCities(this.newFreelance.provinsi); },
        async handleProvinceChangeTravelAgent() { this.citiesTravelAgent = await this.loadCities(this.newTravelAgent.province); },
        
        get downlines() {
             return this.users.filter(u => u.role === 'affiliate' || u.role === 'freelance').map(u => ({
                 ...u,
                 type: u.role === 'affiliate' ? 'Affiliate' : 'Freelance'
             }));
        },
        selectDownline(d) { this.selectedDownline = d; },
        
        // Affiliate Modal Functions
        openConfirmAddModal() {
            this.confirmAddModalOpen = true;
        },
        closeConfirmAddModal() {
            this.confirmAddModalOpen = false;
        },
        confirmAddAffiliate() {
            this.$refs.addAffiliateForm.submit();
        },
        
        // Freelance Modal Functions
        openConfirmAddFreelanceModal() {
            this.confirmAddFreelanceModalOpen = true;
        },
        closeConfirmAddFreelanceModal() {
            this.confirmAddFreelanceModalOpen = false;
        },
        confirmAddFreelance() {
            this.$refs.addFreelanceForm.submit();
        },
        
        // Travel Agent Modal Functions
        openConfirmAddTravelAgentModal() {
            this.confirmAddTravelAgentModalOpen = true;
        },
        closeConfirmAddTravelAgentModal() {
            this.confirmAddTravelAgentModalOpen = false;
        },
        confirmAddTravelAgent() {
            this.$refs.addTravelAgentForm.submit();
        },
        
        // Notification Functions
        showNotification(message, type = 'success') {
            this.notificationMessage = message;
            this.notificationType = type;
            this.notificationModalOpen = true;
            // Auto close after 3 seconds
            setTimeout(() => {
                this.notificationModalOpen = false;
            }, 3000);
        },
        closeNotification() {
            this.notificationModalOpen = false;
        },

        // MAP FUNCTIONS (Condensed for safety)
        async initializeMapAffiliate() { this.initMap('map-affiliate', 'mapAffiliateInstance', 'newAffiliate', 'markerAffiliate'); },
        async initializeMapFreelance() { this.initMap('map-freelance', 'mapFreelanceInstance', 'newFreelance', 'markerFreelance'); },
        async initializeMapAgent() { this.initMap('map-agent', 'mapAgentInstance', 'newTravelAgent', 'markerAgent'); },
        
        handleCityChangeAffiliate() { this.$nextTick(() => this.initializeMapAffiliate()); },
        handleCityChangeFreelance() { this.$nextTick(() => this.initializeMapFreelance()); },
        handleCityChangeTravelAgent() { this.$nextTick(() => this.initializeMapAgent()); },
        
        updateMapFromCoordinatesAffiliate() { this.updateMapCoords('newAffiliate', 'mapAffiliateInstance', 'markerAffiliate'); },
        updateMapFromCoordinatesFreelance() { this.updateMapCoords('newFreelance', 'mapFreelanceInstance', 'markerFreelance'); },
        updateMapFromCoordinatesAgent() { this.updateMapCoords('newTravelAgent', 'mapAgentInstance', 'markerAgent'); },

        // Generic Map Helpers to save space
        async initMap(elemId, instanceKey, dataKey, markerKey) {
            if(this[instanceKey]) return;
            let lat = -2.5, lng = 118, zoom = 5;
            // Try geocode city
            const city = this[dataKey].city || this[dataKey].kab_kota;
            if(city) {
                 try {
                     const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(city + ', Indonesia')}&limit=1`);
                     const d = await res.json();
                     if(d.length) { lat = d[0].lat; lng = d[0].lon; zoom = 11; }
                 } catch(e){}
            }
            this[instanceKey] = L.map(elemId).setView([lat, lng], zoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: 'OSM' }).addTo(this[instanceKey]);
            
            this[instanceKey].on('click', e => {
                 this[dataKey].latitude = e.latlng.lat;
                 this[dataKey].longitude = e.latlng.lng;
                 this.updateMarker(e.latlng.lat, e.latlng.lng, instanceKey, markerKey);
            });
            
            // Initial marker
             if(this[dataKey].latitude && this[dataKey].longitude) {
                 this.updateMarker(this[dataKey].latitude, this[dataKey].longitude, instanceKey, markerKey);
             }
             setTimeout(() => this[instanceKey].invalidateSize(), 200);
        },
        
        updateMarker(lat, lng, instanceKey, markerKey) {
             if(this[markerKey]) this[instanceKey].removeLayer(this[markerKey]);
             this[markerKey] = L.marker([lat, lng]).addTo(this[instanceKey]);
        },
        updateMapCoords(dataKey, instanceKey, markerKey) {
             const lat = this[dataKey].latitude, lng = this[dataKey].longitude;
             if(lat && lng && this[instanceKey]) {
                 this.updateMarker(lat, lng, instanceKey, markerKey);
                 this[instanceKey].flyTo([lat, lng], 16);
             }
        },



        formatDate(dateStr) {
            if (!dateStr) return '-';
            try {
                return new Date(dateStr).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
            } catch(e) { return dateStr; }
        },

        getSortIcon(field) {
            if (this.sortField !== field) return '↕';
            return this.sortDirection === 'asc' ? '↑' : '↓';
        }
    }
}
</script>
@endpush

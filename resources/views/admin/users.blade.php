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
            <button x-show="roleFilter === 'affiliate'" @click="openAddAffiliateModal(false)" 
              class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors inline-flex items-center gap-2">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Tambah Affiliate
            </button>
            <button x-show="roleFilter === 'freelance'" @click="openAddFreelanceModal(false)" 
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
          <button @click="changeTab('newusers')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'newusers' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Pengguna Baru</button>
          <button @click="changeTab('agent')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'agent' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Travel Agent</button>
          <button @click="changeTab('affiliate')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'affiliate' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Affiliate</button>
          <button @click="changeTab('freelance')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="roleFilter === 'freelance' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Freelance</button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <div x-show="roleFilter === 'newusers'" x-cloak>
            @include('admin.partial-users.users-new-users')
          </div>

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

        <!-- Pagination -->
        <div class="mt-4 flex items-center justify-between">
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

  <!-- Modals -->
  @include('partials.form-addaffiliate')
  @include('partials.form-addfreelance')
  @include('partials.form-addtravelagent')

  <!-- Approve Modal -->
  <div x-show="approveModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
       <h3 class="text-lg font-semibold mb-4">Approve Travel Agent</h3>
       <p class="mb-4 text-sm text-gray-600">Setujui pendaftaran <strong x-text="approvalUser?.name"></strong>. Tentukan link referral:</p>
       <div class="mb-4">
         <div class="flex rounded-md shadow-sm">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 bg-gray-50 text-gray-500 text-sm">{{ url('/') }}/u/</span>
            <input type="text" x-model="referralSlug" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm" placeholder="slug-travel">
         </div>
       </div>
       <div class="flex justify-end gap-2">
         <button @click="closeApproveModal()" class="px-4 py-2 text-sm text-gray-600">Batal</button>
         <button @click="submitApprove()" :disabled="!referralSlug" class="px-4 py-2 text-sm bg-green-600 text-white rounded disabled:opacity-50">Approve</button>
       </div>
    </div>
  </div>

  <!-- Reject Modal -->
  <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
       <h3 class="text-lg font-semibold mb-4 text-red-600">Tolak Pendaftaran?</h3>
       <p class="mb-4 text-sm text-gray-600">Yakin tolak <strong x-text="approvalUser?.name"></strong>? Status akan menjadi ditolak/banned.</p>
       <div class="flex justify-end gap-2">
         <button @click="closeRejectModal()" class="px-4 py-2 text-sm text-gray-600">Batal</button>
         <button @click="submitReject()" class="px-4 py-2 text-sm bg-red-600 text-white rounded">Ya, Tolak</button>
       </div>
    </div>
  </div>

  <!-- File Preview & User Detail Modals Placeholders (simplified for brevity but functional) -->
  <div x-show="fileModalOpen" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center bg-black/40" @click.self="closeFileModal()">
     <div class="bg-white p-4 rounded max-w-4xl max-h-[90vh] overflow-auto">
        <div class="flex justify-between mb-2">
            <h3 class="font-bold">Preview</h3>
            <button @click="closeFileModal()">Close</button>
        </div>
        <template x-if="fileModalType === 'image'">
            <img :src="fileModalSrc" class="max-w-full">
        </template>
        <template x-if="fileModalType === 'pdf'">
            <iframe :src="fileModalSrc" class="w-full h-[600px]"></iframe>
        </template>
     </div>
  </div>

  <div x-show="userDetailModalOpen" x-cloak class="fixed inset-0 z-[65] flex items-center justify-center bg-black/40" @click.self="closeUserDetail()">
     <div class="bg-white p-6 rounded max-w-2xl w-full max-h-[90vh] overflow-auto relative">
        <button @click="closeUserDetail()" class="absolute top-4 right-4 text-gray-500">Close</button>
        <h3 class="text-xl font-bold mb-4">Detail User</h3>
        <!-- Detail Content -->
        <div x-show="selectedUser" class="space-y-3">
             <div class="grid grid-cols-2 gap-4">
                 <div><strong>Nama:</strong> <span x-text="selectedUser?.name"></span></div>
                 <div><strong>Email:</strong> <span x-text="selectedUser?.email"></span></div>
                 <div><strong>Phone:</strong> <span x-text="selectedUser?.phone || '-'"></span></div>
                 <div><strong>Role:</strong> <span x-text="selectedUser?.role"></span></div>
             </div>
             <template x-if="selectedUser?.role === 'agent'">
                <div class="border-t pt-3 mt-3">
                    <h4 class="font-semibold mb-2">Travel Info</h4>
                     <div class="grid grid-cols-2 gap-4">
                         <div><strong>Travel:</strong> <span x-text="selectedUser?.travel_name"></span></div>
                         <div><strong>Izin:</strong> <span x-text="selectedUser?.ppiu ? 'Ada' : '-'"></span></div>
                         <div><strong>Lokasi:</strong> <span x-text="(selectedUser?.city || '') + ', ' + (selectedUser?.province || '')"></span></div>
                     </div>
                </div>
             </template>
        </div>
     </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function usersPage() {
    return {
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
        roleFilter: 'newusers',
        statusFilter: 'all',
        search: '',
        currentPage: 1,
        itemsPerPage: 10,
        
        // Modals
        approveModalOpen: false,
        rejectModalOpen: false,
        addAffiliateModalOpen: false,
        addFreelanceModalOpen: false,
        addTravelAgentModalOpen: false,
        userDetailModalOpen: false,
        fileModalOpen: false,
        
        // Data
        approvalUser: null,
        referralSlug: '',
        selectedUser: null,
        fileModalType: 'image',
        fileModalSrc: '',
        
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
            const params = new URLSearchParams(window.location.search);
            if(params.get('tab')) this.roleFilter = params.get('tab');
            
            @if(session('success')) alert("{{ session('success') }}"); @endif
            @if(session('error')) alert("{{ session('error') }}"); @endif
        },

        changeTab(role) {
            this.roleFilter = role;
            this.statusFilter = 'all';
            this.search = '';
            this.currentPage = 1;
        },

        get filteredUsers() {
            return this.users.filter(user => {
                // Search
                const s = this.search.toLowerCase();
                if (s && !user.name.toLowerCase().includes(s) && !user.email.toLowerCase().includes(s)) return false;

                if (this.roleFilter === 'newusers') return user.role === 'agent' && user.status === 'pending';
                if (this.roleFilter === 'agent') {
                    if (user.role !== 'agent') return false;
                    if (this.statusFilter !== 'all') return user.status === this.statusFilter;
                    return user.status !== 'pending';
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
        },
        
        get paginatedFilteredUsers() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredUsers.slice(start, start + this.itemsPerPage);  
        },
        
        get paginatedNewUsers() { return this.paginatedFilteredUsers; }, // Alias for new users partial
        get paginatedUsers() { return this.paginatedFilteredUsers; }, // Alias for other partials

        getReferralLink(user) {
            if (user.role === 'affiliate' || user.role === 'freelance') {
                const baseUrl = '{{ url('/agent') }}';
                const refParam = `${user.role}:${user.id}`;
                const nameParam = encodeURIComponent(user.name);
                return `${baseUrl}?ref=${refParam}&referrer_name=${nameParam}`;
            }
            return '{{ url('/') }}/u/' + user.referral_code;
        },

        // Modal Actions
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
                formData.append('link_referral', this.referralSlug);
                formData.append('_token', '{{ csrf_token() }}');
                
                const res = await fetch(`/admin/agents/${this.approvalUser.id}/approve`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
                const data = await res.json();
                if(data.success) window.location.reload();
                else alert(data.message || 'Error');
            } catch(e) { alert('Error approving user'); }
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
                if(data.success) window.location.reload();
                else alert(data.message || 'Error');
            } catch(e) { alert('Error rejecting user'); }
        },
        
        openFileModal(path) {
            if(!path) return;
            this.fileModalSrc = path.startsWith('http') ? path : `/storage/${path}`;
            this.fileModalType = path.endsWith('.pdf') ? 'pdf' : 'image';
            this.fileModalOpen = true;
        },
        closeFileModal() { this.fileModalOpen = false; },

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
             return this.users.filter(u => u.role === 'affiliate' || u.role === 'freelance');
        },
        selectDownline(d) { this.selectedDownline = d; },

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
        }
    }
}
</script>
@endpush

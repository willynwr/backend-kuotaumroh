@extends('layouts.admin')

@section('title', 'Kelola Users')

@section('content')
<div x-data="usersPage()">
  <!-- Header Component -->
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <!-- Page Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold tracking-tight">Kelola Users</h1>
      <p class="text-muted-foreground mt-2">Lihat dan kelola semua pengguna platform</p>
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
</div>
@endsection

@push('scripts')
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
      roleFilter: 'affiliate',
      statusFilter: 'all',
      search: '',
      currentPage: 1,
      itemsPerPage: 10,

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
        // Implement modal logic
        alert('Fitur tambah user akan segera ditambahkan');
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
      },

      init() {
        // Initialize
      }
    }
  }
</script>
@endpush

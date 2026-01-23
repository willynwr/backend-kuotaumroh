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
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 mb-6">
      <nav class="-mb-px flex space-x-8">
        <button 
          @click="activeTab = 'paket'"
          :class="activeTab === 'paket' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
          class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
          Paket
        </button>
        <button 
          @click="activeTab = 'margin'"
          :class="activeTab === 'margin' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
          class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
          Margin
        </button>
      </nav>
    </div>

    <!-- Paket Table -->
    <div class="rounded-lg border bg-white shadow-sm" x-show="activeTab === 'paket'">
      <div class="p-6">
        <!-- Filters -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
          <div class="relative w-full sm:w-auto">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" x-model="search" placeholder="Cari paket..." class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
          </div>
          <button @click="showAddModal = true" class="h-10 px-4 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
            + Tambah Paket
          </button>
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

    <!-- Margin Table -->
    <div class="rounded-lg border bg-white shadow-sm" x-show="activeTab === 'margin'">
      <div class="p-6">
        <!-- Filters -->
        <div class="flex flex-col gap-4 mb-6">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="relative w-full sm:w-auto">
              <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input type="text" x-model="search" @input="resetPagination()" placeholder="Cari margin..." class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[250px]">
            </div>
            <button @click="showAddMarginModal = true" class="h-10 px-4 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
              + Tambah Margin
            </button>
          </div>
          
          <!-- Filter Dropdowns -->
          <div class="flex flex-wrap gap-3">
            <!-- User Filter -->
            <div class="flex items-center gap-2">
              <label class="text-sm font-medium text-slate-600">User:</label>
              <div class="relative" @click.away="filterUserDropdownOpen = false">
                <button 
                  @click="filterUserDropdownOpen = !filterUserDropdownOpen"
                  type="button"
                  class="h-9 px-3 rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent min-w-[200px] flex items-center justify-between bg-white hover:bg-slate-50 transition-colors">
                  <span x-text="selectedUserName" class="truncate"></span>
                  <svg class="w-4 h-4 ml-2 transition-transform" :class="filterUserDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                
                <div 
                  x-show="filterUserDropdownOpen"
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="opacity-0 scale-95"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-95"
                  class="absolute z-50 mt-1 w-full bg-white border border-slate-300 rounded-lg shadow-lg overflow-hidden">
                  <!-- Search Input -->
                  <div class="border-b border-slate-200 p-3">
                    <div class="relative">
                      <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                      <input 
                        type="text" 
                        x-model="filterUserSearchQuery"
                        @click.stop
                        placeholder="Cari user..."
                        class="w-full h-9 pl-9 pr-3 border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                  </div>
                  
                  <!-- Options List -->
                  <div class="max-h-64 overflow-y-auto p-2">
                    <label class="flex items-center gap-2 cursor-pointer px-3 py-2 text-sm rounded-md hover:bg-primary/10 transition-colors border-b border-slate-100">
                      <input 
                        type="checkbox"
                        :checked="filterUser.length === allUsers.length && allUsers.length > 0"
                        @change="filterUser.length === allUsers.length ? filterUser = [] : filterUser = allUsers.map(u => u.type + '-' + u.id); resetPagination()"
                        class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                      <span class="font-semibold text-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pilih Semua User
                      </span>
                    </label>
                    
                    <template x-if="filteredUserOptions.length === 0">
                      <p class="text-sm text-gray-500 text-center py-4">Tidak ada user ditemukan</p>
                    </template>
                    
                    <template x-for="user in filteredUserOptions" :key="user.type + '-' + user.id">
                      <label class="flex items-center gap-2 cursor-pointer px-3 py-2 text-sm rounded-md hover:bg-primary/5 transition-colors">
                        <input 
                          type="checkbox"
                          :value="user.type + '-' + user.id"
                          x-model="filterUser"
                          @change="resetPagination()"
                          class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <div class="flex-1">
                          <div x-text="user.nama || user.name" class="font-medium"></div>
                          <div class="text-xs text-slate-500" x-text="user.type_label"></div>
                        </div>
                      </label>
                    </template>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Produk Filter -->
            <div class="flex items-center gap-2">
              <label class="text-sm font-medium text-slate-600">Produk:</label>
              <div class="relative" @click.away="filterProdukDropdownOpen = false">
                <button 
                  @click="filterProdukDropdownOpen = !filterProdukDropdownOpen"
                  type="button"
                  class="h-9 px-3 rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent min-w-[200px] flex items-center justify-between bg-white hover:bg-slate-50 transition-colors">
                  <span x-text="selectedProdukName" class="truncate"></span>
                  <svg class="w-4 h-4 ml-2 transition-transform" :class="filterProdukDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                
                <div 
                  x-show="filterProdukDropdownOpen"
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="opacity-0 scale-95"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-95"
                  class="absolute z-50 mt-1 w-full bg-white border border-slate-300 rounded-lg shadow-lg overflow-hidden">
                  <!-- Search Input -->
                  <div class="border-b border-slate-200 p-3">
                    <div class="relative">
                      <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                      <input 
                        type="text" 
                        x-model="filterProdukSearchQuery"
                        @click.stop
                        placeholder="Cari produk..."
                        class="w-full h-9 pl-9 pr-3 border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                  </div>
                  
                  <!-- Options List -->
                  <div class="max-h-64 overflow-y-auto p-2">
                    <label class="flex items-center gap-2 cursor-pointer px-3 py-2 text-sm rounded-md hover:bg-primary/10 transition-colors border-b border-slate-100">
                      <input 
                        type="checkbox"
                        :checked="filterProduk.length === products.length && products.length > 0"
                        @change="filterProduk.length === products.length ? filterProduk = [] : filterProduk = products.map(p => String(p.id)); resetPagination()"
                        class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                      <span class="font-semibold text-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pilih Semua Produk
                      </span>
                    </label>
                    
                    <template x-if="filteredProdukOptions.length === 0">
                      <p class="text-sm text-gray-500 text-center py-4">Tidak ada produk ditemukan</p>
                    </template>
                    
                    <template x-for="produk in filteredProdukOptions" :key="produk.id">
                      <label class="flex items-center gap-2 cursor-pointer px-3 py-2 text-sm rounded-md hover:bg-primary/5 transition-colors">
                        <input 
                          type="checkbox"
                          :value="String(produk.id)"
                          x-model="filterProduk"
                          @change="resetPagination()"
                          class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <div class="flex-1 font-medium" x-text="produk.nama_paket"></div>
                      </label>
                    </template>
                  </div>
                </div>
              </div>
            </div>
            
            <button 
              @click="search = ''; filterUser = []; filterProduk = []; resetPagination()" 
              x-show="search || filterUser.length > 0 || filterProduk.length > 0"
              class="h-9 px-3 rounded-md border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Reset Filter
            </button>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b bg-muted/50">
                <th class="px-4 py-3 text-left text-xs font-semibold text-muted-foreground">Aksi</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-muted-foreground">Agent/Affiliate/Freelance</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-muted-foreground">Produk</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Harga EUP</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">% Margin Star</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Margin Star</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Margin Total</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Fee Travel</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">% Fee Travel</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Fee Affiliate</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Fee Host</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Harga TP Travel</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Harga TP Host</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Poin</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-muted-foreground">Profit</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loading State -->
              <template x-if="loading">
                <tr>
                  <td colspan="15" class="p-8 text-center text-muted-foreground">
                    <div class="flex items-center justify-center gap-3">
                      <span>Memuat margin...</span>
                    </div>
                  </td>
                </tr>
              </template>
              <!-- Margin List -->
              <template x-if="!loading">
                <template x-for="margin in paginatedMargins" :key="margin.id">
                  <tr class="border-b transition-colors hover:bg-muted/30">
                    <td class="px-4 py-3 text-sm align-middle">
                      <div class="flex items-center gap-2">
                        <button @click="editMargin(margin)" class="inline-flex items-center justify-center w-8 h-8 rounded hover:bg-blue-50 text-blue-600" title="Edit">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        </button>
                        <button @click="deleteMargin(margin)" class="inline-flex items-center justify-center w-8 h-8 rounded hover:bg-red-50 text-red-600" title="Hapus">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm align-middle">
                      <span x-text="margin.agent_name || margin.affiliate_name || margin.freelance_name || '-'"></span>
                    </td>
                    <td class="px-4 py-3 text-sm align-middle" x-text="margin.produk_name || '-'"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.harga_eup)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="margin.persentase_margin_star + '%'"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.margin_star)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.margin_total)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.fee_travel)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="margin.persentase_fee_travel + '%'"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.fee_affiliate)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.fee_host)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.harga_tp_travel)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="formatRupiah(margin.harga_tp_host)"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right" x-text="margin.poin"></td>
                    <td class="px-4 py-3 text-sm align-middle text-right font-semibold" x-text="formatRupiah(margin.profit)"></td>
                  </tr>
                </template>
              </template>
              <!-- Empty State -->
              <template x-if="!loading && filteredMargins.length === 0">
                <tr>
                  <td colspan="15" class="p-8 text-center text-muted-foreground">Tidak ada margin ditemukan</td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4" x-show="filteredMargins.length > 0">
          <div class="text-sm text-slate-600">
            Menampilkan <span class="font-semibold" x-text="Math.min((currentPage - 1) * perPage + 1, filteredMargins.length)"></span> - 
            <span class="font-semibold" x-text="Math.min(currentPage * perPage, filteredMargins.length)"></span> 
            dari <span class="font-semibold" x-text="filteredMargins.length"></span> data
          </div>
          
          <div class="flex items-center gap-2">
            <button 
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
              :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-100'"
              class="h-9 px-3 rounded-md border border-slate-300 text-sm font-medium transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            
            <template x-for="page in totalPages" :key="page">
              <button 
                @click="goToPage(page)"
                x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
                :class="page === currentPage ? 'bg-primary text-white' : 'hover:bg-slate-100'"
                class="h-9 w-9 rounded-md border border-slate-300 text-sm font-medium transition-colors"
                x-text="page">
              </button>
            </template>
            
            <button 
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-100'"
              class="h-9 px-3 rounded-md border border-slate-300 text-sm font-medium transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Add/Edit Modal -->
  <div x-show="showAddModal || editingPackage" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto" @click.self="closeModal()"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 my-8"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
      <h3 class="text-lg font-semibold mb-6" x-text="editingPackage ? 'Edit Paket' : 'Tambah Paket Baru'"></h3>
      <form @submit.prevent="savePackage()">
        <div class="max-h-[60vh] overflow-y-auto pr-2 space-y-6">
          
          <!-- Informasi Dasar -->
          <div class="border-b pb-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Informasi Dasar
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Nama Paket</label>
                <input type="text" x-model="formData.nama_paket" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Tipe Paket</label>
                <select x-model="formData.tipe_paket" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                  <option value="">Pilih tipe paket</option>
                  <option value="INTERNET">INTERNET</option>
                  <option value="INTERNET + TELP/SMS">INTERNET + TELP/SMS</option>
                  <option value="TELP/SMS">TELP/SMS</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Masa Aktif (hari)</label>
                <input type="number" x-model="formData.masa_aktif" placeholder="30" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
            </div>
          </div>

          <!-- Detail Kuota -->
          <div class="border-b pb-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              Detail Kuota
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Total Kuota (GB)</label>
                <input type="number" x-model="formData.total_kuota" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Kuota Utama (GB)</label>
                <input type="number" x-model="formData.kuota_utama" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Kuota Bonus (GB)</label>
                <input type="number" x-model="formData.kuota_bonus" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
            </div>
          </div>

          <!-- Layanan Tambahan -->
          <div class="border-b pb-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              Layanan Tambahan
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Telp (menit)</label>
                <input type="number" x-model="formData.telp" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">SMS</label>
                <input type="number" x-model="formData.sms" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
            </div>
          </div>

          <!-- Harga -->
          <div class="pb-2">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Harga
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-1 text-slate-600">Harga Modal</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input 
                    type="text" 
                    x-model="displayHargaModal" 
                    @input="updateHargaModal($event.target.value)"
                    class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" 
                    placeholder="0"
                    required>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
          <button type="button" @click="closeModal()" class="h-10 px-4 border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
          <button type="submit" class="h-10 px-4 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Add/Edit Margin Modal -->
  <div x-show="showAddMarginModal || editingMargin" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto" @click.self="closeMarginModal()"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 my-8"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">
      <h3 class="text-lg font-semibold mb-6" x-text="editingMargin ? 'Edit Margin' : 'Tambah Margin Baru'"></h3>
      <form @submit.prevent="saveMargin()">
        <div class="max-h-[70vh] overflow-y-auto pr-2 space-y-6">
          
          <!-- Informasi Dasar -->
          <div class="border-b pb-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Informasi Dasar
            </h4>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-600">Tipe User</label>
                <div class="flex flex-wrap gap-3">
                  <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border-2 transition-all" :class="formDataMargin.user_types.includes('agent') ? 'border-primary bg-primary/10' : 'border-slate-200 hover:border-slate-300'">
                    <input type="checkbox" value="agent" x-model="formDataMargin.user_types" @change="userSearchQuery = ''; formDataMargin.select_all_users = false; formDataMargin.user_ids = []" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="text-sm font-medium">Agent</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border-2 transition-all" :class="formDataMargin.user_types.includes('affiliate') ? 'border-primary bg-primary/10' : 'border-slate-200 hover:border-slate-300'">
                    <input type="checkbox" value="affiliate" x-model="formDataMargin.user_types" @change="userSearchQuery = ''; formDataMargin.select_all_users = false; formDataMargin.user_ids = []" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="text-sm font-medium">Affiliate</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border-2 transition-all" :class="formDataMargin.user_types.includes('freelance') ? 'border-primary bg-primary/10' : 'border-slate-200 hover:border-slate-300'">
                    <input type="checkbox" value="freelance" x-model="formDataMargin.user_types" @change="userSearchQuery = ''; formDataMargin.select_all_users = false; formDataMargin.user_ids = []" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <span class="text-sm font-medium">Freelance</span>
                  </label>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium mb-2 text-slate-600">Pilih User</label>
                <div class="relative" @click.away="modalUserDropdownOpen = false">
                  <button 
                    type="button"
                    @click="modalUserDropdownOpen = !modalUserDropdownOpen"
                    :disabled="!formDataMargin.user_types.length"
                    class="w-full h-10 px-3 rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent flex items-center justify-between bg-white hover:bg-slate-50 transition-colors text-left"
                    :class="!formDataMargin.user_types.length ? 'opacity-50 cursor-not-allowed bg-gray-50' : ''">
                    <span class="truncate">
                      <template x-if="formDataMargin.user_ids.length === 0">
                        <span class="text-gray-500">Pilih user...</span>
                      </template>
                      <template x-if="formDataMargin.user_ids.length > 0">
                        <span class="font-medium text-primary" x-text="formDataMargin.user_ids.length + ' user dipilih'"></span>
                      </template>
                    </span>
                    <svg class="w-4 h-4 ml-2 transition-transform" :class="modalUserDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  
                  <div 
                    x-show="modalUserDropdownOpen"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 mt-1 w-full bg-white border border-slate-300 rounded-lg shadow-lg overflow-hidden">
                    <!-- Search Input -->
                    <div class="border-b border-slate-200 p-3">
                      <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input 
                          type="text" 
                          x-model="userSearchQuery"
                          @click.stop
                          placeholder="Cari user..."
                          class="w-full h-9 pl-9 pr-3 border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                      </div>
                    </div>
                    
                    <!-- User List -->
                    <div class="p-3 max-h-64 overflow-y-auto">
                      <div class="space-y-1">
                        <!-- Select All -->
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-primary/5 p-2.5 rounded-lg transition-colors border-b border-slate-100">
                          <input 
                            type="checkbox" 
                            x-model="formDataMargin.select_all_users"
                            @change="toggleSelectAllUsers()"
                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                          <span class="text-sm font-semibold text-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pilih Semua (<span x-text="userOptions.length"></span> user)
                          </span>
                        </label>
                        
                        <!-- Individual Users -->
                        <template x-if="userOptions.length === 0">
                          <p class="text-sm text-gray-500 text-center py-4">Tidak ada user ditemukan</p>
                        </template>
                        
                        <template x-for="user in userOptions" :key="user.type + '-' + user.id">
                          <label class="flex items-center gap-2 cursor-pointer hover:bg-primary/5 p-2.5 rounded-lg transition-colors">
                            <input 
                              type="checkbox" 
                              :value="user.type + '-' + user.id"
                              x-model="formDataMargin.user_ids"
                              @change="formDataMargin.select_all_users = false"
                              class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <div class="flex-1 min-w-0">
                              <div class="text-sm font-medium text-slate-700" x-text="user.nama || user.name"></div>
                              <div class="text-xs text-slate-500" x-text="user.type_label"></div>
                            </div>
                          </label>
                        </template>
                      </div>
                    </div>
                  </div>
                </div>
                
                <p class="text-xs text-slate-500 mt-1" x-show="!formDataMargin.user_types.length">
                  Pilih tipe user terlebih dahulu
                </p>
              </div>
              
              <div class="col-span-2">
                <label class="block text-sm font-medium mb-2 text-slate-600">Pilih Produk</label>
                <div class="relative" @click.away="modalProdukDropdownOpen = false">
                  <button 
                    type="button"
                    @click="modalProdukDropdownOpen = !modalProdukDropdownOpen"
                    class="w-full h-10 px-3 rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-primary focus:border-transparent flex items-center justify-between bg-white hover:bg-slate-50 transition-colors text-left">
                    <span class="truncate">
                      <template x-if="formDataMargin.produk_ids.length === 0">
                        <span class="text-gray-500">Pilih produk...</span>
                      </template>
                      <template x-if="formDataMargin.produk_ids.length > 0">
                        <span class="font-medium text-primary" x-text="formDataMargin.produk_ids.length + ' produk dipilih'"></span>
                      </template>
                    </span>
                    <svg class="w-4 h-4 ml-2 transition-transform" :class="modalProdukDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  
                  <div 
                    x-show="modalProdukDropdownOpen"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 mt-1 w-full bg-white border border-slate-300 rounded-lg shadow-lg overflow-hidden">
                    <!-- Search Input -->
                    <div class="border-b border-slate-200 p-3">
                      <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input 
                          type="text" 
                          x-model="produkSearchQuery"
                          @click.stop
                          placeholder="Cari produk..."
                          class="w-full h-9 pl-9 pr-3 border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                      </div>
                    </div>
                    
                    <!-- Produk List -->
                    <div class="p-3 max-h-64 overflow-y-auto">
                      <div class="space-y-1">
                        <!-- Select All -->
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-primary/5 p-2.5 rounded-lg transition-colors border-b border-slate-100">
                          <input 
                            type="checkbox" 
                            x-model="formDataMargin.select_all_products"
                            @change="toggleSelectAllProducts()"
                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                          <span class="text-sm font-semibold text-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pilih Semua (<span x-text="produkOptions.length"></span> produk)
                          </span>
                        </label>
                        
                        <!-- Individual Products -->
                        <template x-if="produkOptions.length === 0">
                          <p class="text-sm text-gray-500 text-center py-4">Tidak ada produk ditemukan</p>
                        </template>
                        
                        <template x-for="produk in produkOptions" :key="produk.id">
                          <label class="flex items-center gap-2 cursor-pointer hover:bg-primary/5 p-2.5 rounded-lg transition-colors">
                            <input 
                              type="checkbox" 
                              :value="produk.id"
                              x-model="formDataMargin.produk_ids"
                              @change="formDataMargin.select_all_products = false"
                              class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <div class="flex-1 min-w-0">
                              <div class="text-sm font-medium text-slate-700" x-text="produk.nama_paket"></div>
                            </div>
                          </label>
                        </template>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Harga & Margin -->
          <div class="border-b pb-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Harga & Margin
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Harga EUP</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input 
                    type="text" 
                    x-model="displayHargaEup" 
                    @input="updateHargaEup($event.target.value)"
                    class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" 
                    placeholder="0"
                    required>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">% Margin Star</label>
                <input type="number" step="0.01" x-model="formDataMargin.persentase_margin_star" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Margin Star</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.margin_star" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Margin Total</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.margin_total" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Fee Travel & Affiliate -->
          <div class="border-b pb-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              Fee Travel & Affiliate
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Fee Travel</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.fee_travel" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">% Fee Travel</label>
                <input type="number" step="0.01" x-model="formDataMargin.persentase_fee_travel" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Fee Affiliate</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.fee_affiliate" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">% Fee Affiliate</label>
                <input type="number" step="0.01" x-model="formDataMargin.persentase_fee_affiliate" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
            </div>
          </div>

          <!-- Fee Host & TP -->
          <div class="border-b pb-4">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
              Fee Host & Harga TP
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Fee Host</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.fee_host" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">% Fee Host</label>
                <input type="number" step="0.01" x-model="formDataMargin.persentase_fee_host" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Harga TP Travel</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.harga_tp_travel" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Harga TP Host</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.harga_tp_host" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Poin & Profit -->
          <div class="pb-2">
            <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
              Poin & Profit
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Poin</label>
                <input type="number" x-model="formDataMargin.poin" class="w-full h-10 rounded-md border border-slate-300 px-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1 text-slate-600">Profit</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                  <input type="number" x-model="formDataMargin.profit" class="w-full h-10 rounded-md border border-slate-300 pl-9 pr-3 focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
          <button type="button" @click="closeMarginModal()" :disabled="marginLoading" class="h-10 px-4 border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50 transition-colors" :class="marginLoading ? 'opacity-50 cursor-not-allowed' : ''">Batal</button>
          <button type="submit" :disabled="marginLoading" class="h-10 px-4 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors flex items-center gap-2" :class="marginLoading ? 'opacity-75 cursor-not-allowed' : ''">
            <svg x-show="marginLoading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="marginLoading ? 'Menyimpan...' : 'Simpan'"></span>
          </button>
        </div>
      </form>
      
      <!-- Loading Overlay -->
      <div x-show="marginLoading" class="absolute inset-0 bg-white/80 backdrop-blur-sm rounded-lg flex items-center justify-center z-50"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0">
        <div class="text-center">
          <svg class="animate-spin h-12 w-12 mx-auto text-primary" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-3 text-sm font-medium text-slate-700">Menyimpan margin...</p>
          <p class="mt-1 text-xs text-slate-500">Mohon tunggu sebentar</p>
        </div>
      </div>
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
      margins: @json($margins ?? []),
      agents: @json($agents ?? []),
      affiliates: @json($affiliates ?? []),
      freelances: @json($freelances ?? []),
      products: @json($products ?? []),
      activeTab: 'paket',
      search: '',
      loading: false,
      marginLoading: false,
      filterUser: [],
      filterProduk: [],
      currentPage: 1,
      perPage: 10,
      showAddModal: false,
      showAddMarginModal: false,
      editingPackage: null,
      editingMargin: null,
      displayHargaModal: '',
      displayHargaEup: '',
      userSearchQuery: '',
      produkSearchQuery: '',
      filterUserSearchQuery: '',
      filterProdukSearchQuery: '',
      filterUserDropdownOpen: false,
      filterProdukDropdownOpen: false,
      modalUserDropdownOpen: false,
      modalProdukDropdownOpen: false,
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
      formDataMargin: {
        user_types: [],
        user_ids: [],
        select_all_users: false,
        produk_ids: [],
        select_all_products: false,
        harga_eup: '',
        persentase_margin_star: '',
        margin_star: '',
        margin_total: '',
        fee_travel: '',
        persentase_fee_travel: '',
        persentase_fee_affiliate: '',
        fee_affiliate: '',
        persentase_fee_host: '',
        fee_host: '',
        harga_tp_travel: '',
        harga_tp_host: '',
        poin: '',
        profit: ''
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
      
      get filteredMargins() {
        let filtered = this.margins;
        
        // Filter by search
        if (this.search) {
          const searchLower = this.search.toLowerCase();
          filtered = filtered.filter(m => {
            const userName = (m.user_name || '').toLowerCase();
            const produkName = (m.produk_name || '').toLowerCase();
            return userName.includes(searchLower) || produkName.includes(searchLower);
          });
        }
        
        // Filter by users (array)
        if (this.filterUser && this.filterUser.length > 0) {
          filtered = filtered.filter(m => {
            const userId = m.agent_id || m.affiliate_id || m.freelance_id;
            const userType = m.agent_id ? 'agent' : (m.affiliate_id ? 'affiliate' : 'freelance');
            return this.filterUser.includes(userType + '-' + userId);
          });
        }
        
        // Filter by products (array)
        if (this.filterProduk && this.filterProduk.length > 0) {
          filtered = filtered.filter(m => this.filterProduk.includes(String(m.produk_id)));
        }
        
        return filtered;
      },
      
      get paginatedMargins() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        return this.filteredMargins.slice(start, end);
      },
      
      get totalPages() {
        return Math.ceil(this.filteredMargins.length / this.perPage);
      },
      
      get allUsers() {
        return [
          ...this.agents.map(u => ({ ...u, type: 'agent', type_label: 'Agent' })),
          ...this.affiliates.map(u => ({ ...u, type: 'affiliate', type_label: 'Affiliate' })),
          ...this.freelances.map(u => ({ ...u, type: 'freelance', type_label: 'Freelance' }))
        ];
      },
      
      get filteredUserOptions() {
        let users = this.allUsers;
        if (this.filterUserSearchQuery) {
          const query = this.filterUserSearchQuery.toLowerCase();
          users = users.filter(u => {
            const name = (u.nama || u.name || '').toLowerCase();
            return name.includes(query);
          });
        }
        return users;
      },
      
      get filteredProdukOptions() {
        let products = this.products || [];
        if (this.filterProdukSearchQuery) {
          const query = this.filterProdukSearchQuery.toLowerCase();
          products = products.filter(p => {
            const name = (p.nama_paket || '').toLowerCase();
            return name.includes(query);
          });
        }
        return products;
      },
      
      get selectedUserName() {
        if (!this.filterUser || this.filterUser.length === 0) return 'Semua User';
        if (this.filterUser.length === 1) {
          const user = this.allUsers.find(u => (u.type + '-' + u.id) === this.filterUser[0]);
          return user ? `${user.nama || user.name} (${user.type_label})` : 'Semua User';
        }
        return `${this.filterUser.length} User Dipilih`;
      },
      
      get selectedProdukName() {
        if (!this.filterProduk || this.filterProduk.length === 0) return 'Semua Produk';
        if (this.filterProduk.length === 1) {
          const produk = this.products.find(p => String(p.id) === this.filterProduk[0]);
          return produk ? produk.nama_paket : 'Semua Produk';
        }
        return `${this.filterProduk.length} Produk Dipilih`;
      },

      get userOptions() {
        let users = [];
        const types = this.formDataMargin.user_types || [];
        
        if (types.includes('agent')) {
          users = users.concat(this.agents.map(u => ({ ...u, type: 'agent', type_label: 'Agent' })));
        }
        if (types.includes('affiliate')) {
          users = users.concat(this.affiliates.map(u => ({ ...u, type: 'affiliate', type_label: 'Affiliate' })));
        }
        if (types.includes('freelance')) {
          users = users.concat(this.freelances.map(u => ({ ...u, type: 'freelance', type_label: 'Freelance' })));
        }
        
        // Filter by search query
        if (this.userSearchQuery) {
          const query = this.userSearchQuery.toLowerCase();
          users = users.filter(u => {
            const name = (u.nama || u.name || '').toLowerCase();
            return name.includes(query);
          });
        }
        
        return users;
      },
      
      get produkOptions() {
        let products = this.products || [];
        
        // Filter by search query
        if (this.produkSearchQuery) {
          const query = this.produkSearchQuery.toLowerCase();
          products = products.filter(p => {
            const name = (p.nama_produk || '').toLowerCase();
            return name.includes(query);
          });
        }
        
        return products;
      },

      toggleSelectAllUsers() {
        if (this.formDataMargin.select_all_users) {
          this.formDataMargin.user_ids = this.userOptions.map(u => u.type + '-' + u.id);
        } else {
          this.formDataMargin.user_ids = [];
        }
      },
      
      toggleSelectAllProducts() {
        if (this.formDataMargin.select_all_products) {
          this.formDataMargin.produk_ids = this.produkOptions.map(p => p.id);
        } else {
          this.formDataMargin.produk_ids = [];
        }
      },
      
      goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
          this.currentPage = page;
        }
      },
      
      resetPagination() {
        this.currentPage = 1;
      },
      
      goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
          this.currentPage = page;
        }
      },
      
      resetPagination() {
        this.currentPage = 1;
      },
      
      selectFilterUser(userIdStr) {
        if (!userIdStr) {
          // Clear all
          this.filterUser = [];
        } else {
          // Toggle checkbox
          const index = this.filterUser.indexOf(userIdStr);
          if (index > -1) {
            this.filterUser.splice(index, 1);
          } else {
            this.filterUser.push(userIdStr);
          }
        }
        this.resetPagination();
      },
      
      selectFilterProduk(produkId) {
        const produkIdStr = String(produkId);
        if (!produkId) {
          // Clear all
          this.filterProduk = [];
        } else {
          // Toggle checkbox
          const index = this.filterProduk.indexOf(produkIdStr);
          if (index > -1) {
            this.filterProduk.splice(index, 1);
          } else {
            this.filterProduk.push(produkIdStr);
          }
        }
        this.resetPagination();
      },

      formatRupiah(amount) {
        return `Rp ${parseInt(amount).toLocaleString('id-ID')}`;
      },

      formatToRupiah(value) {
        // Remove non-numeric characters
        const number = value.replace(/\D/g, '');
        if (!number) return '';
        // Format with thousand separators
        return parseInt(number).toLocaleString('id-ID');
      },

      updateHargaModal(value) {
        // Remove non-numeric characters and store as number
        const numericValue = value.replace(/\D/g, '');
        this.formData.harga_modal = numericValue;
        // Format display value
        this.displayHargaModal = this.formatToRupiah(value);
      },

      updateHargaEup(value) {
        // Remove non-numeric characters and store as number
        const numericValue = value.replace(/\D/g, '');
        this.formDataMargin.harga_eup = numericValue;
        // Format display value
        this.displayHargaEup = this.formatToRupiah(value);
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
        this.displayHargaModal = pkg.harga_modal ? parseInt(pkg.harga_modal).toLocaleString('id-ID') : '';
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

      editMargin(margin) {
        this.editingMargin = margin;
        
        const userType = margin.agent_id ? 'agent' : (margin.affiliate_id ? 'affiliate' : 'freelance');
        const userId = margin.agent_id || margin.affiliate_id || margin.freelance_id;
        
        // Populate formDataMargin with existing data
        this.formDataMargin = {
          user_types: [userType],
          user_ids: [userType + '-' + userId],
          select_all_users: false,
          produk_ids: [margin.produk_id],
          select_all_products: false,
          harga_eup: margin.harga_eup,
          persentase_margin_star: margin.persentase_margin_star,
          margin_star: margin.margin_star,
          margin_total: margin.margin_total,
          fee_travel: margin.fee_travel,
          persentase_fee_travel: margin.persentase_fee_travel,
          persentase_fee_affiliate: margin.persentase_fee_affiliate,
          fee_affiliate: margin.fee_affiliate,
          persentase_fee_host: margin.persentase_fee_host,
          fee_host: margin.fee_host,
          harga_tp_travel: margin.harga_tp_travel,
          harga_tp_host: margin.harga_tp_host,
          poin: margin.poin,
          profit: margin.profit
        };
        
        // Set display value for harga_eup
        this.displayHargaEup = margin.harga_eup ? parseInt(margin.harga_eup).toLocaleString('id-ID') : '';
        
        this.showAddMarginModal = true;
      },

      async deleteMargin(margin) {
        if (!confirm(`Apakah Anda yakin ingin menghapus margin ini?`)) {
          return;
        }

        try {
          const response = await fetch(`/admin/margins/${margin.id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          if (response.ok) {
            this.margins = this.margins.filter(m => m.id !== margin.id);
            this.showToast('Berhasil', 'Margin telah dihapus');
          } else {
            this.showToast('Error', 'Gagal menghapus margin');
          }
        } catch (error) {
          console.error('Error:', error);
          this.showToast('Error', 'Gagal menghapus margin');
        }
      },

      closeMarginModal() {
        this.showAddMarginModal = false;
        this.editingMargin = null;
        this.displayHargaEup = '';
        this.userSearchQuery = '';
        this.produkSearchQuery = '';
        this.modalUserDropdownOpen = false;
        this.modalProdukDropdownOpen = false;
        this.formDataMargin = {
          user_types: [],
          user_ids: [],
          select_all_users: false,
          produk_ids: [],
          select_all_products: false,
          harga_eup: '',
          persentase_margin_star: '',
          margin_star: '',
          margin_total: '',
          fee_travel: '',
          persentase_fee_travel: '',
          persentase_fee_affiliate: '',
          fee_affiliate: '',
          persentase_fee_host: '',
          fee_host: '',
          harga_tp_travel: '',
          harga_tp_host: '',
          poin: '',
          profit: ''
        };
      },

      async saveMargin() {
        // Validasi user type dan user id
        if (!this.formDataMargin.user_types || this.formDataMargin.user_types.length === 0) {
          this.showToast('Error', 'Minimal satu tipe user harus dipilih');
          return;
        }
        
        if (!this.formDataMargin.user_ids || this.formDataMargin.user_ids.length === 0) {
          this.showToast('Error', 'Minimal satu user harus dipilih');
          return;
        }
        
        if (!this.formDataMargin.produk_ids || this.formDataMargin.produk_ids.length === 0) {
          this.showToast('Error', 'Minimal satu produk harus dipilih');
          return;
        }
        
        // Validasi numeric fields tidak boleh kosong atau 0
        if (!this.formDataMargin.harga_eup || parseFloat(this.formDataMargin.harga_eup) <= 0) {
          this.showToast('Error', 'Harga EUP harus diisi dan lebih dari 0');
          return;
        }
        
        this.marginLoading = true;
        
        let successCount = 0;
        let failCount = 0;
        
        try {
          if (this.editingMargin) {
            // Edit mode - update multiple users × multiple products
            const totalCombinations = this.formDataMargin.user_ids.length * this.formDataMargin.produk_ids.length;

            for (const userIdStr of this.formDataMargin.user_ids) {
              for (const produkId of this.formDataMargin.produk_ids) {
                const [type, id] = userIdStr.split('-');
                
                // Find existing margin for this user-produk combination
                const existingMargin = this.margins.find(m => {
                  const mUserId = m.agent_id || m.affiliate_id || m.freelance_id;
                  const mUserType = m.agent_id ? 'agent' : (m.affiliate_id ? 'affiliate' : 'freelance');
                  return (mUserType + '-' + mUserId) === userIdStr && String(m.produk_id) === String(produkId);
                });

                const payload = {
                  ...this.formDataMargin,
                  produk_id: produkId,
                  agent_id: type === 'agent' ? id : null,
                  affiliate_id: type === 'affiliate' ? id : null,
                  freelance_id: type === 'freelance' ? id : null
                };

                try {
                  if (existingMargin) {
                    // Update existing margin
                    const response = await fetch(`/admin/margins/${existingMargin.id}`, {
                      method: 'PUT',
                      headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                      },
                      body: JSON.stringify(payload)
                    });

                    if (response.ok) {
                      const data = await response.json();
                      const index = this.margins.findIndex(m => m.id === existingMargin.id);
                      if (index !== -1) {
                        this.margins[index] = data;
                      }
                      successCount++;
                    } else {
                      failCount++;
                    }
                  } else {
                    // Create new margin if doesn't exist
                    const response = await fetch('/admin/margins', {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                      },
                      body: JSON.stringify(payload)
                    });

                    if (response.ok) {
                      const data = await response.json();
                      this.margins.push(data);
                      successCount++;
                    } else {
                      failCount++;
                    }
                  }
                } catch (error) {
                  failCount++;
                }
              }
            }

            if (successCount > 0) {
              this.showToast('Berhasil', `${successCount} dari ${totalCombinations} margin berhasil diupdate`);
            }
            if (failCount > 0) {
              this.showToast('Peringatan', `${failCount} margin gagal diupdate`);
            }
          } else {
            // Create mode - multiple users × multiple products
            const totalCombinations = this.formDataMargin.user_ids.length * this.formDataMargin.produk_ids.length;

            for (const userIdStr of this.formDataMargin.user_ids) {
              for (const produkId of this.formDataMargin.produk_ids) {
                const [type, id] = userIdStr.split('-');
                const payload = {
                  ...this.formDataMargin,
                  produk_id: produkId,
                  agent_id: type === 'agent' ? id : null,
                  affiliate_id: type === 'affiliate' ? id : null,
                  freelance_id: type === 'freelance' ? id : null
                };

                try {
                  const response = await fetch('/admin/margins', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                  });

                  if (response.ok) {
                    const data = await response.json();
                    this.margins.push(data);
                    successCount++;
                  } else {
                    failCount++;
                  }
                } catch (error) {
                  failCount++;
                }
              }
            }

            if (successCount > 0) {
              this.showToast('Berhasil', `${successCount} dari ${totalCombinations} margin berhasil ditambahkan`);
            }
            if (failCount > 0) {
              this.showToast('Peringatan', `${failCount} margin gagal ditambahkan`);
            }
          }
          
          this.marginLoading = false;
          this.closeMarginModal();
          
          // Refresh halaman setelah sukses
          if (successCount > 0) {
            setTimeout(() => {
              window.location.reload();
            }, 1500);
          }
        } catch (error) {
          console.error('Error:', error);
          this.marginLoading = false;
          this.showToast('Error', 'Terjadi kesalahan: ' + error.message);
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
        this.displayHargaModal = '';
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

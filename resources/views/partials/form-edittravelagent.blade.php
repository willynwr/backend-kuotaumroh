<div x-show="editTravelAgentModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
  <div class="absolute inset-0 bg-black/40" @click="closeEditTravelAgentModal()"></div>
  <div class="relative z-10 w-full max-w-5xl rounded-lg bg-white shadow-lg max-h-[90vh] overflow-y-auto">
    
    <!-- Header -->
    <div class="sticky top-0 z-10 bg-white border-b px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Travel Agent
          </h3>
          <p class="mt-1 text-sm text-muted-foreground">Update data travel agent</p>
        </div>
        <button type="button" @click="closeEditTravelAgentModal()" class="text-muted-foreground hover:text-foreground transition-colors">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    @if ($errors->any() && old('_form') === 'edit_agent')
      <div class="mx-6 mt-4 rounded-md border border-destructive/30 bg-destructive/5 p-4 text-sm text-destructive">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form x-ref="editTravelAgentForm" method="POST"
          :action="editingTravelAgent && editingTravelAgent.id ? `/admin/agent/${editingTravelAgent.id}` : '/admin/agent/0'"
          enctype="multipart/form-data"
          class="p-6 space-y-6">
      @csrf
      @method('PUT')
      <input type="hidden" name="_form" value="edit_agent">
      <input type="hidden" name="redirect_to" value="/admin/users?tab=agent">
      <input type="hidden" name="jenis_travel" :value="editingTravelAgent.travel_type">
      <input type="hidden" name="kategori_agent" :value="editingTravelAgent.kategori_agent">
      <input type="hidden" name="provinsi" x-bind:value="editingTravelAgent.province">
      <input type="hidden" name="kabupaten_kota" x-bind:value="editingTravelAgent.city">
      <input type="hidden" name="latitude" x-bind:value="editingTravelAgent.latitude">
      <input type="hidden" name="longitude" x-bind:value="editingTravelAgent.longitude">
      <input type="hidden" name="affiliate_id" :value="selectedDownlineEdit && selectedDownlineEdit.type === 'Affiliate' ? selectedDownlineEdit.id : ''">
      <input type="hidden" name="freelance_id" :value="selectedDownlineEdit && selectedDownlineEdit.type === 'Freelance' ? selectedDownlineEdit.id : ''">

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-6">
          <!-- Contact Information Section -->
          <div class="space-y-4">
            <h4 class="font-semibold text-slate-900 border-b pb-2 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              Informasi Kontak
            </h4>
            
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nama PIC <span class="text-red-500">*</span></label>
              <input type="text" name="nama_pic" x-model="editingTravelAgent.full_name" required
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
              <input type="email" name="email" x-model="editingTravelAgent.email" required
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">No. HP (+62) <span class="text-red-500">*</span></label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <span class="text-sm text-muted-foreground">+62</span>
                </div>
                <input type="tel" name="no_hp" x-model="editingTravelAgent.phoneClean" required placeholder="81xxx"
                  class="flex h-10 w-full rounded-md border border-input bg-background pl-12 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              </div>
              <p class="text-xs text-muted-foreground mt-1">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
          <!-- Travel Details Section -->
          <div class="space-y-4">
            <h4 class="font-semibold text-slate-900 border-b pb-2 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              Detail Travel
            </h4>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nama Travel <span class="text-red-500">*</span></label>
              <input type="text" name="nama_travel" x-model="editingTravelAgent.travel_name" required
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>

            <div x-data="{ travelTypeOpen: false }">
              <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Travel <span class="text-red-500">*</span></label>
              <div class="relative" @click.away="travelTypeOpen = false">
                <button type="button" @click="travelTypeOpen = !travelTypeOpen"
                  class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                  <span :class="!editingTravelAgent.travel_type && 'text-muted-foreground'" x-text="editingTravelAgent.travel_type || 'Pilih jenis travel'"></span>
                  <svg class="h-4 w-4 transition-transform" :class="travelTypeOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div x-show="travelTypeOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                  <div class="py-1">
                    <button type="button" @click="editingTravelAgent.travel_type = 'UMROH'; travelTypeOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="editingTravelAgent.travel_type === 'UMROH' && 'bg-primary/10 text-primary'">UMROH</button>
                    <button type="button" @click="editingTravelAgent.travel_type = 'LEISURE'; travelTypeOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="editingTravelAgent.travel_type === 'LEISURE' && 'bg-primary/10 text-primary'">LEISURE</button>
                    <button type="button" @click="editingTravelAgent.travel_type = 'UMROH LEISURE'; travelTypeOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="editingTravelAgent.travel_type === 'UMROH LEISURE' && 'bg-primary/10 text-primary'">UMROH & LEISURE</button>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Total Traveller per Bulan <span class="text-red-500">*</span></label>
              <input type="number" name="total_traveller" x-model="editingTravelAgent.monthly_travellers" required min="0"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>

            <!-- Logo moved to File Uploads section -->
          </div>
        </div>
      </div>

      <!-- Agent Category & Downline Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-6">
        <div x-data="{ kategoriOpen: false }">
          <label class="block text-sm font-medium text-slate-700 mb-1">Kategori Agent <span class="text-red-500">*</span></label>
          <div class="relative" @click.away="kategoriOpen = false">
            <button type="button" @click="kategoriOpen = !kategoriOpen"
              class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <span :class="!editingTravelAgent.kategori_agent && 'text-muted-foreground'" x-text="editingTravelAgent.kategori_agent || 'Pilih kategori'"></span>
              <svg class="h-4 w-4 transition-transform" :class="kategoriOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div x-show="kategoriOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
              <div class="py-1">
                <button type="button" @click="editingTravelAgent.kategori_agent = 'Referral'; kategoriOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="editingTravelAgent.kategori_agent === 'Referral' && 'bg-primary/10 text-primary'">Referral</button>
                <button type="button" @click="editingTravelAgent.kategori_agent = 'Host'; kategoriOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="editingTravelAgent.kategori_agent === 'Host' && 'bg-primary/10 text-primary'">Host</button>
              </div>
            </div>
          </div>
        </div>

        <div x-data="{ downlineOpen: false, downlineSearch: '' }">
          <label class="block text-sm font-medium text-slate-700 mb-1">Downline (Opsional)</label>
          <div class="relative" @click.away="downlineOpen = false">
            <button type="button" @click="downlineOpen = !downlineOpen"
              class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <span :class="!selectedDownlineEdit && 'text-muted-foreground'" x-text="selectedDownlineEdit ? selectedDownlineEdit.name + ' (' + selectedDownlineEdit.type + ')' : 'Pilih downline'"></span>
              <svg class="h-4 w-4 transition-transform" :class="downlineOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div x-show="downlineOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
              <div class="p-2 border-b">
                <input type="text" x-model="downlineSearch" @click.stop placeholder="Cari downline..." class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              </div>
              <div class="max-h-60 overflow-y-auto">
                <template x-for="downline in downlines.filter(d => d.name.toLowerCase().includes(downlineSearch.toLowerCase()))" :key="downline.id + '-' + downline.type">
                  <button type="button" @click="selectDownlineEdit(downline); downlineOpen = false; downlineSearch = ''" class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="selectedDownlineEdit && selectedDownlineEdit.id === downline.id && selectedDownlineEdit.type === downline.type && 'bg-muted'">
                    <div class="font-medium" x-text="downline.name"></div>
                    <div class="text-xs text-muted-foreground" x-text="downline.type + ' - ' + downline.email"></div>
                  </button>
                </template>
                <div x-show="downlines.filter(d => d.name.toLowerCase().includes(downlineSearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada downline ditemukan</div>
              </div>
            </div>
          </div>
          <p class="text-xs text-muted-foreground mt-1">Pilih affiliate atau freelance sebagai downline</p>
        </div>
      </div>

      <!-- Address Information Section -->
      <div class="space-y-4 border-t pt-6">
        <h4 class="font-semibold text-slate-900 border-b pb-2 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Informasi Alamat
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div x-data="{ provinceDropdownOpen: false, provinceSearch: '' }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="provinceDropdownOpen = false">
              <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                <span :class="!editingTravelAgent.province && 'text-muted-foreground'" x-text="editingTravelAgent.province || 'Pilih provinsi'"></span>
                <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="provinceDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                <div class="p-2 border-b"><input type="text" x-model="provinceSearch" @click.stop placeholder="Cari provinsi..." class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></div>
                <div class="max-h-60 overflow-y-auto">
                  <template x-for="province in provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase()))" :key="province">
                    <button type="button" @click="editingTravelAgent.province = province; loadCitiesForEditAgent(province); provinceDropdownOpen = false; provinceSearch = ''"
                      class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="editingTravelAgent.province === province && 'bg-muted'" x-text="province"></button>
                  </template>
                  <div x-show="provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada provinsi ditemukan</div>
                </div>
              </div>
            </div>
          </div>

          <div x-data="{ cityDropdownOpen: false, citySearch: '' }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="cityDropdownOpen = false">
              <button type="button" @click="cityDropdownOpen = !cityDropdownOpen" :disabled="!editingTravelAgent.province"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-60">
                <span :class="!editingTravelAgent.city && 'text-muted-foreground'" x-text="editingTravelAgent.city || (editingTravelAgent.province ? 'Pilih kota/kabupaten' : 'Pilih provinsi dulu')"></span>
                <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="cityDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                <div class="p-2 border-b"><input type="text" x-model="citySearch" @click.stop placeholder="Cari kota/kabupaten..." class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></div>
                <div class="max-h-60 overflow-y-auto">
                  <template x-for="city in citiesTravelAgent.filter(c => c.toLowerCase().includes(citySearch.toLowerCase()))" :key="city">
                    <button type="button" @click="editingTravelAgent.city = city; cityDropdownOpen = false; citySearch = ''"
                      class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="editingTravelAgent.city === city && 'bg-muted'" x-text="city"></button>
                  </template>
                  <div x-show="citiesTravelAgent.filter(c => c.toLowerCase().includes(citySearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada kota ditemukan</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
          <textarea name="alamat_lengkap" x-model="editingTravelAgent.address" required rows="3" placeholder="Jl. Contoh No. 123"
            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
        </div>

        <!-- Map Section -->
        <div x-show="editingTravelAgent.city" class="space-y-2">
          <div class="flex items-center justify-between">
            <div>
              <label class="text-sm font-medium text-slate-700">Tandai Lokasi di Peta</label>
              <p class="text-xs text-muted-foreground">Aktifkan peta lalu klik pada peta untuk menandai lokasi</p>
            </div>
            <!-- Map Lock/Unlock Button -->
            <button type="button" @click="toggleMapLockEditAgent()" 
              class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-md border transition-colors"
              :class="mapLockedEditAgent ? 'bg-muted text-muted-foreground border-input hover:bg-muted/80' : 'bg-primary text-primary-foreground border-primary hover:bg-primary/90'">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mapLockedEditAgent">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mapLockedEditAgent">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
              </svg>
              <span x-text="mapLockedEditAgent ? 'Klik untuk Geser Peta' : 'Kunci Peta'"></span>
            </button>
          </div>

          <!-- Map Search -->
          <div class="relative mb-2">
            <div class="relative">
              <input type="text" x-model="mapSearchQueryEditAgent" @input="handleMapSearchEditAgent()"
                @keydown.enter.prevent=""
                placeholder="Cari lokasi (contoh: Monas, Jalan Sudirman)"
                class="flex h-10 w-full rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-muted-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <div class="absolute inset-y-0 right-0 flex items-center pr-3" x-show="isSearchingMapEditAgent">
                <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
            </div>

            <!-- Search Results Dropdown -->
            <div x-show="mapSearchResultsEditAgent.length > 0" @click.away="mapSearchResultsEditAgent = []"
              class="absolute z-40 w-full mt-1 bg-white rounded-lg border border-gray-200 shadow-xl max-h-80 overflow-y-auto" style="display: none;">
              <div class="py-1">
                <template x-for="(result, index) in mapSearchResultsEditAgent" :key="index">
                  <button type="button" @click="selectMapLocationEditAgent(result)"
                    class="w-full text-left px-4 py-3 hover:bg-green-50 transition-colors border-b border-gray-100 last:border-0 group">
                    <div class="flex items-start gap-3">
                      <div class="flex-shrink-0 mt-0.5">
                        <svg class="h-5 w-5 text-green-600 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-900 group-hover:text-green-700 mb-0.5" 
                          x-text="result.name || (result.description ? result.description.split(',')[0] : result.display_name)"></div>
                        <div class="text-xs text-gray-500 truncate leading-relaxed" x-text="result.description || result.display_name"></div>
                      </div>
                      <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                      </div>
                    </div>
                  </button>
                </template>
              </div>
            </div>
          </div>
          
          <!-- Map Container with Overlay -->
          <div class="relative">
            <div id="map-edit-agent" class="w-full h-80 rounded-md border border-input overflow-hidden bg-gray-100"></div>
            
            <!-- Locked Overlay -->
            <div x-show="mapLockedEditAgent" @click="toggleMapLockEditAgent()"
              class="absolute inset-0 bg-black/10 backdrop-blur-[1px] rounded-md cursor-pointer flex items-center justify-center transition-opacity hover:bg-black/20 group">
              <div class="bg-white/95 px-6 py-4 rounded-lg shadow-lg border border-primary/20 text-center max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <p class="text-sm font-semibold text-foreground mb-1">Peta Dikunci</p>
                <p class="text-xs text-muted-foreground leading-relaxed">Peta tidak akan bergerak saat Anda scroll halaman. Klik di sini untuk mengaktifkan peta agar bisa digeser dan di-zoom.</p>
              </div>
            </div>
          </div>

          <!-- Coordinates Input -->
          <div class="grid grid-cols-2 gap-4 pt-2">
            <div class="space-y-2">
              <label class="text-sm font-medium text-slate-700">Latitude</label>
              <input type="number" step="any" x-model.number="editingTravelAgent.latitude"
                @input="updateMapFromCoordinatesEditAgent()" placeholder="-6.xxxxx"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-slate-700">Longitude</label>
              <input type="number" step="any" x-model.number="editingTravelAgent.longitude"
                @input="updateMapFromCoordinatesEditAgent()" placeholder="106.xxxxx"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
          </div>
        </div>
      </div>

      <!-- File Uploads -->
      <div class="space-y-4 border-t pt-6">
        <h4 class="font-semibold text-slate-900 border-b pb-2 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          Dokumen
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Logo -->
            <div x-data="{ logoPreview: editingTravelAgent.logo_url ? '/storage/' + editingTravelAgent.logo_url : null, isNewLogo: false }">
              <label class="block text-sm font-medium text-slate-700 mb-1">Logo Travel</label>
              <div class="space-y-2">
                <!-- Current Logo Preview -->
                <div x-show="logoPreview && !isNewLogo" class="mb-2">
                  <p class="text-xs text-muted-foreground mb-2">Logo saat ini:</p>
                  <div class="relative inline-block group">
                    <img :src="logoPreview" alt="Logo Travel" class="h-20 w-auto object-contain border rounded-md shadow-sm bg-white p-2">
                    <button type="button" @click="window.open(logoPreview, '_blank')"
                      class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-md">
                      <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                  </div>
                </div>

                <input type="file" name="logo" accept="image/*" 
                  @change="
                    const file = $event.target.files[0];
                    if (file) {
                      isNewLogo = true;
                      const reader = new FileReader();
                      reader.onload = (e) => { logoPreview = e.target.result; };
                      reader.readAsDataURL(file);
                    }
                  "
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-muted-foreground">Format: JPG, PNG, GIF, SVG (Max: 2MB). Kosongkan jika tidak ingin mengubah.</p>
                
                <!-- New Logo Preview -->
                <div x-show="logoPreview && isNewLogo" class="mt-3">
                  <p class="text-xs text-green-600 font-medium mb-2">Logo baru dipilih:</p>
                  <div class="relative inline-block">
                    <img :src="logoPreview" alt="Preview Logo Baru" class="h-20 w-auto object-contain border border-green-500 rounded-md shadow-sm bg-white p-2">
                    <button type="button" @click="logoPreview = editingTravelAgent.logo_url ? '/storage/' + editingTravelAgent.logo_url : null; isNewLogo = false; $event.target.closest('div[x-data]').querySelector('input[type=file]').value = ''"
                      class="absolute -top-2 -right-2 bg-destructive text-white rounded-full p-1.5 hover:bg-destructive/90 transition-colors shadow-md">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

          <!-- Surat PPIU -->
          <div class="space-y-2" x-data="{ ppiuPreview: editingTravelAgent.ppiu_url ? '/storage/' + editingTravelAgent.ppiu_url : null, ppiuType: editingTravelAgent.ppiu_url ? (editingTravelAgent.ppiu_url.endsWith('.pdf') ? 'application/pdf' : 'image') : null, isNewPpiu: false }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Surat PPIU (Opsional)</label>
            
            <!-- Current PPIU Preview -->
            <div x-show="ppiuPreview && !isNewPpiu" class="mb-2">
              <p class="text-xs text-muted-foreground mb-2">Surat PPIU saat ini:</p>
              <div x-show="ppiuType === 'image'" class="relative inline-block group">
                <img :src="ppiuPreview" alt="PPIU Saat Ini" class="h-20 w-auto object-contain border rounded-md shadow-sm bg-white p-2">
                <button type="button" @click="window.open(ppiuPreview, '_blank')"
                  class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-md">
                  <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
              <div x-show="ppiuType === 'application/pdf'" class="inline-block">
                <a :href="ppiuPreview" target="_blank" class="flex items-center gap-2 p-2 border rounded-md bg-gray-50 hover:bg-gray-100 transition-colors">
                  <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                  <div class="flex-1">
                    <p class="text-xs font-medium text-gray-900">Surat PPIU (PDF)</p>
                    <p class="text-xs text-gray-500">Klik untuk lihat</p>
                  </div>
                </a>
              </div>
            </div>

            <input type="file" name="surat_ppiu" accept="image/*,application/pdf"
              @change="
                const file = $event.target.files[0];
                if (file) {
                  isNewPpiu = true;
                  ppiuType = file.type;
                  if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => { ppiuPreview = e.target.result; };
                    reader.readAsDataURL(file);
                  } else {
                    ppiuPreview = 'pdf';
                  }
                }
              "
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium file:cursor-pointer hover:file:text-primary">
            <p class="text-xs text-muted-foreground">PNG, JPG, PDF (Max: 2MB). Kosongkan jika tidak ingin mengubah.</p>
            
            <!-- New PPIU Preview -->
            <div x-show="ppiuPreview && isNewPpiu" class="mt-2">
              <p class="text-xs text-green-600 font-medium mb-2">File baru dipilih:</p>
              <div x-show="ppiuType && ppiuType.startsWith('image/')" class="relative inline-block">
                <img :src="ppiuPreview" alt="Preview PPIU Baru" class="h-20 w-auto object-contain border border-green-500 rounded-md shadow-sm bg-white p-2">
                <button type="button" @click="ppiuPreview = editingTravelAgent.ppiu_url ? '/storage/' + editingTravelAgent.ppiu_url : null; ppiuType = editingTravelAgent.ppiu_url ? (editingTravelAgent.ppiu_url.endsWith('.pdf') ? 'application/pdf' : 'image') : null; isNewPpiu = false; $event.target.closest('.space-y-2').querySelector('input[type=file]').value = ''"
                  class="absolute -top-2 -right-2 bg-destructive text-white rounded-full p-1.5 hover:bg-destructive/90 transition-colors shadow-md">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div x-show="ppiuType && ppiuType === 'application/pdf'" class="relative inline-block">
                <div class="flex items-center gap-2 p-2 border border-green-500 rounded-md bg-green-50">
                  <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                  <div class="flex-1">
                    <p class="text-xs font-medium text-gray-900">Surat PPIU Baru (PDF)</p>
                  </div>
                  <button type="button" @click="ppiuPreview = editingTravelAgent.ppiu_url ? '/storage/' + editingTravelAgent.ppiu_url : null; ppiuType = editingTravelAgent.ppiu_url ? (editingTravelAgent.ppiu_url.endsWith('.pdf') ? 'application/pdf' : 'image') : null; isNewPpiu = false; $event.target.closest('.space-y-2').querySelector('input[type=file]').value = ''"
                    class="bg-destructive text-white rounded-full p-1.5 hover:bg-destructive/90 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="sticky bottom-0 bg-white border-t -mx-6 -mb-6 px-6 py-4 flex items-center justify-end gap-3">
        <button type="button" @click="closeEditTravelAgentModal()" class="h-10 px-6 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
        <button type="submit" class="h-10 px-6 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Update</button>
      </div>
    </form>
  </div>
</div>



<div x-show="addAffiliateModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center"
  x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="absolute inset-0 bg-black/40" @click="closeAddAffiliateModal()"></div>
  <div class="relative z-10 w-full max-w-4xl rounded-lg bg-white shadow-lg max-h-[90vh] overflow-y-auto"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    
    <!-- Header -->
    <div class="sticky top-0 z-10 bg-white border-b px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Tambah Affiliate Baru
          </h3>
          <p class="mt-1 text-sm text-muted-foreground">Isi form di bawah untuk menambahkan affiliate baru ke sistem</p>
        </div>
        <button @click="closeAddAffiliateModal()" class="text-muted-foreground hover:text-foreground transition-colors">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    @if ($errors->any() && old('_form') === 'affiliate')
      <div class="mx-6 mt-4 rounded-md border border-destructive/30 bg-destructive/5 p-4 text-sm text-destructive">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form x-ref="addAffiliateForm" method="POST" action="{{ route('admin.affiliates.store') }}" @submit.prevent="openConfirmAddModal()" class="p-6 space-y-6">
      @csrf
      <input type="hidden" name="_form" value="affiliate">
      <input type="hidden" name="redirect_to" value="/admin/users?tab=affiliate">
      <input type="hidden" name="provinsi" :value="newAffiliate.provinsi">
      <input type="hidden" name="kab_kota" :value="newAffiliate.kab_kota">
      <input type="hidden" name="latitude" :value="newAffiliate.latitude">
      <input type="hidden" name="longitude" :value="newAffiliate.longitude">

      <!-- Personal Information Section -->
      <div class="space-y-4">
        <h4 class="font-semibold text-slate-900 border-b pb-2 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          Informasi Pribadi
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="nama" x-model="newAffiliate.nama" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" x-model="newAffiliate.email" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">No. WhatsApp (+62) <span class="text-red-500">*</span></label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="text-sm text-muted-foreground">+62</span>
              </div>
              <input type="tel" name="no_wa" x-model="newAffiliate.no_wa" @input="newAffiliate.no_wa = newAffiliate.no_wa.replace(/[^0-9]/g, '')" required placeholder="81xxx"
                class="flex h-10 w-full rounded-md border border-input bg-background pl-12 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
            <p class="text-xs text-muted-foreground mt-1">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Link Referral <span class="text-red-500">*</span></label>
            <input type="text" name="link_referral" x-model="newAffiliate.link_referral" required placeholder="john123"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>
        </div>
      </div>

      <!-- Address Information Section -->
      <div class="space-y-4">
        <h4 class="font-semibold text-slate-900 border-b pb-2 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Informasi Alamat
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div x-data="{ provinceDropdownOpen: false, provinceSearch: '' }" x-init="$watch('provinceDropdownOpen', value => { if (value) $nextTick(() => $refs.provinceSearchInput.focus()) })">
            <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="provinceDropdownOpen = false">
              <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                <span :class="!newAffiliate.provinsi && 'text-muted-foreground'" x-text="newAffiliate.provinsi || 'Pilih provinsi'"></span>
                <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="provinceDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                <div class="p-2 border-b">
                  <input type="text" x-ref="provinceSearchInput" x-model="provinceSearch" @click.stop placeholder="Cari provinsi..."
                    class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="max-h-60 overflow-y-auto">
                  <template x-for="province in provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase()))" :key="province">
                    <button type="button" @click="newAffiliate.provinsi = province; handleProvinceChange(); provinceDropdownOpen = false; provinceSearch = ''"
                      class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="newAffiliate.provinsi === province && 'bg-muted'" x-text="province"></button>
                  </template>
                  <div x-show="provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada provinsi ditemukan</div>
                </div>
              </div>
            </div>
          </div>

          <div x-data="{ cityDropdownOpen: false, citySearch: '' }" x-init="$watch('cityDropdownOpen', value => { if (value) $nextTick(() => $refs.citySearchInput.focus()) })">
            <label class="block text-sm font-medium text-slate-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="cityDropdownOpen = false">
              <button type="button" @click="cityDropdownOpen = !cityDropdownOpen" :disabled="!newAffiliate.provinsi"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-60">
                <span :class="!newAffiliate.kab_kota && 'text-muted-foreground'" x-text="newAffiliate.kab_kota || (newAffiliate.provinsi ? 'Pilih kota/kabupaten' : 'Pilih provinsi dulu')"></span>
                <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="cityDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                <div class="p-2 border-b">
                  <input type="text" x-ref="citySearchInput" x-model="citySearch" @click.stop placeholder="Cari kota/kabupaten..."
                    class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>
                <div class="max-h-60 overflow-y-auto">
                  <template x-for="city in cities.filter(c => c.toLowerCase().includes(citySearch.toLowerCase()))" :key="city">
                    <button type="button" @click="newAffiliate.kab_kota = city; handleCityChangeAffiliate(); cityDropdownOpen = false; citySearch = ''"
                      class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="newAffiliate.kab_kota === city && 'bg-muted'" x-text="city"></button>
                  </template>
                  <div x-show="cities.filter(c => c.toLowerCase().includes(citySearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada kota ditemukan</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
          <textarea name="alamat_lengkap" x-model="newAffiliate.alamat_lengkap" required rows="3" placeholder="Jl. Contoh No. 123"
            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
        </div>

        <!-- Map Section -->
        <div x-show="newAffiliate.kab_kota" class="space-y-2">
          <label class="text-sm font-medium text-slate-700">Tandai Lokasi di Peta</label>
          <p class="text-xs text-muted-foreground">Klik pada peta untuk menandai lokasi Anda</p>
          
          <!-- Map Container -->
          <div :id="'map-affiliate'" class="w-full h-80 rounded-md border border-input overflow-hidden"
            x-init="$nextTick(() => { if (newAffiliate.kab_kota && !mapAffiliateInitialized) initializeMapAffiliate(); })"></div>

          <!-- Coordinates Input -->
          <div class="grid grid-cols-2 gap-4 pt-2">
            <div class="space-y-2">
              <label class="text-sm font-medium text-slate-700">Latitude</label>
              <input type="number" step="any" x-model.number="newAffiliate.latitude"
                @input="updateMapFromCoordinatesAffiliate()" placeholder="-6.xxxxx"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-slate-700">Longitude</label>
              <input type="number" step="any" x-model.number="newAffiliate.longitude"
                @input="updateMapFromCoordinatesAffiliate()" placeholder="106.xxxxx"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="sticky bottom-0 bg-white border-t -mx-6 -mb-6 px-6 py-4 flex items-center justify-end gap-3">
        <button type="button" @click="closeAddAffiliateModal()" class="h-10 px-6 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
        <button type="submit" class="h-10 px-6 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Confirmation Modal -->
<div x-show="confirmAddModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center"
  x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="absolute inset-0 bg-black/40" @click="closeConfirmAddModal()"></div>
  <div class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Tambah Affiliate</h3>
    <p class="mt-2 text-sm text-muted-foreground">Apakah Anda yakin ingin menambahkan affiliate baru dengan data yang telah diisi?</p>
    <div class="mt-6 flex items-center justify-end gap-3">
      <button @click="closeConfirmAddModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
      <button @click="confirmAddAffiliate()" class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Ya, Simpan</button>
    </div>
  </div>
</div>

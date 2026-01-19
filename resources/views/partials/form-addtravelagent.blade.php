<div x-show="addTravelAgentModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center"
  x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="absolute inset-0 bg-black/40" @click="closeAddTravelAgentModal()"></div>
  <div class="relative z-10 w-full max-w-4xl rounded-lg bg-white shadow-lg p-6 max-h-[90vh] overflow-y-auto"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <h3 class="text-lg font-semibold text-slate-900">Tambah Travel Agent Baru</h3>
    <p class="mt-2 text-sm text-muted-foreground">Isi form di bawah untuk menambahkan travel agent baru.</p>

    @if ($errors->any() && old('_form') === 'agent')
      <div class="mt-4 rounded-md border border-destructive/30 bg-destructive/5 p-4 text-sm text-destructive">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form x-ref="addTravelAgentForm" method="POST" action="{{ route('admin.agents.store') }}" @submit.prevent="openConfirmAddTravelAgentModal()" class="mt-6 space-y-6">
      @csrf
      <input type="hidden" name="_form" value="agent">
      <input type="hidden" name="redirect_to" value="/admin/users?tab=agent">
      <input type="hidden" name="jenis_travel" :value="newTravelAgent.travel_type">
      <input type="hidden" name="kategori_agent" :value="newTravelAgent.kategori_agent">
      <input type="hidden" name="provinsi" :value="newTravelAgent.province">
      <input type="hidden" name="kabupaten_kota" :value="newTravelAgent.city">
      <input type="hidden" name="affiliate_id" :value="selectedDownline && selectedDownline.type === 'Affiliate' ? selectedDownline.id : ''">
      <input type="hidden" name="freelance_id" :value="selectedDownline && selectedDownline.type === 'Freelance' ? selectedDownline.id : ''">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
          <h4 class="font-medium text-slate-900 border-b pb-2">Informasi Kontak</h4>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama PIC <span class="text-red-500">*</span></label>
            <input type="text" name="nama_pic" x-model="newTravelAgent.full_name" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" x-model="newTravelAgent.email" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">No. HP (+62) <span class="text-red-500">*</span></label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="text-sm text-muted-foreground">+62</span>
              </div>
              <input type="tel" name="no_hp" x-model="newTravelAgent.phone" @input="newTravelAgent.phone = newTravelAgent.phone.replace(/[^0-9]/g, '')" required placeholder="81xxx"
                class="flex h-10 w-full rounded-md border border-input bg-background pl-12 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
            <p class="text-xs text-muted-foreground mt-1">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
          </div>
        </div>

        <div class="space-y-4">
          <h4 class="font-medium text-slate-900 border-b pb-2">Detail Travel</h4>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Travel <span class="text-red-500">*</span></label>
            <input type="text" name="nama_travel" x-model="newTravelAgent.travel_name" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>

          <div x-data="{ travelTypeOpen: false }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Travel <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="travelTypeOpen = false">
              <button type="button" @click="travelTypeOpen = !travelTypeOpen"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                <span :class="!newTravelAgent.travel_type && 'text-muted-foreground'" x-text="newTravelAgent.travel_type || 'Pilih jenis travel'"></span>
                <svg class="h-4 w-4 transition-transform" :class="travelTypeOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="travelTypeOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                <div class="py-1">
                  <button type="button" @click="newTravelAgent.travel_type = 'UMROH'; travelTypeOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="newTravelAgent.travel_type === 'UMROH' && 'bg-primary/10 text-primary'">UMROH</button>
                  <button type="button" @click="newTravelAgent.travel_type = 'LEISURE'; travelTypeOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="newTravelAgent.travel_type === 'LEISURE' && 'bg-primary/10 text-primary'">LEISURE</button>
                  <button type="button" @click="newTravelAgent.travel_type = 'UMROH LEISURE'; travelTypeOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="newTravelAgent.travel_type === 'UMROH LEISURE' && 'bg-primary/10 text-primary'">UMROH & LEISURE</button>
                </div>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Total Traveller per Bulan <span class="text-red-500">*</span></label>
            <input type="number" name="total_traveller" x-model="newTravelAgent.travel_member" required min="0"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
        <div x-data="{ kategoriOpen: false }">
          <label class="block text-sm font-medium text-slate-700 mb-1">Kategori Agent <span class="text-red-500">*</span></label>
          <div class="relative" @click.away="kategoriOpen = false">
            <button type="button" @click="kategoriOpen = !kategoriOpen"
              class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <span :class="!newTravelAgent.kategori_agent && 'text-muted-foreground'" x-text="newTravelAgent.kategori_agent || 'Pilih kategori'"></span>
              <svg class="h-4 w-4 transition-transform" :class="kategoriOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div x-show="kategoriOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
              <div class="py-1">
                <button type="button" @click="newTravelAgent.kategori_agent = 'Referral'; kategoriOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="newTravelAgent.kategori_agent === 'Referral' && 'bg-primary/10 text-primary'">Referral</button>
                <button type="button" @click="newTravelAgent.kategori_agent = 'Host'; kategoriOpen = false" class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors" :class="newTravelAgent.kategori_agent === 'Host' && 'bg-primary/10 text-primary'">Host</button>
              </div>
            </div>
          </div>
        </div>

        <div x-data="{ downlineOpen: false, downlineSearch: '' }">
          <label class="block text-sm font-medium text-slate-700 mb-1">Downline (Opsional)</label>
          <div class="relative" @click.away="downlineOpen = false">
            <button type="button" @click="downlineOpen = !downlineOpen"
              class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <span :class="!selectedDownline && 'text-muted-foreground'" x-text="selectedDownline ? selectedDownline.name + ' (' + selectedDownline.type + ')' : 'Pilih downline'"></span>
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
                  <button type="button" @click="selectDownline(downline); downlineOpen = false; downlineSearch = ''" class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="selectedDownline && selectedDownline.id === downline.id && selectedDownline.type === downline.type && 'bg-muted'">
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

      <div class="border-t pt-4">
        <h4 class="font-medium text-slate-900 mb-4">Informasi Alamat</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div x-data="{ provinceDropdownOpen: false, provinceSearch: '' }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="provinceDropdownOpen = false">
              <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                <span :class="!newTravelAgent.province && 'text-muted-foreground'" x-text="newTravelAgent.province || 'Pilih provinsi'"></span>
                <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
              </button>
              <div x-show="provinceDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                <div class="p-2 border-b"><input type="text" x-ref="provinceSearchInputAgent" x-model="provinceSearch" @click.stop placeholder="Cari provinsi..." class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></div>
                <div class="max-h-60 overflow-y-auto">
                  <template x-for="province in provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase()))" :key="province">
                    <button type="button" @click="newTravelAgent.province = province; handleProvinceChangeTravelAgent(); provinceDropdownOpen = false; provinceSearch = ''" class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="newTravelAgent.province === province && 'bg-muted'" x-text="province"></button>
                  </template>
                  <div x-show="provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada provinsi ditemukan</div>
                </div>
              </div>
            </div>
          </div>

          <div x-data="{ cityDropdownOpen: false, citySearch: '' }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="cityDropdownOpen = false">
              <button type="button" @click="cityDropdownOpen = !cityDropdownOpen" :disabled="!newTravelAgent.province" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-60">
                <span :class="!newTravelAgent.city && 'text-muted-foreground'" x-text="newTravelAgent.city || (newTravelAgent.province ? 'Pilih kota/kabupaten' : 'Pilih provinsi dulu')"></span>
                <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
              </button>
              <div x-show="cityDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
                <div class="p-2 border-b"><input type="text" x-ref="citySearchInputAgent" x-model="citySearch" @click.stop placeholder="Cari kota/kabupaten..." class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></div>
                <div class="max-h-60 overflow-y-auto">
                  <template x-for="city in citiesTravelAgent.filter(c => c.toLowerCase().includes(citySearch.toLowerCase()))" :key="city">
                    <button type="button" @click="newTravelAgent.city = city; cityDropdownOpen = false; citySearch = ''" class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="newTravelAgent.city === city && 'bg-muted'" x-text="city"></button>
                  </template>
                  <div x-show="citiesTravelAgent.filter(c => c.toLowerCase().includes(citySearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada kota ditemukan</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
          <textarea name="alamat_lengkap" x-model="newTravelAgent.address" required rows="3" placeholder="Jl. Contoh No. 123" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4">
        <button type="button" @click="closeAddTravelAgentModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
        <button type="submit" class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Simpan</button>
      </div>
    </form>
  </div>
</div>

<div x-show="confirmAddTravelAgentModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center"
  x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="absolute inset-0 bg-black/40" @click="closeConfirmAddTravelAgentModal()"></div>
  <div class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Tambah Travel Agent</h3>
    <p class="mt-2 text-sm text-muted-foreground">Apakah Anda yakin ingin menambahkan travel agent baru dengan data yang telah diisi?</p>
    <div class="mt-6 flex items-center justify-end gap-3">
      <button @click="closeConfirmAddTravelAgentModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
      <button @click="confirmAddTravelAgent()" class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Ya, Simpan</button>
    </div>
  </div>
</div>

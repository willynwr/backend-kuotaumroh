<div x-show="addFreelanceModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center"
  x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="absolute inset-0 bg-black/40" @click="closeAddFreelanceModal()"></div>
  <div class="relative z-10 w-full max-w-2xl rounded-lg bg-white shadow-lg p-6 max-h-[90vh] overflow-y-auto"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <h3 class="text-lg font-semibold text-slate-900">Tambah Freelance Baru</h3>
    <p class="mt-2 text-sm text-muted-foreground">Isi form di bawah untuk menambahkan freelance baru.</p>

    @if ($errors->any() && old('_form') === 'freelance')
      <div class="mt-4 rounded-md border border-destructive/30 bg-destructive/5 p-4 text-sm text-destructive">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form x-ref="addFreelanceForm" method="POST" action="{{ route('admin.freelances.store') }}" @submit.prevent="openConfirmAddFreelanceModal()" class="mt-6 space-y-4">
      @csrf
      <input type="hidden" name="_form" value="freelance">
      <input type="hidden" name="redirect_to" value="/admin/users?tab=freelance">
      <input type="hidden" name="provinsi" :value="newFreelance.provinsi">
      <input type="hidden" name="kab_kota" :value="newFreelance.kab_kota">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Nama <span class="text-red-500">*</span></label>
          <input type="text" name="nama" x-model="newFreelance.nama" required
            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
          <input type="email" name="email" x-model="newFreelance.email" required
            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">No. WhatsApp (+62) <span class="text-red-500">*</span></label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <span class="text-sm text-muted-foreground">+62</span>
            </div>
            <input type="tel" name="no_wa" x-model="newFreelance.no_wa" @input="newFreelance.no_wa = newFreelance.no_wa.replace(/[^0-9]/g, '')" required placeholder="81xxx"
              class="flex h-10 w-full rounded-md border border-input bg-background pl-12 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>
          <p class="text-xs text-muted-foreground mt-1">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Link Referral <span class="text-red-500">*</span></label>
          <input type="text" name="link_referral" x-model="newFreelance.link_referral" required placeholder="john123"
            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
        </div>

        <div x-data="{ provinceDropdownOpen: false, provinceSearch: '' }" x-init="$watch('provinceDropdownOpen', value => { if (value) $nextTick(() => $refs.provinceSearchInputFreelance.focus()) })">
          <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
          <div class="relative" @click.away="provinceDropdownOpen = false">
            <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
              class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <span :class="!newFreelance.provinsi && 'text-muted-foreground'" x-text="newFreelance.provinsi || 'Pilih provinsi'"></span>
              <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div x-show="provinceDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
              <div class="p-2 border-b">
                <input type="text" x-ref="provinceSearchInputFreelance" x-model="provinceSearch" @click.stop placeholder="Cari provinsi..."
                  class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              </div>
              <div class="max-h-60 overflow-y-auto">
                <template x-for="province in provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase()))" :key="province">
                  <button type="button" @click="newFreelance.provinsi = province; handleProvinceChangeFreelance(); provinceDropdownOpen = false; provinceSearch = ''"
                    class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="newFreelance.provinsi === province && 'bg-muted'" x-text="province"></button>
                </template>
                <div x-show="provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada provinsi ditemukan</div>
              </div>
            </div>
          </div>
        </div>

        <div x-data="{ cityDropdownOpen: false, citySearch: '' }" x-init="$watch('cityDropdownOpen', value => { if (value) $nextTick(() => $refs.citySearchInputFreelance.focus()) })">
          <label class="block text-sm font-medium text-slate-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
          <div class="relative" @click.away="cityDropdownOpen = false">
            <button type="button" @click="cityDropdownOpen = !cityDropdownOpen" :disabled="!newFreelance.provinsi"
              class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-60">
              <span :class="!newFreelance.kab_kota && 'text-muted-foreground'" x-text="newFreelance.kab_kota || (newFreelance.provinsi ? 'Pilih kota/kabupaten' : 'Pilih provinsi dulu')"></span>
              <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div x-show="cityDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg" style="display: none;">
              <div class="p-2 border-b">
                <input type="text" x-ref="citySearchInputFreelance" x-model="citySearch" @click.stop placeholder="Cari kota/kabupaten..."
                  class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              </div>
              <div class="max-h-60 overflow-y-auto">
                <template x-for="city in citiesFreelance.filter(c => c.toLowerCase().includes(citySearch.toLowerCase()))" :key="city">
                  <button type="button" @click="newFreelance.kab_kota = city; cityDropdownOpen = false; citySearch = ''"
                    class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" :class="newFreelance.kab_kota === city && 'bg-muted'" x-text="city"></button>
                </template>
                <div x-show="citiesFreelance.filter(c => c.toLowerCase().includes(citySearch.toLowerCase())).length === 0" class="px-3 py-2 text-sm text-muted-foreground">Tidak ada kota ditemukan</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
        <textarea name="alamat_lengkap" x-model="newFreelance.alamat_lengkap" required rows="3" placeholder="Jl. Contoh No. 123"
          class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4">
        <button type="button" @click="closeAddFreelanceModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
        <button type="submit" class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Simpan</button>
      </div>
    </form>
  </div>
</div>

<div x-show="confirmAddFreelanceModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center"
  x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
  <div class="absolute inset-0 bg-black/40" @click="closeConfirmAddFreelanceModal()"></div>
  <div class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
    <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Tambah Freelance</h3>
    <p class="mt-2 text-sm text-muted-foreground">Apakah Anda yakin ingin menambahkan freelance baru dengan data yang telah diisi?</p>
    <div class="mt-6 flex items-center justify-end gap-3">
      <button @click="closeConfirmAddFreelanceModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
      <button @click="confirmAddFreelance()" class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Ya, Simpan</button>
    </div>
  </div>
</div>

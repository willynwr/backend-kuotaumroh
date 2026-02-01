<div
  x-show="packagePickerOpen && !numberSelectionOpen"
  x-cloak
  x-transition
  role="dialog"
  aria-labelledby="package-picker-dialog-title"
  aria-modal="true"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  @click.self="packagePickerOpen = false"
  @keydown.escape="packagePickerOpen = false"
>
  <div class="relative bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden animate-fade-in">
    <!-- Header -->
    <div class="p-6 border-b flex items-center justify-between">
      <h2 id="package-picker-dialog-title" class="text-xl font-bold">PILIH PAKET</h2>
      <button @click="packagePickerOpen = false" class="p-2 hover:bg-muted rounded-md">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <!-- Search & Filters -->
    <div class="p-4 border-b space-y-4">
      <!-- Search and SubType Filter Row -->
      <div class="flex gap-2">
        <!-- Search -->
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            type="text"
            x-model="packageSearch"
            placeholder="Cari paket"
            class="w-full h-10 pl-10 pr-4 rounded-md border border-input bg-background text-sm"
          >
        </div>
        <!-- SubType Filter Dropdown -->
        <div class="relative w-56">
          <select
            x-model="selectedSubTypeFilter"
            class="w-full h-10 px-3 pr-8 rounded-md border border-input bg-background text-sm appearance-none cursor-pointer"
          >
            <template x-for="filter in subTypeFilters" :key="filter.value">
              <option :value="filter.value" x-text="filter.label"></option>
            </template>
          </select>
          <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
      <!-- Duration Filter Tabs -->
      <div class="flex gap-2 flex-wrap">
        <template x-for="filter in durationFilters" :key="filter.value">
          <button
            @click="selectedDurationFilter = filter.value"
            :class="selectedDurationFilter === filter.value ? 'bg-primary text-primary-foreground' : 'bg-muted hover:bg-muted/80'"
            class="px-4 py-2 rounded-md text-sm font-medium transition-colors"
            x-text="filter.label"
          ></button>
        </template>
      </div>
    </div>
    
    <!-- Package Cards Grid -->
    <div class="p-4 max-h-[50vh] overflow-y-auto">
      <!-- Empty State -->
      <template x-if="getFilteredPackages().length === 0">
        <div class="flex flex-col items-center justify-center py-12 text-center">
          <svg class="h-16 w-16 text-muted-foreground/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
          <h3 class="text-lg font-semibold mb-2">Tidak ada paket yang ditemukan</h3>
          <p class="text-sm text-muted-foreground mb-6" x-text="'Tidak ada paket tersedia untuk provider ' + pickerProviderDisplay"></p>
          <button
            @click="deleteProviderNumbers(pickerProvider); packagePickerOpen = false;"
            class="inline-flex items-center rounded-md h-10 px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90 font-medium"
          >
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span x-text="'Hapus ' + (providerGroups[pickerProvider]?.length || 0) + ' Nomor dari ' + pickerProviderDisplay"></span>
          </button>
        </div>
      </template>

      <!-- Package Grid -->
      <div x-show="getFilteredPackages().length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <template x-for="(pkg, index) in getFilteredPackages()" :key="`${pkg.id}-${pkg.type}-${index}`">
          <div class="border rounded-lg p-4 hover:shadow-md transition-shadow bg-white flex flex-col h-full">
            <!-- Provider with Badge -->
            <div class="flex items-center justify-between mb-3">
              <span class="text-sm font-semibold text-gray-700" x-text="pickerProviderDisplay"></span>
              <!-- Promo Badge -->
              <span x-show="pkg.promo"
                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold text-white shadow-sm"
                :class="{
                  'bg-red-500': pkg.promo && pkg.promo.toLowerCase().includes('promo'),
                  'bg-amber-500': pkg.promo && pkg.promo.toLowerCase().includes('best'),
                  'bg-blue-500': !pkg.promo || (!pkg.promo.toLowerCase().includes('promo') && !pkg.promo.toLowerCase().includes('best'))
                }"
                x-text="pkg.promo">
              </span>
            </div>

            <!-- Package Name -->
            <h3 x-show="getPackageTitle(pkg)" class="font-bold text-lg mb-3 text-gray-900" x-text="getPackageTitle(pkg)"></h3>

            <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
              <!-- Quota -->
              <template x-if="pkg.quota">
                <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'kuota') ? 'font-bold' : ''">
                  <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                  </svg>
                  <span x-text="getQuotaDisplay(pkg)"></span>
                </div>
              </template>

              <!-- Bonus -->
              <template x-if="pkg.bonus">
                <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'bonus') ? 'font-bold' : ''">
                  <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                  </svg>
                  <span x-text="getBonusDisplay(pkg)"></span>
                </div>
              </template>

              <!-- Telp -->
              <template x-if="pkg.telp">
                <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'telp') ? 'font-bold' : ''">
                  <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span x-text="pkg.telp + ' menit'"></span>
                </div>
              </template>

              <!-- SMS -->
              <template x-if="pkg.sms">
                <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'sms') ? 'font-bold' : ''">
                  <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                  </svg>
                  <span x-text="pkg.sms + ' SMS'"></span>
                </div>
              </template>

              <!-- Days -->
              <template x-if="pkg.days">
                <div class="flex items-center gap-2 text-sm text-gray-600" :class="isFieldBold(pkg, 'hari') ? 'font-bold' : ''">
                  <svg class="h-4 w-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span x-text="pkg.days + ' hari'"></span>
                </div>
              </template>
            </div>

            <!-- Pricing Section -->
            <div class="space-y-2 mb-4 mt-auto">
              <!-- Harga Coret (Price App / Harga Rekomendasi) -->
              <template x-if="pkg.price_app && pkg.price_app > 0">
                <div class="text-xs text-gray-400 line-through mb-1" x-text="formatRupiah(pkg.price_app)"></div>
              </template>

              <!-- Harga Beli (Highlighted) - dari bulk_harga_beli -->
              <div class="bg-primary/10 rounded-lg p-2.5">
                <p class="text-xs text-gray-600 mb-0.5">Harga Beli</p>
                <p class="text-xl font-bold text-primary" x-text="formatRupiah(pkg.price_bulk || pkg.price)"></p>
              </div>

              <!-- Harga Customer / Rekomendasi -->
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-600">Harga Rekomendasi:</span>
                <span class="text-sm font-semibold text-gray-900" x-text="formatRupiah(pkg.price_customer || pkg.sellPrice)"></span>
              </div>

              <!-- Potensi Profit -->
              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-600">Potensi Profit:</span>
                <span class="text-sm font-bold text-green-600" x-text="formatRupiah(pkg.profit || (pkg.price_customer - pkg.price_bulk))"></span>
              </div>
            </div>

            <!-- PILIH Button -->
            <button
              @click="selectPackageAndOpenNumberSelection(pkg)"
              class="w-full h-10 rounded-md bg-primary text-primary-foreground font-medium hover:bg-primary/90 transition-colors shadow-sm"
            >
              PILIH
            </button>
          </div>
        </template>
      </div>
    </div>
  </div>
</div>

<div
  x-show="packagePickerOpen && numberSelectionOpen"
  x-cloak
  role="dialog"
  aria-labelledby="number-selection-dialog-title"
  aria-modal="true"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  @click.self="closeNumberSelection()"
  @keydown.escape="closeNumberSelection()"
>
  <div class="relative bg-white rounded-lg shadow-lg w-full max-w-xl max-h-[90vh] overflow-hidden animate-fade-in">
    <!-- Header -->
    <div class="p-6 border-b flex items-center gap-4">
      <button @click="closeNumberSelection()" class="p-2 hover:bg-muted rounded-md">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
      </button>
      <h2 id="number-selection-dialog-title" class="text-xl font-bold">PILIH NOMOR</h2>
      <div class="flex-1"></div>
      <button @click="closeNumberSelection(); packagePickerOpen = false;" class="p-2 hover:bg-muted rounded-md">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <!-- Selected Package Info -->
    <div class="px-6 py-4 bg-muted/30 border-b">
      <p class="text-sm text-muted-foreground">Paket dipilih:</p>
      <p class="font-semibold" x-text="selectedPackageForNumbers?.name"></p>
      <p class="text-primary font-medium" x-text="formatRupiah(selectedPackageForNumbers?.price || 0) + ' / nomor'"></p>
    </div>
    
    <!-- Search -->
    <div class="p-4 border-b">
      <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input 
          type="text" 
          x-model="numberSearch"
          placeholder="Cari nomor..." 
          class="w-full h-10 pl-10 pr-4 rounded-md border border-input bg-background text-sm"
        >
      </div>
    </div>
    
    <!-- Select All -->
    <div class="px-4 py-3 border-b">
      <label class="flex items-center gap-3 cursor-pointer">
        <input 
          type="checkbox" 
          :checked="areAllNumbersSelected()"
          @change="toggleSelectAllNumbers()"
          class="h-4 w-4 rounded border-primary text-primary"
        >
        <span class="font-medium text-primary">Pilih Semua (<span x-text="getPickerNumbers().length"></span> nomor)</span>
      </label>
    </div>
    
    <!-- Number List -->
    <div class="max-h-[40vh] overflow-y-auto">
      <template x-for="(num, index) in getFilteredPickerNumbers()" :key="num">
        <label 
          class="flex items-center gap-3 px-4 py-3 border-b cursor-pointer hover:bg-muted/30 transition-colors"
          :class="tempSelectedNumbers.includes(num) ? 'bg-primary/5' : ''"
        >
          <input 
            type="checkbox" 
            :checked="tempSelectedNumbers.includes(num)"
            @change="toggleNumberSelection(num)"
            class="h-4 w-4 rounded border-primary text-primary"
          >
          <span class="text-sm text-muted-foreground" x-text="(index + 1) + '.'"></span>
          <span class="font-mono" x-text="num"></span>
          <!-- Show existing package badge if assigned -->
          <template x-if="getNumberCurrentPackage(num)">
            <span class="ml-auto px-2 py-1 bg-primary/10 text-primary text-xs rounded-md" x-text="getNumberCurrentPackage(num)"></span>
          </template>
        </label>
      </template>
    </div>
    
    <!-- Footer -->
    <div class="p-4 border-t flex items-center justify-between">
      <span class="text-sm text-muted-foreground">
        <span x-text="tempSelectedNumbers.length"></span> dari <span x-text="getPickerNumbers().length"></span> nomor dipilih
      </span>
      <button 
        @click="applyNumberSelection()"
        :disabled="tempSelectedNumbers.length === 0"
        :class="tempSelectedNumbers.length > 0 ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'"
        class="h-10 px-6 rounded-md font-medium transition-colors"
      >
        Terapkan (<span x-text="formatRupiah(tempSelectedNumbers.length * (selectedPackageForNumbers?.price || 0))"></span>)
      </button>
    </div>
  </div>
</div>

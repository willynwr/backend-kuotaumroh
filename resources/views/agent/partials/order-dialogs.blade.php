<!-- Edit Batch Name Dialog -->
<div
  x-show="editBatchNameDialog"
  x-cloak
  @click.self="editBatchNameDialog = false"
  x-init="$watch('editBatchNameDialog', value => { if(value) tempBatchName = batchName })"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
>
  <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md animate-fade-in">
    <div class="p-6 border-b">
      <h2 class="text-lg font-semibold">Edit Nama Batch</h2>
      <p class="text-sm text-muted-foreground mt-1">
        Ubah nama batch untuk memudahkan identifikasi pesanan
      </p>
    </div>

    <div class="p-6">
      <div class="space-y-2">
        <label for="batchNameInput" class="text-sm font-medium">Nama Batch</label>
        <input
          id="batchNameInput"
          type="text"
          x-model="tempBatchName"
          @keydown.enter="saveBatchName()"
          class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
          placeholder="Contoh: Jamaah Umroh 15 Januari 2026"
        >
      </div>
    </div>

    <div class="p-6 border-t flex gap-3">
      <button
        type="button"
        @click="editBatchNameDialog = false"
        class="flex-1 inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors"
      >
        Batal
      </button>
      <button
        type="button"
        @click="saveBatchName()"
        class="flex-1 inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors"
      >
        Simpan
      </button>
    </div>
  </div>
</div>

<!-- Invalid Numbers Dialog -->
<div
  x-show="invalidDialogOpen"
  x-cloak
  role="dialog"
  aria-labelledby="invalid-dialog-title"
  aria-modal="true"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  @click.self="invalidDialogOpen = false"
  @keydown.escape="invalidDialogOpen = false"
>
  <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md max-h-[80vh] overflow-hidden animate-fade-in">
    <div class="p-6 border-b">
      <h2 id="invalid-dialog-title" class="text-lg font-semibold">Nomor Tidak Valid</h2>
      <p class="text-sm text-muted-foreground mt-1" x-text="invalidNumbers.length + ' nomor tidak dapat diproses'"></p>
    </div>
    <div class="p-4 max-h-[50vh] overflow-y-auto space-y-2">
      <template x-for="num in invalidNumbers" :key="num.msisdn">
        <div class="flex items-center justify-between p-2 bg-muted/50 rounded-md">
          <span class="font-mono text-sm" x-text="num.msisdn"></span>
          <button @click="deleteInvalidNumber(num.msisdn)" class="text-destructive hover:bg-destructive/10 p-1 rounded">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </template>
    </div>
    <div class="p-4 border-t flex gap-3">
      <button @click="invalidDialogOpen = false" class="flex-1 inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 hover:bg-muted">
        Tutup
      </button>
      <button @click="deleteAllInvalidNumbers()" class="flex-1 inline-flex items-center justify-center rounded-md h-10 px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90">
        Hapus Semua
      </button>
    </div>
  </div>
</div>

<!-- Number List Edit Dialog (Daftar Nomor Provider) -->
<div
  x-show="numberListDialogOpen"
  x-cloak
  role="dialog"
  aria-labelledby="number-list-dialog-title"
  aria-modal="true"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  @click.self="numberListDialogOpen = false"
  @keydown.escape="numberListDialogOpen = false"
>
  <div class="relative bg-white rounded-lg shadow-lg w-full max-w-xl max-h-[80vh] overflow-hidden animate-fade-in">
    <!-- Header -->
    <div class="p-6 border-b flex items-center justify-between">
      <h2 id="number-list-dialog-title" class="text-lg font-semibold">Daftar Nomor <span x-text="editingProvider"></span></h2>
      <button @click="numberListDialogOpen = false" class="p-2 hover:bg-muted rounded-md">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <!-- Table Header -->
    <div class="px-6 py-3 border-b bg-muted/30 hidden sm:block">
      <div class="flex items-center">
        <span class="w-1/2 text-sm font-medium text-muted-foreground">Nomor HP</span>
        <span class="w-1/2 text-sm font-medium text-muted-foreground">Paket</span>
      </div>
    </div>
    
    <!-- Number List with Package Dropdowns -->
    <div class="max-h-[50vh] overflow-y-auto">
      <template x-for="num in getEditingNumbers()" :key="num">
        <div class="flex flex-col sm:flex-row sm:items-center px-6 py-3 border-b hover:bg-muted/10 gap-2 sm:gap-0">
          <!-- Phone Number -->
          <span class="w-full sm:w-1/2 font-mono text-sm" x-text="num"></span>
          <!-- Package Picker Button -->
          <div class="w-full sm:w-1/2 flex items-center gap-2">
            <button
              @click="openSingleNumberPackagePicker(editingProvider, num)"
              class="flex-1 h-9 rounded-md border border-input bg-background px-3 text-sm text-left hover:bg-muted/50 flex items-center justify-between"
            >
              <span :class="getNumberPackageId(editingProvider, num) ? '' : 'text-muted-foreground'" x-text="getNumberPackageId(editingProvider, num) ? getPackageName(getNumberPackageId(editingProvider, num)) : 'Pilih paket'"></span>
              <svg class="h-4 w-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <button @click="deleteNumberFromProvider(editingProvider, num)" class="p-1.5 text-destructive hover:bg-destructive/10 rounded">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </template>
    </div>
    
    <!-- Footer with Total and Save Button -->
    <div class="p-4 border-t flex items-center justify-between">
      <p class="text-sm text-muted-foreground">
        <span x-text="getEditingNumbers().length"></span> nomor • Total: <span class="font-semibold text-foreground" x-text="formatRupiah(getTempEditingSubtotal())"></span>
      </p>
      <div class="flex gap-2">
        <button @click="numberListDialogOpen = false" class="inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 hover:bg-muted text-sm">
          Tutup
        </button>
        <template x-if="hasUnsavedChanges">
          <button 
            @click="saveListChanges()"
            class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 hover:bg-primary/90 text-sm font-medium animate-fade-in"
          >
            Simpan Perubahan
          </button>
        </template>
      </div>
    </div>
  </div>
</div>

<!-- Invalid Numbers for Checkout Modal -->
<div
  x-show="showInvalidNumbersCheckout"
  x-cloak
  role="dialog"
  aria-labelledby="invalid-checkout-dialog-title"
  aria-modal="true"
  class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50"
  @click.self="showInvalidNumbersCheckout = false"
  @keydown.escape="showInvalidNumbersCheckout = false"
>
  <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md max-h-[80vh] overflow-hidden animate-fade-in">
    <div class="bg-red-50 px-6 pt-6 pb-5 border-b border-red-100">
      <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
          <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
        </div>
        <div class="flex-1">
          <h3 id="invalid-checkout-dialog-title" class="text-lg font-bold text-gray-900">
            Nomor Tidak Valid
          </h3>
          <p class="mt-1 text-sm text-gray-600">
            Terdapat <span class="font-semibold" x-text="invalidNumbersForCheckout.length"></span> nomor yang tidak valid. Silakan perbaiki sebelum melanjutkan checkout.
          </p>
        </div>
      </div>
    </div>
    
    <div class="bg-white px-6 py-4 max-h-96 overflow-y-auto">
      <div class="space-y-3">
        <template x-for="(item, index) in invalidNumbersForCheckout" :key="index">
          <div class="bg-red-50 border border-red-200 rounded-lg p-3">
            <div class="flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-red-900 font-mono" x-text="item.number"></p>
                <p class="text-xs text-red-700 mt-1" x-text="item.reason"></p>
              </div>
              <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
        </template>
      </div>
    </div>
    
    <div class="bg-gray-50 px-6 py-4 border-t">
      <button @click="showInvalidNumbersCheckout = false" type="button" 
        class="w-full inline-flex justify-center items-center rounded-lg px-4 py-3 bg-red-600 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
        Tutup & Perbaiki Nomor
      </button>
    </div>
  </div>
</div>

<!-- Package Picker Dialog - Step 1: PILIH PAKET -->
@include('agent.partials.order-package-picker')

<!-- Package Picker Dialog - Step 2: PILIH NOMOR -->
@include('agent.partials.order-number-selection')

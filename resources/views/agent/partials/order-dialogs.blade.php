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

<!-- Invalid Numbers Checkout Warning Dialog -->
<div
  x-show="invalidCheckoutWarningOpen"
  x-cloak
  role="dialog"
  aria-labelledby="invalid-checkout-warning-title"
  aria-modal="true"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  @click.self="invalidCheckoutWarningOpen = false"
  @keydown.escape="invalidCheckoutWarningOpen = false"
>
  <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md animate-fade-in">
    <div class="p-6 border-b bg-gradient-to-r from-orange-50 to-red-50">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
          <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div class="flex-1">
          <h2 id="invalid-checkout-warning-title" class="text-lg font-semibold text-gray-900">Peringatan Nomor Tidak Valid</h2>
          <p class="text-sm text-gray-600 mt-1">
            Ada <span class="font-bold text-orange-600" x-text="invalidCount"></span> nomor yang tidak valid
          </p>
        </div>
      </div>
    </div>

    <div class="p-6">
      <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <div class="flex gap-3">
          <svg class="h-5 w-5 text-orange-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="flex-1">
            <p class="text-sm font-medium text-orange-900">Nomor tidak valid tidak akan masuk ke checkout</p>
            <p class="text-sm text-orange-700 mt-1">
              Hanya <span class="font-semibold" x-text="validCount"></span> nomor valid yang akan diproses.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="p-6 border-t bg-gray-50 flex gap-3">
      <button
        type="button"
        @click="invalidCheckoutWarningOpen = false"
        class="flex-1 inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors"
      >
        Batal
      </button>
      <button
        type="button"
        @click="proceedToCheckout()"
        class="flex-1 inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors font-medium"
      >
        Lanjutkan
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

<!-- Upload Validation Dialog (Invalid & Duplicate Numbers) -->
<div
  x-show="uploadValidationDialogOpen"
  x-cloak
  role="dialog"
  aria-labelledby="upload-validation-dialog-title"
  aria-modal="true"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
  @click.self="uploadValidationDialogOpen = false"
  @keydown.escape="uploadValidationDialogOpen = false"
>
  <div class="relative bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[85vh] overflow-hidden animate-fade-in">
    <div class="p-6 border-b bg-gradient-to-r from-orange-50 to-red-50">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
          <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <div class="flex-1">
          <h2 id="upload-validation-dialog-title" class="text-lg font-semibold text-gray-900">Validasi File Upload</h2>
          <template x-if="uploadValidationErrors">
            <p class="text-sm text-gray-600 mt-1">
              Ditemukan <span class="font-semibold text-orange-600" x-text="(uploadValidationErrors.invalid?.length || 0) + (uploadValidationErrors.duplicates?.length || 0)"></span> masalah dari <span class="font-semibold" x-text="uploadValidationErrors.totalUploaded"></span> nomor yang diupload
            </p>
          </template>
        </div>
        <button @click="uploadValidationDialogOpen = false" class="text-gray-400 hover:text-gray-600">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
    
    <div class="p-6 max-h-[calc(85vh-200px)] overflow-y-auto space-y-6">
      <template x-if="uploadValidationErrors">
        <div class="space-y-6">
          <!-- Summary Cards -->
          <div class="grid grid-cols-3 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
              <div class="text-2xl font-bold text-blue-600" x-text="uploadValidationErrors.validCount"></div>
              <div class="text-xs text-blue-600 mt-1">Nomor Valid</div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
              <div class="text-2xl font-bold text-red-600" x-text="uploadValidationErrors.invalid?.length || 0"></div>
              <div class="text-xs text-red-600 mt-1">Tidak Valid</div>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
              <div class="text-2xl font-bold text-orange-600" x-text="uploadValidationErrors.duplicates?.length || 0"></div>
              <div class="text-xs text-orange-600 mt-1">Duplicate</div>
            </div>
          </div>
          
          <!-- Invalid Numbers Section -->
          <template x-if="uploadValidationErrors.invalid?.length > 0">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <h3 class="font-semibold text-red-600 flex items-center gap-2">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Nomor Tidak Valid (<span x-text="uploadValidationErrors.invalid.length"></span>)
                </h3>
                <button 
                  @click="removeInvalidNumbersFromUpload()"
                  class="text-xs bg-red-600 text-white px-3 py-1.5 rounded-md hover:bg-red-700 transition-colors flex items-center gap-1"
                >
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  Hapus Semua
                </button>
              </div>
              <div class="bg-red-50 rounded-lg p-4 max-h-60 overflow-y-auto">
                <div class="space-y-2">
                  <template x-for="(item, index) in uploadValidationErrors.invalid" :key="index">
                    <div class="flex items-center justify-between p-2 bg-white rounded border border-red-200">
                      <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 w-8" x-text="'#' + item.position"></span>
                        <span class="font-mono text-sm text-gray-900" x-text="item.msisdn"></span>
                      </div>
                      <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded">Format salah</span>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </template>
          
          <!-- Duplicate Numbers Section -->
          <template x-if="uploadValidationErrors.duplicates?.length > 0">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <h3 class="font-semibold text-orange-600 flex items-center gap-2">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  Nomor Duplicate (<span x-text="uploadValidationErrors.duplicates.length"></span>)
                </h3>
                <button 
                  @click="removeDuplicateNumbersFromUpload()"
                  class="text-xs bg-orange-600 text-white px-3 py-1.5 rounded-md hover:bg-orange-700 transition-colors flex items-center gap-1"
                >
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  Hapus Duplicate
                </button>
              </div>
              <div class="bg-orange-50 rounded-lg p-4 max-h-60 overflow-y-auto">
                <div class="space-y-2">
                  <template x-for="(item, index) in uploadValidationErrors.duplicates" :key="index">
                    <div class="flex items-center justify-between p-2 bg-white rounded border border-orange-200">
                      <div class="flex items-center gap-3">
                        <span class="font-mono text-sm text-gray-900" x-text="item.msisdn"></span>
                      </div>
                      <span class="text-xs text-orange-600 bg-orange-100 px-2 py-1 rounded">
                        Muncul <span x-text="item.positions.length"></span>x di posisi #<span x-text="item.positions.join(', #')"></span>
                      </span>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </template>
        </div>
      </template>
    </div>
    
    <div class="p-6 border-t bg-gray-50">
      <div class="flex items-center justify-between gap-4">
        <div class="flex-1">
          <p class="text-sm text-gray-600">
            💡 <strong>Tip:</strong> Perbaiki nomor yang bermasalah di textarea atau hapus dan upload ulang
          </p>
        </div>
        <div class="flex gap-2">
          <template x-if="uploadValidationErrors && ((uploadValidationErrors.invalid?.length > 0) || (uploadValidationErrors.duplicates?.length > 0))">
            <button 
              @click="removeAllProblematicNumbers()" 
              class="inline-flex items-center justify-center rounded-md bg-red-600 text-white h-10 px-4 hover:bg-red-700 font-medium transition-colors gap-2"
            >
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Hapus Semua Masalah
            </button>
          </template>
          <button 
            @click="uploadValidationDialogOpen = false" 
            class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-6 hover:bg-primary/90 font-medium"
          >
            Lanjutkan
          </button>
        </div>
      </div>
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

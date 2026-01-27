<div :style="editAffiliateModalOpen ? 'display: flex' : 'display: none'" class="fixed inset-0 z-50 items-center justify-center">
  <div class="absolute inset-0 bg-black/40" @click="closeEditAffiliateModal()"></div>
  <div class="relative z-10 w-full max-w-4xl rounded-lg bg-white shadow-lg max-h-[90vh] overflow-y-auto">
    
    <!-- Header -->
    <div class="sticky top-0 z-10 bg-white border-b px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Affiliate
          </h3>
          <p class="mt-1 text-sm text-muted-foreground">Update data affiliate</p>
        </div>
        <button @click="closeEditAffiliateModal()" class="text-muted-foreground hover:text-foreground transition-colors">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <form method="POST" :action="`/admin/affiliate/${editingAffiliate.id}`" enctype="multipart/form-data" class="p-6 space-y-6">
      @csrf
      @method('PUT')
      <input type="hidden" name="redirect_to" value="/admin/users?tab=affiliate">
      <input type="hidden" name="provinsi" x-bind:value="editingAffiliate.province">
      <input type="hidden" name="kab_kota" x-bind:value="editingAffiliate.city">

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
            <input type="text" name="nama" x-model="editingAffiliate.name" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" x-model="editingAffiliate.email" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">No. WhatsApp (+62) <span class="text-red-500">*</span></label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="text-sm text-muted-foreground">+62</span>
              </div>
              <input type="tel" name="no_wa" x-model="editingAffiliate.phoneClean" @input="editingAffiliate.phoneClean = editingAffiliate.phoneClean.replace(/[^0-9]/g, '')" required placeholder="81xxx"
                class="flex h-10 w-full rounded-md border border-input bg-background pl-12 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
            <p class="text-xs text-muted-foreground mt-1">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Link Referral <span class="text-red-500">*</span></label>
            <input type="text" name="link_referral" x-model="editingAffiliate.referral_code" required
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
          <div x-data="{ provinceDropdownOpen: false, provinceSearch: '' }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="provinceDropdownOpen = false">
              <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                <span :class="!editingAffiliate.province && 'text-muted-foreground'" x-text="editingAffiliate.province || 'Pilih provinsi'"></span>
                <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="provinceDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg max-h-60 overflow-y-auto">
                <template x-for="province in provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase()))" :key="province">
                  <button type="button" @click="editingAffiliate.province = province; loadCitiesForEdit(province); provinceDropdownOpen = false"
                    class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" x-text="province"></button>
                </template>
              </div>
            </div>
          </div>

          <div x-data="{ cityDropdownOpen: false }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="cityDropdownOpen = false">
              <button type="button" @click="cityDropdownOpen = !cityDropdownOpen"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                <span :class="!editingAffiliate.city && 'text-muted-foreground'" x-text="editingAffiliate.city || 'Pilih kota/kabupaten'"></span>
                <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="cityDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg max-h-60 overflow-y-auto">
                <template x-for="city in cities" :key="city">
                  <button type="button" @click="editingAffiliate.city = city; cityDropdownOpen = false"
                    class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" x-text="city"></button>
                </template>
              </div>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
          <textarea name="alamat_lengkap" x-model="editingAffiliate.address" required rows="3"
            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
        </div>

        <!-- KTP Upload Section -->
        <div class="space-y-2" x-data="{ ktpPreview: editingAffiliate.ktp_url ? '/storage/' + editingAffiliate.ktp_url : null, ktpType: editingAffiliate.ktp_url ? (editingAffiliate.ktp_url.endsWith('.pdf') ? 'application/pdf' : 'image') : null, isNewFile: false }">
          <label class="block text-sm font-medium text-slate-700 mb-1">Upload KTP Baru (Opsional)</label>
          
          <!-- Current File Preview (if exists) -->
          <div x-show="ktpPreview && !isNewFile" class="mb-3">
            <p class="text-xs text-muted-foreground mb-2">File KTP saat ini:</p>
            <div x-show="ktpType === 'image'" class="relative inline-block group">
              <img :src="ktpPreview" alt="KTP Saat Ini" class="h-32 w-auto object-contain border rounded-md shadow-sm">
              <button type="button" @click="window.open(ktpPreview, '_blank')"
                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            <div x-show="ktpType === 'application/pdf'" class="inline-block">
              <a :href="ktpPreview" target="_blank" class="flex items-center gap-3 p-3 border rounded-md bg-gray-50 hover:bg-gray-100 transition-colors">
                <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900">File KTP (PDF)</p>
                  <p class="text-xs text-gray-500">Klik untuk lihat</p>
                </div>
              </a>
            </div>
          </div>

          <input type="file" name="ktp" accept="image/png,image/jpeg,image/jpg,application/pdf"
            @change="
              const file = $event.target.files[0];
              if (file) {
                isNewFile = true;
                ktpType = file.type;
                if (file.type.startsWith('image/')) {
                  const reader = new FileReader();
                  reader.onload = (e) => { ktpPreview = e.target.result; };
                  reader.readAsDataURL(file);
                } else {
                  ktpPreview = 'pdf';
                }
              }
            "
            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium file:cursor-pointer hover:file:text-primary">
          <p class="text-xs text-muted-foreground">Format: PNG, JPG, PDF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
          
          <!-- New File Preview -->
          <div x-show="ktpPreview && isNewFile" class="mt-3">
            <p class="text-xs text-green-600 font-medium mb-2">File baru dipilih:</p>
            <div x-show="ktpType && ktpType.startsWith('image/')" class="relative inline-block">
              <img :src="ktpPreview" alt="Preview KTP Baru" class="h-32 w-auto object-contain border border-green-500 rounded-md shadow-sm">
              <button type="button" @click="ktpPreview = editingAffiliate.ktp_url ? '/storage/' + editingAffiliate.ktp_url : null; ktpType = editingAffiliate.ktp_url ? (editingAffiliate.ktp_url.endsWith('.pdf') ? 'application/pdf' : 'image') : null; isNewFile = false; $el.closest('.space-y-2').querySelector('input[type=file]').value = ''"
                class="absolute -top-2 -right-2 bg-destructive text-white rounded-full p-1.5 hover:bg-destructive/90 transition-colors shadow-md">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div x-show="ktpType && ktpType === 'application/pdf'" class="relative inline-block">
              <div class="flex items-center gap-3 p-3 border border-green-500 rounded-md bg-green-50">
                <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900">File KTP Baru (PDF)</p>
                </div>
                <button type="button" @click="ktpPreview = editingAffiliate.ktp_url ? '/storage/' + editingAffiliate.ktp_url : null; ktpType = editingAffiliate.ktp_url ? (editingAffiliate.ktp_url.endsWith('.pdf') ? 'application/pdf' : 'image') : null; isNewFile = false; $el.closest('.space-y-2').querySelector('input[type=file]').value = ''"
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

      <!-- Footer -->
      <div class="sticky bottom-0 bg-white border-t -mx-6 -mb-6 px-6 py-4 flex items-center justify-end gap-3">
        <button type="button" @click="closeEditAffiliateModal()" class="h-10 px-6 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">Batal</button>
        <button type="submit" class="h-10 px-6 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">Update</button>
      </div>
    </form>
  </div>
</div>

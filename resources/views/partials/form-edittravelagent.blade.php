<div :style="editTravelAgentModalOpen ? 'display: flex' : 'display: none'" class="fixed inset-0 z-50 items-center justify-center">
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
        <button @click="closeEditTravelAgentModal()" class="text-muted-foreground hover:text-foreground transition-colors">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <form method="POST" :action="`/admin/agent/${editingTravelAgent.id}`" enctype="multipart/form-data" class="p-6 space-y-6">
      @csrf
      @method('PUT')
      <input type="hidden" name="redirect_to" value="/admin/users?tab=agent">
      <input type="hidden" name="provinsi" x-bind:value="editingTravelAgent.province">
      <input type="hidden" name="kabupaten_kota" x-bind:value="editingTravelAgent.city">
      <input type="hidden" name="latitude" x-bind:value="editingTravelAgent.latitude">
      <input type="hidden" name="longitude" x-bind:value="editingTravelAgent.longitude">

      <!-- PIC Information -->
      <div class="space-y-4">
        <h4 class="font-semibold text-slate-900 border-b pb-2">Informasi PIC</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
          </div>
        </div>
      </div>

      <!-- Travel Information -->
      <div class="space-y-4">
        <h4 class="font-semibold text-slate-900 border-b pb-2">Informasi Travel</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Travel <span class="text-red-500">*</span></label>
            <input type="text" name="nama_travel" x-model="editingTravelAgent.travel_name" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Travel <span class="text-red-500">*</span></label>
            <select name="jenis_travel" x-model="editingTravelAgent.travel_type" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <option value="">Pilih jenis travel</option>
              <option value="PPIU">PPIU</option>
              <option value="PIHK">PIHK</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Anggota Travel <span class="text-red-500">*</span></label>
            <select name="anggota_travel" x-model="editingTravelAgent.travel_member" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <option value="">Pilih anggota</option>
              <option value="ASITA">ASITA</option>
              <option value="ASTINDO">ASTINDO</option>
              <option value="AMPHURI">AMPHURI</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Kategori Agent <span class="text-red-500">*</span></label>
            <select name="kategori_agent" x-model="editingTravelAgent.kategori_agent" required
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
              <option value="">Pilih kategori</option>
              <option value="B2B">B2B</option>
              <option value="B2C">B2C</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Total Traveller/Bulan</label>
            <input type="number" name="total_traveller" x-model="editingTravelAgent.monthly_travellers"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
          </div>
        </div>
      </div>

      <!-- Address Information -->
      <div class="space-y-4">
        <h4 class="font-semibold text-slate-900 border-b pb-2">Informasi Alamat</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div x-data="{ provinceDropdownOpen: false }">
            <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
            <div class="relative" @click.away="provinceDropdownOpen = false">
              <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                <span :class="!editingTravelAgent.province && 'text-muted-foreground'" x-text="editingTravelAgent.province || 'Pilih provinsi'"></span>
                <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="provinceDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg max-h-60 overflow-y-auto">
                <template x-for="province in provinces" :key="province">
                  <button type="button" @click="editingTravelAgent.province = province; loadCitiesForEditAgent(province); provinceDropdownOpen = false"
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
                <span :class="!editingTravelAgent.city && 'text-muted-foreground'" x-text="editingTravelAgent.city || 'Pilih kota/kabupaten'"></span>
                <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="cityDropdownOpen" x-transition class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg max-h-60 overflow-y-auto">
                <template x-for="city in citiesTravelAgent" :key="city">
                  <button type="button" @click="editingTravelAgent.city = city; cityDropdownOpen = false"
                    class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors" x-text="city"></button>
                </template>
              </div>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
          <textarea name="alamat_lengkap" x-model="editingTravelAgent.address" required rows="3"
            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
        </div>

        <!-- Map Section -->
        <div x-show="editingTravelAgent.city" class="space-y-2">
          <div class="flex items-center justify-between">
            <div>
              <label class="text-sm font-medium text-slate-700">Tandai Lokasi di Peta</label>
              <p class="text-xs text-muted-foreground">Klik pada peta untuk memperbarui lokasi</p>
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
                <p class="text-xs text-muted-foreground leading-relaxed">Klik di sini untuk mengaktifkan peta.</p>
              </div>
            </div>
          </div>

          <!-- Coordinates Display -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-slate-600 mb-1">Latitude</label>
              <input type="text" x-model="editingTravelAgent.latitude" readonly
                class="flex h-9 w-full rounded-md border border-input bg-muted px-3 py-1 text-sm text-muted-foreground">
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-600 mb-1">Longitude</label>
              <input type="text" x-model="editingTravelAgent.longitude" readonly
                class="flex h-9 w-full rounded-md border border-input bg-muted px-3 py-1 text-sm text-muted-foreground">
            </div>
          </div>
        </div>
      </div>

      <!-- File Uploads -->
      <div class="space-y-4">
        <h4 class="font-semibold text-slate-900 border-b pb-2">Dokumen</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Logo Travel (Opsional)</label>
            <input type="file" name="logo" accept="image/*"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium">
            <p class="text-xs text-muted-foreground mt-1">PNG, JPG. Kosongkan jika tidak ingin mengubah.</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Surat PPIU (Opsional)</label>
            <input type="file" name="surat_ppiu" accept="image/*,application/pdf"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium">
            <p class="text-xs text-muted-foreground mt-1">PNG, JPG, PDF. Kosongkan jika tidak ingin mengubah.</p>
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

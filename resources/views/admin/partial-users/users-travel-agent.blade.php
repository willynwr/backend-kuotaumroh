<table class="w-full">
  <thead>
    <tr class="border-b whitespace-nowrap">
      <th @click="sort('status')" class="h-12 px-4 text-center align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center justify-center gap-1">
          <span>Status</span>
          <span class="text-xs" x-text="getSortIcon('status')"></span>
        </div>
      </th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
      <th @click="sort('name')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Nama PIC</span>
          <span class="text-xs" x-text="getSortIcon('name')"></span>
        </div>
      </th>
      <th @click="sort('email')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Email</span>
          <span class="text-xs" x-text="getSortIcon('email')"></span>
        </div>
      </th>
      <th @click="sort('agent_category')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Kategori Agent</span>
          <span class="text-xs" x-text="getSortIcon('agent_category')"></span>
        </div>
      </th>
      <th @click="sort('phone')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>No. HP</span>
          <span class="text-xs" x-text="getSortIcon('phone')"></span>
        </div>
      </th>
      <th @click="sort('travel_name')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Nama Travel</span>
          <span class="text-xs" x-text="getSortIcon('travel_name')"></span>
        </div>
      </th>
      <th @click="sort('travel_type')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Jenis Travel</span>
          <span class="text-xs" x-text="getSortIcon('travel_type')"></span>
        </div>
      </th>
      <th @click="sort('monthly_travellers')" class="h-12 px-4 text-right align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center justify-end gap-1">
          <span>Total Travel/Bulan</span>
          <span class="text-xs" x-text="getSortIcon('monthly_travellers')"></span>
        </div>
      </th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Logo</th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Surat PPIU</th>
      <th @click="sort('province')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Provinsi</span>
          <span class="text-xs" x-text="getSortIcon('province')"></span>
        </div>
      </th>
      <th @click="sort('city')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Kota/Kab</span>
          <span class="text-xs" x-text="getSortIcon('city')"></span>
        </div>
      </th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Detail Alamat</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Koordinat</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Link Referral</th>
    </tr>
  </thead>
  <tbody>
    <template x-for="user in paginatedUsers" :key="user.id">
      <tr class="border-b transition-colors hover:bg-muted/50 whitespace-nowrap">
        <td class="p-4 align-middle text-center">
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
            'bg-green-100 text-green-800': user.status === 'active',
            'bg-red-100 text-red-800': user.status === 'reject',
            'bg-yellow-100 text-yellow-800': user.status === 'pending'
          }" x-text="user.status === 'active' ? 'Aktif' : (user.status === 'reject' ? 'Nonaktif' : 'Pending')"></span>
        </td>
        <td class="p-4 align-middle">
          <!-- Actions for Pending Agents -->
          <template x-if="user.status === 'pending'">
            <div class="flex items-center justify-center gap-2">
              <button @click="openApproveModal(user)" title="Approve" class="text-green-600 hover:text-green-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </button>
              <button @click="openRejectModal(user)" title="Reject" class="text-red-600 hover:text-red-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
              <button @click="openUserDetail(user)" title="Detail" class="text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
              <button @click="openEditTravelAgent(user)" title="Edit" class="text-yellow-600 hover:text-yellow-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </button>
            </div>
          </template>
          
          <!-- Actions for Approved/Rejected Agents -->
          <template x-if="user.status !== 'pending'">
            <div class="flex items-center justify-center gap-2">
              <button @click="openUserDetail(user)" title="Detail" class="text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
              <button @click="openEditTravelAgent(user)" title="Edit" class="text-yellow-600 hover:text-yellow-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </button>
              <button @click="toggleBan(user)" :title="user.status === 'active' ? 'Ban' : 'Aktifkan'" :class="user.status === 'active' ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800'" class="transition-colors">
                <svg x-show="user.status === 'active'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <svg x-show="user.status === 'reject'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </button>
            </div>
          </template>
        </td>
        <td class="p-4 align-middle font-medium" x-text="user.name"></td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.email"></td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.agent_category || '-'"> </td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.phone || '-'"> </td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.travel_name || '-'"> </td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.travel_type || '-'"> </td>
        <td class="p-4 align-middle text-right" x-text="(user.monthly_travellers ?? 0).toLocaleString('id-ID')"></td>
        <td class="p-4 align-middle text-center">
          <button x-show="user.logo" @click="openFileModal(user.logo)" class="text-sm text-primary hover:underline">Lihat</button>
          <span x-show="!user.logo" class="text-muted-foreground">-</span>
        </td>
        <td class="p-4 align-middle text-center">
          <button x-show="user.ppiu" @click="openFileModal(user.ppiu)" class="text-sm text-primary hover:underline">Lihat</button>
          <span x-show="!user.ppiu" class="text-muted-foreground">-</span>
        </td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.province || '-'"> </td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.city || '-'"> </td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.address || '-'"> </td>
        <td class="p-4 align-middle text-muted-foreground">
          <template x-if="user.latitude && user.longitude">
            <a :href="`https://www.google.com/maps?q=${user.latitude},${user.longitude}`" target="_blank" class="text-primary hover:underline" x-text="`${user.latitude}, ${user.longitude}`"></a>
          </template>
          <span x-show="!user.latitude || !user.longitude" class="text-muted-foreground">-</span>
        </td>
        <td class="p-4 align-middle text-sm">
          <a x-show="user.referral_code" :href="getReferralLink(user)" target="_blank" x-text="user.referral_code" class="text-primary hover:underline"></a>
          <span x-show="!user.referral_code" class="text-muted-foreground">-</span>
        </td>
      </tr>
    </template>
    <tr x-show="paginatedUsers.length === 0">
      <td colspan="16" class="p-8 text-center text-muted-foreground">Tidak ada data travel agent</td>
    </tr>
  </tbody>
</table>

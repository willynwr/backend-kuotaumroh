<table class="w-full">
  <thead>
    <tr class="border-b whitespace-nowrap">
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama PIC</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Kategori Agent</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">No. HP</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Travel</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Jenis Travel</th>
      <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total Travel/Bulan</th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Logo</th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Surat PPIU</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Provinsi</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Kota/Kab</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Detail Alamat</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Koordinat</th>

      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <template x-for="user in paginatedNewUsers" :key="user.id">
      <tr class="border-b transition-colors hover:bg-muted/50 whitespace-nowrap">
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

        <td class="p-4 align-middle text-center">
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
        </td>
        <td class="p-4 align-middle text-center">
          <div class="flex items-center justify-center gap-3">
            <button @click="openUserDetail(user)" class="text-blue-600 hover:text-blue-800" title="Detail">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
            <button @click="openApproveModal(user)" class="text-green-600 hover:text-green-800" title="Approve">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </button>
            <button @click="openRejectModal(user)" class="text-red-600 hover:text-red-800" title="Reject">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </td>
      </tr>
    </template>
    <tr x-show="paginatedNewUsers.length === 0">
      <td colspan="15" class="p-8 text-center text-muted-foreground">Tidak ada pengguna baru</td>
    </tr>
  </tbody>
</table>

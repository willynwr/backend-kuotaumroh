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
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Link Referral</th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <template x-for="user in paginatedUsers" :key="user.id">
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
        <td class="p-4 align-middle text-sm">
          <a x-show="user.referral_code" :href="getReferralLink(user)" target="_blank" x-text="user.referral_code" class="text-primary hover:underline"></a>
          <span x-show="!user.referral_code" class="text-muted-foreground">-</span>
        </td>
        <td class="p-4 align-middle text-center">
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
            'bg-green-100 text-green-800': user.status === 'active',
            'bg-red-100 text-red-800': user.status === 'reject',
            'bg-yellow-100 text-yellow-800': user.status === 'pending'
          }" x-text="user.status === 'active' ? 'Approve' : (user.status === 'reject' ? 'Reject' : 'Pending')"></span>
        </td>
        <td class="p-4 align-middle text-center">
          <div class="flex items-center justify-center gap-2">
            <button @click="openUserDetail(user)" class="text-sm text-primary hover:underline">Detail</button>
            <button x-show="user.status !== 'reject'" @click="toggleBan(user)" class="text-sm text-destructive hover:underline" x-text="user.status === 'active' ? 'Ban' : 'Aktifkan'"></button>
          </div>
        </td>
      </tr>
    </template>
    <tr x-show="paginatedUsers.length === 0">
      <td colspan="16" class="p-8 text-center text-muted-foreground">Tidak ada data travel agent</td>
    </tr>
  </tbody>
</table>

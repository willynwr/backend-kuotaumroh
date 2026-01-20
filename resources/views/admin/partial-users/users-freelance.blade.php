<table class="w-full">
  <thead>
    <tr class="border-b whitespace-nowrap">
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
      <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal Daftar</th>
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
        <td class="p-4 align-middle" x-text="formatDate(user.created_at)"></td>
        <td class="p-4 align-middle text-sm">
          <a x-show="user.referral_code" :href="getReferralLink(user)" target="_blank" x-text="user.referral_code" class="text-primary hover:underline"></a>
          <span x-show="!user.referral_code" class="text-muted-foreground">-</span>
        </td>
        <td class="p-4 align-middle text-center">
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
            'bg-green-100 text-green-800': user.status === 'active',
            'bg-red-100 text-red-800': user.status === 'reject',
            'bg-yellow-100 text-yellow-800': user.status === 'pending'
          }" x-text="user.status === 'active' ? 'Aktif' : (user.status === 'reject' ? 'Ditolak' : 'Pending')"></span>
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
      <td colspan="6" class="p-8 text-center text-muted-foreground">Tidak ada data freelance</td>
    </tr>
  </tbody>
</table>

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
          <span>Nama</span>
          <span class="text-xs" x-text="getSortIcon('name')"></span>
        </div>
      </th>
      <th @click="sort('email')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Email</span>
          <span class="text-xs" x-text="getSortIcon('email')"></span>
        </div>
      </th>
      <th @click="sort('created_at')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Tanggal Daftar</span>
          <span class="text-xs" x-text="getSortIcon('created_at')"></span>
        </div>
      </th>
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
          <div class="flex items-center justify-center gap-2">
            <button @click="openUserDetail(user)" title="Detail" class="text-blue-600 hover:text-blue-800 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
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
        </td>
        <td class="p-4 align-middle font-medium" x-text="user.name"></td>
        <td class="p-4 align-middle text-muted-foreground" x-text="user.email"></td>
        <td class="p-4 align-middle" x-text="formatDate(user.created_at)"></td>
        <td class="p-4 align-middle text-sm">
          <a x-show="user.referral_code" :href="getReferralLink(user)" target="_blank" x-text="user.referral_code" class="text-primary hover:underline"></a>
          <span x-show="!user.referral_code" class="text-muted-foreground">-</span>
        </td>
      </tr>
    </template>
    <tr x-show="paginatedUsers.length === 0">
      <td colspan="6" class="p-8 text-center text-muted-foreground">Tidak ada data freelance</td>
    </tr>
  </tbody>
</table>

<table class="w-full">
  <thead>
    <tr class="border-b whitespace-nowrap">
      <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
      <th @click="sort('id')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>ID</span>
          <span class="text-xs" x-text="getSortIcon('id')"></span>
        </div>
      </th>
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
      <th @click="sort('phone')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>No. WhatsApp</span>
          <span class="text-xs" x-text="getSortIcon('phone')"></span>
        </div>
      </th>
      <th @click="sort('created_at')" class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:bg-muted/50 transition-colors">
        <div class="flex items-center gap-1">
          <span>Tanggal Dibuat</span>
          <span class="text-xs" x-text="getSortIcon('created_at')"></span>
        </div>
      </th>
    </tr>
  </thead>
  <tbody>
    <template x-for="user in paginatedUsers" :key="user.id">
      <tr class="border-b transition-colors hover:bg-muted/50 whitespace-nowrap">
        <!-- Aksi Column (Delete) -->
        <td class="p-4 align-middle text-center">
          <button @click="deleteAdmin(user)" title="Hapus Admin" class="text-red-600 hover:text-red-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
          </button>
        </td>
        
        <!-- ID -->
        <td class="p-4 align-middle">
          <span class="font-medium text-sm" x-text="user.id"></span>
        </td>
        
        <!-- Nama -->
        <td class="p-4 align-middle">
          <span class="font-medium" x-text="user.name"></span>
        </td>
        
        <!-- Email -->
        <td class="p-4 align-middle">
          <span x-text="user.email"></span>
        </td>
        
        <!-- No. WhatsApp -->
        <td class="p-4 align-middle">
          <span x-text="user.phone || '-'"></span>
        </td>
        
        <!-- Tanggal Dibuat -->
        <td class="p-4 align-middle">
          <span x-text="formatDate(user.created_at)"></span>
        </td>
      </tr>
    </template>
    
    <!-- Empty State -->
    <template x-if="paginatedUsers.length === 0">
      <tr>
        <td colspan="6" class="p-8 text-center text-muted-foreground">
          <div class="flex flex-col items-center gap-2">
            <svg class="w-12 h-12 text-muted-foreground/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-sm">Tidak ada data admin yang ditemukan.</p>
          </div>
        </td>
      </tr>
    </template>
  </tbody>
</table>

@extends('layouts.admin')

@section('title', 'Rewards')

@section('content')
<div x-data="rewardsPage()">
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <div class="mb-6 flex items-center justify-between">
      <div class="flex items-start gap-4">
        <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h1 class="text-3xl font-bold tracking-tight">Rewards</h1>
          <p class="text-muted-foreground mt-1">Kelola reward points dan hadiah</p>
        </div>
      </div>
      <button @click="openAddModal()" class="h-10 px-4 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
        + Tambah Reward
      </button>
    </div>

    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b">
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Reward</th>
                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Poin Dibutuhkan</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Stok</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="reward in rewards" :key="reward.id">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <td class="p-4 align-middle">
                    <div class="font-medium" x-text="reward.name"></div>
                    <div class="text-xs text-muted-foreground" x-text="reward.description"></div>
                  </td>
                  <td class="p-4 align-middle text-right font-semibold" x-text="reward.points_required.toLocaleString('id-ID') + ' poin'"></td>
                  <td class="p-4 align-middle text-center" x-text="reward.stock"></td>
                  <td class="p-4 align-middle text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="reward.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" x-text="reward.status === 'active' ? 'Aktif' : 'Nonaktif'"></span>
                  </td>
                  <td class="p-4 align-middle text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button @click="editReward(reward)" class="text-sm text-primary hover:underline">Edit</button>
                      <button @click="toggleStatus(reward)" class="text-sm text-destructive hover:underline" x-text="reward.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'"></button>
                    </div>
                  </td>
                </tr>
              </template>
              <tr x-show="rewards.length === 0">
                <td colspan="5" class="p-8 text-center text-muted-foreground">Tidak ada reward</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function rewardsPage() {
    return {
      rewards: @json($rewards ?? []),

      editReward(reward) {
        window.location.href = `/admin/rewards/${reward.id}/edit`;
      },

      async toggleStatus(reward) {
        if (!confirm(`${reward.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'} reward ini?`)) return;
        
        try {
          const response = await fetch(`/admin/rewards/${reward.id}/toggle-status`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          if (response.ok) {
            reward.status = reward.status === 'active' ? 'inactive' : 'active';
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan');
        }
      },

      init() {}
    }
  }
</script>
@endpush

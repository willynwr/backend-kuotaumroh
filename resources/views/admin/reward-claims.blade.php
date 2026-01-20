@extends('layouts.admin')

@section('title', 'Reward Claims')

@section('content')
<div x-data="rewardClaimsPage()">
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <div class="mb-6 flex items-start gap-4">
      <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Reward Claims</h1>
        <p class="text-muted-foreground mt-1">Kelola klaim reward dari user</p>
      </div>
    </div>

    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold">Daftar Claims</h3>
          <select x-model="statusFilter" class="h-10 rounded-md border border-input bg-background px-3 text-sm">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b">
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Reward</th>
                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Poin</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="claim in filteredClaims" :key="claim.id">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <td class="p-4 align-middle">
                    <div class="font-medium" x-text="claim.user_name"></div>
                    <div class="text-xs text-muted-foreground" x-text="claim.user_email"></div>
                  </td>
                  <td class="p-4 align-middle" x-text="claim.reward_name"></td>
                  <td class="p-4 align-middle text-right font-semibold" x-text="claim.points_used.toLocaleString('id-ID') + ' poin'"></td>
                  <td class="p-4 align-middle" x-text="formatDate(claim.created_at)"></td>
                  <td class="p-4 align-middle text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
                      'bg-yellow-100 text-yellow-800': claim.status === 'pending',
                      'bg-green-100 text-green-800': claim.status === 'approved',
                      'bg-red-100 text-red-800': claim.status === 'rejected'
                    }" x-text="claim.status"></span>
                  </td>
                  <td class="p-4 align-middle text-center">
                    <div class="flex items-center justify-center gap-2" x-show="claim.status === 'pending'">
                      <button @click="approveClaim(claim)" class="text-sm text-green-600 hover:underline">Approve</button>
                      <button @click="rejectClaim(claim)" class="text-sm text-red-600 hover:underline">Reject</button>
                    </div>
                    <span x-show="claim.status !== 'pending'" class="text-sm text-muted-foreground">-</span>
                  </td>
                </tr>
              </template>
              <tr x-show="filteredClaims.length === 0">
                <td colspan="6" class="p-8 text-center text-muted-foreground">Tidak ada claim</td>
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
  function rewardClaimsPage() {
    return {
      claims: @json($claims ?? []),
      statusFilter: 'all',

      get filteredClaims() {
        if (this.statusFilter === 'all') return this.claims;
        return this.claims.filter(claim => claim.status === this.statusFilter);
      },

      formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
          year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });
      },

      async approveClaim(claim) {
        if (!confirm('Approve claim ini?')) return;
        
        try {
          const response = await fetch(`/admin/reward-claims/${claim.id}/approve`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          if (response.ok) {
            claim.status = 'approved';
            alert('Claim berhasil diapprove');
          }
        } catch (error) {
          console.error('Error:', error);
          alert('Terjadi kesalahan');
        }
      },

      async rejectClaim(claim) {
        if (!confirm('Reject claim ini?')) return;
        
        try {
          const response = await fetch(`/admin/reward-claims/${claim.id}/reject`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          if (response.ok) {
            claim.status = 'rejected';
            alert('Claim berhasil direject');
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

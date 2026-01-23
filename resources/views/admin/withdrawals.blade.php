@extends('layouts.admin')

@section('title', 'Withdrawals')

@push('styles')
<style>
  @keyframes progress {
    from { width: 0%; }
    to { width: 100%; }
  }
  .animate-progress {
    animation: progress 3s linear forwards;
  }
</style>
@endpush

@section('content')
<div x-data="withdrawalsPage()">
  @include('components.admin.header')

  <!-- Toast Notification -->
  <div x-show="showToast" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-100%]" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-100%]" class="fixed top-4 right-4 z-[100] max-w-md">
    <div class="rounded-xl shadow-2xl overflow-hidden" :class="toastType === 'success' ? 'bg-white border-2 border-green-500' : 'bg-white border-2 border-red-500'">
      <div class="p-4">
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0">
            <div x-show="toastType === 'success'" class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <div x-show="toastType === 'error'" class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </div>
          </div>
          <div class="flex-1 pt-0.5">
            <p class="font-semibold text-gray-900 mb-1" x-text="toastType === 'success' ? 'Berhasil!' : 'Error!'"></p>
            <p class="text-sm text-gray-600" x-text="toastMessage"></p>
          </div>
          <button @click="showToast = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
      <div class="h-1 bg-gradient-to-r" :class="toastType === 'success' ? 'from-green-500 to-green-400' : 'from-red-500 to-red-400'">
        <div class="h-full bg-white/30 animate-progress"></div>
      </div>
    </div>
  </div>

  <main class="container mx-auto py-6 px-4">
    <div class="mb-6 flex items-start gap-4">
      <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <div>
        <h1 class="text-3xl font-bold tracking-tight">Withdrawals</h1>
        <p class="text-muted-foreground mt-1">Kelola permintaan penarikan dana</p>
      </div>
    </div>

    <div class="rounded-lg border bg-white shadow-sm">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold">Daftar Withdrawal</h3>
          <select x-model="statusFilter" class="h-10 rounded-md border border-input bg-background px-3 text-sm">
            <option value="all">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="approve">Approved</option>
            <option value="reject">Rejected</option>
          </select>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b">
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">User</th>
                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Amount</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Bank</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Keterangan</th>
                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="wd in filteredWithdrawals" :key="wd.id">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <td class="p-4 align-middle text-center">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
                      'bg-yellow-100 text-yellow-800': wd.status === 'pending',
                      'bg-green-100 text-green-800': wd.status === 'approve',
                      'bg-red-100 text-red-800': wd.status === 'reject'
                    }" x-text="wd.status === 'approve' ? 'Approved' : (wd.status === 'reject' ? 'Rejected' : 'Pending')"></span>
                  </td>
                  <td class="p-4 align-middle text-center">
                    <div class="flex items-center justify-center gap-2" x-show="wd.status === 'pending'">
                      <button @click="openApproveDialog(wd)" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors">Approve</button>
                      <button @click="openRejectDialog(wd)" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors">Reject</button>
                    </div>
                    <span x-show="wd.status !== 'pending'" class="text-sm text-muted-foreground">-</span>
                  </td>
                  <td class="p-4 align-middle">
                    <div class="font-medium" x-text="wd.user_name"></div>
                    <div class="text-xs text-muted-foreground" x-text="wd.user_email"></div>
                  </td>
                  <td class="p-4 align-middle text-right font-semibold" x-text="formatRupiah(wd.amount)"></td>
                  <td class="p-4 align-middle">
                    <div x-text="wd.bank_name"></div>
                    <div class="text-xs text-muted-foreground" x-text="wd.account_number"></div>
                  </td>
                  <td class="p-4 align-middle">
                    <span class="text-sm text-muted-foreground" x-text="wd.keterangan || '-'"></span>
                  </td>
                  <td class="p-4 align-middle" x-text="formatDate(wd.created_at)"></td>
                </tr>
              </template>
              <tr x-show="filteredWithdrawals.length === 0">
                <td colspan="7" class="p-8 text-center text-muted-foreground">Tidak ada withdrawal</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal Approve -->
    <div x-show="approveDialogOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" @click.self="closeApproveDialog()">
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md animate-fade-in overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-white">Approve Withdrawal</h2>
              <p class="text-green-50 text-sm">Konfirmasi persetujuan penarikan</p>
            </div>
          </div>
        </div>
        <div class="p-6 space-y-4">
          <div x-show="selectedWithdrawal" class="bg-gray-50 rounded-xl p-4 space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">User</span>
              <span class="text-sm font-semibold text-gray-900" x-text="selectedWithdrawal?.user_name"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Jumlah</span>
              <span class="text-base font-bold text-green-600" x-text="formatRupiah(selectedWithdrawal?.amount)"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Bank</span>
              <div class="text-right">
                <div class="text-sm font-medium" x-text="selectedWithdrawal?.bank_name"></div>
                <div class="text-xs text-gray-500" x-text="selectedWithdrawal?.account_number"></div>
              </div>
            </div>
            <template x-if="selectedWithdrawal?.keterangan">
              <div class="pt-2 border-t border-gray-200">
                <span class="text-xs text-gray-500">Keterangan:</span>
                <p class="text-sm text-gray-700 mt-1" x-text="selectedWithdrawal?.keterangan"></p>
              </div>
            </template>
          </div>
          <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
              <p class="text-sm font-medium text-amber-900">Perhatian</p>
              <p class="text-xs text-amber-700 mt-1">Saldo agent akan dikurangi sesuai jumlah penarikan dan tidak dapat dibatalkan.</p>
            </div>
          </div>
        </div>
        <div class="p-6 bg-gray-50 border-t flex gap-3">
          <button @click="closeApproveDialog()" class="flex-1 inline-flex items-center justify-center rounded-lg border-2 border-gray-300 bg-white h-11 px-4 font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            Batal
          </button>
          <button @click="confirmApprove()" class="flex-1 inline-flex items-center justify-center rounded-lg h-11 px-4 font-semibold text-white bg-green-600 hover:bg-green-700 shadow-lg shadow-green-500/30 transition-all hover:shadow-xl">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Ya, Approve
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Reject -->
    <div x-show="rejectDialogOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" @click.self="closeRejectDialog()">
      <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md animate-fade-in overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-white">Reject Withdrawal</h2>
              <p class="text-red-50 text-sm">Konfirmasi penolakan penarikan</p>
            </div>
          </div>
        </div>
        <div class="p-6 space-y-4">
          <div x-show="selectedWithdrawal" class="bg-gray-50 rounded-xl p-4 space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">User</span>
              <span class="text-sm font-semibold text-gray-900" x-text="selectedWithdrawal?.user_name"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Jumlah</span>
              <span class="text-base font-bold text-red-600" x-text="formatRupiah(selectedWithdrawal?.amount)"></span>
            </div>
          </div>
          <div class="space-y-2">
            <label for="alasan_reject" class="block text-sm font-semibold text-gray-900">
              Alasan Penolakan <span class="text-red-500">*</span>
            </label>
            <textarea 
              id="alasan_reject" 
              x-model="alasanReject" 
              placeholder="Masukkan alasan penolakan minimal 10 karakter..."
              rows="4" 
              class="w-full rounded-lg border-2 border-gray-300 bg-white px-4 py-3 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none transition-all"
            ></textarea>
            <div class="flex items-start gap-2 text-xs">
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-gray-500">Alasan ini akan diberitahukan kepada agent. Minimal 10 karakter.</p>
            </div>
            <p x-show="rejectError" x-text="rejectError" class="text-sm text-red-600 font-medium flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </p>
          </div>
        </div>
        <div class="p-6 bg-gray-50 border-t flex gap-3">
          <button @click="closeRejectDialog()" class="flex-1 inline-flex items-center justify-center rounded-lg border-2 border-gray-300 bg-white h-11 px-4 font-medium text-gray-700 hover:bg-gray-50 transition-colors">
            Batal
          </button>
          <button @click="confirmReject()" :disabled="!alasanReject || alasanReject.length < 10" :class="alasanReject && alasanReject.length >= 10 ? 'bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/30 hover:shadow-xl' : 'bg-gray-400 cursor-not-allowed'" class="flex-1 inline-flex items-center justify-center rounded-lg h-11 px-4 font-semibold text-white transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Ya, Reject
          </button>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function withdrawalsPage() {
    return {
      withdrawals: @json($withdrawals ?? []),
      statusFilter: 'all',
      approveDialogOpen: false,
      rejectDialogOpen: false,
      selectedWithdrawal: null,
      alasanReject: '',
      rejectError: '',
      toastMessage: '',
      toastType: '', // 'success' or 'error'
      showToast: false,

      get filteredWithdrawals() {
        if (this.statusFilter === 'all') return this.withdrawals;
        return this.withdrawals.filter(wd => wd.status === this.statusFilter);
      },

      displayToast(message, type = 'success') {
        this.toastMessage = message;
        this.toastType = type;
        this.showToast = true;
        setTimeout(() => {
          this.showToast = false;
        }, 3000);
      },

      formatRupiah(amount) {
        return `Rp ${parseInt(amount).toLocaleString('id-ID')}`;
      },

      formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
          year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });
      },

      openApproveDialog(wd) {
        this.selectedWithdrawal = wd;
        this.approveDialogOpen = true;
      },

      closeApproveDialog() {
        this.approveDialogOpen = false;
        this.selectedWithdrawal = null;
      },

      openRejectDialog(wd) {
        this.selectedWithdrawal = wd;
        this.alasanReject = '';
        this.rejectError = '';
        this.rejectDialogOpen = true;
      },

      closeRejectDialog() {
        this.rejectDialogOpen = false;
        this.selectedWithdrawal = null;
        this.alasanReject = '';
        this.rejectError = '';
      },

      async confirmApprove() {
        if (!this.selectedWithdrawal) return;
        
        try {
          const response = await fetch(`/admin/withdrawals/${this.selectedWithdrawal.id}/approve`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          const result = await response.json();
          
          if (result.success) {
            this.closeApproveDialog();
            this.displayToast('Withdrawal berhasil diapprove dan saldo agent telah dikurangi', 'success');
            setTimeout(() => window.location.reload(), 1500);
          } else {
            this.displayToast(result.message || 'Gagal approve withdrawal', 'error');
          }
        } catch (error) {
          console.error('Error:', error);
          this.displayToast('Terjadi kesalahan saat approve withdrawal', 'error');
        }
      },

      async confirmReject() {
        if (!this.selectedWithdrawal || !this.alasanReject || this.alasanReject.length < 10) {
          this.rejectError = 'Alasan penolakan minimal 10 karakter';
          return;
        }
        
        try {
          const response = await fetch(`/admin/withdrawals/${this.selectedWithdrawal.id}/reject`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              alasan_reject: this.alasanReject
            })
          });

          const result = await response.json();
          
          if (result.success) {
            this.closeRejectDialog();
            this.displayToast('Withdrawal berhasil direject', 'success');
            setTimeout(() => window.location.reload(), 1500);
          } else {
            this.rejectError = result.message || 'Gagal reject withdrawal';
          }
        } catch (error) {
          console.error('Error:', error);
          this.rejectError = 'Terjadi kesalahan saat reject withdrawal';
        }
      },

      init() {}
    }
  }
</script>
@endpush

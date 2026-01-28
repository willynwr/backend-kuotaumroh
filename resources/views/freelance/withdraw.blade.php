@extends('layouts.freelance')

@section('title', 'Tarik Saldo')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .animate-fade-in {
      animation: fadeIn 0.2s ease-out;
    }
</style>
@endpush

@section('content')
<div x-data="withdrawApp()">
    <main class="container mx-auto py-6 px-4 max-w-2xl">
      <div class="mb-6">
        <div class="flex items-stretch gap-3">
          <a href="{{ url('/dash/' . $linkReferral) }}" class="inline-flex items-center justify-center w-12 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali ke Dashboard">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </a>
          <div>
            <div class="flex items-center gap-2 text-sm text-muted-foreground mb-1">
              <a href="{{ url('/dash/' . $linkReferral . '/wallet') }}" class="hover:text-foreground">Dompet</a>
              <span>/</span>
              <span class="text-foreground">Tarik Saldo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight">Tarik Saldo</h1>
            <p class="text-muted-foreground mt-1">Tarik saldo ke rekening bank Anda</p>
          </div>
        </div>
      </div>

      <!-- Balance Card -->
      <div class="mb-6 relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm">
        <div class="pointer-events-none absolute right-0 top-0 h-32 w-32 -translate-y-1/3 translate-x-1/3 rounded-full bg-primary/5"></div>
        <div class="relative z-10 flex flex-row items-center justify-between p-4 pb-3">
          <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Saldo Komisi Tersedia</h3>
          <div class="rounded-lg p-2 bg-primary/10 text-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="relative z-10 p-4 pt-0">
          <div class="flex items-center gap-3">
            <img src="{{ asset('images/wallet.png') }}" alt="Wallet" class="h-10 w-10 object-contain">
            <div class="text-2xl font-extrabold text-primary tracking-tight" x-text="'Rp ' + walletBalance.toLocaleString('id-ID')"></div>
          </div>
          <div class="flex items-center justify-between border-t border-slate-100 pt-3 mt-3">
            <div>
              <p class="text-xs font-bold uppercase text-slate-400">Minimum Penarikan</p>
              <p class="text-lg font-extrabold text-primary" x-text="'Rp ' + minWithdrawal.toLocaleString('id-ID')"></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Withdrawal Form -->
      <form @submit.prevent="handleSubmit()">
        <div class="rounded-lg border bg-white shadow-sm">
          <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Form Penarikan</h3>
            <p class="text-sm text-muted-foreground mt-1">Minimum penarikan <span x-text="formatRupiah(minWithdrawal)"></span></p>
          </div>
          <div class="p-6 space-y-6">
            <!-- Amount Input -->
            <div class="space-y-2">
              <label for="amount" class="text-sm font-medium">Jumlah Penarikan</label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">Rp</span>
                <input id="amount" type="text" placeholder="0" x-model="amount" @input="formatAmount()" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-28 py-2 text-lg font-medium">
                <button type="button" @click="withdrawAll()" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-medium text-primary hover:text-primary/80 px-2 py-1 rounded hover:bg-primary/10 transition-colors">Tarik semua</button>
              </div>
              <template x-if="numericAmount > 0 && numericAmount < minWithdrawal">
                <p class="text-sm text-destructive flex items-center gap-1">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Minimum penarikan <span x-text="formatRupiah(minWithdrawal)"></span>
                </p>
              </template>
              <template x-if="numericAmount > walletBalance">
                <p class="text-sm text-destructive flex items-center gap-1">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Saldo tidak mencukupi
                </p>
              </template>
            </div>

            <!-- Bank Selection -->
            <div class="space-y-2">
              <label for="bank" class="text-sm font-medium">Rekening Tujuan</label>
              <select id="bank" x-model="selectedAccount" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                <option value="">Pilih rekening</option>
                <template x-for="account in savedAccounts" :key="account.id">
                  <option :value="account.id" x-text="account.bankName + ' - ' + account.accountNumber"></option>
                </template>
              </select>
              <button type="button" @click="openAddAccountDialog()" class="h-auto p-0 text-primary hover:text-primary/80 font-medium text-sm flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Rekening
              </button>
            </div>

            <!-- Selected Account Preview -->
            <template x-if="selectedBank">
              <div class="rounded-lg bg-muted/50 border">
                <div class="flex items-center gap-4 p-4">
                  <div class="rounded-full bg-background p-2">
                    <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                  </div>
                  <div class="flex-1">
                    <p class="font-medium" x-text="selectedBank.bankName"></p>
                    <p class="text-sm text-muted-foreground"><span x-text="selectedBank.accountNumber"></span> • <span x-text="selectedBank.accountName"></span></p>
                  </div>
                  <template x-if="selectedBank.isDefault">
                    <span class="text-xs text-primary font-medium">Default</span>
                  </template>
                </div>
              </div>
            </template>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4">
              <button type="button" @click="window.location.href = '{{ url('/dash/' . $linkReferral . '/wallet') }}'" class="flex-1 inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors">Batal</button>
              <button type="submit" :disabled="!isValidAmount" :class="isValidAmount ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'" class="flex-1 inline-flex items-center justify-center rounded-md h-10 px-4 py-2 font-medium transition-colors">Ajukan Penarikan</button>
            </div>
          </div>
        </div>
      </form>
    </main>

    <!-- Add Account Dialog -->
    <div x-show="addAccountDialogOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeAddAccountDialog()">
      <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md max-h-[90vh] overflow-hidden animate-fade-in">
        <div class="p-6 border-b">
          <h2 class="text-lg font-semibold">Tambah Rekening Baru</h2>
          <p class="text-sm text-muted-foreground mt-1">Masukkan informasi rekening bank yang akan digunakan untuk penarikan.</p>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto max-h-[calc(90vh-180px)]">
          <div class="space-y-2">
            <label for="newBank" class="text-sm font-medium">Nama Bank</label>
            <select id="newBank" x-model="newAccountBank" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
              <option value="">Pilih bank</option>
              <template x-for="bank in bankList" :key="bank">
                <option :value="bank" x-text="bank"></option>
              </template>
            </select>
          </div>
          <div class="space-y-2">
            <label for="newAccountNumber" class="text-sm font-medium">Nomor Rekening</label>
            <input id="newAccountNumber" type="text" placeholder="1234567890" x-model="newAccountNumber" @input="newAccountNumber = newAccountNumber.replace(/\D/g, '')" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-mono">
          </div>
          <div class="space-y-2">
            <label for="newAccountName" class="text-sm font-medium">Nama Pemilik Rekening</label>
            <input id="newAccountName" type="text" placeholder="Nama sesuai rekening" x-model="newAccountName" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
          </div>
          <div class="flex items-center space-x-2">
            <input type="checkbox" id="newAccountDefault" x-model="newAccountIsDefault" class="h-4 w-4 rounded border-gray-300">
            <label for="newAccountDefault" class="text-sm font-normal cursor-pointer">Jadikan rekening utama</label>
          </div>
        </div>
        <div class="p-6 border-t flex gap-3">
          <button type="button" @click="closeAddAccountDialog()" class="flex-1 inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors">Batal</button>
          <button type="button" @click="handleAddAccount()" :disabled="!newAccountBank || !newAccountNumber || !newAccountName" :class="(newAccountBank && newAccountNumber && newAccountName) ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'" class="flex-1 inline-flex items-center justify-center rounded-md h-10 px-4 py-2 font-medium transition-colors">Tambah Rekening</button>
        </div>
      </div>
    </div>

    <!-- OTP Verification Dialog -->
    <div x-show="otpDialogOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
      <div class="relative bg-white rounded-lg shadow-lg w-full max-w-sm overflow-hidden animate-fade-in">
        <div class="p-6 text-center">
             <div class="mx-auto w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
               </svg>
             </div>
             <h3 class="text-lg font-bold mb-2">Verifikasi Penarikan</h3>
             <p class="text-sm text-muted-foreground mb-6">Masukkan 6 digit kode OTP yang telah dikirim ke WhatsApp Anda <br><span class="font-semibold text-foreground" x-text="maskedPhoneNumber"></span></p>

             <div class="flex justify-center gap-2 mb-6">
                <template x-for="(val, index) in otpValues" :key="index">
                    <input type="text" maxlength="1" class="w-10 h-10 rounded-md border border-input text-center text-lg font-bold focus:border-primary focus:ring-1 focus:ring-primary outline-none" x-model="otpValues[index]" :id="'otp-input-' + index" @input="handleOtpInput(index, $event)" @keydown="handleOtpKeyDown(index, $event)" @paste="handlePaste($event)">
                </template>
             </div>

             <div class="text-sm text-center mb-6">
                 <template x-if="!canResend">
                     <span class="text-muted-foreground">Kirim ulang dalam <span x-text="otpTimer"></span>s</span>
                 </template>
                 <template x-if="canResend">
                     <button @click="resendOtp()" class="text-primary hover:underline font-medium">Kirim Ulang OTP</button>
                 </template>
             </div>
             
             <div class="flex gap-3">
                <button type="button" @click="closeOtpDialog()" class="flex-1 inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors text-sm font-medium">Batal</button>
                <button type="button" @click="confirmWithdrawal()" :disabled="otpValues.join('').length !== 6" :class="otpValues.join('').length === 6 ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'" class="flex-1 inline-flex items-center justify-center rounded-md h-10 px-4 py-2 text-sm font-medium transition-colors">Konfirmasi</button>
             </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div x-show="toastVisible" x-transition class="toast fixed bottom-4 right-4 bg-background border rounded-lg shadow-lg p-4 min-w-[300px] z-50">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function withdrawApp() {
  return {
    walletBalance: 3250000,
    minWithdrawal: 100000,
    amount: '',
    selectedAccount: '',
    savedAccounts: [
      { id: '1', bankName: 'BCA', accountNumber: '1234567890', accountName: 'Ahmad Fauzi', isDefault: true },
      { id: '2', bankName: 'Mandiri', accountNumber: '0987654321', accountName: 'Ahmad Fauzi', isDefault: false },
    ],
    bankList: ['BCA', 'Mandiri', 'BNI', 'BRI', 'CIMB Niaga', 'Permata', 'Danamon', 'Bank Syariah Indonesia (BSI)', 'BTN', 'Mega'],
    addAccountDialogOpen: false,
    newAccountBank: '',
    newAccountNumber: '',
    newAccountName: '',
    newAccountIsDefault: false,
    otpDialogOpen: false,
    otpValues: ['', '', '', '', '', ''],
    otpTimer: 30,
    canResend: false,
    timerInterval: null,
    toastVisible: false,
    toastTitle: '',
    toastMessage: '',
    userPhone: '081234561234',

    get numericAmount() {
      return parseInt(this.amount.replace(/\D/g, '')) || 0;
    },

    get isValidAmount() {
      return this.numericAmount >= this.minWithdrawal && this.numericAmount <= this.walletBalance && this.selectedAccount;
    },

    get selectedBank() {
      return this.savedAccounts.find(a => a.id === this.selectedAccount);
    },

    get maskedPhoneNumber() {
      if (!this.userPhone) return '';
      const last4 = this.userPhone.slice(-4);
      return '********' + last4;
    },

    formatAmount() {
      const value = this.amount.replace(/\D/g, '');
      if (value) this.amount = parseInt(value).toLocaleString('id-ID');
      else this.amount = '';
    },

    formatRupiah(amount) {
      return `Rp ${amount.toLocaleString('id-ID')}`;
    },

    withdrawAll() {
      this.amount = this.walletBalance.toLocaleString('id-ID');
    },

    handleSubmit() {
      if (!this.isValidAmount) return;
      this.otpDialogOpen = true;
      this.otpValues = ['', '', '', '', '', ''];
      this.startOtpTimer();
      this.$nextTick(() => {
        const firstInput = document.getElementById('otp-input-0');
        if (firstInput) firstInput.focus();
      });
    },

    startOtpTimer() {
      this.otpTimer = 30;
      this.canResend = false;
      clearInterval(this.timerInterval);
      this.timerInterval = setInterval(() => {
        if (this.otpTimer > 0) this.otpTimer--;
        else { this.canResend = true; clearInterval(this.timerInterval); }
      }, 1000);
    },

    resendOtp() {
      this.startOtpTimer();
      this.showToast('OTP Dikirim Ulang', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    },

    handleOtpInput(index, event) {
      const input = event.target;
      const value = input.value;
      if (!/^\d*$/.test(value)) {
        this.otpValues[index] = value.replace(/\D/g, '');
        return;
      }
      if (value.length === 1) {
        if (index < 5) document.getElementById(`otp-input-${index + 1}`).focus();
      } else if (value.length > 1) {
        const chars = value.split('').slice(0, 6 - index);
        chars.forEach((char, i) => { this.otpValues[index + i] = char; });
        const nextIndex = Math.min(index + chars.length, 5);
        document.getElementById(`otp-input-${nextIndex}`).focus();
      }
    },

    handleOtpKeyDown(index, event) {
      if (event.key === 'Backspace') {
        if (this.otpValues[index] === '' && index > 0) {
          this.otpValues[index - 1] = '';
          document.getElementById(`otp-input-${index - 1}`).focus();
        }
      }
      if (event.key === 'ArrowLeft' && index > 0) document.getElementById(`otp-input-${index - 1}`).focus();
      if (event.key === 'ArrowRight' && index < 5) document.getElementById(`otp-input-${index + 1}`).focus();
    },

    handlePaste(event) {
      event.preventDefault();
      const pasteData = (event.clipboardData || window.clipboardData).getData('text');
      const numericData = pasteData.replace(/\D/g, '').slice(0, 6);
      if (numericData) {
        const chars = numericData.split('');
        chars.forEach((char, i) => { this.otpValues[i] = char; });
        const nextIndex = Math.min(chars.length - 1, 5);
        document.getElementById(`otp-input-${nextIndex}`).focus();
      }
    },

    closeOtpDialog() {
      this.otpDialogOpen = false;
      this.otpValues = ['', '', '', '', '', ''];
      clearInterval(this.timerInterval);
    },

    confirmWithdrawal() {
      const otpCode = this.otpValues.join('');
      if (otpCode.length === 6) this.performWithdrawal();
      else this.showToast('OTP Tidak Valid', 'Mohon masukkan kode OTP 6 digit.');
    },

    performWithdrawal() {
      this.closeOtpDialog();
      this.showToast('Permintaan Penarikan Dikirim', `Penarikan sebesar ${this.formatRupiah(this.numericAmount)} berhasil diajukan.`);
      setTimeout(() => { window.location.href = '{{ url('/dash/' . $linkReferral . '/wallet') }}'; }, 1500);
    },

    openAddAccountDialog() {
      this.addAccountDialogOpen = true;
    },

    closeAddAccountDialog() {
      this.addAccountDialogOpen = false;
      this.newAccountBank = '';
      this.newAccountNumber = '';
      this.newAccountName = '';
      this.newAccountIsDefault = false;
    },

    handleAddAccount() {
      if (!this.newAccountBank || !this.newAccountNumber || !this.newAccountName) {
        this.showToast('Validasi Gagal', 'Mohon lengkapi semua field.');
        return;
      }
      if (this.savedAccounts.some(acc => acc.accountNumber === this.newAccountNumber)) {
        this.showToast('Rekening Sudah Ada', 'Nomor rekening ini sudah terdaftar.');
        return;
      }
      const newAccount = { id: Date.now().toString(), bankName: this.newAccountBank, accountNumber: this.newAccountNumber, accountName: this.newAccountName, isDefault: this.newAccountIsDefault };
      if (this.newAccountIsDefault) {
        this.savedAccounts = this.savedAccounts.map(acc => ({ ...acc, isDefault: false }));
      }
      this.savedAccounts.push(newAccount);
      this.selectedAccount = newAccount.id;
      this.showToast('Rekening Ditambahkan', `Rekening ${this.newAccountBank} berhasil ditambahkan.`);
      this.closeAddAccountDialog();
    },

    showToast(title, message) {
      this.toastTitle = title;
      this.toastMessage = message;
      this.toastVisible = true;
      setTimeout(() => { this.toastVisible = false; }, 3000);
    },

    init() {
      const defaultAcc = this.savedAccounts.find(a => a.isDefault);
      if (defaultAcc) this.selectedAccount = defaultAcc.id;
    }
  }
}
</script>
@endpush

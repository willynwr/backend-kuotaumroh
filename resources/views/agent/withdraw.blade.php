@extends('agent.layout')

@section('title', 'Tarik Saldo - Kuotaumroh.id')

@section('content')
  <div x-data="withdrawApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4 max-w-2xl">
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/wallet') : route('agent.wallet') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <div class="flex items-center gap-2 text-sm text-muted-foreground mb-1">
              <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/wallet') : route('agent.wallet') }}" class="hover:text-foreground">Dompet</a>
              <span>/</span>
              <span class="text-foreground">Tarik Saldo</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight">Tarik Saldo</h1>
            <p class="text-muted-foreground mt-1">Tarik saldo ke rekening bank Anda</p>
          </div>
        </div>
      </div>

      <div class="mb-6 relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm">
        <div class="relative z-10 flex flex-row items-center justify-between space-y-0 pb-4 p-6">
          <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Saldo Tersedia</div>
        </div>
        <div class="relative z-10 px-6 pb-6">
          <div class="flex items-center gap-3">
            <img :src="imageBase + '/wallet.png'" alt="Wallet" class="h-12 w-12 object-contain">
            <div class="text-3xl font-bold text-slate-900 tracking-tight" x-text="formatRupiah(walletBalance)"></div>
          </div>
          <div class="mt-2 text-sm text-slate-500">
            <span>Minimum penarikan</span>
            <span class="ml-2 font-semibold text-slate-900" x-text="formatRupiah(minWithdrawal)"></span>
          </div>
        </div>
      </div>

      <form @submit.prevent="handleSubmit()">
        <div class="rounded-lg border bg-white shadow-sm">
          <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Form Penarikan</h3>
            <p class="text-sm text-muted-foreground mt-1">Minimum penarikan <span x-text="formatRupiah(minWithdrawal)"></span></p>
          </div>
          <div class="p-6 space-y-6">
            <div class="space-y-2">
              <label for="amount" class="text-sm font-medium">Jumlah Penarikan</label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">Rp</span>
                <input id="amount" type="text" placeholder="0" x-model="amount" @input="formatAmount()" class="flex h-10 w-full rounded-md border border-input bg-background pl-10 pr-28 py-2 text-lg font-medium">
                <button type="button" @click="withdrawAll()" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-medium text-primary hover:text-primary/80 px-2 py-1 rounded hover:bg-primary/10 transition-colors">Tarik semua</button>
              </div>
              <template x-if="numericAmount > 0 && numericAmount < minWithdrawal">
                <p class="text-sm text-destructive flex items-center gap-1">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                  Minimum penarikan <span x-text="formatRupiah(minWithdrawal)"></span>
                </p>
              </template>
              <template x-if="numericAmount > walletBalance">
                <p class="text-sm text-destructive flex items-center gap-1">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                  Saldo tidak mencukupi
                </p>
              </template>
            </div>

            <div class="space-y-2">
              <label for="bank" class="text-sm font-medium">Rekening Tujuan</label>
              <select id="bank" x-model="selectedAccount" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                <option value="">Pilih rekening</option>
                <template x-for="account in savedAccounts" :key="account.id">
                  <option :value="account.id" x-text="account.bankName + ' - ' + account.accountNumber"></option>
                </template>
              </select>
              <button type="button" @click="openAddAccountDialog()" class="h-auto p-0 text-primary hover:text-primary/80 font-medium text-sm flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Rekening
              </button>
            </div>

            <template x-if="selectedBank">
              <div class="rounded-lg bg-muted/50 border">
                <div class="flex items-center gap-4 p-4">
                  <div class="rounded-full bg-background p-2">
                    <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
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

            <div class="flex gap-3 pt-4">
              <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/wallet') : route('agent.wallet') }}" class="flex-1 inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors">Batal</a>
              <button type="submit" :disabled="!isValidAmount" :class="isValidAmount ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'" class="flex-1 inline-flex items-center justify-center rounded-md h-10 px-4 py-2 font-medium transition-colors">Ajukan Penarikan</button>
            </div>
          </div>
        </div>
      </form>
    </main>

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

    <div x-show="toastVisible" x-transition class="toast">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function withdrawApp() {
      return {
        imageBase: @json(asset('images')),
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
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',
        init() {
          const defaultAcc = this.savedAccounts.find(a => a.isDefault);
          if (defaultAcc) this.selectedAccount = defaultAcc.id;
        },
        get numericAmount() {
          return parseInt(String(this.amount || '').replace(/\D/g, '')) || 0;
        },
        get isValidAmount() {
          return this.numericAmount >= this.minWithdrawal && this.numericAmount <= this.walletBalance && this.selectedAccount;
        },
        get selectedBank() {
          return this.savedAccounts.find(a => a.id === this.selectedAccount);
        },
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        formatAmount() {
          const value = String(this.amount || '').replace(/\D/g, '');
          this.amount = value ? parseInt(value, 10).toLocaleString('id-ID') : '';
        },
        withdrawAll() {
          this.amount = this.walletBalance.toLocaleString('id-ID');
        },
        handleSubmit() {
          if (!this.isValidAmount) return;
          this.showToast('Permintaan Penarikan Dikirim', `Penarikan sebesar ${this.formatRupiah(this.numericAmount)} sedang diproses.`);
          setTimeout(() => { window.location.href = @json(isset($linkReferral) ? url('/dash/' . $linkReferral . '/wallet') : route('agent.wallet')); }, 1500);
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
          const newAccount = {
            id: Date.now().toString(),
            bankName: this.newAccountBank,
            accountNumber: this.newAccountNumber,
            accountName: this.newAccountName,
            isDefault: this.newAccountIsDefault,
          };
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
      };
    }
  </script>
@endsection

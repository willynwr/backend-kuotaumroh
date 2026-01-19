@extends('agent.layout')

@section('title', 'Pesanan Baru - Kuotaumroh.id')

@section('content')
  <div x-data="orderApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <h1 class="text-3xl font-bold tracking-tight">Pesanan Baru</h1>
        <p class="text-muted-foreground mt-2">Buat pesanan kuota umroh baru</p>
      </div>

      <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
          <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
              <h3 class="text-lg font-semibold">Pilih Metode Input</h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 gap-1 p-1 bg-muted rounded-lg mb-6">
                <button @click="mode = 'bulk'" :class="mode === 'bulk' ? 'bg-white shadow-sm' : 'hover:bg-white/50'" class="py-2 px-4 rounded-md text-sm font-medium transition-all">Input Massal</button>
                <button @click="mode = 'individual'" :class="mode === 'individual' ? 'bg-white shadow-sm' : 'hover:bg-white/50'" class="py-2 px-4 rounded-md text-sm font-medium transition-all">Input Individu</button>
              </div>

              <div x-show="mode === 'bulk'" class="space-y-4">
                <div class="space-y-2">
                  <div class="flex items-center justify-between">
                    <label for="bulk-input" class="text-sm font-medium">Daftar Nomor</label>
                  </div>
                  <textarea id="bulk-input" x-model="bulkInput" @input="parseBulkNumbers()" placeholder="Masukkan nomor telepon, satu per baris atau pisahkan dengan koma..." rows="6" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-mono"></textarea>
                  <p class="text-xs text-muted-foreground">Format: 08xx-xxxx-xxxx atau 628xxxxxxxxxx</p>
                  <div class="flex items-center gap-4 text-sm">
                    <span class="text-muted-foreground" x-text="parsedNumbers.length + ' nomor terdeteksi'"></span>
                    <template x-if="validCount > 0"><span class="badge badge-primary" x-text="validCount + ' valid'"></span></template>
                    <template x-if="invalidCount > 0"><span class="badge badge-destructive" x-text="invalidCount + ' tidak valid'"></span></template>
                  </div>
                </div>

                <div class="rounded-lg border" x-show="Object.keys(providerGroups).length > 0">
                  <div class="p-4 border-b"><h4 class="font-medium">Pilih Paket per Provider</h4></div>
                  <div class="overflow-x-auto">
                    <table class="w-full">
                      <thead>
                        <tr class="border-b">
                          <th class="h-10 px-4 text-left text-sm font-medium text-muted-foreground">Provider</th>
                          <th class="h-10 px-4 text-center text-sm font-medium text-muted-foreground">Jumlah</th>
                          <th class="h-10 px-4 text-left text-sm font-medium text-muted-foreground">Paket</th>
                          <th class="h-10 px-4 text-right text-sm font-medium text-muted-foreground">Harga Paket</th>
                          <th class="h-10 px-4 text-right text-sm font-medium text-muted-foreground">Harga Jual</th>
                        </tr>
                      </thead>
                      <tbody>
                        <template x-for="(numbers, provider) in providerGroups" :key="provider">
                          <tr class="border-b">
                            <td class="p-4 font-medium" x-text="provider"></td>
                            <td class="p-4 text-center" x-text="numbers.length"></td>
                            <td class="p-4">
                              <select x-model="providerPackage[provider]" class="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                <option value="">Pilih paket</option>
                                <template x-for="pkg in getPackagesForProvider(provider)" :key="pkg.id">
                                  <option :value="pkg.id" x-text="pkg.name"></option>
                                </template>
                              </select>
                            </td>
                            <td class="p-4 text-right font-medium" x-text="formatRupiah(getProviderSubtotal(provider))"></td>
                            <td class="p-4 text-right">
                              <div class="font-medium" x-text="formatRupiah(getProviderSellTotal(provider))"></div>
                              <div class="text-xs text-primary font-medium">(Profit +<span x-text="formatRupiah(getProviderProfit(provider))"></span>)</div>
                            </td>
                          </tr>
                        </template>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div x-show="mode === 'individual'" class="space-y-4">
                <div class="rounded-lg border">
                  <div class="p-4 border-b"><h4 class="font-medium">Daftar Nomor</h4></div>
                  <div class="p-4 space-y-3">
                    <template x-for="(item, index) in individualItems" :key="item.id">
                      <div class="flex items-center gap-3 p-3 rounded-lg">
                        <span class="text-sm text-muted-foreground w-6" x-text="(index + 1) + '.'"></span>
                        <input type="text" placeholder="Nomor telepon" x-model="item.msisdn" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-mono">
                        <select x-model="item.packageId" class="h-10 w-56 rounded-md border border-input bg-background px-3 py-2 text-sm">
                          <option value="">Pilih paket</option>
                          <template x-for="pkg in packages" :key="pkg.id">
                            <option :value="pkg.id" x-text="pkg.provider + ' - ' + pkg.name"></option>
                          </template>
                        </select>
                        <button @click="removeIndividualItem(item.id)" class="p-2 hover:bg-muted rounded-md" title="Hapus">
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                      </div>
                    </template>
                    <button @click="addIndividualItem()" class="w-full inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 hover:bg-muted">
                      <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                      Tambah Nomor
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-1">
          <div class="rounded-lg border bg-white shadow-sm sticky top-24">
            <div class="p-6 border-b">
              <h3 class="text-lg font-semibold flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Ringkasan Pembayaran
              </h3>
            </div>
            <div class="p-6 space-y-4">
              <div class="flex justify-between text-sm"><span class="text-muted-foreground">Subtotal (<span x-text="itemCount"></span> nomor)</span><span x-text="formatRupiah(subtotal)"></span></div>
              <div class="flex justify-between text-sm"><span class="text-muted-foreground">Biaya Platform</span><span x-text="formatRupiah(platformFee)"></span></div>
              <div class="flex justify-between text-sm"><span class="text-muted-foreground">Keuntungan</span><span class="font-medium text-primary">+<span x-text="formatRupiah(profit)"></span></span></div>
              <div class="border-t pt-4"><div class="flex justify-between"><span class="font-medium">Total Pembayaran</span><span class="text-xl font-bold" x-text="formatRupiah(totalWithFee)"></span></div></div>
              <button @click="handleConfirmOrder()" :disabled="itemCount === 0" :class="itemCount > 0 ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'" class="w-full h-12 rounded-md font-medium transition-colors">Konfirmasi Pesanan</button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div x-show="toastVisible" x-transition class="toast">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function normalizeMsisdn(input) {
      const raw = String(input || '').replace(/\s+/g, '');
      const digits = raw.replace(/\D/g, '');
      if (digits.startsWith('62')) return digits;
      if (digits.startsWith('0')) return '62' + digits.slice(1);
      return digits;
    }
    function validateMsisdn(msisdn) {
      const s = String(msisdn || '');
      return /^62\d{9,13}$/.test(s);
    }
    function detectProvider(msisdn) {
      const s = String(msisdn || '');
      if (/^62(811|812|813|821|822|823|851|852|853)/.test(s)) return 'TELKOMSEL';
      if (/^62(814|815|816|855|856|857|858)/.test(s)) return 'INDOSAT';
      if (/^62(817|818|819|859|877|878)/.test(s)) return 'XL';
      if (/^62(895|896|897|898|899)/.test(s)) return 'TRI';
      return 'TELKOMSEL';
    }
    function formatRupiah(value) {
      const n = Number(value || 0);
      return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
    }
  </script>
  <script>
    function orderApp() {
      return {
        mode: 'bulk',
        bulkInput: '',
        parsedNumbers: [],
        providerGroups: {},
        providerPackage: {},
        platformFee: 0,
        packages: [
          { id: 1, provider: 'TELKOMSEL', name: 'Internet Umroh 10GB', price: 150000, sellPrice: 175000 },
          { id: 2, provider: 'INDOSAT', name: 'Internet Umroh 15GB', price: 165000, sellPrice: 190000 },
          { id: 3, provider: 'XL', name: 'Internet Umroh 20GB', price: 185000, sellPrice: 215000 },
        ],
        individualItems: [{ id: '1', msisdn: '', packageId: '' }],
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',
        get validCount() {
          return this.parsedNumbers.filter(n => n.isValid && n.provider).length;
        },
        get invalidCount() {
          return this.parsedNumbers.filter(n => !n.isValid || !n.provider).length;
        },
        get itemCount() {
          if (this.mode === 'bulk') return this.validCount;
          return this.individualItems.filter(i => i.msisdn && i.packageId).length;
        },
        get subtotal() {
          if (this.mode === 'bulk') {
            return Object.keys(this.providerGroups).reduce((sum, provider) => sum + this.getProviderSubtotal(provider), 0);
          }
          return this.individualItems.reduce((sum, item) => {
            const pkg = this.packages.find(p => String(p.id) === String(item.packageId));
            return sum + (pkg ? pkg.price : 0);
          }, 0);
        },
        get profit() {
          if (this.mode === 'bulk') {
            return Object.keys(this.providerGroups).reduce((sum, provider) => sum + this.getProviderProfit(provider), 0);
          }
          return this.individualItems.reduce((sum, item) => {
            const pkg = this.packages.find(p => String(p.id) === String(item.packageId));
            return sum + (pkg ? (pkg.sellPrice - pkg.price) : 0);
          }, 0);
        },
        get totalWithFee() {
          return this.subtotal + this.platformFee;
        },
        parseBulkNumbers() {
          if (!this.bulkInput.trim()) {
            this.parsedNumbers = [];
            this.providerGroups = {};
            this.providerPackage = {};
            return;
          }
          const lines = this.bulkInput.split(/[\n,;]+/).map(l => l.trim()).filter(Boolean);
          this.parsedNumbers = lines.map(line => {
            const normalized = normalizeMsisdn(line);
            const isValid = validateMsisdn(normalized);
            const provider = isValid ? detectProvider(normalized) : null;
            return { msisdn: normalized, provider, isValid };
          });
          const groups = {};
          this.parsedNumbers.filter(n => n.isValid && n.provider).forEach(num => {
            if (!groups[num.provider]) groups[num.provider] = [];
            groups[num.provider].push(num.msisdn);
          });
          this.providerGroups = groups;
        },
        getPackagesForProvider(provider) {
          return this.packages.filter(p => p.provider === provider);
        },
        getProviderSubtotal(provider) {
          const pkgId = this.providerPackage[provider];
          const pkg = this.packages.find(p => String(p.id) === String(pkgId));
          const count = (this.providerGroups[provider] || []).length;
          return pkg ? pkg.price * count : 0;
        },
        getProviderSellTotal(provider) {
          const pkgId = this.providerPackage[provider];
          const pkg = this.packages.find(p => String(p.id) === String(pkgId));
          const count = (this.providerGroups[provider] || []).length;
          return pkg ? pkg.sellPrice * count : 0;
        },
        getProviderProfit(provider) {
          return this.getProviderSellTotal(provider) - this.getProviderSubtotal(provider);
        },
        addIndividualItem() {
          this.individualItems.push({ id: Date.now().toString(), msisdn: '', packageId: '' });
        },
        removeIndividualItem(id) {
          if (this.individualItems.length <= 1) return;
          this.individualItems = this.individualItems.filter(i => i.id !== id);
        },
        handleConfirmOrder() {
          if (this.itemCount === 0) {
            this.showToast('Validasi Gagal', 'Silakan isi nomor dan pilih paket.');
            return;
          }
          this.showToast('Pesanan Dikonfirmasi', 'Flow submit ke backend akan diintegrasikan.');
        },
        showToast(title, message) {
          this.toastTitle = title;
          this.toastMessage = message;
          this.toastVisible = true;
          setTimeout(() => { this.toastVisible = false; }, 3000);
        },
        formatRupiah,
      };
    }
  </script>
@endsection

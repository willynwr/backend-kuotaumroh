@extends('agent.layout')

@section('title', 'Program Referral - Kuotaumroh.id')

@section('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
  <style>
    .flatpickr-calendar { border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; }
    .flatpickr-months { border-radius: 0.5rem 0.5rem 0 0; }
    .flatpickr-current-month .flatpickr-monthDropdown-months, .flatpickr-current-month .numInputWrapper { font-weight: 600; }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay { background: #10b981; border-color: #10b981; }
    .flatpickr-day.inRange { background: rgba(16, 185, 129, 0.15); border-color: transparent; }
    .flatpickr-day:hover:not(.selected):not(.startRange):not(.endRange) { background: #f1f5f9; border-color: #e2e8f0; }
    .flatpickr-day.today { border-color: #10b981; }
    .flatpickr-day.today:hover, .flatpickr-day.today:focus { background: rgba(16, 185, 129, 0.1); border-color: #10b981; }
    .flatpickr-months .flatpickr-prev-month:hover svg, .flatpickr-months .flatpickr-next-month:hover svg { fill: #10b981; }
    .flatpickr-weekdays { background: #f1f5f9; }
    .flatpickr-weekday { color: #64748b; font-weight: 500; }
  </style>
@endsection

@section('content')
  <div x-data="referralsApp()">
    <main class="container mx-auto py-10 animate-fade-in px-4">
      <div class="mb-6">
        <h1 class="text-3xl font-bold tracking-tight">Program Referral</h1>
        <p class="text-muted-foreground mt-2">Undang agen baru dan dapatkan komisi dari setiap transaksi mereka</p>
      </div>

      <div class="mb-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-lg border bg-slate-50 border-slate-200 shadow-sm lg:col-span-2">
          <div class="p-6">
            <div class="flex w-full flex-col gap-6 lg:flex-row lg:items-start">
              <div class="space-y-2 lg:shrink-0">
                <label class="text-sm font-medium">QR Code</label>
                <div class="flex items-center gap-4">
                  <div class="flex items-center gap-2">
                    <div id="qrcode" class="h-32 w-32 p-2 flex items-center justify-center rounded-lg border bg-white"></div>
                    <div class="flex flex-col gap-2">
                      <button @click="copyQR()" class="inline-flex items-center justify-center rounded-md border bg-white h-10 w-10 hover:bg-muted transition-colors" title="Copy QR Code">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                      </button>
                      <button @click="downloadQR()" class="inline-flex items-center justify-center rounded-md border bg-white h-10 w-10 hover:bg-muted transition-colors" title="Download QR Code">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="space-y-2 w-full lg:flex-1">
                <label class="text-sm font-medium">Link Referral</label>
                <div class="flex flex-col gap-2 sm:flex-row">
                  <input type="text" :value="agentData.referralLink" readonly class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm">
                  <button @click="copyToClipboard(agentData.referralLink, 'link')" class="inline-flex items-center justify-center rounded-md border bg-white h-10 w-full sm:w-10 hover:bg-muted transition-colors">
                    <svg x-show="!copied || copied !== 'link'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    <svg x-show="copied === 'link'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                  </button>
                </div>
                <button @click="handleShare()" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 text-sm font-medium hover:bg-primary/90 transition-colors w-full sm:w-auto">
                  <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                  Bagikan
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="grid gap-4 w-full lg:col-span-1">
          <div class="rounded-lg border bg-slate-50 border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 p-4">
              <div class="rounded-full bg-primary/10 p-3">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
              </div>
              <div>
                <p class="text-sm text-muted-foreground">Total Komisi</p>
                <p class="text-2xl font-bold" x-text="formatRupiah(agentData.totalCommission)"></p>
              </div>
            </div>
          </div>
          <div class="rounded-lg border bg-slate-50 border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 p-4">
              <div class="rounded-full bg-muted p-3">
                <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
              </div>
              <div>
                <p class="text-sm text-muted-foreground">Komisi Pending</p>
                <p class="text-2xl font-bold" x-text="formatRupiah(agentData.pendingCommission)"></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
              <h3 class="text-lg font-semibold">Riwayat Komisi Referral</h3>
              <p class="text-sm text-muted-foreground mt-1">Daftar transaksi referral yang menghasilkan komisi</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
              <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <input type="text" id="dateRangePicker" placeholder="Pilih Rentang Tanggal" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-8 py-2 text-sm w-full sm:w-[250px]" readonly>
                <button type="button" x-show="dateFrom || dateTo" @click="clearDateFilter()" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-muted-foreground hover:text-foreground rounded-full hover:bg-muted transition-colors">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" placeholder="Cari nomor/provider/paket/tanggal" x-model="searchQuery" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[260px]">
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nomor HP</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Provider</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Paket</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Komisi</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="row in filteredAgents" :key="row.id">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-mono" x-text="row.msisdn"></td>
                    <td class="p-4 align-middle" x-text="row.provider"></td>
                    <td class="p-4 align-middle" x-text="row.packageName"></td>
                    <td class="p-4 align-middle" x-text="formatDate(row.orderDate)"></td>
                    <td class="p-4 align-middle text-right font-medium text-primary" x-text="row.commission > 0 ? '+' + formatRupiah(row.commission) : formatRupiah(row.commission)"></td>
                  </tr>
                </template>
                <template x-if="filteredAgents.length === 0">
                  <tr><td colspan="5" class="p-8 text-center text-muted-foreground">Tidak ada data referral</td></tr>
                </template>
              </tbody>
            </table>
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
    function formatRupiah(value) {
      const n = Number(value || 0);
      return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
    }
    function formatDate(date) {
      const d = new Date(date);
      return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }
  </script>
  <script>
    function referralsApp() {
      return {
        user: { name: 'Agent Demo', email: 'agent@kuotaumroh.id', agentCode: 'AGN-2024-001', initials: 'AD' },
        agentData: { agentCode: 'AGN-2024-001', referralLink: '', totalCommission: 0, pendingCommission: 0 },
        packages: [
          { id: 1, provider: 'TELKOMSEL', name: 'Internet 12 Hari 50GB', feeAffiliate: 9400 },
          { id: 2, provider: 'INDOSAT', name: 'Spesial 12 Hari', feeAffiliate: 8400 },
          { id: 3, provider: 'XL', name: 'Internet 10 Hari 20GB', feeAffiliate: 7600 },
        ],
        referredAgents: [],
        copied: null,
        searchQuery: '',
        dateFrom: '',
        dateTo: '',
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',
        init() {
          this.agentData.agentCode = this.user.agentCode || this.agentData.agentCode;
          this.agentData.referralLink = window.location.origin + '/r/' + (this.agentData.agentCode || 'AGN-DEMO');
          this.generateQRCode();
          this.generateDemoReferralData();
          this.$nextTick(() => {
            flatpickr('#dateRangePicker', {
              mode: 'range',
              dateFormat: 'd M Y',
              locale: { rangeSeparator: ' - ' },
              onChange: (selectedDates) => {
                if (selectedDates.length === 2) {
                  this.dateFrom = selectedDates[0].toISOString().split('T')[0];
                  this.dateTo = selectedDates[1].toISOString().split('T')[0];
                } else if (selectedDates.length === 0) {
                  this.dateFrom = '';
                  this.dateTo = '';
                }
              },
              onClose: (selectedDates, _dateStr, instance) => {
                if (selectedDates.length === 1) {
                  instance.clear();
                  this.dateFrom = '';
                  this.dateTo = '';
                }
              }
            });
          });
        },
        generateDemoReferralData() {
          const demoMSISDNs = [
            { msisdn: '081234567890', provider: 'TELKOMSEL' },
            { msisdn: '085234567891', provider: 'INDOSAT' },
            { msisdn: '087834567892', provider: 'XL' },
            { msisdn: '082234567893', provider: 'TELKOMSEL' },
            { msisdn: '081299990001', provider: 'INDOSAT' },
          ];
          this.referredAgents = demoMSISDNs.map((demo, idx) => {
            const pkg = this.packages.find(p => p.provider === demo.provider) || this.packages[0];
            const daysAgo = idx * 5 + Math.floor(Math.random() * 5);
            const orderDate = new Date();
            orderDate.setDate(orderDate.getDate() - daysAgo);
            return { id: String(idx + 1), msisdn: demo.msisdn, provider: pkg.provider, packageName: pkg.name, orderDate, commission: pkg.feeAffiliate || 0 };
          });
          this.agentData.totalCommission = this.referredAgents.reduce((sum, r) => sum + r.commission, 0);
          this.agentData.pendingCommission = Math.round(this.agentData.totalCommission * 0.15);
        },
        generateQRCode() {
          const qrContainer = document.getElementById('qrcode');
          if (!qrContainer || !window.QRCode) return;
          qrContainer.innerHTML = '';
          new QRCode(qrContainer, { text: this.agentData.referralLink, width: 112, height: 112, colorDark: '#1f2937', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H });
        },
        get filteredAgents() {
          return this.referredAgents.filter(agent => {
            if (this.dateFrom && this.dateTo) {
              const start = new Date(this.dateFrom);
              const end = new Date(this.dateTo);
              const orderDate = new Date(agent.orderDate);
              if (orderDate < start || orderDate > end) return false;
            }
            if (!this.searchQuery.trim()) return true;
            const query = this.searchQuery.toLowerCase();
            const searchable = [agent.msisdn, agent.provider, agent.packageName, formatDate(agent.orderDate), formatRupiah(agent.commission)].join(' ').toLowerCase();
            return searchable.includes(query);
          });
        },
        clearDateFilter() {
          this.dateFrom = '';
          this.dateTo = '';
          const input = document.querySelector('#dateRangePicker');
          const picker = input ? input._flatpickr : null;
          if (picker) picker.clear();
          else if (input) input.value = '';
        },
        downloadQR() {
          const canvas = document.querySelector('#qrcode canvas');
          if (!canvas) {
            this.showToast('Gagal', 'QR code belum tersedia');
            return;
          }
          const url = canvas.toDataURL('image/png');
          const link = document.createElement('a');
          link.download = `qr-referral-${this.user.agentCode || 'code'}.png`;
          link.href = url;
          link.click();
          this.showToast('QR Code Disimpan', 'QR code berhasil diunduh');
        },
        async copyQR() {
          const canvas = document.querySelector('#qrcode canvas');
          if (!canvas) return;
          try {
            canvas.toBlob(async (blob) => {
              try {
                await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
                this.showToast('QR Code Disalin', 'QR code berhasil disalin ke clipboard');
              } catch (_err) {
                this.showToast('Gagal Menyalin', 'Browser tidak mendukung copy image');
              }
            });
          } catch (_err) {
            this.showToast('Gagal', 'Tidak dapat menyalin QR code');
          }
        },
        async copyToClipboard(text, type) {
          try {
            await navigator.clipboard.writeText(text);
            this.copied = type;
            this.showToast('Berhasil Disalin', type === 'code' ? 'Kode agen berhasil disalin' : 'Link referral berhasil disalin');
            setTimeout(() => { this.copied = null; }, 2000);
          } catch (_err) {
            this.showToast('Gagal Menyalin', 'Tidak dapat menyalin ke clipboard');
          }
        },
        async handleShare() {
          if (navigator.share) {
            try {
              await navigator.share({ title: 'Gabung Kuotaumroh.id', text: 'Daftar sebagai agen Kuotaumroh.id dan dapatkan komisi menarik!', url: this.agentData.referralLink });
            } catch (err) {
              if (err.name !== 'AbortError') this.copyToClipboard(this.agentData.referralLink, 'link');
            }
          } else {
            this.copyToClipboard(this.agentData.referralLink, 'link');
          }
        },
        formatRupiah,
        showToast(title, message) {
          this.toastTitle = title;
          this.toastMessage = message;
          this.toastVisible = true;
          setTimeout(() => { this.toastVisible = false; }, 3000);
        }
      };
    }
  </script>
@endsection

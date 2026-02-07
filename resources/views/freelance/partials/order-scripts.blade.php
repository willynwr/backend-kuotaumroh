<script>
// Utility Functions (Moved from order-utils.js)
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

function orderApp() {
  return {
    // Agent selection
    selectedAgentId: '',

    // Mode
    mode: 'bulk',

    // Bulk input
    bulkInput: '',
    parsedNumbers: [],
    providerGroups: {},

    // Individual input
    individualItems: [{ id: '1', msisdn: '', packageId: '', provider: null }],

    // Packages from API (loaded dynamically)
    packages: [],
    allPackages: [],
    packagesLoading: true,

    // Checkout
    activationTime: 'now',
    scheduledDate: '',
    scheduledTime: '',
    paymentMethod: 'qris',
    quickTimes: ['02:00', '04:00', '13:00', '22:00'],

    // Payment methods
    paymentMethods: [
      { id: 'qris', name: 'QRIS', description: 'Bayar dengan QRIS', icon: 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z' },
      { id: 'wallet', name: 'Saldo Dompet', description: 'Bayar dengan saldo dompet Anda', icon: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' },
      { id: 'bank', name: 'Transfer Bank', description: 'BCA, Mandiri, BNI, BRI', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
      { id: 'va', name: 'Virtual Account', description: 'Bayar via Virtual Account', icon: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' },
    ],

    // Wallet balance
    walletBalance: 3250000,
    platformFee: 0,

    // Batch info
    batchId: 'BATCH-' + Date.now(),
    batchName: `Order ${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}`,
    editBatchNameDialog: false,
    tempBatchName: '',

    // Processing
    isProcessing: false,

    // Toast
    toastVisible: false,
    toastTitle: '',
    toastMessage: '',

    // Dialogs
    invalidDialogOpen: false,
    numberListDialogOpen: false,
    packagePickerOpen: false,
    numberSelectionOpen: false,
    editingProvider: '',
    pickerProvider: '',
    tempSelectedPackage: '',
    tempEditingNumbers: [],
    tempEditingAssignments: [],
    hasUnsavedChanges: false,

    // Package Picker State
    packageSearch: '',
    selectedDurationFilter: 'all',
    durationFilters: [],
    selectedSubTypeFilter: 'all',
    subTypeFilters: [],
    pickerProviderDisplay: '',

    // Number Selection State (Step 2)
    selectedPackageForNumbers: null,
    tempSelectedNumbers: [],
    numberSearch: '',
    singleEditNumber: null,
    singleEditItemId: null,

    // Multi-package assignments: { provider: [{ packageId, numbers[] }] }
    providerPackages: {},

    // Validation
    validationError: false,

    // Computed
    get todayDate() {
      return new Date().toISOString().split('T')[0];
    },

    get validCount() {
      return this.parsedNumbers.filter(n => n.isValid && n.provider).length;
    },

    get invalidCount() {
      return this.parsedNumbers.filter(n => !n.isValid || !n.provider).length;
    },

    get itemCount() {
      if (this.mode === 'bulk') {
        return this.validCount;
      } else {
        return this.individualItems.filter(i => i.msisdn && i.packageId && i.provider).length;
      }
    },

    get subtotal() {
      if (this.mode === 'bulk') {
        let total = 0;
        Object.entries(this.providerPackages).forEach(([provider, assignments]) => {
          assignments.forEach(assignment => {
            const pkg = this.packages.find(p => p.id === assignment.packageId);
            if (pkg) {
              total += pkg.price * assignment.numbers.length;
            }
          });
        });
        return total;
      } else {
        let total = 0;
        this.individualItems.forEach(item => {
          if (item.msisdn && item.packageId && item.provider) {
            const pkg = this.packages.find(p => p.id === item.packageId);
            if (pkg) total += pkg.price;
          }
        });
        return total;
      }
    },

    get profit() {
      if (this.mode === 'bulk') {
        let profit = 0;
        Object.entries(this.providerPackages).forEach(([provider, assignments]) => {
          assignments.forEach(assignment => {
            const pkg = this.packages.find(p => p.id === assignment.packageId);
            if (pkg) {
              profit += (pkg.sellPrice - pkg.price) * assignment.numbers.length;
            }
          });
        });
        return profit;
      } else {
        let profit = 0;
        this.individualItems.forEach(item => {
          if (item.msisdn && item.packageId && item.provider) {
            const pkg = this.packages.find(p => p.id === item.packageId);
            if (pkg) profit += (pkg.sellPrice - pkg.price);
          }
        });
        return profit;
      }
    },

    get totalWithFee() {
      return this.subtotal + this.platformFee;
    },

    get invalidNumbers() {
      return this.parsedNumbers.filter(n => !n.isValid || !n.provider);
    },

    // Lifecycle
    async init() {
      // Watch for agent selection changes
      this.$watch('selectedAgentId', async (newAgentId) => {
        if (newAgentId && newAgentId.startsWith('AGT')) {
          console.log('🔄 Agent changed, reloading packages for:', newAgentId);
          await this.loadAllPackages();
        }
      });
      
      // Load packages from API when component initializes
      await this.loadAllPackages();
      
      // Clear previous completed order if exists
      const savedOrder = localStorage.getItem('pendingOrder');
      if (savedOrder) {
        try {
          const parsed = JSON.parse(savedOrder);
          if (parsed.paymentStatus === 'activated' || parsed.paymentStatus === 'expired') {
            console.log('🧹 Clearing completed/expired order from storage');
            localStorage.removeItem('pendingOrder');
          }
        } catch (e) {
          localStorage.removeItem('pendingOrder');
        }
      }
    },

    // Load all packages from API
    async loadAllPackages() {
      try {
        this.packagesLoading = true;
        
        // Get freelance link from URL
        const pathParts = window.location.pathname.split('/');
        const linkReferral = pathParts[2]; // /dash/{linkReferral}/order
        
        // Determine API URL based on selected agent
        let apiUrl;
        
        // If agent is selected (freelance choosing agent)
        if (this.selectedAgentId && this.selectedAgentId.startsWith('AGT')) {
          apiUrl = `${API_BASE_URL}/api/proxy/umroh/package?agent_id=${this.selectedAgentId}`;
          console.log('📦 Loading packages for agent:', this.selectedAgentId);
        } 
        // Otherwise use ref_code based on link (legacy for now)
        else {
          apiUrl = `${API_BASE_URL}/api/proxy/umroh/package?ref_code=${linkReferral}`;
          console.log('📦 Loading packages with ref_code:', linkReferral);
        }

        const response = await fetch(apiUrl);
        if (!response.ok) {
          throw new Error('Failed to fetch packages');
        }
        
        const data = await response.json();
        console.log('📦 API Response:', data);

        this.processPackagesData(data);
        this.packagesLoading = false;
      } catch (error) {
        console.error('Error loading packages:', error);
        this.packages = [];
        this.allPackages = [];
        this.packagesLoading = false;
      }
    },

    processPackagesData(data) {
        // Response langsung array, tidak wrapped
        if (Array.isArray(data)) {
          this.allPackages = data.map(pkg => {
            // Parse harga dengan fallback
            const priceBulk = parseInt(pkg.price_bulk) || parseInt(pkg.bulk_harga_beli) || parseInt(pkg.price) || 0;
            const priceCustomer = parseInt(pkg.price_customer) || parseInt(pkg.bulk_harga_rekomendasi) || priceBulk;
            const priceApp = parseInt(pkg.price_app) || parseInt(pkg.bulk_harga_rekomendasi) || 0;
            const profit = parseInt(pkg.bulk_potensi_profit) || parseInt(pkg.profit) || (priceCustomer - priceBulk);
            
            return {
              id: pkg.id,
              package_id: pkg.id,
              packageId: pkg.id,
              name: pkg.name,
              packageName: pkg.name,
              type: pkg.type || pkg.provider,
              provider: pkg.type || pkg.provider,
              days: parseInt(pkg.days) || parseInt(pkg.masa_aktif) || 0,
              masa_aktif: parseInt(pkg.days) || parseInt(pkg.masa_aktif) || 0,
              quota: pkg.quota || pkg.kuota_utama || '',
              kuota_utama: pkg.kuota_utama || pkg.quota || '',
              total_kuota: pkg.total_kuota || pkg.quota || '',
              kuota_bonus: pkg.kuota_bonus || pkg.bonus || '',
              bonus: pkg.bonus || pkg.kuota_bonus || '',
              telp: pkg.telp || '',
              sms: pkg.sms || '',
              price: priceBulk,
              harga: priceBulk,
              sellPrice: priceCustomer,
              displayPrice: priceCustomer,
              price_bulk: priceBulk,
              price_customer: priceCustomer,
              price_app: priceApp,
              profit: profit,
              bulk_harga_beli: priceBulk,
              bulk_harga_rekomendasi: priceCustomer,
              bulk_potensi_profit: profit,
              subType: pkg.sub_type || pkg.tipe_paket || '',
              tipe_paket: pkg.sub_type || pkg.tipe_paket || '',
              is_active: pkg.is_active,
              promo: pkg.promo || null,
            };
          });
          const uniqueMap = new Map();
          this.allPackages.forEach(pkg => {
            const key = pkg.id || pkg.package_id || `${pkg.provider}-${pkg.name}-${pkg.days}-${pkg.price}`;
            if (!uniqueMap.has(key)) uniqueMap.set(key, pkg);
          });
          this.allPackages = Array.from(uniqueMap.values());
          this.packages = this.allPackages;
          console.log('📦 Mapped packages:', this.packages.length);
        }
    },

    // Methods
    parseBulkNumbers() {
      if (!this.bulkInput.trim()) {
        this.parsedNumbers = [];
        this.providerGroups = {};
        this.providerPackages = {}; // FIX BUG #3: Reset package assignments when all numbers are cleared
        return;
      }
      
      const lines = this.bulkInput.split(/[\n,;]+/).map(l => l.trim()).filter(Boolean);
      this.parsedNumbers = lines.map(line => {
        const normalized = normalizeMsisdn(line);
        const isValid = validateMsisdn(normalized);
        const provider = isValid ? detectProvider(normalized) : null;
        return { msisdn: normalized, provider, isValid };
      });

      // Group by provider
      const groups = {};
      this.parsedNumbers.filter(n => n.isValid && n.provider).forEach(num => {
        if (!groups[num.provider]) groups[num.provider] = [];
        groups[num.provider].push(num.msisdn);
      });
      this.providerGroups = groups;
      
      // FIX BUG #2: Clean up package assignments for numbers that no longer exist
      const currentNumbers = new Set(this.parsedNumbers.map(n => n.msisdn));
      Object.keys(this.providerPackages).forEach(provider => {
        this.providerPackages[provider] = this.providerPackages[provider]
          .map(assignment => ({
            ...assignment,
            numbers: assignment.numbers.filter(num => currentNumbers.has(num))
          }))
          .filter(assignment => assignment.numbers.length > 0);
        
        // Remove provider key if no assignments left
        if (this.providerPackages[provider].length === 0) {
          delete this.providerPackages[provider];
        }
      });
      this.providerPackages = { ...this.providerPackages };
    },

    handleFileUpload(event) {
      const file = event.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (e) => {
        const content = e.target.result;
        this.bulkInput = this.bulkInput ? this.bulkInput + '\n' + content : content;
        this.parseBulkNumbers();
      };
      reader.readAsText(file);
      event.target.value = '';
    },

    getPackagesForProvider(provider) {
      if (!provider) return [];
      const normalizedProvider = this.normalizeProviderForApi(provider);
      return this.packages.filter(p => p.type === normalizedProvider);
    },

    generateDurationFilters(provider) {
      const packages = this.getPackagesForProvider(provider);
      const uniqueDays = [...new Set(packages.map(p => parseInt(p.days) || 0))].sort((a, b) => a - b);

      this.durationFilters = [
        { value: 'all', label: 'Semua' },
        ...uniqueDays.map(days => ({
          value: days.toString(),
          label: `${days} hari`
        }))
      ];
    },

    generateSubTypeFilters(provider) {
      const packages = this.getPackagesForProvider(provider);
      const uniqueSubTypes = [...new Set(packages.map(p => p.subType).filter(Boolean))].sort();

      this.subTypeFilters = [
        { value: 'all', label: 'Semua Tipe' },
        ...uniqueSubTypes.map(subType => ({
          value: subType,
          label: subType
        }))
      ];
    },

    normalizeProviderForApi(provider) {
      const map = {
        'TELKOMSEL': 'TELKOMSEL',
        'XL': 'XL',
        'INDOSAT': 'INDOSAT',
        'TRI': 'TRI',
      };
      return map[provider?.toUpperCase()] || provider?.toUpperCase();
    },

    openPackagePicker(provider) {
      this.pickerProvider = provider;
      this.pickerProviderDisplay = provider;
      this.tempSelectedPackage = '';
      this.packageSearch = '';
      this.selectedDurationFilter = 'all';
      this.selectedSubTypeFilter = 'all';
      this.generateDurationFilters(provider);
      this.generateSubTypeFilters(provider);
      this.singleEditNumber = null;
      this.singleEditItemId = null;
      this.numberSelectionOpen = false;
      this.numberListDialogOpen = false;
      this.packagePickerOpen = true;
    },

    openSingleNumberPackagePicker(provider, msisdn) {
      this.pickerProvider = provider;
      this.pickerProviderDisplay = provider;
      this.tempSelectedPackage = '';
      this.packageSearch = '';
      this.selectedDurationFilter = 'all';
      this.selectedSubTypeFilter = 'all';
      this.generateDurationFilters(provider);
      this.generateSubTypeFilters(provider);
      this.singleEditNumber = msisdn;
      this.singleEditItemId = null;
      this.numberSelectionOpen = false;
      this.packagePickerOpen = true;
    },

    openIndividualPackagePicker(item) {
      if (!item?.provider) return;
      this.pickerProvider = item.provider;
      this.pickerProviderDisplay = item.provider;
      this.tempSelectedPackage = '';
      this.packageSearch = '';
      this.selectedDurationFilter = 'all';
      this.selectedSubTypeFilter = 'all';
      this.generateDurationFilters(item.provider);
      this.generateSubTypeFilters(item.provider);
      this.singleEditNumber = null;
      this.singleEditItemId = item.id;
      this.numberSelectionOpen = false;
      this.numberListDialogOpen = false;
      this.packagePickerOpen = true;
    },

    getPickerNumbers() {
      return this.providerGroups[this.pickerProvider] || [];
    },

    getFilteredPackages() {
      let pkgs = this.getPackagesForProvider(this.pickerProvider);

      if (this.packageSearch.trim()) {
        const search = this.packageSearch.toLowerCase();
        pkgs = pkgs.filter(p => p.name.toLowerCase().includes(search));
      }

      if (this.selectedDurationFilter !== 'all') {
        const filterDays = parseInt(this.selectedDurationFilter);
        pkgs = pkgs.filter(p => {
          const packageDays = parseInt(p.days) || 0;
          return packageDays === filterDays;
        });
      }

      if (this.selectedSubTypeFilter !== 'all') {
        pkgs = pkgs.filter(p => p.subType === this.selectedSubTypeFilter);
      }

      return pkgs;
    },

    selectPackageAndOpenNumberSelection(pkg) {
      // Single-number mode: apply directly
      if (this.singleEditNumber) {
        this.updateNumberPackage(this.pickerProvider, this.singleEditNumber, pkg.id);
        this.showToast('Paket Dipilih', `${pkg.name} berhasil diterapkan`);
        this.packagePickerOpen = false;
        this.singleEditNumber = null;
        return;
      }
      if (this.singleEditItemId) {
        const target = this.individualItems.find(item => item.id === this.singleEditItemId);
        if (target) {
          target.packageId = pkg.id;
          this.showToast('Paket Dipilih', `${pkg.name} berhasil diterapkan`);
        }
        this.packagePickerOpen = false;
        this.singleEditItemId = null;
        return;
      }
      
      // Multi-number mode: open step 2
      this.selectedPackageForNumbers = pkg;
      // FIX BUG #1: Only pre-select unassigned numbers (numbers without a package)
      this.tempSelectedNumbers = this.getUnassignedNumbers(this.pickerProvider);
      this.numberSearch = '';
      this.numberSelectionOpen = true;
    },

    closeNumberSelection() {
      this.numberSelectionOpen = false;
      this.selectedPackageForNumbers = null;
      this.tempSelectedNumbers = [];
    },

    areAllNumbersSelected() {
      const numbers = this.getPickerNumbers();
      return numbers.length > 0 && numbers.every(n => this.tempSelectedNumbers.includes(n));
    },

    toggleSelectAllNumbers() {
      const numbers = this.getPickerNumbers();
      if (this.areAllNumbersSelected()) {
        this.tempSelectedNumbers = [];
      } else {
        this.tempSelectedNumbers = [...numbers];
      }
    },

    toggleNumberSelection(num) {
      const idx = this.tempSelectedNumbers.indexOf(num);
      if (idx === -1) {
        this.tempSelectedNumbers.push(num);
      } else {
        this.tempSelectedNumbers.splice(idx, 1);
      }
    },

    getFilteredPickerNumbers() {
      let numbers = this.getPickerNumbers();
      if (this.numberSearch.trim()) {
        numbers = numbers.filter(n => n.includes(this.numberSearch));
      }
      return numbers;
    },

    getNumberCurrentPackage(msisdn) {
      const assignments = this.providerPackages[this.pickerProvider] || [];
      for (const a of assignments) {
        if (a.numbers.includes(msisdn)) {
          return this.getPackageName(a.packageId);
        }
      }
      return null;
    },

    applyNumberSelection() {
      if (!this.selectedPackageForNumbers || this.tempSelectedNumbers.length === 0) return;
      
      const packageId = this.selectedPackageForNumbers.id;
      const provider = this.pickerProvider;
      
      if (!this.providerPackages[provider]) {
        this.providerPackages[provider] = [];
      }
      
      // Remove selected numbers from other assignments
      this.providerPackages[provider] = this.providerPackages[provider].map(a => ({
        ...a,
        numbers: a.numbers.filter(n => !this.tempSelectedNumbers.includes(n))
      })).filter(a => a.numbers.length > 0);
      
      // Add or update assignment for this package
      const existing = this.providerPackages[provider].find(a => a.packageId === packageId);
      if (existing) {
        existing.numbers = [...existing.numbers, ...this.tempSelectedNumbers];
      } else {
        this.providerPackages[provider].push({
          packageId: packageId,
          numbers: [...this.tempSelectedNumbers]
        });
      }
      
      this.providerPackages = { ...this.providerPackages };
      this.validationError = false;
      
      this.showToast('Paket Dipilih', `${this.tempSelectedNumbers.length} nomor akan menggunakan ${this.selectedPackageForNumbers.name}`);
      this.closeNumberSelection();
      this.packagePickerOpen = false;
    },

    getNumberPackageId(provider, msisdn) {
      const assignments = this.numberListDialogOpen ? this.tempEditingAssignments : (this.providerPackages[provider] || []);
      for (const a of assignments) {
        if (a.numbers.includes(msisdn)) {
          return a.packageId;
        }
      }
      return '';
    },

    updateNumberPackage(provider, msisdn, packageId) {
      if (this.numberListDialogOpen) {
        this.updateTempNumberPackage(provider, msisdn, packageId);
        return;
      }

      if (!this.providerPackages[provider]) {
        this.providerPackages[provider] = [];
      }
      
      // Remove from current assignment
      this.providerPackages[provider] = this.providerPackages[provider].map(a => ({
        ...a,
        numbers: a.numbers.filter(n => n !== msisdn)
      })).filter(a => a.numbers.length > 0);
      
      // Add to new assignment if packageId is provided
      if (packageId) {
        const existing = this.providerPackages[provider].find(a => a.packageId === packageId);
        if (existing) {
          existing.numbers.push(msisdn);
        } else {
          this.providerPackages[provider].push({
            packageId: packageId,
            numbers: [msisdn]
          });
        }
      }
      
      this.providerPackages = { ...this.providerPackages };
    },

    updateTempNumberPackage(provider, msisdn, packageId) {
      this.tempEditingAssignments = this.tempEditingAssignments.map(a => ({
        ...a,
        numbers: a.numbers.filter(n => n !== msisdn)
      })).filter(a => a.numbers.length > 0);
      
      if (packageId) {
        const existing = this.tempEditingAssignments.find(a => a.packageId === packageId);
        if (existing) {
          existing.numbers.push(msisdn);
        } else {
          this.tempEditingAssignments.push({
            packageId: packageId,
            numbers: [msisdn]
          });
        }
      }
      this.hasUnsavedChanges = true;
    },

    getProviderAssignments(provider) {
      return this.providerPackages[provider] || [];
    },

    getPackageName(packageId) {
      const pkg = this.packages.find(p => p.id === packageId);
      return pkg ? pkg.name : 'Unknown';
    },

    getPackageTitle(pkg) {
      const subType = pkg.subType ? pkg.subType.toUpperCase() : '';
      
      if (subType === 'INTERNET' || subType === 'INTERNET + TELP/SMS') {
        const quota = typeof pkg.quota === 'number' ? pkg.quota : parseFloat(pkg.quota || 0);
        let bonus = 0;
        if (pkg.bonus) {
          const bonusStr = pkg.bonus.toString();
          const match = bonusStr.match(/(\d+(\.\d+)?)/);
          if (match) {
            bonus = parseFloat(match[0]);
          }
        }
        return `Kuota ${Math.round((quota + bonus) * 100) / 100}GB`;
      }
      return pkg.name;
    },

    isFieldBold(pkg, field) {
      const subType = pkg.subType ? pkg.subType.toUpperCase() : '';
      
      if (subType === 'INTERNET' || subType === 'INTERNET + TELP/SMS') {
        return ['kuota', 'bonus'].includes(field);
      } else if (subType === 'TELP/SMS') {
        return ['telp', 'sms'].includes(field);
      }
      return false;
    },

    getUnassignedNumbers(provider) {
      const allNumbers = this.providerGroups[provider] || [];
      const assignedNumbers = (this.providerPackages[provider] || []).flatMap(a => a.numbers);
      return allNumbers.filter(n => !assignedNumbers.includes(n));
    },

    getProviderSubtotal(provider) {
      const assignments = this.providerPackages[provider] || [];
      let total = 0;
      assignments.forEach(a => {
        const pkg = this.packages.find(p => p.id === a.packageId);
        if (pkg) total += pkg.price * a.numbers.length;
      });
      return total;
    },

    getProviderSellTotal(provider) {
      const assignments = this.providerPackages[provider] || [];
      let total = 0;
      assignments.forEach(a => {
        const pkg = this.packages.find(p => p.id === a.packageId);
        if (pkg) total += pkg.sellPrice * a.numbers.length;
      });
      return total;
    },

    getProviderProfit(provider) {
      return this.getProviderSellTotal(provider) - this.getProviderSubtotal(provider);
    },

    detectProviderForItem(item) {
      if (!item.msisdn) return null;
      const normalized = normalizeMsisdn(item.msisdn);
      if (!validateMsisdn(normalized)) return null;
      return detectProvider(normalized);
    },

    addIndividualItem() {
      this.individualItems.push({ id: Date.now().toString(), msisdn: '', packageId: '', provider: null });
    },

    removeIndividualItem(id) {
      if (this.individualItems.length > 1) {
        this.individualItems = this.individualItems.filter(i => i.id !== id);
      }
    },

    handleConfirmOrder() {
      if (this.itemCount === 0 || this.subtotal === 0) {
        this.showToast('Validasi Gagal', 'Silakan lengkapi pesanan Anda');
        return;
      }

      // Check for unpaired numbers in bulk mode
      if (this.mode === 'bulk') {
        const providersWithUnpaired = Object.keys(this.providerGroups).filter(p => this.getUnassignedNumbers(p).length > 0);
        if (providersWithUnpaired.length > 0) {
          this.validationError = true;
          this.showToast('Validasi Gagal', `Ada ${providersWithUnpaired.length} provider dengan nomor yang belum dipilih paketnya`);
          return;
        }
      }

      // Check for unpaired in individual mode
      if (this.mode === 'individual') {
        const unpaired = this.individualItems.filter(i => i.msisdn && i.provider && !i.packageId);
        if (unpaired.length > 0) {
          this.validationError = true;
          this.showToast('Validasi Gagal', 'Ada nomor yang belum dipilih paketnya');
          return;
        }
      }

      this.validationError = false;
      this.isProcessing = true;

      // Prepare order data
      const orderItems = [];
      
      if (this.mode === 'bulk') {
        // From bulk mode with multi-package assignments
        Object.entries(this.providerPackages).forEach(([provider, assignments]) => {
          assignments.forEach(assignment => {
            const pkg = this.packages.find(p => p.id === assignment.packageId);
            if (pkg) {
              assignment.numbers.forEach(msisdn => {
                orderItems.push({
                  msisdn: msisdn,
                  packageId: pkg.id,
                  packageName: pkg.name,
                  provider: provider,
                  price: pkg.price,
                  sellPrice: pkg.sellPrice
                });
              });
            }
          });
        });
      } else {
        // From individual mode
        this.individualItems.forEach(item => {
          if (item.msisdn && item.packageId && item.provider) {
            const pkg = this.packages.find(p => p.id === item.packageId);
            if (pkg) {
              orderItems.push({
                msisdn: item.msisdn,
                packageId: pkg.id,
                packageName: pkg.name,
                provider: item.provider,
                price: pkg.price,
                sellPrice: pkg.sellPrice
              });
            }
          }
        });
      }

      // Save to localStorage
      // RESET paymentId saat membuat order baru agar tidak resume order lama
      const orderData = {
        batchId: this.batchId,
        batchName: this.batchName,
        items: orderItems,
        subtotal: this.subtotal,
        platformFee: this.platformFee,
        profit: this.profit,
        paymentMethod: this.paymentMethod,
        activationTime: this.activationTime,
        scheduledDate: this.scheduledDate,
        scheduledTime: this.scheduledTime,
        timestamp: Date.now()
      };

      try {
        localStorage.removeItem('pendingOrder');
        localStorage.setItem('pendingOrder', JSON.stringify(orderData));

        // Redirect to checkout page
        @if(isset($linkReferral))
          window.location.href = '{{ url('/dash/' . $linkReferral . '/checkout') }}';
        @else
          window.location.href = '{{ route('freelance.checkout') }}';
        @endif
      } catch (e) {
        console.error('Error saving order to localStorage:', e);
        this.showToast('Error', 'Gagal memproses pesanan (Storage Full). Silakan hapus cache browser Anda.');
        this.isProcessing = false;
        return;
      }
    },

    saveBatchName() {
      if (this.tempBatchName.trim()) {
        this.batchName = this.tempBatchName.trim();
        this.showToast('Berhasil', 'Nama batch telah diperbarui');
        this.editBatchNameDialog = false;
      }
    },

    showToast(title, message) {
      this.toastTitle = title;
      this.toastMessage = message;
      this.toastVisible = true;
      setTimeout(() => { this.toastVisible = false; }, 3000);
    },

    deleteInvalidNumber(msisdn) {
      this.bulkInput = this.bulkInput.split(/[\n,;]+/)
        .map(l => l.trim())
        .filter(l => normalizeMsisdn(l) !== msisdn)
        .join('\n');
      this.parseBulkNumbers();
    },

    deleteAllInvalidNumbers() {
      const invalidMsisdns = this.invalidNumbers.map(n => n.msisdn);
      this.bulkInput = this.bulkInput.split(/[\n,;]+/)
        .map(l => l.trim())
        .filter(l => !invalidMsisdns.includes(normalizeMsisdn(l)))
        .join('\n');
      this.parseBulkNumbers();
      this.invalidDialogOpen = false;
      this.showToast('Berhasil', `${invalidMsisdns.length} nomor tidak valid dihapus`);
    },

    openNumberListDialog(provider) {
      this.editingProvider = provider;
      this.tempEditingNumbers = [...(this.providerGroups[provider] || [])];
      const assignments = this.providerPackages[provider] || [];
      this.tempEditingAssignments = JSON.parse(JSON.stringify(assignments));
      this.hasUnsavedChanges = false;
      this.packagePickerOpen = false;
      this.numberSelectionOpen = false;
      this.numberListDialogOpen = true;
    },

    getEditingNumbers() {
      if (this.numberListDialogOpen) {
        return this.tempEditingNumbers;
      }
      return this.providerGroups[this.editingProvider] || [];
    },

    deleteNumberFromProvider(provider, msisdn) {
      if (this.numberListDialogOpen) {
        this.tempEditingNumbers = this.tempEditingNumbers.filter(n => n !== msisdn);
        this.tempEditingAssignments = this.tempEditingAssignments.map(a => ({
          ...a,
          numbers: a.numbers.filter(n => n !== msisdn)
        })).filter(a => a.numbers.length > 0);
        this.hasUnsavedChanges = true;
        return;
      }

      this.bulkInput = this.bulkInput.split(/[\n,;]+/)
        .map(l => l.trim())
        .filter(l => normalizeMsisdn(l) !== msisdn)
        .join('\n');
      this.parseBulkNumbers();
    },

    deleteProviderNumbers(provider) {
      const nums = this.providerGroups[provider] || [];
      this.bulkInput = this.bulkInput.split(/[\n,;]+/)
        .map(l => l.trim())
        .filter(l => !nums.includes(normalizeMsisdn(l)))
        .join('\n');
      delete this.providerPackages[provider];
      this.parseBulkNumbers();
      this.showToast('Berhasil', `${nums.length} nomor ${provider} dihapus`);
    },

    saveListChanges() {
      const currentProviderNumbers = this.providerGroups[this.editingProvider] || [];
      const deletedNumbers = currentProviderNumbers.filter(n => !this.tempEditingNumbers.includes(n));
      
      if (deletedNumbers.length > 0) {
        this.bulkInput = this.bulkInput.split(/[\n,;]+/)
          .map(l => l.trim())
          .filter(l => {
            const normalized = normalizeMsisdn(l);
            return !deletedNumbers.includes(normalized);
          })
          .join('\n');
        this.parseBulkNumbers();
      }
      
      this.providerPackages[this.editingProvider] = JSON.parse(JSON.stringify(this.tempEditingAssignments));
      this.providerPackages = { ...this.providerPackages };
      
      this.hasUnsavedChanges = false;
      this.showToast('Berhasil', 'Perubahan disimpan');
    },

    getTempEditingSubtotal() {
      let total = 0;
      this.tempEditingAssignments.forEach(a => {
        const pkg = this.packages.find(p => p.id === a.packageId);
        if (pkg) total += pkg.price * a.numbers.length;
      });
      return total;
    },

    formatRupiah
  };
}
</script>

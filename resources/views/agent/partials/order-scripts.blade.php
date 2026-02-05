<script>
// Use shared utils.js functions: normalizeMsisdn, validateMsisdn, detectProvider, normalizeProviderForApi, formatRupiah
// These are loaded from /shared/utils.js in layout.blade.php

// Local wrapper for MSISDN normalization (converts to 62xxx format for API)
function normalizeMsisdnFor62(input) {
  const raw = String(input || '').replace(/\s+/g, '');
  const digits = raw.replace(/\D/g, '');
  if (digits.startsWith('62')) return digits;
  if (digits.startsWith('0')) return '62' + digits.slice(1);
  return digits;
}

// Local wrapper for validation (checks 62xxx format)
function validateMsisdn62(msisdn) {
  const s = String(msisdn || '');
  return /^62\d{9,13}$/.test(s);
}

// Use detectProvider from utils.js and convert result to uppercase for API
function detectProviderForOrder(msisdn) {
  // Convert 62xxx to 0xxx for detectProvider in utils.js
  let normalized = String(msisdn || '');
  if (normalized.startsWith('62')) {
    normalized = '0' + normalized.slice(2);
  }
  const provider = detectProvider(normalized);
  return provider ? normalizeProviderForApi(provider) : null;
}

function orderApp() {
  return {
    // Mode
    mode: 'bulk',

    // Bulk input
    bulkInput: '',
    parsedNumbers: [],
    providerGroups: {},

    // Individual input
    individualItems: [{ id: '1', msisdn: '', packageId: '', provider: null }],

    // Packages from API
    packages: [],
    allPackages: [], // All packages from API
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
    ],

    // Wallet balance (dari backend)
    walletBalance: {{ $saldo ?? 0 }},
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
    showInvalidNumbersCheckout: false,
    invalidNumbersForCheckout: [],
    uploadValidationDialogOpen: false,
    uploadValidationErrors: null,
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
          console.error('Error parsing saved order:', e);
          localStorage.removeItem('pendingOrder');
        }
      }

      // Load packages from API when component initializes
      await this.loadAllPackages();
    },

    // Load all packages from API (same as store.blade.php)
    async loadAllPackages() {
      try {
        this.packagesLoading = true;
        
        // PENTING: Gunakan agent_id untuk dapat data dari VIEW v_pembelian_paket_agent_travel
        // Bukan ref_code=bulk_umroh yang fallback ke BulkPaymentService lama
        const agentId = typeof STORE_CONFIG !== 'undefined' ? STORE_CONFIG.agent_id : null;
        
        // Build API URL dengan agent_id jika tersedia
        let apiUrl;
        if (agentId && agentId.startsWith('AGT')) {
            // Gunakan PackagePricingService via agent_id
            apiUrl = `${API_BASE_URL}/api/proxy/umroh/package?agent_id=${agentId}`;
            console.log('📦 Loading packages for agent:', agentId);
            // Clear cache karena beda per agent
            sessionStorage.removeItem('cachedPackages');
        } else {
            // Fallback ke legacy ref_code
            apiUrl = `${API_BASE_URL}/api/proxy/umroh/package?ref_code=bulk_umroh`;
            console.log('📦 Loading packages with legacy ref_code');
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
        this.allPackages = [];
        this.packages = [];
        this.packagesLoading = false;
        this.showToast('Error', 'Gagal memuat data paket. Silakan refresh halaman.');
      }
    },

    processPackagesData(data) {
        // Response langsung array, tidak wrapped
        if (Array.isArray(data)) {
          this.allPackages = data.map(pkg => {
            // Parse harga dengan fallback
            const priceBulk = parseInt(pkg.price_bulk) || parseInt(pkg.bulk_harga_beli) || 0;
            const priceCustomer = parseInt(pkg.price_customer) || parseInt(pkg.bulk_harga_rekomendasi) || priceBulk;
            const priceApp = parseInt(pkg.price_app) || parseInt(pkg.bulk_harga_rekomendasi) || 0;
            const profit = parseInt(pkg.bulk_potensi_profit) || parseInt(pkg.profit) || (priceCustomer - priceBulk);
            
            return {
              id: pkg.id,
              package_id: pkg.id,
              packageId: pkg.id,
              name: pkg.name,
              packageName: pkg.name,
              type: pkg.type, // type = provider (TELKOMSEL, XL, etc)
              provider: pkg.type,
              days: parseInt(pkg.days) || 0,
              masa_aktif: parseInt(pkg.days) || 0,
              quota: pkg.quota || pkg.kuota_utama || '',
              kuota_utama: pkg.kuota_utama || pkg.quota || '',
              total_kuota: pkg.total_kuota || pkg.quota || '',
              kuota_bonus: pkg.kuota_bonus || pkg.bonus || '',
              bonus: pkg.bonus || pkg.kuota_bonus || '',
              telp: pkg.telp || '',
              sms: pkg.sms || '',
              price: priceBulk,              // Harga modal (untuk agent)
              harga: priceBulk,
              sellPrice: priceCustomer,      // Harga jual ke customer
              displayPrice: priceCustomer,
              price_bulk: priceBulk,
              price_customer: priceCustomer,
              price_app: priceApp,           // Harga Coret
              profit: profit,                // Potensi profit dari API (JANGAN hitung sendiri!)
              bulk_harga_beli: priceBulk,
              bulk_harga_rekomendasi: priceCustomer,
              bulk_potensi_profit: profit,
              subType: pkg.sub_type || '',
              tipe_paket: pkg.sub_type || '',
              is_active: pkg.is_active,
              promo: pkg.promo || null,
            };
          });
          // Set packages to allPackages for compatibility
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
        const normalized = normalizeMsisdnFor62(line);
        const isValid = validateMsisdn62(normalized);
        const provider = isValid ? detectProviderForOrder(normalized) : null;
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
      
      const fileName = file.name.toLowerCase();
      const isExcel = fileName.endsWith('.xlsx') || fileName.endsWith('.xls');
      
      if (isExcel) {
        // Handle Excel files
        if (typeof XLSX === 'undefined') {
          this.showToast('Error', 'Library Excel belum dimuat. Silakan refresh halaman.');
          return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
          try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
            
            // Extract all numbers from all cells
            const numbers = [];
            jsonData.forEach(row => {
              row.forEach(cell => {
                if (cell) {
                  const cellStr = cell.toString().trim();
                  if (cellStr) numbers.push(cellStr);
                }
              });
            });
            
            const content = numbers.join('\n');
            this.bulkInput = this.bulkInput ? this.bulkInput + '\n' + content : content;
            this.parseBulkNumbers();
            
            // Validasi setelah parsing
            this.validateUploadedNumbers(numbers.length);
          } catch (error) {
            console.error('Error reading Excel:', error);
            this.showToast('Error', 'Gagal membaca file Excel. Pastikan format file benar.');
          }
        };
        reader.readAsArrayBuffer(file);
      } else {
        // Handle text files (.txt, .csv)
        const reader = new FileReader();
        reader.onload = (e) => {
          const content = e.target.result;
          const lines = content.split(/[\n,;]+/).map(l => l.trim()).filter(Boolean);
          this.bulkInput = this.bulkInput ? this.bulkInput + '\n' + content : content;
          this.parseBulkNumbers();
          
          // Validasi setelah parsing
          this.validateUploadedNumbers(lines.length);
        };
        reader.readAsText(file);
      }
      
      event.target.value = '';
    },
    
    validateUploadedNumbers(totalUploaded) {
      // Deteksi nomor duplicate
      const seen = new Map();
      const duplicates = [];
      
      this.parsedNumbers.forEach((num, index) => {
        if (seen.has(num.msisdn)) {
          duplicates.push({
            msisdn: num.msisdn,
            positions: [seen.get(num.msisdn) + 1, index + 1]
          });
        } else {
          seen.set(num.msisdn, index);
        }
      });
      
      // Deteksi nomor tidak valid
      const invalidNumbers = this.parsedNumbers
        .filter(n => !n.isValid)
        .map((n, index) => ({
          msisdn: n.msisdn,
          position: this.parsedNumbers.indexOf(n) + 1
        }));
      
      // Tampilkan popup jika ada masalah
      if (duplicates.length > 0 || invalidNumbers.length > 0) {
        this.uploadValidationErrors = {
          duplicates: duplicates,
          invalid: invalidNumbers,
          totalUploaded: totalUploaded,
          validCount: this.validCount
        };
        this.uploadValidationDialogOpen = true;
      } else {
        // Semua nomor valid
        this.showToast('Berhasil', `${totalUploaded} nomor berhasil dimuat dan semua valid`);
      }
    },
    
    removeInvalidNumbersFromUpload() {
      if (!this.uploadValidationErrors || !this.uploadValidationErrors.invalid?.length) return;
      
      // Filter out invalid numbers from bulkInput
      const invalidMsisdns = new Set(this.uploadValidationErrors.invalid.map(item => item.msisdn));
      
      // Split and filter
      const lines = this.bulkInput.split(/[\n,;]+/).map(l => l.trim()).filter(Boolean);
      const validLines = lines.filter(line => {
        const normalized = normalizeMsisdnFor62(line);
        return !invalidMsisdns.has(normalized);
      });
      
      // Update bulkInput
      this.bulkInput = validLines.join('\n');
      this.parseBulkNumbers();
      
      // Show success message
      const removedCount = this.uploadValidationErrors.invalid.length;
      this.showToast('Berhasil', `${removedCount} nomor tidak valid telah dihapus`);
      
      // Close dialog if no more issues
      if (this.uploadValidationErrors.duplicates?.length === 0) {
        this.uploadValidationDialogOpen = false;
        this.uploadValidationErrors = null;
      } else {
        // Refresh validation
        this.validateUploadedNumbers(this.parsedNumbers.length);
      }
    },
    
    removeDuplicateNumbersFromUpload() {
      if (!this.uploadValidationErrors || !this.uploadValidationErrors.duplicates?.length) return;
      
      // Get unique numbers only (keep first occurrence)
      const seen = new Set();
      const lines = this.bulkInput.split(/[\n,;]+/).map(l => l.trim()).filter(Boolean);
      const uniqueLines = [];
      
      lines.forEach(line => {
        const normalized = normalizeMsisdnFor62(line);
        if (!seen.has(normalized)) {
          seen.add(normalized);
          uniqueLines.push(line);
        }
      });
      
      // Update bulkInput
      const duplicateCount = lines.length - uniqueLines.length;
      this.bulkInput = uniqueLines.join('\n');
      this.parseBulkNumbers();
      
      // Show success message
      this.showToast('Berhasil', `${duplicateCount} nomor duplicate telah dihapus`);
      
      // Close dialog if no more issues
      if (this.uploadValidationErrors.invalid?.length === 0) {
        this.uploadValidationDialogOpen = false;
        this.uploadValidationErrors = null;
      } else {
        // Refresh validation
        this.validateUploadedNumbers(this.parsedNumbers.length);
      }
    },
    
    removeAllProblematicNumbers() {
      if (!this.uploadValidationErrors) return;
      
      // Get all problematic msisdns
      const problematicMsisdns = new Set();
      
      // Add invalid numbers
      if (this.uploadValidationErrors.invalid?.length) {
        this.uploadValidationErrors.invalid.forEach(item => {
          problematicMsisdns.add(item.msisdn);
        });
      }
      
      // Add duplicate numbers (keep only first occurrence)
      const seen = new Set();
      const lines = this.bulkInput.split(/[\n,;]+/).map(l => l.trim()).filter(Boolean);
      const cleanLines = [];
      
      lines.forEach(line => {
        const normalized = normalizeMsisdnFor62(line);
        
        // Skip if invalid
        if (problematicMsisdns.has(normalized)) return;
        
        // Skip if duplicate
        if (seen.has(normalized)) return;
        
        seen.add(normalized);
        cleanLines.push(line);
      });
      
      // Update bulkInput
      const removedCount = lines.length - cleanLines.length;
      this.bulkInput = cleanLines.join('\n');
      this.parseBulkNumbers();
      
      // Show success message
      this.showToast('Berhasil', `${removedCount} nomor bermasalah telah dihapus`);
      
      // Close dialog
      this.uploadValidationDialogOpen = false;
      this.uploadValidationErrors = null;
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
      const p = (provider || '').toUpperCase();
      // Normalize provider names to match API response
      // API menggunakan: TELKOMSEL, INDOSAT, XL, TRI, AXIS, SMARTFREN, BYU
      if (p === 'SIMPATI' || p === 'TSEL') return 'TELKOMSEL';
      if (p === 'IM3' || p === 'ISAT') return 'INDOSAT';
      if (p === '3' || p === 'THREE') return 'TRI';
      if (p === 'SF') return 'SMARTFREN';
      return p;
    },

    getPackagesForProvider(provider) {
      if (!provider) return [];
      const targetProvider = this.normalizeProviderForApi(provider);
      return this.packages.filter(pkg => {
        const pkgProvider = (pkg.type || pkg.provider || '').toUpperCase();
        return pkgProvider === targetProvider;
      });
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
      return pkg ? (pkg.name || pkg.packageName || 'Unknown') : 'Unknown';
    },

    getPackageTitle(pkg) {
      const subType = (pkg.subType || pkg.sub_type || '').toUpperCase();
      const days = pkg.days || pkg.masa_aktif;
      const daysSuffix = days ? ` - ${days} Hari` : '';
      
      // Untuk paket INTERNET atau INTERNET + TELP/SMS
      if (subType.includes('INTERNET')) {
        // Hitung total kuota (quota + bonus)
        const quotaStr = String(pkg.quota || '');
        const bonusStr = String(pkg.bonus || '');
        
        const quotaMatch = quotaStr.match(/(\d+(?:\.\d+)?)\s*GB/i);
        const bonusMatch = bonusStr.match(/(\d+(?:\.\d+)?)\s*GB/i);
        
        let totalGB = 0;
        if (quotaMatch) totalGB += parseFloat(quotaMatch[1]);
        if (bonusMatch) totalGB += parseFloat(bonusMatch[1]);
        
        // Jika quota adalah angka langsung (bukan string GB)
        if (totalGB === 0 && quotaStr) {
          const numQuota = parseFloat(quotaStr);
          if (!isNaN(numQuota)) totalGB = numQuota;
        }
        
        if (totalGB > 0) {
          return `Kuota ${totalGB}GB${daysSuffix}`;
        }
        return pkg.quota ? `Kuota ${pkg.quota}${daysSuffix}` : `Paket Internet${daysSuffix}`;
      }
      
      // Untuk paket TELP/SMS
      if (subType.includes('TELP') || subType.includes('SMS')) {
        const telpStr = pkg.telp ? `Telp ${pkg.telp}` : '';
        const smsStr = pkg.sms ? `SMS ${pkg.sms}` : '';
        
        if (telpStr && smsStr) {
          return `${telpStr} & ${smsStr}${daysSuffix}`;
        } else if (telpStr) {
          return `${telpStr}${daysSuffix}`;
        } else if (smsStr) {
          return `${smsStr}${daysSuffix}`;
        }
      }
      
      // Fallback ke nama asli
      if (pkg.name) return pkg.name;
      if (pkg.packageName) return pkg.packageName;
      
      const parts = [];
      if (pkg.quota) parts.push(pkg.quota);
      if (days) parts.push(`${days} Hari`);
      return parts.join(' - ') || 'Paket';
    },

    getQuotaDisplay(pkg) {
      const quotaStr = String(pkg.quota || '');
      
      // Extract numbers from quota
      const extractNumber = (str) => {
        const match = str.match(/(\d+(?:\.\d+)?)/);
        return match ? parseFloat(match[1]) : 0;
      };
      
      const quotaNum = extractNumber(quotaStr);
      
      if (quotaNum === 0) return '';
      
      // Format: "49 GB Kuota Arab" if quota exists
      return `${quotaNum} GB Kuota Arab`;
    },

    getBonusDisplay(pkg) {
      const bonusStr = String(pkg.bonus || '');
      
      // Extract numbers from bonus
      const extractNumber = (str) => {
        const match = str.match(/(\d+(?:\.\d+)?)/);
        return match ? parseFloat(match[1]) : 0;
      };
      
      const bonusNum = extractNumber(bonusStr);
      
      if (bonusNum === 0) return '';
      
      // Format: "1 GB Kuota Transit"
      return `${bonusNum} GB Kuota Transit`;
    },

    isFieldBold(pkg, field) {
      const subtype = (pkg.subType || '').toUpperCase();
      if (field === 'kuota') return subtype.includes('INTERNET') || !subtype;
      if (field === 'telp') return subtype.includes('TELP') || subtype.includes('VOICE');
      if (field === 'sms') return subtype.includes('SMS');
      if (field === 'bonus') return true;
      if (field === 'hari') return true;
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
      const normalized = normalizeMsisdnFor62(item.msisdn);
      if (!validateMsisdn62(normalized)) return null;
      return detectProviderForOrder(normalized);
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
      
      // Validasi saldo dompet jika metode pembayaran adalah wallet
      if (this.paymentMethod === 'wallet' && this.walletBalance < this.totalWithFee) {
        this.showToast('Saldo Tidak Cukup', `Saldo Anda: ${this.formatRupiah(this.walletBalance)}. Dibutuhkan: ${this.formatRupiah(this.totalWithFee)}`);
        return;
      }

      this.validationError = false;
      this.isProcessing = true;
      
      // Validate all phone numbers and prepare order items
      const invalidNumbers = [];
      const orderItems = [];

      // Format schedule date if scheduled
      let scheduleDate = null;
      if (this.activationTime === 'scheduled' && this.scheduledDate) {
        const date = new Date(this.scheduledDate);
        if (this.scheduledTime) {
          const [hours, minutes] = this.scheduledTime.split(':');
          date.setHours(parseInt(hours), parseInt(minutes), 0, 0);
        }
        scheduleDate = date.toISOString().slice(0, 16); // Format: yyyy-mm-ddThh:mm
      }

      // Build order items with validation
      if (this.mode === 'bulk') {
        console.log('🔍 Validating bulk numbers...');
        Object.entries(this.providerPackages).forEach(([provider, assignments]) => {
          assignments.forEach(assignment => {
            const pkg = this.packages.find(p => p.id === assignment.packageId);
            if (pkg) {
              assignment.numbers.forEach(msisdn => {
                console.log('Checking number:', msisdn);
                const validation = validateMsisdn(msisdn);
                console.log('Validation result:', validation);
                if (!validation.isValid) {
                  console.log('❌ Invalid number found:', msisdn, validation.reason);
                  invalidNumbers.push({
                    number: msisdn,
                    reason: validation.reason
                  });
                } else {
                  orderItems.push({
                    msisdn: msisdn,
                    provider: provider,
                    package_id: pkg.id,
                    packageId: pkg.id,
                    packageName: pkg.name || pkg.packageName,
                    nama_paket: pkg.name,
                    tipe_paket: pkg.tipe_paket || pkg.subType,
                    masa_aktif: pkg.masa_aktif || pkg.days,
                    days: pkg.days || pkg.masa_aktif,
                    total_kuota: pkg.total_kuota || pkg.quota,
                    price: pkg.price,
                    harga: pkg.price,
                    sellPrice: pkg.sellPrice
                  });
                }
              });
            }
          });
        });
      } else {
        this.individualItems.forEach(item => {
          if (item.msisdn && item.packageId && item.provider) {
            const validation = validateMsisdn(item.msisdn);
            if (!validation.isValid) {
              invalidNumbers.push({
                number: item.msisdn,
                reason: validation.reason
              });
            } else {
              const pkg = this.packages.find(p => p.id === item.packageId);
              if (pkg) {
                orderItems.push({
                  msisdn: item.msisdn,
                  provider: item.provider,
                  package_id: pkg.id,
                  packageId: pkg.id,
                  packageName: pkg.name || pkg.packageName,
                  nama_paket: pkg.name,
                  tipe_paket: pkg.tipe_paket || pkg.subType,
                  masa_aktif: pkg.masa_aktif || pkg.days,
                  days: pkg.days || pkg.masa_aktif,
                  total_kuota: pkg.total_kuota || pkg.quota,
                  price: pkg.price,
                  harga: pkg.price,
                  sellPrice: pkg.sellPrice
                });
              }
            }
          }
        });
      }
      
      // Show error modal if there are invalid numbers
      if (invalidNumbers.length > 0) {
        console.log('❌ Found', invalidNumbers.length, 'invalid numbers, showing modal');
        console.log('Invalid numbers:', invalidNumbers);
        this.invalidNumbersForCheckout = invalidNumbers;
        this.showInvalidNumbersCheckout = true;
        this.isProcessing = false;
        return;
      }
      
      console.log('✅ All numbers are valid, proceeding with', orderItems.length, 'items');
      
      // If no valid items after validation
      if (orderItems.length === 0) {
        this.showToast('Error', 'Tidak ada nomor valid untuk diproses');
        this.isProcessing = false;
        return;
      }

      // Prepare order items (format sama dengan store)
      // OrderItems sudah dibuat di bagian validasi di atas
      
      // Save to localStorage (format sama dengan store)
      // RESET paymentId saat membuat order baru agar tidak resume order lama
      const orderData = {
        batchId: this.batchId,
        batchName: this.batchName,
        items: orderItems,
        subtotal: this.subtotal,
        platformFee: this.platformFee,
        profit: this.profit,
        total: this.totalWithFee,
        paymentMethod: this.paymentMethod,
        activationTime: this.activationTime,
        scheduleDate: scheduleDate,
        scheduledDate: this.scheduledDate,
        scheduledTime: this.scheduledTime,
        refCode: '{{ $agent->id ?? 1 }}',
        linkReferal: '{{ $agent->link_referal ?? "kuotaumroh" }}',
        mode: 'agent',
        isBulk: true,
        createdAt: new Date().toISOString(),
        timestamp: Date.now()
      };
      
      // Add price to each item for backend processing
      orderData.items = orderData.items.map(item => ({
        ...item,
        price: item.price || item.harga || 0 // Ensure price is included
      }));

      try {
        localStorage.removeItem('pendingOrder');
        localStorage.setItem('pendingOrder', JSON.stringify(orderData));

        // Redirect to checkout page
        @if(isset($linkReferral))
          window.location.href = '{{ url('/dash/' . $linkReferral . '/checkout') }}';
        @else
          // Fallback: use link_referal from STORE_CONFIG
          const linkReferal = STORE_CONFIG.link_referal || 'kuotaumroh';
          window.location.href = `/dash/${linkReferal}/checkout`;
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

@extends('agent.layout')

@section('title', 'Checkout - Kuotaumroh.id')

@section('content')
  <div x-data="checkoutApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      
      <!-- Success State -->
      <div x-show="paymentStatus === 'success'" x-cloak class="flex items-center justify-center min-h-[60vh]">
        <div class="max-w-md w-full rounded-lg border bg-white shadow-sm">
          <div class="p-6 text-center space-y-4">
            <div class="flex justify-center">
              <div class="rounded-full bg-green-500/10 p-4">
                <svg class="h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <h2 class="text-2xl font-bold">Pembayaran Berhasil!</h2>
            <p class="text-muted-foreground">
              Pesanan Anda sedang diproses. Paket akan segera diaktifkan.
            </p>
            <div class="pt-4 space-y-2">
            <button @click="window.location.href = '{{ isset($linkReferral) ? url('/dash/' . $linkReferral) : route('agent.dashboard') }}'"
              class="w-full inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors">
              Kembali ke Dashboard
            </button>
            <button @click="window.location.href = '{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/history') : route('agent.orders') }}'"
                class="w-full inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors">
                Lihat Riwayat Pesanan
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Expired State -->
      <div x-show="paymentStatus === 'expired'" x-cloak class="flex items-center justify-center min-h-[60vh]">
        <div class="max-w-md w-full rounded-lg border bg-white shadow-sm">
          <div class="p-6 text-center space-y-4">
            <div class="flex justify-center">
              <div class="rounded-full bg-destructive/10 p-4">
                <svg class="h-16 w-16 text-destructive" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
            <h2 class="text-2xl font-bold">Pembayaran Kedaluwarsa</h2>
            <p class="text-muted-foreground">
              Waktu pembayaran telah habis. Silakan buat pesanan baru untuk melanjutkan.
            </p>
            <div class="pt-4">
              <button @click="window.location.href = '{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/order') : route('agent.order') }}'"
                class="w-full inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground h-10 px-4 py-2 hover:bg-primary/90 transition-colors">
                Buat Pesanan Baru
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending State (Payment Page) -->
      <div x-show="paymentStatus === 'pending'" x-cloak>
        <!-- Page Header -->
        <div class="mb-6">
          <div class="flex items-start gap-4">
            <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/order') : route('agent.order') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <div>
              <h1 class="text-3xl font-bold tracking-tight">Pembayaran</h1>
              <p class="text-muted-foreground mt-2">Selesaikan pembayaran Anda</p>
            </div>
          </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
          <!-- QR Code & Payment Info -->
          <div class="lg:col-span-1 space-y-4">
            <!-- QR Card -->
            <div class="rounded-lg border bg-white shadow-sm">
              <div class="p-6 border-b">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                  </svg>
                  Scan QR Code
                </h3>
              </div>
              <div class="p-6 space-y-4">
                <!-- QR Code Container -->
                <div class="flex justify-center">
                  <div class="bg-white p-4 rounded-lg border-2 border-border">
                    <!-- QR Code dari QRIS String (prioritas) -->
                    <div x-show="qrisString || qrisStaticString" id="qrContainer" class="w-48 h-48 flex items-center justify-center"></div>
                    
                    <!-- Fallback: QR Code dari URL -->
                    <template x-if="qrCodeUrl && !qrisString && !qrisStaticString">
                      <img :src="qrCodeUrl" alt="QR Code" class="w-48 h-48 object-contain">
                    </template>
                    
                    <!-- Loading State -->
                    <template x-if="!qrCodeUrl && !qrisString && !qrisStaticString">
                      <div class="w-48 h-48 bg-muted flex flex-col items-center justify-center gap-2">
                        <svg class="animate-spin h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm text-muted-foreground">Memuat QRIS...</span>
                      </div>
                    </template>
                  </div>
                </div>

                <!-- QRIS Type Toggle -->
                <div x-show="qrisStaticString" class="flex items-center justify-center gap-3">
                  <span class="text-sm" :class="!useStaticQris ? 'font-medium' : 'text-muted-foreground'">Dynamic</span>
                  <button @click="useStaticQris = !useStaticQris"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                    :class="useStaticQris ? 'bg-primary' : 'bg-muted'">
                    <span :class="useStaticQris ? 'translate-x-6' : 'translate-x-1'"
                      class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                  </button>
                  <span class="text-sm" :class="useStaticQris ? 'font-medium' : 'text-muted-foreground'">Static</span>
                </div>
                <p x-show="qrisStaticString" class="text-xs text-center text-muted-foreground">
                  Gunakan Static jika Dynamic tidak bisa di-scan
                </p>

                <!-- Amount -->
                <div class="space-y-2">
                  <p class="text-sm text-muted-foreground text-center">Total Pembayaran</p>
                  <div class="flex items-center justify-center gap-2">
                    <p class="text-2xl font-bold text-center" x-text="formatRupiah(totalAmount)"></p>
                    <button @click="handleCopyAmount()" class="h-8 w-8 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                      </svg>
                    </button>
                  </div>
                  <!-- Payment Unique Info -->
                  <p x-show="orderData.paymentUnique > 0" class="text-xs text-center text-muted-foreground">
                    (Termasuk kode unik: <span x-text="formatRupiah(orderData.paymentUnique)"></span>)
                  </p>
                </div>

                <!-- Timer -->
                <div class="bg-muted rounded-lg p-4 text-center space-y-2">
                  <div class="flex items-center justify-center gap-2 text-muted-foreground">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Sisa Waktu</span>
                  </div>
                  <p class="text-3xl font-bold font-mono" x-text="formattedTime"></p>
                </div>

                <!-- Payment Method Badge -->
                <div class="flex justify-center">
                  <span class="inline-flex items-center rounded-full bg-secondary px-3 py-1 text-sm font-medium text-secondary-foreground" x-text="paymentMethodLabel"></span>
                </div>

                <!-- Check Payment Button -->
                <button @click="handleCheckPayment()" class="w-full inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors">
                  Cek Status Pembayaran
                </button>
              </div>
            </div>

            <!-- Instructions Card -->
            <div class="rounded-lg border bg-white shadow-sm">
              <div class="p-6 border-b">
                <h3 class="text-base font-semibold">Cara Pembayaran</h3>
              </div>
              <div class="p-6">
                <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                  <li>Buka aplikasi mobile banking atau e-wallet Anda</li>
                  <li>Pilih menu Scan QR / QRIS</li>
                  <li>Arahkan kamera ke QR code di atas</li>
                  <li>Periksa nominal pembayaran</li>
                  <li>Konfirmasi pembayaran</li>
                </ol>
              </div>
            </div>
          </div>

          <!-- Order Details -->
          <div class="lg:col-span-2">
            <div class="rounded-lg border bg-white shadow-sm">
              <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Detail Pesanan</h3>
              </div>
              <div class="p-6">
                <div class="relative overflow-x-auto">
                  <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-muted/50">
                      <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nomor HP</th>
                        <th class="px-4 py-3">Paket</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template x-for="(item, index) in orderData.items" :key="index">
                        <tr class="border-b">
                          <td class="px-4 py-3 text-muted-foreground" x-text="index + 1"></td>
                          <td class="px-4 py-3 font-mono" x-text="item.msisdn"></td>
                          <td class="px-4 py-3" x-text="item.packageName"></td>
                          <td class="px-4 py-3 text-right font-medium" x-text="formatRupiah(item.price)"></td>
                        </tr>
                      </template>

                      <!-- Subtotal -->
                      <tr class="border-b">
                        <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal</td>
                        <td class="px-4 py-3 text-right font-medium" x-text="formatRupiah(orderData.total)"></td>
                      </tr>

                      <!-- Platform Fee -->
                      <tr class="border-b">
                        <td colspan="3" class="px-4 py-3 text-right text-muted-foreground">Biaya Platform</td>
                        <td class="px-4 py-3 text-right text-muted-foreground" x-text="formatRupiah(orderData.platformFee)"></td>
                      </tr>

                      <!-- Total -->
                      <tr class="border-t-2">
                        <td colspan="3" class="px-4 py-3 text-right font-bold text-lg">Total Pembayaran</td>
                        <td class="px-4 py-3 text-right font-bold text-lg" x-text="formatRupiah(totalAmount)"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>

    <!-- Toast Notification -->
    <div x-show="toastVisible" x-transition class="toast">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function checkoutApp() {
      return {
        // Payment state
        paymentStatus: 'pending', // 'pending', 'success', 'expired'
        timeRemaining: 15 * 60, // 15 minutes in seconds

        // Order data from localStorage
        orderData: {
          items: [],
          total: 0,
          platformFee: 0,
          paymentMethod: 'qris',
          refCode: null,
        },

        // Payment transaction data
        paymentId: null,
        batchId: null,
        qrCodeUrl: null,
        qrisString: null,      // QRIS string for dynamic QR
        qrisStaticString: null, // QRIS static string
        useStaticQris: false,   // Toggle untuk pakai QRIS static
        paymentAmount: 0,       // Total pembayaran dari API

        // Toast
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',

        // Timer interval
        timerInterval: null,
        paymentCheckInterval: null,

        // Lifecycle
        async init() {
          // Watch for QRIS type toggle
          this.$watch('useStaticQris', (value) => {
            console.log('🔄 Switched to', value ? 'STATIC' : 'DYNAMIC', 'QRIS');
            this.generateQRCode();
          });
          
          // Load order data from localStorage
          const savedOrderData = localStorage.getItem('pendingOrder');
          if (!savedOrderData) {
            // Redirect back if no order data
            window.location.href = '{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/order') : route('agent.order') }}';
            return;
          }

          const parsedData = JSON.parse(savedOrderData);

          // Map the data structure to match payment page expectations (sama dengan checkout.blade.php)
          this.orderData = {
            items: parsedData.items || [],
            total: parsedData.subtotal || parsedData.total || 0,
            platformFee: parsedData.platformFee || 0,
            paymentMethod: parsedData.paymentMethod || 'qris',
            refCode: parsedData.refCode || '{{ $agent->id ?? 1 }}',
            scheduleDate: parsedData.scheduleDate || null,
            isBulk: true,  // Agent always uses bulk payment
          };
          
          console.log('📦 Agent Order Data:', this.orderData);
          console.log('📦 Order mode: BULK (Agent)');

          // Create payment transaction via API
          await this.createPayment();

          // Start countdown timer
          this.startTimer();

          // Start periodic payment status check (every 5 seconds)
          this.startPaymentPolling();
        },

        // Computed: Total amount - prioritize API payment_amount
        get totalAmount() {
          if (this.paymentAmount > 0) {
            return this.paymentAmount;
          }
          return this.orderData.total + this.orderData.platformFee;
        },

        // Computed: Formatted time
        get formattedTime() {
          const minutes = Math.floor(this.timeRemaining / 60);
          const seconds = this.timeRemaining % 60;
          return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        },

        // Computed: Payment method label
        get paymentMethodLabel() {
          return this.orderData.paymentMethod === 'qris' ? 'QRIS' : this.orderData.paymentMethod.toUpperCase();
        },

        // Generate QR Code from QRIS string (sama dengan checkout.blade.php)
        qrCodeInstance: null,
        
        generateQRCode() {
          const qrisData = this.useStaticQris ? this.qrisStaticString : this.qrisString;
          if (!qrisData) {
            console.log('⚠️ No QRIS data available');
            return;
          }
          
          const container = document.getElementById('qrContainer');
          if (!container) {
            console.log('⚠️ QR Container not found');
            return;
          }
          
          console.log('🎨 Generating QR Code...', this.useStaticQris ? 'STATIC' : 'DYNAMIC');
          
          // Clear previous QR code
          container.innerHTML = '';
          
          try {
            // qrcodejs library uses constructor
            if (typeof QRCode !== 'undefined') {
              this.qrCodeInstance = new QRCode(container, {
                text: qrisData,
                width: 192,
                height: 192,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
              });
              console.log('✅ QR Code generated successfully');
            }
          } catch (error) {
            console.error('❌ QR Code generation error:', error);
          }
        },

        // Fetch QRIS data from payment endpoint
        async fetchQrisData() {
          if (!this.paymentId) return;
          
          try {
            console.log('📥 Fetching QRIS data for payment:', this.paymentId);
            const response = await getPaymentStatus(this.paymentId);
            
            // Response is array, get first item
            const data = Array.isArray(response) ? response[0] : response;
            
            console.log('📦 Payment data:', data);
            
            if (data && data.qris) {
              this.qrisString = data.qris;
              this.qrisStaticString = data.qris_static || null;
              console.log('✅ QRIS data received');
              
              // Generate QR code
              this.$nextTick(() => {
                this.generateQRCode();
              });
            }
            
            // Update payment amount if available
            if (data && data.payment_amount) {
              this.paymentAmount = parseInt(data.payment_amount) || 0;
              this.orderData.paymentUnique = parseInt(data.payment_unique) || 0;
              console.log('💰 Payment amount:', this.paymentAmount);
            }
            
            // Update time remaining
            if (data && data.payment_expired) {
              const expiredDate = new Date(data.payment_expired);
              const now = new Date();
              const remainingMs = expiredDate - now;
              this.timeRemaining = Math.max(0, Math.floor(remainingMs / 1000));
              console.log('⏰ Time remaining:', this.timeRemaining, 'seconds');
            }
          } catch (error) {
            console.error('❌ Error fetching QRIS:', error);
          }
        },

        startTimer() {
          this.timerInterval = setInterval(() => {
            if (this.timeRemaining <= 1) {
              clearInterval(this.timerInterval);
              clearInterval(this.paymentCheckInterval);
              this.paymentStatus = 'expired';
              this.timeRemaining = 0;
            } else {
              this.timeRemaining--;
            }
          }, 1000);
        },

        async createPayment() {
          try {
            console.log('💳 Creating BULK payment transaction for Agent...');
            
            const batchId = 'BATCH_' + Date.now();
            const batchName = 'ORDER_' + new Date().toISOString().slice(0,10).replace(/-/g,'');
            
            // Extract msisdn and package_id arrays from items
            const msisdnList = this.orderData.items.map(item => {
              let msisdn = item.msisdn || item.phoneNumber;
              if (msisdn.startsWith('08')) {
                msisdn = '62' + msisdn.substring(1);
              } else if (msisdn.startsWith('8')) {
                msisdn = '62' + msisdn;
              }
              return msisdn;
            });
            
            const packageIdList = this.orderData.items.map(item => {
              return item.packageId || item.package_id;
            });

            let detail = null;
            if (this.orderData.scheduleDate) {
              detail = `{date: ${this.orderData.scheduleDate}}`;
            }

            const requestData = {
              batch_id: batchId,
              batch_name: batchName,
              payment_method: 'QRIS',
              detail: detail,
              ref_code: this.orderData.refCode || '{{ $agent->id ?? 1 }}',
              msisdn: msisdnList,          // Array
              package_id: packageIdList,   // Array
            };

            console.log('📤 Sending BULK payment request:', requestData);
            const response = await createBulkPayment(requestData);
            
            console.log('📥 Payment response:', response);

            // Handle both response formats
            const data = response.data || response;
            const isSuccess = response.success === true || (data && data.id);
            
            if (isSuccess && data) {
              this.paymentId = data.payment_id || data.id;
              this.batchId = data.batch_id || data.location_id;
              
              console.log('🔍 Checking QRIS data:', {
                has_qris: !!data.qris,
                qris: data.qris,
                qr_code_url: data.qris?.qr_code_url
              });
              
              // Set QR code URL
              if (data.qris && data.qris.qr_code_url) {
                this.qrCodeUrl = data.qris.qr_code_url;
                console.log('✅ QR Code URL set:', this.qrCodeUrl);
              } else if (data.qr_code_url) {
                this.qrCodeUrl = data.qr_code_url;
                console.log('✅ QR Code URL set (fallback):', this.qrCodeUrl);
              }
              
              // Update time remaining from server
              if (data.remaining_time) {
                this.timeRemaining = data.remaining_time;
              } else if (data.payment_expired) {
                const expiredDate = new Date(data.payment_expired);
                const now = new Date();
                const remainingMs = expiredDate - now;
                this.timeRemaining = Math.max(0, Math.floor(remainingMs / 1000));
                console.log('⏰ Calculated remaining time:', this.timeRemaining, 'seconds');
              }

              // Update total from server
              if (data.total_pembayaran) {
                this.orderData.total = data.sub_total || this.orderData.total;
                this.orderData.platformFee = data.platform_fee || 0;
                this.orderData.paymentUnique = data.payment_unique || 0;
              } else if (data.payment_amount) {
                const paymentAmount = parseInt(data.payment_amount) || 0;
                const paymentUnique = parseInt(data.payment_unique) || 0;
                this.paymentAmount = paymentAmount;
                this.orderData.paymentUnique = paymentUnique;
                console.log('💰 Payment amount:', paymentAmount, 'Unique:', paymentUnique);
              }

              console.log('✅ Payment created:', this.paymentId);
              
              // Fetch QRIS data after payment created
              await this.fetchQrisData();
            } else {
              throw new Error(response.message || 'Gagal membuat transaksi');
            }
          } catch (error) {
            console.error('❌ Failed to create payment:', error);
            this.showToast('Error', error.message || 'Gagal membuat transaksi pembayaran');
          }
        },

        startPaymentPolling() {
          if (!this.paymentId) return;

          // Check payment status every 5 seconds
          this.paymentCheckInterval = setInterval(async () => {
            try {
              const response = await getPaymentStatus(this.paymentId);
              
              // Response is array, get first item
              const rawData = Array.isArray(response) ? response[0] : (response.data || response);
              const data = rawData;

              if (data && data.id) {
                // Update QRIS strings if available
                if (data.qris && !this.qrisString) {
                  this.qrisString = data.qris;
                  this.qrisStaticString = data.qris_static || null;
                  console.log('✅ QRIS data updated from polling');
                  
                  // Generate QR code
                  this.$nextTick(() => {
                    this.generateQRCode();
                  });
                }

                // Check payment status
                const status = data.status_payment || data.status;
                if (status === 'SUCCESS' || status === 'PAID') {
                  this.paymentStatus = 'success';
                  clearInterval(this.paymentCheckInterval);
                  clearInterval(this.timerInterval);
                  localStorage.removeItem('pendingOrder');
                  this.showToast('Pembayaran Berhasil', 'Pembayaran telah dikonfirmasi');
                } else if (status === 'EXPIRED' || status === 'FAILED') {
                  this.paymentStatus = 'expired';
                  clearInterval(this.paymentCheckInterval);
                  clearInterval(this.timerInterval);
                }
              }
            } catch (error) {
              console.error('Failed to check payment status:', error);
            }
          }, 5000); // Check every 5 seconds
        },

        handleCopyAmount() {
          navigator.clipboard.writeText(this.totalAmount.toString());
          this.showToast('Berhasil disalin', 'Nominal pembayaran telah disalin ke clipboard');
        },

        async handleCheckPayment() {
          if (!this.paymentId) {
            this.showToast('Error', 'Payment ID tidak ditemukan');
            return;
          }

          this.showToast('Memeriksa pembayaran', 'Mohon tunggu, kami sedang memeriksa status pembayaran Anda...');

          try {
            const response = await getPaymentStatus(this.paymentId);
            
            // Response is array, get first item
            const rawData = Array.isArray(response) ? response[0] : (response.data || response);
            const data = rawData;
            const status = data?.status_payment || data?.status;

            if (status === 'SUCCESS' || status === 'PAID') {
              this.paymentStatus = 'success';
              clearInterval(this.timerInterval);
              if (this.paymentCheckInterval) {
                clearInterval(this.paymentCheckInterval);
              }
              localStorage.removeItem('pendingOrder');
              this.showToast('Pembayaran Berhasil', 'Pembayaran Anda telah dikonfirmasi');
            } else if (status === 'PENDING' || status === 'pending') {
              this.showToast('Pembayaran Pending', 'Pembayaran belum diterima, mohon selesaikan pembayaran');
            } else {
              this.showToast('Pembayaran Gagal', 'Status: ' + (status || 'unknown'));
            }
          } catch (error) {
            console.error('Failed to check payment:', error);
            this.showToast('Error', 'Gagal memeriksa status pembayaran');
          }
        },

        showToast(title, message) {
          this.toastTitle = title;
          this.toastMessage = message;
          this.toastVisible = true;
          setTimeout(() => {
            this.toastVisible = false;
          }, 3000);
        }
      }
    }
  </script>
@endpush

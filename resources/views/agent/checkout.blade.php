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
                <!-- QR Code -->
                <div class="flex justify-center">
                  <div class="bg-white p-4 rounded-lg border-2 border-border">
                    <template x-if="qrCodeUrl">
                      <img :src="qrCodeUrl" alt="QR Code" class="w-48 h-48 object-contain">
                    </template>
                    <template x-if="!qrCodeUrl">
                      <div class="w-48 h-48 bg-muted flex items-center justify-center">
                        <svg class="h-32 w-32 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                      </div>
                    </template>
                  </div>
                </div>

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
          paymentMethod: 'qris'
        },

        // Payment transaction data
        paymentId: null,
        qrCodeUrl: null,

        // Toast
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',

        // Timer interval
        timerInterval: null,
        paymentCheckInterval: null,

        // Lifecycle
        async init() {
          // Load order data from localStorage
          const savedOrderData = localStorage.getItem('pendingOrder');
          if (!savedOrderData) {
            // Redirect back if no order data
            window.location.href = '{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/order') : route('agent.order') }}';
            return;
          }

          const parsedData = JSON.parse(savedOrderData);

          // Map the data structure to match payment page expectations
          this.orderData = {
            items: parsedData.items || [],
            total: parsedData.subtotal || 0,
            platformFee: parsedData.platformFee || 0,
            paymentMethod: parsedData.paymentMethod || 'qris'
          };

          // Create payment transaction and get QR code
          await this.createPayment();

          // Start countdown timer
          this.startTimer();

          // Start periodic payment status check (every 5 seconds)
          this.startPaymentPolling();
        },

        // Computed: Total amount
        get totalAmount() {
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
            console.log('💳 Creating payment transaction...');
            const response = await createPaymentTransaction({
              batch_id: this.orderData.batchId || 'BATCH-' + Date.now(),
              amount: this.totalAmount,
              payment_method: this.orderData.paymentMethod
            });

            if (response.success) {
              this.paymentId = response.payment_id;
              this.qrCodeUrl = response.qr_code_url;
              console.log('✅ Payment created:', response.payment_id);
            }
          } catch (error) {
            console.error('❌ Failed to create payment:', error);
            this.showToast('Error', 'Gagal membuat transaksi pembayaran');
          }
        },

        startPaymentPolling() {
          if (!this.paymentId) return;

          // Check payment status every 5 seconds
          this.paymentCheckInterval = setInterval(async () => {
            try {
              const response = await checkPaymentStatus(this.paymentId);

              if (response.status === 'success') {
                this.paymentStatus = 'success';
                clearInterval(this.paymentCheckInterval);
                clearInterval(this.timerInterval);
                localStorage.removeItem('pendingOrder');
                this.showToast('Pembayaran Berhasil', 'Pembayaran telah dikonfirmasi');
              } else if (response.status === 'expired' || response.status === 'failed') {
                this.paymentStatus = 'expired';
                clearInterval(this.paymentCheckInterval);
                clearInterval(this.timerInterval);
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
            const response = await checkPaymentStatus(this.paymentId);

            if (response.status === 'success') {
              this.paymentStatus = 'success';
              clearInterval(this.timerInterval);
              if (this.paymentCheckInterval) {
                clearInterval(this.paymentCheckInterval);
              }
              localStorage.removeItem('pendingOrder');
              this.showToast('Pembayaran Berhasil', 'Pembayaran Anda telah dikonfirmasi');
            } else if (response.status === 'pending') {
              this.showToast('Pembayaran Pending', 'Pembayaran belum diterima, mohon selesaikan pembayaran');
            } else {
              this.showToast('Pembayaran Gagal', 'Status: ' + response.status);
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

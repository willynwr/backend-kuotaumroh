@extends('agent.layout')

@section('title', 'Checkout - Kuotaumroh.id')

@section('content')
<div x-data="checkoutApp()" x-init="init()">
    
    <!-- Loading Skeleton (shown before QR ready) -->
    <div x-show="isLoading" x-cloak class="container mx-auto py-6 px-4">
        <div class="flex items-center justify-center min-h-[60vh]">
            <div class="text-center space-y-4">
                <div class="w-20 h-20 mx-auto border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <p class="text-lg font-medium text-gray-700">Memuat pembayaran...</p>
                <p class="text-sm text-muted-foreground">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <main x-show="!isLoading" x-cloak class="container mx-auto py-6 animate-fade-in px-4">
        
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

        <!-- Pending/Verifying/Activated State (Payment Page) -->
        <div x-show="['pending', 'verifying', 'activated'].includes(paymentStatus)" x-cloak class="overflow-hidden">
            <!-- Page Header with Back Button -->
            <div class="mb-6">
                <div class="flex items-start gap-4">
                    <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/order') : route('agent.order') }}" 
                       class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight" x-text="paymentStatus === 'pending' ? 'Pembayaran' : (paymentStatus === 'verifying' ? 'Verifikasi Pembayaran' : 'Paket Aktif')"></h1>
                        <p class="text-muted-foreground mt-2 text-sm sm:text-base" x-text="paymentStatus === 'pending' ? 'Selesaikan pembayaran Anda' : (paymentStatus === 'verifying' ? 'Pembayaran sedang diverifikasi...' : 'Paket kuota umroh sudah aktif!')"></p>
                    </div>
                    
                    <!-- Step Indicator Desktop -->
                    <div class="hidden lg:flex items-center gap-1">
                        <!-- Step 1: Pilih -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs bg-green-500 text-white">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600">Pilih</span>
                        </div>
                        <div class="w-4 h-0.5 bg-green-500"></div>
                        
                        <!-- Step 2: Bayar -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs"
                                :class="paymentStatus === 'pending' ? 'bg-yellow-500 text-white animate-pulse' : 'bg-green-500 text-white'">
                                <i class="fas" :class="paymentStatus === 'pending' ? 'fa-clock' : 'fa-check'"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600">Bayar</span>
                        </div>
                        <div class="w-4 h-0.5" :class="['verifying', 'activated'].includes(paymentStatus) ? 'bg-green-500' : 'bg-gray-300'"></div>
                        
                        <!-- Step 3: Verifikasi -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs"
                                :class="paymentStatus === 'verifying' ? 'bg-yellow-500 text-white animate-pulse' : (paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500')">
                                <i class="fas" :class="paymentStatus === 'verifying' ? 'fa-spinner fa-spin' : (paymentStatus === 'activated' ? 'fa-check' : 'fa-shield-alt')"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600">Verifikasi</span>
                        </div>
                        <div class="w-4 h-0.5" :class="paymentStatus === 'activated' ? 'bg-green-500' : 'bg-gray-300'"></div>
                        
                        <!-- Step 4: Paket Aktif -->
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs"
                                :class="paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500'">
                                <i class="fas" :class="paymentStatus === 'activated' ? 'fa-check' : 'fa-box'"></i>
                            </div>
                            <span class="text-[10px] ml-1 text-gray-600">Paket Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- QR Code & Payment Info -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- QR Card -->
                    <div class="rounded-lg border bg-white shadow-sm">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-semibold flex items-center gap-2 mb-4">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <span x-text="paymentStatus === 'pending' ? 'Scan QR Code QRIS' : (paymentStatus === 'verifying' ? 'Verifikasi Pembayaran' : 'Paket Aktif ✓')"></span>
                            </h3>
                            
                            <!-- Payment ID Info -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-blue-600 font-medium mb-1">Payment ID</p>
                                        <p class="text-sm font-mono text-blue-900 font-semibold truncate" x-text="paymentId"></p>
                                    </div>
                                    <button @click="navigator.clipboard.writeText(paymentId); showToast('Tersalin', 'Payment ID berhasil disalin')"
                                        class="ml-2 h-10 w-10 inline-flex items-center justify-center rounded-md hover:bg-blue-100 transition-colors flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-blue-600 mt-2">Simpan ID ini untuk verifikasi pembayaran</p>
                            </div>
                            
                            <!-- Step Indicator Mobile Only -->
                            <div class="lg:hidden mt-4">
                                <div class="flex justify-between items-center px-2">
                                    <!-- Step 1: Pilih -->
                                    <div class="text-center flex-1">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1 bg-green-500 text-white">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-gray-600">Pilih</span>
                                    </div>
                                    <div class="flex-1 h-0.5 bg-green-500 -mt-4"></div>
                                    
                                    <!-- Step 2: Bayar -->
                                    <div class="text-center flex-1">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1"
                                            :class="paymentStatus === 'pending' ? 'bg-yellow-500 text-white animate-pulse' : 'bg-green-500 text-white'">
                                            <i class="fas text-xs" :class="paymentStatus === 'pending' ? 'fa-clock' : 'fa-check'"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-gray-600">Bayar</span>
                                    </div>
                                    <div class="flex-1 h-0.5 -mt-4" :class="['verifying', 'activated'].includes(paymentStatus) ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    
                                    <!-- Step 3: Verifikasi -->
                                    <div class="text-center flex-1">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1"
                                            :class="paymentStatus === 'verifying' ? 'bg-yellow-500 text-white animate-pulse' : (paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500')">
                                            <i class="fas text-xs" :class="paymentStatus === 'verifying' ? 'fa-spinner fa-spin' : (paymentStatus === 'activated' ? 'fa-check' : 'fa-shield-alt')"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-gray-600">Verifikasi</span>
                                    </div>
                                    <div class="flex-1 h-0.5 -mt-4" :class="paymentStatus === 'activated' ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    
                                    <!-- Step 4: Paket Aktif -->
                                    <div class="text-center flex-1">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto mb-1"
                                            :class="paymentStatus === 'activated' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500'">
                                            <i class="fas text-xs" :class="paymentStatus === 'activated' ? 'fa-check' : 'fa-box'"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-gray-600">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <!-- Detail Paket & Nomor -->
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-100 rounded-lg p-3 text-center">
                                <template x-if="orderData.items && orderData.items.length > 0">
                                    <div>
                                        <p class="text-xs text-purple-600 font-medium mb-1">Detail Pesanan</p>
                                        <p class="text-sm font-semibold text-purple-900" x-text="orderData.items.length + ' Paket'"></p>
                                        <p class="text-xs text-purple-700 mt-1" x-text="orderData.items.map(i => i.msisdn).join(', ')"></p>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Catatan Penting -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-sm text-yellow-800 font-medium mb-2">Catatan Penting:</p>
                                <p class="text-sm text-yellow-700">
                                    Harap membayar sesuai dengan nominal yang tertera (termasuk kode unik di belakang).
                                </p>
                            </div>
                            
                            <!-- QR Code Container -->
                            <div class="flex justify-center">
                                <div class="bg-white p-4 rounded-lg border-2 border-border">
                                    <div id="qrContainer" class="flex items-center justify-center"></div>
                                </div>
                            </div>
                            
                            <!-- Toggle Static QRIS -->
                            <template x-if="qrisStaticString">
                                <div class="flex items-center justify-center gap-2">
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" x-model="useStaticQris" class="rounded">
                                        <span>Gunakan QRIS Static (jika Dynamic gagal)</span>
                                    </label>
                                </div>
                            </template>

                            <!-- Amount -->
                            <div class="space-y-2">
                                <p class="text-sm text-muted-foreground text-center">Pembayaran:</p>
                                <p class="text-sm text-center text-gray-600">
                                    Rp <span x-text="formatNumber(orderData.total)"></span> + 
                                    <span x-text="orderData.uniqueCode || orderData.paymentUnique || orderData.platformFee"></span> (kode unik)
                                </p>
                                <div class="flex items-center justify-center gap-2">
                                    <p class="text-3xl font-bold text-center" x-text="formatRupiah(totalAmount)"></p>
                                    <button @click="handleCopyAmount()"
                                        class="h-8 w-8 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors">
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
                            <div class="space-y-4">
                                <button @click="handleCheckPayment()"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-primary text-primary-foreground h-11 px-4 py-2 hover:bg-primary/90 transition-colors font-medium">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="paymentStatus === 'pending' ? 'Cek Status Pembayaran' : (paymentStatus === 'verifying' ? 'Memeriksa...' : 'Status Terkonfirmasi')"></span>
                                </button>
                                
                                <!-- Instructions -->
                                <div>
                                    <h4 class="text-sm font-semibold mb-3">Cara Pembayaran</h4>
                                    <ol class="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                        <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                                        <li>Scan QR Code QRIS di atas</li>
                                        <li>Pastikan nominal pembayaran sesuai (dengan kode unik)</li>
                                        <li>Konfirmasi pembayaran</li>
                                        <li>Simpan bukti pembayaran</li>
                                        <li>Klik tombol "Cek Status Pembayaran" di atas</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="lg:col-span-2">
                    <div class="rounded-lg border bg-white shadow-sm">
                        <div class="p-4 sm:p-6 border-b">
                            <h3 class="text-lg font-semibold">Detail Pesanan</h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <!-- Mobile Card View -->
                            <div class="sm:hidden space-y-3">
                                <template x-for="(item, index) in orderData.items" :key="index">
                                    <div class="border rounded-lg p-3 bg-muted/30">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-sm font-medium" x-text="'#' + (index + 1)"></span>
                                            <span class="font-semibold text-primary" x-text="formatRupiah(item.price)"></span>
                                        </div>
                                        <p class="text-sm text-muted-foreground font-mono" x-text="item.msisdn"></p>
                                        <p class="text-sm" x-text="item.packageName"></p>
                                    </div>
                                </template>
                                
                                <!-- Summary Mobile -->
                                <div class="border-t pt-3 mt-3 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span>Subtotal</span>
                                        <span class="font-medium" x-text="formatRupiah(orderData.total)"></span>
                                    </div>
                                    <div class="flex justify-between text-sm text-muted-foreground">
                                        <span>Kode Unik</span>
                                        <span x-text="formatRupiah(orderData.uniqueCode || orderData.paymentUnique || orderData.platformFee)"></span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t">
                                        <span class="font-bold">Total</span>
                                        <span class="font-bold text-primary" x-text="formatRupiah(totalAmount)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden sm:block overflow-x-auto">
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

                                        <tr class="border-b">
                                            <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal</td>
                                            <td class="px-4 py-3 text-right font-medium" x-text="formatRupiah(orderData.total)"></td>
                                        </tr>
                                        <tr class="border-b">
                                            <td colspan="3" class="px-4 py-3 text-right text-muted-foreground">Kode Unik</td>
                                            <td class="px-4 py-3 text-right text-muted-foreground" x-text="formatRupiah(orderData.uniqueCode || orderData.paymentUnique || orderData.platformFee)"></td>
                                        </tr>
                                        <tr class="border-t-2">
                                            <td colspan="3" class="px-4 py-3 text-right font-bold text-lg">Total Pembayaran</td>
                                            <td class="px-4 py-3 text-right font-bold text-lg" x-text="formatRupiah(totalAmount)"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 pt-6 border-t space-y-3">
                                <button @click="handleViewInvoice()"
                                    :disabled="!canAccessInvoice"
                                    :class="canAccessInvoice ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md h-10 px-4 py-2 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span x-text="canAccessInvoice ? 'Lihat Invoice' : 'Invoice (Selesaikan Pembayaran)'"></span>
                                </button>
                                
                                <p x-show="!canAccessInvoice" class="text-center text-sm text-muted-foreground">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Invoice hanya dapat diakses setelah pembayaran berhasil
                                </p>
                                
                                <!-- Back to Dashboard -->
                                <button @click="window.location.href = '{{ isset($linkReferral) ? url('/dash/' . $linkReferral) : route('agent.dashboard') }}'"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-md border bg-background h-10 px-4 py-2 hover:bg-muted transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Kembali ke Dashboard
                                </button>
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
        // Loading state
        isLoading: true,
        
        // Payment state
        paymentStatus: 'pending', // 'pending', 'verifying', 'activated', 'expired'
        timeRemaining: 15 * 60,

        // Order data
        orderData: {
            items: [],
            total: 0,
            platformFee: 0,
            uniqueCode: 0,
            paymentUnique: 0,
            paymentMethod: 'qris',
            refCode: null,
        },

        // Payment data
        paymentId: null,
        batchId: null,
        qrCodeUrl: null,
        qrisString: null,
        qrisStaticString: null,
        useStaticQris: false,
        paymentAmount: 0,

        // Toast
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',

        // Intervals
        timerInterval: null,
        paymentCheckInterval: null,

        async init() {
            this.$watch('useStaticQris', (value) => {
                console.log('🔄 Switched to', value ? 'STATIC' : 'DYNAMIC', 'QRIS');
                this.generateQRCode();
            });
            
            const savedOrderData = localStorage.getItem('pendingOrder');
            if (!savedOrderData) {
                window.location.href = '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/order") : route("agent.order") }}';
                return;
            }

            const parsedData = JSON.parse(savedOrderData);

            this.orderData = {
                items: parsedData.items || [],
                total: parsedData.subtotal || parsedData.total || 0,
                platformFee: parsedData.platformFee || 0,
                paymentMethod: parsedData.paymentMethod || 'qris',
                refCode: parsedData.refCode || '{{ $agent->id ?? 1 }}',
                scheduleDate: parsedData.scheduleDate || null,
                isBulk: true,
            };
            
            console.log('📦 Agent Order Data:', this.orderData);

            if (parsedData.paymentId) {
                console.log('♻️ Payment exists:', parsedData.paymentId);
                this.paymentId = parsedData.paymentId;
                this.batchId = parsedData.batchId || null;
                await this.fetchQrisData();
            } else {
                console.log('🆕 Creating new payment...');
                await this.createPayment();
            }

            this.startTimer();
            this.startPaymentPolling();
        },

        get totalAmount() {
            if (this.paymentAmount > 0) return this.paymentAmount;
            return this.orderData.total + (this.orderData.uniqueCode || this.orderData.paymentUnique || this.orderData.platformFee);
        },

        get formattedTime() {
            const minutes = Math.floor(this.timeRemaining / 60);
            const seconds = this.timeRemaining % 60;
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        },

        get paymentMethodLabel() {
            return this.orderData.paymentMethod === 'qris' ? 'QRIS' : this.orderData.paymentMethod.toUpperCase();
        },

        get canAccessInvoice() {
            return this.paymentStatus === 'activated';
        },

        qrCodeInstance: null,
        
        generateQRCode() {
            const qrisData = this.useStaticQris ? this.qrisStaticString : this.qrisString;
            if (!qrisData) return;
            
            const container = document.getElementById('qrContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            try {
                const wrapper = document.createElement('div');
                wrapper.style.position = 'relative';
                wrapper.style.width = '260px';
                wrapper.style.height = '300px';
                
                const templateImg = document.createElement('img');
                templateImg.src = '{{ asset("images/template_qris.png") }}';
                templateImg.style.width = '100%';
                templateImg.style.height = '100%';
                templateImg.style.objectFit = 'contain';
                templateImg.style.position = 'absolute';
                templateImg.style.top = '0';
                templateImg.style.left = '0';
                wrapper.appendChild(templateImg);
                
                const qrDiv = document.createElement('div');
                qrDiv.id = 'qrcode-inner';
                qrDiv.style.position = 'absolute';
                qrDiv.style.top = '55%';
                qrDiv.style.left = '50%';
                qrDiv.style.transform = 'translate(-50%, -50%)';
                qrDiv.style.background = 'white';
                qrDiv.style.padding = '4px';
                qrDiv.style.borderRadius = '4px';
                wrapper.appendChild(qrDiv);
                
                container.appendChild(wrapper);
                
                this.qrCodeInstance = new QRCode(qrDiv, {
                    text: qrisData,
                    width: 170,
                    height: 170,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M
                });
                console.log('✅ QR Code generated');
            } catch (error) {
                console.error('❌ QR Code error:', error);
            }
        },

        async fetchQrisData() {
            if (!this.paymentId) return;
            
            try {
                const response = await getPaymentStatus(this.paymentId);
                const data = Array.isArray(response) ? response[0] : response;
                
                if (data && data.qris) {
                    this.qrisString = data.qris;
                    this.qrisStaticString = data.qris_static || null;
                    
                    this.$nextTick(() => {
                        this.generateQRCode();
                        setTimeout(() => { this.isLoading = false; }, 500);
                    });
                }
                
                if (data && data.payment_amount) {
                    this.paymentAmount = parseInt(data.payment_amount) || 0;
                    this.orderData.uniqueCode = parseInt(data.payment_unique) || 0;
                }
                
                if (data && data.payment_expired) {
                    const expiredDate = new Date(data.payment_expired);
                    const now = new Date();
                    this.timeRemaining = Math.max(0, Math.floor((expiredDate - now) / 1000));
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
                const batchId = 'BATCH_' + Date.now();
                const batchName = 'ORDER_' + new Date().toISOString().slice(0,10).replace(/-/g,'');
                
                const msisdnList = this.orderData.items.map(item => {
                    let msisdn = item.msisdn || item.phoneNumber;
                    if (msisdn.startsWith('08')) msisdn = '62' + msisdn.substring(1);
                    else if (msisdn.startsWith('8')) msisdn = '62' + msisdn;
                    return msisdn;
                });
                
                const packageIdList = this.orderData.items.map(item => item.packageId || item.package_id);

                const requestData = {
                    batch_id: batchId,
                    batch_name: batchName,
                    payment_method: 'QRIS',
                    detail: this.orderData.scheduleDate ? `{date: ${this.orderData.scheduleDate}}` : null,
                    ref_code: this.orderData.refCode || '{{ $agent->id ?? 1 }}',
                    msisdn: msisdnList,
                    package_id: packageIdList,
                };

                console.log('📤 BULK payment request:', requestData);
                const response = await createBulkPayment(requestData);
                
                const data = response.data || response;
                const isSuccess = response.success === true || (data && data.id);
                
                if (isSuccess && data) {
                    this.paymentId = data.payment_id || data.id;
                    this.batchId = data.batch_id || data.location_id;
                    
                    if (data.payment_expired) {
                        const expiredDate = new Date(data.payment_expired);
                        this.timeRemaining = Math.max(0, Math.floor((expiredDate - new Date()) / 1000));
                    }

                    if (data.payment_amount) {
                        this.paymentAmount = parseInt(data.payment_amount) || 0;
                        this.orderData.uniqueCode = parseInt(data.payment_unique) || 0;
                    }

                    // Save to localStorage
                    const savedOrder = localStorage.getItem('pendingOrder');
                    if (savedOrder) {
                        const orderData = JSON.parse(savedOrder);
                        orderData.paymentId = this.paymentId;
                        orderData.batchId = this.batchId;
                        localStorage.setItem('pendingOrder', JSON.stringify(orderData));
                    }
                    
                    await this.fetchQrisData();
                } else {
                    throw new Error(response.message || 'Gagal membuat transaksi');
                }
            } catch (error) {
                console.error('❌ Payment error:', error);
                this.showToast('Error', error.message || 'Gagal membuat transaksi');
                this.isLoading = false;
            }
        },

        startPaymentPolling() {
            if (!this.paymentId) return;

            this.paymentCheckInterval = setInterval(async () => {
                try {
                    const verifyResponse = await verifyPayment(this.paymentId);
                    
                    if (verifyResponse.success && ['berhasil', 'success', 'sukses'].includes(verifyResponse.status?.toLowerCase())) {
                        this.setPaymentActivated();
                        return;
                    }
                    
                    const response = await getPaymentStatus(this.paymentId);
                    const data = Array.isArray(response) ? response[0] : (response.data || response);

                    if (data && data.id) {
                        if (data.qris && !this.qrisString) {
                            this.qrisString = data.qris;
                            this.qrisStaticString = data.qris_static || null;
                            this.$nextTick(() => this.generateQRCode());
                        }
                        
                        const status = (data.status || data.payment_status || '').toLowerCase();
                        
                        if (['success', 'sukses', 'paid', 'berhasil', 'completed'].includes(status)) {
                            this.setPaymentActivated();
                        } else if (['expired', 'failed'].includes(status)) {
                            this.paymentStatus = 'expired';
                            clearInterval(this.paymentCheckInterval);
                            clearInterval(this.timerInterval);
                            localStorage.removeItem('pendingOrder');
                        }
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }, 10000);
        },

        handleCopyAmount() {
            navigator.clipboard.writeText(this.totalAmount.toString());
            this.showToast('Tersalin', 'Nominal pembayaran telah disalin');
        },

        async handleCheckPayment() {
            if (!this.paymentId) {
                this.showToast('Error', 'Payment ID tidak ditemukan');
                return;
            }

            if (this.paymentStatus === 'pending') {
                this.paymentStatus = 'verifying';
            }

            this.showToast('Memeriksa', 'Sedang memeriksa status pembayaran...');

            try {
                const verifyResponse = await verifyPayment(this.paymentId);
                
                if (verifyResponse.success && ['berhasil', 'success', 'sukses'].includes(verifyResponse.status?.toLowerCase())) {
                    this.setPaymentActivated();
                    return;
                }
                
                const response = await getPaymentStatus(this.paymentId);
                const data = Array.isArray(response) ? response[0] : (response.data || response);
                const status = (data?.status || data?.payment_status || '').toLowerCase();

                if (['success', 'sukses', 'paid', 'berhasil', 'completed'].includes(status)) {
                    this.setPaymentActivated();
                } else if (['pending', 'unpaid', 'menunggu pembayaran'].includes(status)) {
                    this.showToast('Menunggu', 'Pembayaran belum diterima. Silakan selesaikan pembayaran.');
                } else if (['expired', 'failed'].includes(status)) {
                    this.paymentStatus = 'expired';
                    clearInterval(this.timerInterval);
                    clearInterval(this.paymentCheckInterval);
                    localStorage.removeItem('pendingOrder');
                } else {
                    this.showToast('Status', 'Status: ' + (data?.status || data?.payment_status || 'checking'));
                }
            } catch (error) {
                console.error('Check error:', error);
                this.showToast('Error', 'Gagal memeriksa status');
            }
        },

        async handleViewInvoice() {
            if (!this.paymentId) {
                this.showToast('Error', 'Payment ID tidak ditemukan');
                return;
            }

            if (!this.canAccessInvoice) {
                this.showToast('Info', 'Memeriksa status pembayaran...');
                await this.handleCheckPayment();
                if (!this.canAccessInvoice) {
                    this.showToast('Menunggu', 'Invoice hanya dapat diakses setelah pembayaran berhasil');
                    return;
                }
            }

            window.open(`/invoice/${this.paymentId}`, '_blank');
        },

        setPaymentActivated() {
            if (this.paymentStatus === 'activated') return;
            
            this.paymentStatus = 'activated';
            this.showToast('Paket Aktif! 🎉', 'Pembayaran berhasil! Silakan lihat invoice.');
            
            clearInterval(this.timerInterval);
            clearInterval(this.paymentCheckInterval);
            localStorage.removeItem('pendingOrder');
        },

        showToast(title, message) {
            this.toastTitle = title;
            this.toastMessage = message;
            this.toastVisible = true;
            setTimeout(() => { this.toastVisible = false; }, 3000);
        },
        
        formatNumber(num) {
            if (!num) return '0';
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endpush

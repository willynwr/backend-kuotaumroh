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
                    <a href="#" @click.prevent="handleUiBack('{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/order') : route('agent.order') }}')" 
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
                            <!-- Detail Paket & Provider -->
                            <div class="bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-100 rounded-lg p-3 text-center">
                                <template x-if="orderData.items && orderData.items.length > 0">
                                    <div>
                                        <p class="text-xs text-purple-600 font-medium mb-1">Detail Pesanan</p>
                                        <p class="text-sm font-semibold text-purple-900" x-text="orderData.items.length + ' Paket'"></p>
                                        <p class="text-xs text-purple-700 mt-1" x-text="getPackageSummary()"></p>
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

                            <!-- Check Payment Button -->
                            <button @click="handleCheckPayment()"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-primary text-primary-foreground h-11 px-4 py-2 hover:bg-primary/90 transition-colors font-medium mt-4">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span x-text="paymentStatus === 'activated' ? 'Status Terkonfirmasi' : 'Cek Status Pembayaran'"></span>
                            </button>
                            
                            <!-- QR Code Container -->
                            <div class="flex justify-center">
                                <div class="bg-white p-4 rounded-lg border-2 border-border">
                                    <div id="qrContainer" class="flex items-center justify-center"></div>
                                </div>
                            </div>
                            
                            <!-- Toggle Static QRIS -->
                            <template x-if="qrisStaticString">
                                <div class="flex flex-col items-center justify-center gap-3 mt-4">
                                    <p class="text-xs text-muted-foreground text-center px-4">
                                        Jika pembayaran QRIS di atas gagal <br> (khususnya BCA Mobile), <br> klik tombol di bawah:
                                    </p>
                                    <button @click="useStaticQris = !useStaticQris"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2"
                                        :class="useStaticQris ? 'bg-primary text-primary-foreground hover:bg-primary/90 border-primary' : ''">
                                        <span x-text="useStaticQris ? 'Kembali ke QRIS Utama' : 'QRIS Alternatif'"></span>
                                    </button>
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
                                <template x-for="(group, provider) in getGroupedByProvider()" :key="provider">
                                    <div class="border rounded-lg p-3 bg-muted/30">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-sm font-medium" x-text="provider"></span>
                                            <span class="font-semibold text-primary" x-text="formatRupiah(group.total)"></span>
                                        </div>
                                        <p class="text-sm text-muted-foreground" x-text="group.count + ' Paket'"></p>
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
                                            <th class="px-4 py-3">Provider</th>
                                            <th class="px-4 py-3">Jumlah Paket</th>
                                            <th class="px-4 py-3 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, idx) in Object.entries(getGroupedByProvider())" :key="item[0]">
                                            <tr class="border-b">
                                                <td class="px-4 py-3 text-muted-foreground" x-text="idx + 1"></td>
                                                <td class="px-4 py-3 font-medium" x-text="item[0]"></td>
                                                <td class="px-4 py-3" x-text="item[1].count + ' Paket'"></td>
                                                <td class="px-4 py-3 text-right font-medium" x-text="formatRupiah(item[1].total)"></td>
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

    <!-- Error Modal -->
    <div x-show="errorModalVisible" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full overflow-hidden"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="p-4 border-b flex items-center gap-3 bg-red-50">
                <div class="bg-red-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-lg text-red-900" x-text="errorModalTitle"></h3>
            </div>
            
            <div class="p-6">
                <p class="text-gray-700 break-words" x-text="errorModalMessage"></p>
            </div>
            
            <div class="p-4 bg-gray-50 flex justify-end">
                <button @click="errorModalVisible = false" 
                    class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toastVisible" x-transition class="toast">
        <div class="font-semibold mb-1" x-text="toastTitle"></div>
        <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>

    <!-- Error Modal -->
    <div x-show="errorModalVisible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         @click.self="errorModalVisible = false">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            
            <!-- Modal panel -->
            <div x-show="errorModalVisible"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="errorModalTitle"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="errorModalMessage"></p>
                            <p class="text-xs text-gray-400 mt-2">Kembali dalam <span x-text="errorModalCountdown"></span> detik...</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button @click="redirectAfterError()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Success Modal (Pembayaran Berhasil) -->
    <div x-show="showSuccessModal" 
         style="display: none;"
         class="fixed inset-0 z-[60] overflow-y-auto"
         x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Background overlay -->
            <div x-show="showSuccessModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 blur-sm" 
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showSuccessModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 px-6 pt-6 pb-5">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-500 mb-4">
                            <svg class="h-9 w-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            Pembayaran Berhasil!
                        </h3>
                        <p class="text-sm text-gray-600">
                            Paket kuota umroh Anda sudah aktif dan siap digunakan.
                        </p>
                    </div>
                </div>
                
                <div class="bg-white px-6 py-5 space-y-3">
                    <button @click="redirectToInvoice()" type="button" 
                        class="w-full inline-flex justify-center items-center rounded-lg px-4 py-3 bg-green-600 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Invoice
                    </button>
                    <button @click="redirectToOrder()" type="button" 
                        class="w-full inline-flex justify-center items-center rounded-lg px-4 py-3 bg-gray-100 text-sm font-semibold text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal (Custom UI) -->
    <div x-show="showExitModal" 
         style="display: none;"
         class="fixed inset-0 z-[60] overflow-y-auto"
         x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Background overlay -->
            <div x-show="showExitModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 blur-sm" 
                 @click="showExitModal = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showExitModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                                Batalkan Pembayaran?
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Transaksi Anda belum selesai. Jika Anda keluar sekarang, pembayaran ini akan dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button @click="showExitModal = false" type="button" 
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Lanjutkan Pembayaran
                    </button>
                    <button @click="confirmExit()" type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
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
        paymentStatus: 'verifying', // 'pending', 'verifying', 'activated', 'expired'
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

        // Error Modal
        errorModalVisible: false,
        errorModalTitle: '',
        errorModalMessage: '',
        errorModalCountdown: 5,
        
        // Exit Modal
        showExitModal: false,
        isForceExit: false,
        
        // Success Modal
        showSuccessModal: false,
        modalShown: false,
        
        isCreatingPayment: false,

        // Intervals
        timerInterval: null,
        paymentCheckInterval: null,

        async init() {
            this.$watch('useStaticQris', async (value) => {
                console.log('🔄 Switched to', value ? 'STATIC' : 'DYNAMIC', 'QRIS');
                if (value && !this.qrisStaticDynamicString) {
                    await this.fetchStaticDynamicQris();
                }
                this.generateQRCode();
            });
            
            // 1. Intercept Browser Back Button (Mobile Friendly)
            // Push state awal
            history.pushState(null, null, location.href);
            
            // Listen popstate (saat tombol back ditekan)
            window.addEventListener('popstate', (e) => {
                if (['pending', 'verifying'].includes(this.paymentStatus)) {
                        // Push state lagi supaya tidak benar-benar kembali (stay di page)
                        history.pushState(null, null, location.href);
                        // Tampilkan modal custom
                        this.showExitModal = true;
                }
            });

            // 2. Fallback: Prevent Accidental Tab Close/Refresh
            window.addEventListener('beforeunload', (e) => {
                if (this.isForceExit) return;
                if (['pending', 'verifying'].includes(this.paymentStatus)) {
                    e.preventDefault();
                    e.returnValue = ''; // Trigger default browser warning
                }
            });
            
            // Watch for payment status changes and persist to localStorage
            this.$watch('paymentStatus', (newStatus) => {
                console.log('📊 Payment status changed to:', newStatus);
                this.savePaymentState();
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
                refCode: parsedData.refCode || '{{ isset($user) ? $user->id : (auth()->check() ? auth()->user()->id : "") }}',
                scheduleDate: parsedData.scheduleDate || null,
                isBulk: true,
            };
            
            console.log('📦 Agent Order Data:', this.orderData);
            
            // Restore payment status dari localStorage jika ada (untuk handle refresh)
            // Jangan restore pending - langsung set ke verifying (biar masuk step verifikasi)
            if (parsedData.paymentStatus && ['verifying', 'activated'].includes(parsedData.paymentStatus)) {
                this.paymentStatus = parsedData.paymentStatus;
                console.log('♻️ Restored payment status:', this.paymentStatus);
            } else if (parsedData.paymentStatus === 'pending') {
                this.paymentStatus = 'verifying';
                console.log('♻️ Override pending to verifying');
            }

            if (parsedData.paymentId) {
                console.log('♻️ Payment exists:', parsedData.paymentId);
                this.paymentId = parsedData.paymentId;
                this.batchId = parsedData.batchId || null;
                
                // Jika status sudah activated, langsung tampilkan tanpa fetch ulang
                if (this.paymentStatus === 'activated') {
                    console.log('✅ Status sudah activated, skip loading...');
                    this.isLoading = false;
                } else {
                    await this.fetchQrisData();
                    
                    // Auto-verify payment saat page load
                    console.log('🔄 Auto-verifying payment on page load...');
                    await this.autoVerifyPayment();
                }
            } else {
                console.log('🆕 Creating new payment...');
                await this.createPayment();
            }

            this.startTimer();
            this.startPaymentPolling();
        },
        
        // Action saat user pilih "Ya, Batalkan"
        confirmExit() {
            this.isForceExit = true;
            // Redirect ke halaman order
            window.location.href = '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/order") : route("agent.order") }}';
        },

        // Handle Back Button UI Click
        handleUiBack(url) {
             if (['pending', 'verifying'].includes(this.paymentStatus)) {
                this.showExitModal = true;
            } else {
                this.isForceExit = true;
                window.location.href = url;
            }
        },
        
        // Save payment state ke localStorage (untuk handle refresh)
        savePaymentState() {
            const savedOrder = localStorage.getItem('pendingOrder');
            if (savedOrder) {
                const orderData = JSON.parse(savedOrder);
                orderData.paymentStatus = this.paymentStatus === 'pending' ? 'verifying' : this.paymentStatus;
                
                // PENTING: Jangan overwrite paymentId jika sudah ada di state tapi belum ada di LS
                if (this.paymentId && !orderData.paymentId) {
                    orderData.paymentId = this.paymentId;
                    orderData.batchId = this.batchId;
                }
                
                localStorage.setItem('pendingOrder', JSON.stringify(orderData));
                console.log('💾 Payment status saved:', orderData.paymentStatus);
            }
        },
        
        // Auto-verify payment saat page load (untuk handle refresh)
        // Alur sama seperti tokodigi: verifyPayment (cek mutasi) → getPayment (baca DB)
        async autoVerifyPayment() {
            if (!this.paymentId) return;
            try {
                console.log('🔍 Auto-verifying payment:', this.paymentId);
                
                // Step 1: Trigger verifyPayment untuk cek mutasi QRIS
                const verifyResponse = await verifyPayment(this.paymentId);
                console.log('🔍 Verify response:', verifyResponse);
                
                if (verifyResponse.success && ['berhasil', 'success', 'sukses'].includes(verifyResponse.status?.toLowerCase())) {
                    console.log('✅ Verify found payment successful!');
                    this.setPaymentActivated();
                    return;
                }
                
                // Step 2: Get payment data dari database
                const response = await getPaymentStatus(this.paymentId);
                const data = Array.isArray(response) ? response[0] : (response.data || response);
                const status = (data.status || data.payment_status || '').toLowerCase();
                console.log('🔍 Manual check - Status:', status, 'Data:', data);
                if (data && data.id) {
                    if (data.qris && !this.qrisString) {
                        this.qrisString = data.qris;
                        this.qrisStaticString = data.qris_static || null;
                        this.$nextTick(() => this.generateQRCode());
                    }
                    if (['success', 'sukses', 'paid', 'berhasil', 'completed'].includes(status)) {
                        this.setPaymentActivated();
                    }
                    // PENTING: Abaikan status pending dari API saat page load
                    // Status sudah di-set ke 'verifying' di init(), jangan override ke pending
                    else if (['pending', 'unpaid'].includes(status) || status.includes('menunggu')) {
                        console.log('⚠️ API returned pending, keeping current status:', this.paymentStatus);
                        // Tidak mengubah this.paymentStatus, biarkan tetap 'verifying'
                    }
                    else if (['expired', 'failed'].includes(status)) {
                        this.paymentStatus = 'expired';
                        localStorage.removeItem('pendingOrder');
                    }
                }
            } catch (error) {
                console.error('❌ Auto-verify failed:', error);
            }
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

        getPackageSummary() {
            const grouped = {};
            this.orderData.items.forEach(item => {
                const provider = this.extractProvider(item.packageName || '');
                grouped[provider] = (grouped[provider] || 0) + 1;
            });
            return Object.entries(grouped).map(([provider, count]) => `${count} Paket ${provider}`).join(', ');
        },

        getGroupedByProvider() {
            const grouped = {};
            this.orderData.items.forEach(item => {
                const provider = this.extractProvider(item.packageName || '');
                if (!grouped[provider]) {
                    grouped[provider] = { count: 0, total: 0 };
                }
                grouped[provider].count++;
                grouped[provider].total += parseInt(item.price || 0);
            });
            return grouped;
        },

        extractProvider(packageName) {
            const name = packageName.toUpperCase();
            if (name.includes('TELKOMSEL') || name.includes('TSEL')) return 'Telkomsel';
            if (name.includes('INDOSAT') || name.includes('ISAT')) return 'Indosat';
            if (name.includes('XL')) return 'XL';
            if (name.includes('AXIS')) return 'Axis';
            if (name.includes('TRI') || name.includes('3')) return 'Tri';
            if (name.includes('SMARTFREN') || name.includes('SFREN')) return 'Smartfren';
            if (name.includes('BY.U') || name.includes('BYU')) return 'by.U';
            return 'Lainnya';
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
            } finally {
                // Always stop loading after fetch attempt, whether successful or not
                // This prevents infinite loading screen
                setTimeout(() => { this.isLoading = false; }, 500);
            }
        },

        startTimer() {
            this.timerInterval = setInterval(() => {
                if (this.timeRemaining <= 1) {
                    clearInterval(this.timerInterval);
                    clearInterval(this.paymentCheckInterval);
                    
                    if (this.paymentStatus === 'activated') {
                        localStorage.removeItem('pendingOrder');
                    } else {
                        this.paymentStatus = 'expired';
                        this.timeRemaining = 0;
                        localStorage.removeItem('pendingOrder');
                    }
                } else {
                    this.timeRemaining--;
                }
            }, 1000);
        },

        async createPayment() {
            if (this.isCreatingPayment) return;
            this.isCreatingPayment = true;
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
                const priceList = this.orderData.items.map(item => item.price || 0);

                const requestData = {
                    batch_id: batchId,
                    batch_name: batchName,
                    payment_method: 'QRIS',
                    detail: this.orderData.scheduleDate ? `{date: ${this.orderData.scheduleDate}}` : null,
                    ref_code: this.orderData.refCode || '{{ isset($user) ? ($user->link_referral ?? $user->id) : (auth()->check() ? (auth()->user()->link_referral ?? auth()->user()->id) : "") }}',
                    agent_id: '{{ isset($user) ? $user->id : (auth()->check() ? auth()->user()->id : "") }}',
                    msisdn: msisdnList,
                    package_id: packageIdList,
                    price: priceList,
                };

                // Validate authentication before proceeding
                if (!requestData.agent_id) {
                    console.error('❌ User not authenticated - redirecting to login');
                    window.location.href = '/agent?redirect=' + encodeURIComponent(window.location.pathname);
                    return;
                }

                console.log('✅ AUTH CHECK: User ID =', requestData.agent_id);
                console.log('📤 BULK payment request:', requestData);

                const response = await createBulkPayment(requestData);
                
                console.log('📥 Payment response:', response);
                
                // Check for error response first
                if (response.success === false || response.error) {
                    const errorMessage = response.error || response.message || 'Gagal membuat transaksi';
                    console.error('❌ API returned error:', errorMessage);
                    throw new Error(errorMessage);
                }
                
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

                    // OPTIMISASI: Generate QR Code langsung dari response createPayment
                    if (data.qris) {
                        // Handle if qris is object or string
                        if (typeof data.qris === 'object' && data.qris !== null) {
                             this.qrisString = data.qris.qris_string || data.qris.string || null;
                             if (!this.qrisString && data.qris_string) {
                                 this.qrisString = data.qris_string;
                             }
                        } else {
                             this.qrisString = data.qris;
                        }
                        
                        this.qrisStaticString = data.qris_static || null; 
                        
                        if (this.qrisString) {
                            this.$nextTick(() => {
                                this.generateQRCode();
                                this.isLoading = false; 
                            });
                        }
                    } else if (data.qris_string) {
                        this.qrisString = data.qris_string;
                        this.$nextTick(() => {
                            this.generateQRCode();
                            this.isLoading = false; 
                        });
                    }

                    // Save to localStorage
                    const savedOrder = localStorage.getItem('pendingOrder');
                    if (savedOrder) {
                        const orderData = JSON.parse(savedOrder);
                        orderData.paymentId = this.paymentId;
                        orderData.batchId = this.batchId;
                        localStorage.setItem('pendingOrder', JSON.stringify(orderData));
                    }
                    
                    // OPTIMISASI: Skip fetchQrisData jika QR string sudah didapat
                    if (!this.qrisString) {
                        await this.fetchQrisData();
                    }
                } else {
                    throw new Error(response.message || 'Gagal membuat transaksi');
                }
            } catch (error) {
                console.error('❌ Payment error:', error);
                
                // Check if error is about invalid/unregistered phone number
                const errorMessage = error.message || '';
                const errorStr = JSON.stringify(error).toLowerCase();
                
                if (errorMessage.includes('tidak terdaftar') || errorMessage.includes('not registered') || 
                    errorMessage.includes('bukan nomor') || errorMessage.includes('tidak dapat diproses') ||
                    errorMessage.includes('invalid') || errorMessage.toLowerCase().includes('msisdn') ||
                    errorStr.includes('tidak terdaftar') || errorStr.includes('bukan nomor')) {
                    
                    // Extract nomor from error message if available
                    const numberMatch = errorMessage.match(/(\d{10,15})/);
                    const invalidNumber = numberMatch ? numberMatch[1] : '';
                    
                    this.showErrorModal(
                        'Nomor Tidak Terdaftar', 
                        errorMessage || 'Terdapat nomor telepon yang tidak terdaftar atau tidak valid. Silakan periksa kembali nomor telepon yang Anda masukkan dan pastikan nomor tersebut aktif.'
                    );
                } else {
                    this.showErrorModal('Error', error.message || 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
                }
                
                this.isLoading = false;
            } finally {
                this.isCreatingPayment = false;
            }
        },

        startPaymentPolling() {
            if (!this.paymentId) return;
            
            // Jika sudah activated, tidak perlu polling
            if (this.paymentStatus === 'activated') {
                console.log('✅ Status sudah activated, skip polling');
                return;
            }

            // Check payment status every 5 seconds
            // Alur sama seperti tokodigi: verifyPayment (cek mutasi) → getPayment (baca DB)
            this.paymentCheckInterval = setInterval(async () => {
                try {
                    // Step 1: Trigger verifyPayment untuk cek mutasi QRIS
                    const verifyResponse = await verifyPayment(this.paymentId);
                    console.log('🔄 Polling verify response:', verifyResponse);
                    
                    if (verifyResponse.success && ['berhasil', 'success', 'sukses'].includes(verifyResponse.status?.toLowerCase())) {
                        console.log('✅ Polling: Payment successful!');
                        this.setPaymentActivated();
                        return;
                    }
                    
                    // Step 2: Get payment data dari database
                    const response = await getPaymentStatus(this.paymentId);
                    const data = Array.isArray(response) ? response[0] : (response.data || response);
                    const status = (data.status || data.payment_status || '').toLowerCase();
                    
                    console.log('🔍 Manual check - Status:', status, 'Data:', data);

                    if (data && data.id) {
                        if (data.qris && !this.qrisString) {
                            this.qrisString = data.qris;
                            this.qrisStaticString = data.qris_static || null;
                            this.$nextTick(() => this.generateQRCode());
                        }
                        
                        // Update indikator berdasarkan status dari API (sama seperti manual check)
                        if (['success', 'sukses', 'paid', 'berhasil', 'completed'].includes(status)) {
                            this.setPaymentActivated();
                        }
                        // PENTING: Abaikan status pending dari API saat polling
                        // Biarkan tetap di 'verifying' (step 3)
                        else if (['pending', 'unpaid'].includes(status) || status.includes('menunggu')) {
                            console.log('⚠️ Polling: API returned pending, keeping verifying status');
                            // Tidak mengubah status, tetap di 'verifying'
                        }
                        else if (['expired', 'failed'].includes(status)) {
                            this.paymentStatus = 'expired';
                            clearInterval(this.paymentCheckInterval);
                            clearInterval(this.timerInterval);
                            localStorage.removeItem('pendingOrder');
                        }
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }, 5000); // Check every 5 seconds
        },

        handleCopyAmount() {
            navigator.clipboard.writeText(this.totalAmount.toString());
            this.showToast('Tersalin', 'Nominal pembayaran telah disalin');
        },

        async handleCheckPayment() {
            if (!this.paymentId) {
                this.showErrorModal('Error', 'Payment ID tidak ditemukan');
                return;
            }

            this.showToast('Mengecek', 'Sedang mengecek status pembayaran...');

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
                } else if (status.includes('verifikasi') || status === 'verify' || status === 'verifying') {
                    // Set ke verifying berdasarkan status API
                    if (this.paymentStatus !== 'activated') {
                        this.paymentStatus = 'verifying';
                        console.log('📊 Status dari API: verifying');
                    }
                    this.showToast('Verifikasi', 'Pembayaran sedang diverifikasi oleh sistem...');
                } else if (['pending', 'unpaid', 'menunggu pembayaran'].includes(status)) {
                    // Tetap di pending
                    if (this.paymentStatus !== 'activated') {
                        this.paymentStatus = 'pending';
                        console.log('📊 Status dari API: pending');
                    }
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
                this.showErrorModal('Error', 'Gagal mengecek status pembayaran. Silakan coba lagi.');
            }
        },

        async handleViewInvoice() {
            if (!this.paymentId) {
                this.showErrorModal('Error', 'Payment ID tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            if (!this.canAccessInvoice) {
                this.showToast('Info', 'Mengecek status pembayaran...');
                await this.handleCheckPayment();
                if (!this.canAccessInvoice) {
                    this.showToast('Menunggu', 'Invoice hanya dapat diakses setelah pembayaran berhasil');
                    return;
                }
            }

            // Build invoice URL with source parameter
            const agentId = '{{ $user->id ?? "" }}';
            const linkReferral = '{{ $linkReferral ?? "kuotaumroh" }}';
            const invoiceUrl = `/invoice/${this.paymentId}?source=order&refCode=${agentId}&linkReferral=${linkReferral}`;
            window.open(invoiceUrl, '_blank');
        },

        setPaymentActivated() {
            console.log('🎉 Payment successful! Setting to activated...');
            
            // Show success modal FIRST (sebelum check status, agar tidak ter-skip)
            if (!this.modalShown) {
                console.log('🎊 Showing success modal...');
                this.showSuccessModal = true;
                this.modalShown = true;
                console.log('🎊 showSuccessModal set to:', this.showSuccessModal);
            }
            
            // Check if already activated (after showing modal)
            if (this.paymentStatus === 'activated') {
                console.log('⚠️ Already activated, skipping status update...');
                return;
            }
            
            this.paymentStatus = 'activated';
            console.log('📊 Status: activated (paket aktif)');
            
            // Save final state before cleanup
            this.savePaymentState();
            
            // Keep timer running
            // clearInterval(this.timerInterval);
            if (this.paymentCheckInterval) clearInterval(this.paymentCheckInterval);
        },
        
        redirectToInvoice() {
            if (this.paymentId) {
                const agentId = '{{ $user->id ?? "" }}';
                const linkReferral = '{{ $linkReferral ?? "kuotaumroh" }}';
                const invoiceUrl = `/invoice/${this.paymentId}?source=order&refCode=${agentId}&linkReferral=${linkReferral}`;
                window.open(invoiceUrl, '_blank');
            }
        },
        
        redirectToOrder() {
            this.isForceExit = true;
            window.location.href = '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/order") : route("agent.order") }}';
        },

        showToast(title, message) {
            this.toastTitle = title;
            this.toastMessage = message;
            this.toastVisible = true;
            setTimeout(() => { this.toastVisible = false; }, 3000);
        },

        showErrorModal(title, message) {
            this.errorModalTitle = title;
            this.errorModalMessage = message;
            this.errorModalVisible = true;
            this.errorModalCountdown = 5;
            
            // Countdown timer
            const countdownInterval = setInterval(() => {
                this.errorModalCountdown--;
                if (this.errorModalCountdown <= 0) {
                    clearInterval(countdownInterval);
                    this.redirectAfterError();
                }
            }, 1000);
        },
        
        // Redirect after error - untuk tombol Tutup atau countdown habis
        redirectAfterError() {
            this.errorModalVisible = false;
            this.isForceExit = true; // Bypass beforeunload confirmation
            window.location.href = '{{ isset($linkReferral) ? url("/dash/" . $linkReferral . "/order") : route("agent.order") }}';
        },
        
        formatNumber(num) {
            if (!num) return '0';
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endpush

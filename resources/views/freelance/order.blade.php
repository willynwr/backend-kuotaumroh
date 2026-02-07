@extends('layouts.freelance')

@section('title', 'Pesanan Baru - Kuotaumroh.id')

@section('content')
  <div x-data="orderApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <!-- Page Header -->
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral) : url('/dash/freelance') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Pesanan Baru</h1>
            <p class="text-muted-foreground mt-2">Buat pesanan kuota umroh baru</p>
          </div>
        </div>
      </div>

      <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Form (2/3) -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Agent Selection Card -->
          <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
              <h3 class="text-lg font-semibold">Pilih Travel Agent</h3>
            </div>
            <div class="p-6">
              <div class="space-y-2">
                <label for="agent-select" class="text-sm font-medium">Travel Agent</label>
                <select 
                  id="agent-select" 
                  x-model="selectedAgentId"
                  class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                >
                  <option value="">-- Pilih Travel Agent --</option>
                  @foreach($agents ?? [] as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->nama_travel }}</option>
                  @endforeach
                </select>
                <p class="text-xs text-muted-foreground">
                  Pilih travel agent terlebih dahulu sebelum membuat pesanan
                </p>
              </div>
            </div>
          </div>

          <!-- Input Method Card -->
          <div x-show="selectedAgentId" class="rounded-lg border bg-white shadow-sm">
            <div class="p-6 border-b">
              <h3 class="text-lg font-semibold">Pilih Metode Input</h3>
            </div>
            <div class="p-6">
              <!-- Tab Buttons -->
              <div class="grid grid-cols-2 gap-1 p-1 bg-muted rounded-lg mb-6">
                <button
                  @click="mode = 'bulk'"
                  :class="mode === 'bulk' ? 'bg-white shadow-sm' : 'hover:bg-white/50'"
                  class="py-2 px-4 rounded-md text-sm font-medium transition-all"
                >
                  Input Massal
                </button>
                <button
                  @click="mode = 'individual'"
                  :class="mode === 'individual' ? 'bg-white shadow-sm' : 'hover:bg-white/50'"
                  class="py-2 px-4 rounded-md text-sm font-medium transition-all"
                >
                  Input Individu
                </button>
              </div>

              <!-- Bulk Mode -->
              <div x-show="mode === 'bulk'" class="space-y-4">
                <div class="space-y-2">
                  <div class="flex items-center justify-between">
                    <label for="bulk-input" class="text-sm font-medium">Daftar Nomor</label>
                    <input type="file" @change="handleFileUpload($event)" accept=".csv,.txt" class="hidden" x-ref="fileInput">
                    <button @click="$refs.fileInput.click()" class="inline-flex items-center rounded-md border bg-background h-9 px-3 text-sm hover:bg-muted">
                      <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                      </svg>
                      Upload File
                    </button>
                  </div>
                  <textarea
                    id="bulk-input"
                    x-model="bulkInput"
                    @input="parseBulkNumbers()"
                    aria-label="Masukkan nomor telepon, pisahkan dengan enter atau koma"
                    aria-describedby="bulk-input-help"
                    placeholder="Masukkan nomor telepon, satu per baris atau pisahkan dengan koma..."
                    rows="6"
                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-mono"
                  ></textarea>
                  <p id="bulk-input-help" class="text-xs text-muted-foreground">
                    Format: 08xx-xxxx-xxxx atau 628xxxxxxxxxx
                  </p>
                  <div class="flex items-center gap-4 text-sm">
                    <span class="text-muted-foreground" x-text="parsedNumbers.length + ' nomor terdeteksi'"></span>
                    <template x-if="validCount > 0">
                      <span class="badge badge-primary" x-text="validCount + ' valid'"></span>
                    </template>
                    <template x-if="invalidCount > 0">
                      <span @click="invalidDialogOpen = true" class="badge badge-destructive cursor-pointer flex items-center gap-1">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span x-text="invalidCount + ' tidak valid'"></span>
                      </span>
                    </template>
                  </div>
                </div>

                <!-- Provider Groups Table -->
                <template x-if="Object.keys(providerGroups).length > 0">
                  <div class="rounded-lg border">
                    <div class="p-4 border-b"><h4 class="font-medium">Pilih Paket per Provider</h4></div>
                    <div class="overflow-x-auto">
                      <table class="w-full">
                        <thead>
                          <tr class="border-b">
                            <th class="h-10 px-4 text-left text-sm font-medium text-muted-foreground">Provider</th>
                            <th class="h-10 px-4 text-center text-sm font-medium text-muted-foreground">Jumlah</th>
                            <th class="h-10 px-4 text-left text-sm font-medium text-muted-foreground">Paket</th>
                            <th class="h-10 px-4 text-right text-sm font-medium text-muted-foreground">Harga Jual Jamaah</th>
                            <th class="h-10 px-4 text-right text-sm font-medium text-muted-foreground">Harga Beli Paket</th>
                          </tr>
                        </thead>
                        <tbody>
                          <template x-for="(numbers, provider) in providerGroups" :key="provider">
                            <tr 
                              class="border-b transition-colors"
                              :class="validationError && getUnassignedNumbers(provider).length > 0 ? 'bg-destructive/10 border-l-4 border-l-destructive' : ''"
                            >
                              <td class="p-4 font-medium" x-text="provider"></td>
                              <td class="p-4 text-center" x-text="numbers.length"></td>
                              <td class="p-4">
                                <div class="flex items-start gap-2">
                                  <button
                                    type="button"
                                    @click.stop="openPackagePicker(provider)"
                                    class="flex-1 w-full min-h-[40px] h-auto py-2 px-3 rounded-md border border-input bg-background text-left flex items-center justify-between hover:bg-muted/50 cursor-pointer transition-colors"
                                  >
                                    <div class="flex-1">
                                      <template x-if="!getProviderAssignments(provider).length">
                                        <span class="text-muted-foreground">Pilih paket</span>
                                      </template>
                                      <template x-if="getProviderAssignments(provider).length > 0">
                                        <div class="flex flex-wrap gap-1">
                                          <template x-for="assignment in getProviderAssignments(provider)" :key="assignment.packageId">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary/10 text-primary text-xs rounded-md">
                                              <span x-text="getPackageName(assignment.packageId)"></span>
                                              (<span x-text="assignment.numbers.length"></span>)
                                            </span>
                                          </template>
                                          <template x-if="getUnassignedNumbers(provider).length > 0">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-muted text-muted-foreground text-xs rounded-md">
                                              +<span x-text="getUnassignedNumbers(provider).length"></span> belum dipilih
                                            </span>
                                          </template>
                                        </div>
                                      </template>
                                    </div>
                                    <svg class="h-4 w-4 shrink-0 opacity-50 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                  </button>
                                  <button type="button" @click.stop="openNumberListDialog(provider)" class="p-2 hover:bg-muted rounded-md" title="Edit Nomor">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                  </button>
                                </div>
                              </td>
                              <td class="p-4 text-right">
                                <template x-if="getProviderSellTotal(provider) > 0">
                                  <div class="space-y-0.5">
                                    <div class="font-medium" x-text="formatRupiah(getProviderSellTotal(provider))"></div>
                                    <div class="text-xs text-primary font-medium">
                                      (Profit +<span x-text="formatRupiah(getProviderProfit(provider))"></span>)
                                    </div>
                                  </div>
                                </template>
                                <template x-if="getProviderSellTotal(provider) === 0">
                                  <span>-</span>
                                </template>
                              </td>
                              <td class="p-4 text-right font-medium">
                                <span x-text="getProviderSubtotal(provider) > 0 ? formatRupiah(getProviderSubtotal(provider)) : '-'"></span>
                              </td>
                            </tr>
                          </template>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </template>
              </div>

              <!-- Individual Mode -->
              <div x-show="mode === 'individual'" class="space-y-4">
                <div class="rounded-lg border">
                  <div class="p-4 border-b"><h4 class="font-medium">Daftar Nomor</h4></div>
                  <div class="p-4 space-y-3">
                    <template x-for="(item, index) in individualItems" :key="item.id">
                      <div 
                        class="flex flex-col sm:flex-row sm:items-center gap-3 p-3 rounded-lg transition-colors border sm:border-0"
                        :class="validationError && item.provider && !item.packageId ? 'bg-destructive/10 border-destructive' : 'bg-gray-50 sm:bg-transparent'"
                      >
                        <div class="flex items-center justify-between sm:w-auto w-full flex-shrink-0">
                          <span class="text-sm text-muted-foreground w-6" x-text="(index + 1) + '.'"></span>
                          <button @click="removeIndividualItem(item.id)" :disabled="individualItems.length === 1" class="sm:hidden p-2 hover:bg-muted rounded-md disabled:opacity-50 text-destructive">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                          </button>
                        </div>
                        
                        <div class="relative flex-1 w-full min-w-0">
                          <input
                            type="text"
                            placeholder="Nomor telepon"
                            x-model="item.msisdn"
                            @input="item.provider = detectProviderForItem(item)"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 pr-24 py-2 text-sm font-mono"
                          >
                          <template x-if="item.provider">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 badge badge-secondary text-xs" x-text="item.provider"></span>
                          </template>
                        </div>
                        
                        <button
                          type="button"
                          @click="openIndividualPackagePicker(item)"
                          :disabled="!item.provider"
                          :class="item.provider ? 'hover:bg-muted/50' : 'opacity-50 cursor-not-allowed'"
                          class="flex h-10 w-full sm:w-48 rounded-md border border-input bg-background px-3 py-2 text-sm text-left flex items-center justify-between flex-shrink-0"
                        >
                          <span class="truncate pr-2" :class="item.packageId ? '' : 'text-muted-foreground'" x-text="item.packageId ? getPackageName(item.packageId) : 'Pilih paket'"></span>
                          <svg class="h-4 w-4 opacity-50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                        </button>
                        
                        <button @click="removeIndividualItem(item.id)" :disabled="individualItems.length === 1" class="hidden sm:block p-2 hover:bg-muted rounded-md disabled:opacity-50 flex-shrink-0">
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      </div>
                    </template>
                    <button @click="addIndividualItem()" class="w-full inline-flex items-center justify-center rounded-md border bg-background h-10 px-4 hover:bg-muted">
                      <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                      Tambah Nomor
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Activation Time Card -->
          <template x-if="itemCount > 0">
            <div class="rounded-lg border bg-white shadow-sm">
              <div class="p-6 border-b">
                <h3 class="text-lg font-semibold flex items-center gap-2">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Pilih Waktu Aktivasi
                </h3>
              </div>
              <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <button
                    @click="activationTime = 'now'"
                    :class="activationTime === 'now' ? 'bg-primary text-primary-foreground' : 'border hover:bg-muted'"
                    class="h-12 rounded-md font-medium transition-colors"
                  >
                    AKTIFKAN LANGSUNG
                  </button>
                  <button
                    @click="activationTime = 'scheduled'"
                    :class="activationTime === 'scheduled' ? 'bg-primary text-primary-foreground' : 'border hover:bg-muted'"
                    class="h-12 rounded-md font-medium transition-colors"
                  >
                    PILIH JADWAL AKTIVASI
                  </button>
                </div>

                <template x-if="activationTime === 'scheduled'">
                  <div class="space-y-4">
                    <div class="space-y-2">
                      <label class="text-xs text-muted-foreground uppercase tracking-wide">Pilih Tanggal</label>
                      <input type="date" x-model="scheduledDate" :min="todayDate" class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    </div>
                    <div class="space-y-2">
                      <label class="text-xs text-muted-foreground uppercase tracking-wide">Pilih Waktu (WIB)</label>
                      <input type="time" x-model="scheduledTime" class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                      <div class="flex gap-2 flex-wrap">
                        <template x-for="time in quickTimes" :key="time">
                          <button
                            @click="scheduledTime = time"
                            :class="scheduledTime === time ? 'bg-primary text-primary-foreground' : 'border hover:bg-muted'"
                            class="flex-1 py-2 rounded-md text-sm font-medium transition-colors"
                            x-text="time"
                          ></button>
                        </template>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </template>

          <!-- Payment Method Card -->
          <template x-if="itemCount > 0">
            <div class="rounded-lg border bg-white shadow-sm">
              <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Metode Pembayaran</h3>
              </div>
              <div class="p-6 space-y-3">
                <template x-for="method in paymentMethods" :key="method.id">
                  <label
                    :class="paymentMethod === method.id ? 'border-primary bg-primary/5' : 'hover:bg-muted/50'"
                    class="flex items-center gap-4 p-4 border rounded-lg cursor-pointer transition-colors"
                  >
                    <input type="radio" :value="method.id" x-model="paymentMethod" class="h-4 w-4 text-primary">
                    <div class="rounded-full bg-muted p-2">
                      <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="method.icon" />
                      </svg>
                    </div>
                    <div class="flex-1">
                      <p class="font-medium" x-text="method.name"></p>
                      <p class="text-sm text-muted-foreground" x-text="method.description"></p>
                    </div>
                    <template x-if="method.id === 'wallet'">
                      <div class="text-right">
                        <p class="text-sm font-medium" x-text="formatRupiah(walletBalance)"></p>
                        <template x-if="walletBalance < totalWithFee">
                          <p class="text-xs text-destructive">Saldo tidak cukup</p>
                        </template>
                      </div>
                    </template>
                  </label>
                </template>
              </div>
            </div>
          </template>
        </div>

        <!-- Order Summary Sidebar (1/3) -->
        <div class="lg:col-span-1">
          <div class="rounded-lg border bg-white shadow-sm sticky top-24">
            <div class="p-6 border-b">
              <h3 class="text-lg font-semibold flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Ringkasan Pembayaran
              </h3>
            </div>
            <div class="p-6 space-y-4">
              <!-- Batch Info -->
              <div class="space-y-2 pb-4 border-b">
                <div class="flex justify-between items-center text-sm">
                  <span class="text-muted-foreground">Batch ID</span>
                  <span class="font-mono text-xs" x-text="batchId"></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-muted-foreground">Nama Batch</span>
                  <div class="flex items-center gap-2">
                    <span class="font-medium" x-text="batchName"></span>
                    <button 
                      @click="editBatchNameDialog = true"
                      class="text-primary hover:text-primary/80 transition-colors"
                      title="Edit nama batch"
                    >
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
              
              <!-- Price Breakdown -->
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-muted-foreground">Subtotal (<span x-text="itemCount"></span> nomor)</span>
                  <span x-text="formatRupiah(subtotal)"></span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-muted-foreground">Biaya Platform</span>
                  <span x-text="formatRupiah(platformFee)"></span>
                </div>
              </div>
              <div class="border-t pt-4">
                <div class="flex justify-between">
                  <span class="font-medium">Total Pembayaran</span>
                  <span class="text-xl font-bold" x-text="formatRupiah(totalWithFee)"></span>
                </div>
              </div>
              <div class="rounded-md border border-dashed bg-muted/30 p-3 text-sm">
                <div class="flex justify-between">
                  <span class="text-muted-foreground">Harga Rekomendasi</span>
                  <span class="font-medium" x-text="formatRupiah(subtotal + profit)"></span>
                </div>
                <div class="flex justify-between mt-1">
                  <span class="text-muted-foreground">Keuntungan</span>
                  <span class="font-medium text-primary">+<span x-text="formatRupiah(profit)"></span></span>
                </div>
                <p class="mt-2 text-xs text-muted-foreground">Informasi ini bukan bagian dari pembayaran.</p>
              </div>
              <button
                @click="handleConfirmOrder()"
                :disabled="itemCount === 0 || subtotal === 0 || isProcessing"
                :class="(itemCount > 0 && subtotal > 0) ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'"
                class="w-full h-12 rounded-md font-medium transition-colors flex items-center justify-center gap-2"
              >
                <template x-if="isProcessing">
                  <span>Memproses...</span>
                </template>
                <template x-if="!isProcessing">
                  <span>
                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Konfirmasi Pesanan
                  </span>
                </template>
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Loading Overlay -->
    <div x-show="packagesLoading" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
      <div class="bg-white rounded-lg p-8 shadow-lg flex flex-col items-center gap-4">
        <svg class="animate-spin h-12 w-12 text-primary" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-sm font-medium">Memuat data paket...</p>
      </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toastVisible" x-transition class="toast">
      <div class="font-semibold mb-1" x-text="toastTitle"></div>
      <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>

    {{-- All Dialogs Below --}}
    @include('freelance.partials.order-dialogs')
  </div>
@endsection

@push('scripts')
  <!-- Store Config for Freelance Order -->
  <script>
    const STORE_CONFIG = {
      freelance_id: '{{ $user->id ?? "" }}',
      link_referal: '{{ $linkReferral ?? "" }}',
      portal_type: 'freelance',
    };
    console.log('[STORE_CONFIG] Freelance Order Config:', STORE_CONFIG);
  </script>
  @include('freelance.partials.order-scripts')
@endpush

@extends('agent.layout')

@section('title', 'Dashboard - Menunggu Persetujuan')

@section('content')
  <div x-data="pendingDashboardApp()">
    <main class="container mx-auto py-10 animate-fade-in px-4">
      
      {{-- Status Badge di Pojok Kanan --}}
      <div class="fixed top-24 right-4 z-50 animate-slide-in-right">
        <div class="flex items-center gap-2 rounded-full bg-yellow-50 border-2 border-yellow-200 px-4 py-2 shadow-lg">
          <div class="h-2 w-2 rounded-full bg-yellow-400 animate-pulse"></div>
          <span class="text-xs font-semibold text-yellow-700">Menunggu Proses Verifikasi</span>
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">
        <!-- Box 1: Profit Bulan Ini - LOCKED -->
        <div>
          <div class="relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm h-full opacity-60">
            <div class="pointer-events-none absolute right-0 top-0 h-40 w-40 -translate-y-1/3 translate-x-1/3 rounded-full bg-primary/5"></div>
            
            {{-- Lock Overlay --}}
            <div class="absolute inset-0 z-20 flex items-center justify-center bg-black/5 backdrop-blur-[2px]">
              <div class="rounded-full bg-muted p-4 shadow-lg">
                <svg class="h-8 w-8 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
            </div>

            <div class="relative z-10 flex flex-row items-center justify-between p-6 pb-4">
              <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Profit Bulan Ini</h3>
              <div class="rounded-lg p-2 bg-primary/10 text-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
            </div>
            <div class="relative z-10 p-6 pt-0">
              <div class="text-4xl font-extrabold text-slate-300 tracking-tight">Rp 0</div>
              <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                <div>
                  <p class="text-xs font-bold uppercase text-slate-400">Total akumulasi</p>
                  <p class="text-xl font-extrabold text-slate-300">Rp 0</p>
                </div>
                <div class="flex items-end gap-1 opacity-70">
                  <div class="h-4 w-2 rounded-t-sm bg-slate-300"></div>
                  <div class="h-6 w-2 rounded-t-sm bg-slate-300"></div>
                  <div class="h-5 w-2 rounded-t-sm bg-slate-300"></div>
                  <div class="h-8 w-2 rounded-t-sm bg-slate-300"></div>
                  <div class="h-10 w-2 rounded-t-sm bg-slate-300"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Link Toko UMROH - LOCKED -->
        <div>
          <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full opacity-60">
            <div class="flex flex-row items-center justify-between p-6 pb-4">
              <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Link Toko: Kuotaumroh.id</h3>
            </div>
            <div class="p-6 pt-0 relative">
              {{-- Lock Overlay --}}
              <div class="absolute inset-0 z-20 flex items-center justify-center bg-black/5 backdrop-blur-[1px] rounded-b-2xl">
                <div class="rounded-full bg-muted p-3 shadow-lg">
                  <svg class="h-6 w-6 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
              </div>

              <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="sm:w-24 sm:shrink-0">
                  <div class="w-full aspect-square rounded-lg border bg-slate-100 flex items-center justify-center p-2">
                    <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                </div>
                <div class="space-y-2 sm:flex-1">
                  <label class="text-xs font-medium text-muted-foreground">Link Toko Umroh</label>
                  <div class="flex flex-col gap-2 sm:flex-row">
                    <input type="text" readonly value="Menunggu Verifikasi..." class="flex h-9 w-full min-w-0 rounded-md border border-input bg-slate-100 px-3 py-2 text-xs text-slate-400 cursor-not-allowed">
                    <button disabled class="h-9 w-full px-3 bg-slate-300 text-slate-500 rounded-md text-xs font-medium cursor-not-allowed sm:w-auto">
                      Salin
                    </button>
                  </div>
                  <p class="text-xs text-yellow-600 font-medium">🔒 Tersedia setelah verifikasi</p>
                  <p class="text-xs text-amber-600 font-medium">🔒 Tersedia setelah verifikasi</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Link Toko LEISURE - LOCKED -->
        <div>
          <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full opacity-60">
            <div class="flex flex-row items-center justify-between p-6 pb-4">
              <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Link Toko: Roamer.id</h3>
            </div>
            <div class="p-6 pt-0 relative">
              {{-- Lock Overlay --}}
              <div class="absolute inset-0 z-20 flex items-center justify-center bg-black/5 backdrop-blur-[1px] rounded-b-2xl">
                <div class="rounded-full bg-muted p-3 shadow-lg">
                  <svg class="h-6 w-6 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
              </div>

              <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="sm:w-24 sm:shrink-0">
                  <div class="w-full aspect-square rounded-lg border bg-slate-100 flex items-center justify-center p-2">
                    <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                </div>
                <div class="space-y-2 sm:flex-1">
                  <label class="text-xs font-medium text-muted-foreground">Link Toko Leisure</label>
                  <div class="flex flex-col gap-2 sm:flex-row">
                    <input type="text" readonly value="Menunggu Verifikasi..." class="flex h-9 w-full min-w-0 rounded-md border border-input bg-slate-100 px-3 py-2 text-xs text-slate-400 cursor-not-allowed">
                    <button disabled class="h-9 w-full px-3 bg-slate-300 text-slate-500 rounded-md text-xs font-medium cursor-not-allowed sm:w-auto">
                      Salin
                    </button>
                  </div>
                  <p class="text-xs text-yellow-600 font-medium">🔒 Tersedia setelah verifikasi</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-8">
        <div class="flex items-center gap-4">
          <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Menu Utama</h2>
          <div class="h-px flex-1 bg-slate-200"></div>
        </div>
        <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-5">
          <template x-for="item in menuItems" :key="item.id">
            <div class="cursor-not-allowed">
              <div class="group flex h-48 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm opacity-50 relative">
                {{-- Lock Icon Overlay --}}
                <div class="absolute top-2 right-2 z-10">
                  <div class="rounded-full bg-muted p-1.5">
                    <svg class="h-4 w-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                </div>
                
                <div class="flex flex-col items-center justify-center gap-3 p-6 text-center">
                  <img :src="imageBase + '/' + item.icon + '.png'" :alt="item.title" class="h-24 w-24 object-contain grayscale" onerror="this.src = imageBase + '/LOGO.png'" />
                  <h3 class="text-xs font-bold uppercase tracking-wide leading-tight text-slate-400" x-text="item.title"></h3>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      {{-- Info Section --}}
      <div class="mt-12 rounded-lg border bg-white shadow-sm p-6">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="rounded-full bg-blue-50 p-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold mb-2">📋 Status Verifikasi</h3>
            <p class="text-sm text-muted-foreground">
              Akun Anda sedang dalam <strong>proses verifikasi</strong> oleh tim kami. 
             
            </p>
          </div>
        </div>
      </div>

    </main>
  </div>
@endsection

@section('scripts')
  <script>
    function pendingDashboardApp() {
      return {
        imageBase: @json(asset('images')),
        menuItems: [
          { id: 'new-order', title: 'Pesanan Baru', icon: 'order' },
          { id: 'history', title: 'Riwayat Transaksi', icon: 'history' },
          { id: 'wallet', title: 'Dompet Saya', icon: 'wallet' },
          { id: 'referrals', title: 'Program Referral', icon: 'referral' },
          { id: 'catalog', title: 'Katalog Harga', icon: 'catalog' },
        ],
        init() {
          console.log('Pending Dashboard initialized');
        }
      };
    }
  </script>
@endsection

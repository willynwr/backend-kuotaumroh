@extends('agent.layout')

@section('title', 'Dashboard - Menunggu Persetujuan')

@section('content')
  <div x-data="pendingDashboardApp()">
    <main class="container mx-auto py-10 animate-fade-in px-4">
      
      {{-- Notifikasi Status Pending --}}
      <div class="mb-6 rounded-lg border-2 border-amber-400 bg-gradient-to-r from-amber-50 to-orange-50 p-6 shadow-lg">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="rounded-full bg-amber-100 p-3">
              <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-amber-900 mb-2">🎉 Selamat Datang di Kuotaumroh.id!</h3>
            <p class="text-amber-800 mb-3">
              Akun Anda sedang dalam <strong>proses verifikasi</strong> oleh tim kami. Terima kasih atas kesabaran Anda!
            </p>
            <div class="rounded-lg bg-white/80 p-4 mb-3">
              <p class="text-sm font-semibold text-amber-900 mb-2">📋 Status Pendaftaran:</p>
              <div class="flex items-center gap-2 mb-2">
                <div class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></div>
                <span class="text-sm text-amber-800">Menunggu Persetujuan Admin</span>
              </div>
              <p class="text-xs text-amber-700 mt-2">
                ⏱️ Estimasi waktu verifikasi: <strong>1-2 hari kerja</strong>
              </p>
            </div>
            <div class="rounded-lg bg-amber-100/50 p-4">
              <p class="text-sm font-semibold text-amber-900 mb-2">🔒 Fitur yang Akan Tersedia Setelah Disetujui:</p>
              <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-amber-800">
                <li class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  Pesanan Baru
                </li>
                <li class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Riwayat Transaksi
                </li>
                <li class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                  Dompet & Penarikan
                </li>
                <li class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                  </svg>
                  Link Referral
                </li>
                <li class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
                  Katalog Produk
                </li>
                <li class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  Program Referral
                </li>
              </ul>
            </div>
            <div class="mt-4 flex flex-col sm:flex-row gap-3">
              <a href="mailto:support@kuotaumroh.id" class="inline-flex items-center justify-center rounded-md bg-amber-600 text-white hover:bg-amber-700 px-4 py-2 text-sm font-medium transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Hubungi Support
              </a>
              <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center justify-center rounded-md border border-amber-600 text-amber-700 hover:bg-amber-50 px-4 py-2 text-sm font-medium transition-colors">
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp Support
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- Info Akun --}}
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-blue-100 p-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Nama PIC</p>
              <h3 class="text-lg font-bold">{{ $user->nama_pic ?? 'N/A' }}</h3>
            </div>
          </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-green-100 p-3">
              <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Email</p>
              <h3 class="text-sm font-semibold">{{ $user->email ?? 'N/A' }}</h3>
            </div>
          </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-purple-100 p-3">
              <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Lokasi</p>
              <h3 class="text-sm font-semibold">{{ $user->kabupaten_kota ?? 'N/A' }}</h3>
            </div>
          </div>
        </div>
      </div>

      {{-- FAQ Section --}}
      <div class="rounded-lg border bg-white shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">❓ Pertanyaan yang Sering Diajukan</h3>
        <div class="space-y-4">
          <div class="rounded-lg border border-slate-200 p-4">
            <h4 class="font-semibold text-slate-900 mb-2">Berapa lama proses verifikasi?</h4>
            <p class="text-sm text-slate-600">Proses verifikasi biasanya memakan waktu 1-2 hari kerja. Tim kami akan menghubungi Anda melalui email atau WhatsApp jika ada informasi tambahan yang diperlukan.</p>
          </div>
          <div class="rounded-lg border border-slate-200 p-4">
            <h4 class="font-semibold text-slate-900 mb-2">Apa yang terjadi setelah akun disetujui?</h4>
            <p class="text-sm text-slate-600">Setelah akun Anda disetujui, Anda akan mendapatkan akses penuh ke semua fitur termasuk link referral, pembuatan pesanan, dan akses ke dompet digital.</p>
          </div>
          <div class="rounded-lg border border-slate-200 p-4">
            <h4 class="font-semibold text-slate-900 mb-2">Bagaimana cara menghubungi support?</h4>
            <p class="text-sm text-slate-600">Anda bisa menghubungi kami melalui email di support@kuotaumroh.id atau WhatsApp di nomor yang tertera di atas.</p>
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
        init() {
          console.log('Pending Dashboard initialized');
        }
      };
    }
  </script>
@endsection

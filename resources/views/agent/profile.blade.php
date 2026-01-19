@extends('agent.layout')

@section('title', 'Profil - Kuotaumroh.id')

@section('head')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@section('content')
  <div x-data="profileApp()">
    <main class="container py-8">
      <div class="max-w-4xl mx-auto">
        <div class="mb-8">
          <h1 class="text-3xl font-bold">Profil Saya</h1>
          <p class="text-muted-foreground mt-2">Informasi akun dan data agent Anda</p>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
          <div class="p-6 space-y-6">
            <div class="space-y-4">
              <h2 class="text-lg font-semibold border-b pb-2">Informasi Pribadi</h2>
              <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Email</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.email || '-'"></span></div>
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">No. HP</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.phone ? '+62' + user.phone : '-'"></span></div>
                </div>
                <div class="space-y-2 md:col-span-2">
                  <label class="text-sm font-medium text-muted-foreground">Nama Lengkap</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.full_name || user.name || '-'"></span></div>
                </div>
              </div>
            </div>

            <div class="space-y-4">
              <h2 class="text-lg font-semibold border-b pb-2">Informasi Agent</h2>
              <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Jenis Agent</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.user_type || '-'"></span></div>
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Nama Travel</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.travel_name || '-'"></span></div>
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Jenis Travel</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.travel_type || '-'"></span></div>
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Total Traveller per Bulan</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.travel_member || '-'"></span></div>
                </div>
              </div>
            </div>

            <div class="space-y-4">
              <h2 class="text-lg font-semibold border-b pb-2">Informasi Alamat</h2>
              <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Provinsi</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.province || '-'"></span></div>
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Kota/Kabupaten</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.city || '-'"></span></div>
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">Kecamatan</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.subdistrict || '-'"></span></div>
                </div>
                <div class="space-y-2">
                  <label class="text-sm font-medium text-muted-foreground">RT/RW</label>
                  <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.rt && user.rw ? user.rt + '/' + user.rw : '-'"></span></div>
                </div>
                <div class="space-y-2 md:col-span-2">
                  <label class="text-sm font-medium text-muted-foreground">Alamat Lengkap</label>
                  <div class="flex min-h-[80px] w-full rounded-md border border-input bg-muted px-3 py-2 text-sm"><span x-text="user.address || '-'"></span></div>
                </div>
                <div class="space-y-2 md:col-span-2" x-show="user.latitude && user.longitude">
                  <label class="text-sm font-medium text-muted-foreground">Lokasi Peta</label>
                  <div id="map" class="w-full h-[300px] rounded-md border border-input z-0"></div>
                  <div class="flex items-center gap-2 mt-2 text-xs text-muted-foreground">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span x-text="user.latitude + ', ' + user.longitude"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 rounded-lg border border-muted bg-muted/50 p-4">
          <div class="flex gap-3">
            <svg class="h-5 w-5 text-muted-foreground flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div class="text-sm text-muted-foreground">
              <p class="font-medium">Informasi Profil</p>
              <p class="mt-1">Data profil Anda saat ini tidak dapat diubah melalui halaman ini. Jika Anda memerlukan perubahan data, silakan hubungi administrator.</p>
            </div>
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
    function profileApp() {
      return {
        mapInstance: null,
        user: {
          name: 'Ahmad Fauzi',
          full_name: 'Ahmad Fauzi',
          email: 'ahmad.fauzi@email.com',
          phone: '81234567890',
          user_type: 'Referral',
          travel_name: 'Travel Umroh Demo',
          travel_type: 'PPIU',
          travel_member: 50,
          province: 'Jawa Barat',
          city: 'Bandung',
          subdistrict: 'Coblong',
          rt: '01',
          rw: '02',
          address: 'Jl. Contoh No. 1',
          latitude: '-6.884',
          longitude: '107.609',
        },
        toastVisible: false,
        toastTitle: '',
        toastMessage: '',
        init() {
          this.$nextTick(() => {
            if (this.user.latitude && this.user.longitude) this.initMap();
          });
        },
        initMap() {
          if (!window.L) return;
          const lat = parseFloat(this.user.latitude);
          const lng = parseFloat(this.user.longitude);
          if (Number.isNaN(lat) || Number.isNaN(lng)) return;
          if (this.mapInstance) return;
          this.mapInstance = L.map('map').setView([lat, lng], 15);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(this.mapInstance);
          L.marker([lat, lng]).addTo(this.mapInstance).bindPopup(this.user.address || 'Lokasi Anda').openPopup();
          setTimeout(() => { this.mapInstance.invalidateSize(); }, 100);
        },
        showToast(title, message) {
          this.toastTitle = title;
          this.toastMessage = message;
          this.toastVisible = true;
          setTimeout(() => { this.toastVisible = false; }, 3000);
        },
      };
    }
  </script>
@endsection

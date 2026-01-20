@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div x-data="profilePage()">
  @include('components.admin.header')

  <main class="container mx-auto py-6 px-4">
    <div class="max-w-4xl mx-auto">
      <div class="mb-8 flex items-start gap-4">
        <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h1 class="text-3xl font-bold">Profil Admin</h1>
          <p class="text-muted-foreground mt-1">Informasi akun administrator</p>
        </div>
      </div>

      <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="p-6 space-y-6">
          <div class="space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">Informasi Pribadi</h2>
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground">Nama</label>
                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                  {{ Auth::user()->name ?? '-' }}
                </div>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground">Email</label>
                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                  {{ Auth::user()->email ?? '-' }}
                </div>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground">Role</label>
                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                  <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">Administrator</span>
                </div>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground">Status</label>
                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                  <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Aktif</span>
                </div>
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">Informasi Sistem</h2>
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground">Akun Dibuat</label>
                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                  {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : '-' }}
                </div>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground">Last Update</label>
                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                  {{ Auth::user()->updated_at ? Auth::user()->updated_at->format('d M Y, H:i') : '-' }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 rounded-lg border border-muted bg-muted/50 p-4">
        <div class="flex gap-3">
          <svg class="h-5 w-5 text-muted-foreground flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="text-sm text-muted-foreground">
            <p class="font-medium">Informasi Profil</p>
            <p class="mt-1">Untuk perubahan kredensial atau izin akses, hubungi sistem administrator.</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script>
  function profilePage() {
    return {
      init() {}
    }
  }
</script>
@endpush

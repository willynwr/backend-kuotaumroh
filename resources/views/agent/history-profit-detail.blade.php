@extends('agent.layout')

@section('title', 'Detail Profit - Kuotaumroh.id')

@section('content')
  <div x-data="detailApp()">
    <main class="container mx-auto py-6 animate-fade-in px-4">
      <div class="mb-6">
        <div class="flex items-start gap-4">
          <a href="{{ isset($linkReferral) ? url('/dash/' . $linkReferral . '/history-profit') : route('agent.history-profit') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-md border bg-white hover:bg-muted transition-colors" aria-label="Kembali">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </a>
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Detail Transaksi</h1>
            <p class="text-muted-foreground mt-2" x-text="formatMonth('{{ $month }}')"></p>
          </div>
        </div>
      </div>

      <!-- Summary Card -->
      <div class="grid gap-4 md:grid-cols-2 mb-6">
        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-blue-100 p-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Total Transaksi</p>
              <h3 class="text-2xl font-bold">{{ $totalTransactions }}</h3>
            </div>
          </div>
        </div>

        <div class="rounded-lg border bg-white shadow-sm p-6">
          <div class="flex items-center gap-4">
            <div class="rounded-full bg-green-100 p-3">
              <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
              <p class="text-sm font-medium text-muted-foreground">Total Profit</p>
              <h3 class="text-2xl font-bold text-primary" x-text="formatRupiah({{ $totalProfit }})"></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Table Detail -->
      <div class="rounded-lg border bg-white shadow-sm">
        <div class="p-6">
          <h3 class="text-lg font-semibold mb-4">Detail Transaksi</h3>
          <div class="overflow-auto">
            <table class="w-full caption-bottom text-sm">
              <thead class="border-b">
                <tr class="border-b transition-colors hover:bg-muted/50">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Produk</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Profit</th>
                </tr>
              </thead>
              <tbody>
                @forelse($details as $detail)
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle">{{ $detail['date'] }}</td>
                    <td class="p-4 align-middle">{{ $detail['product_name'] }}</td>
                    <td class="p-4 align-middle text-right font-semibold text-primary" x-text="formatRupiah({{ $detail['profit'] }})"></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="p-8 text-center text-muted-foreground">
                      <div class="flex flex-col items-center gap-2">
                        <svg class="h-12 w-12 text-muted-foreground/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p>Tidak ada detail transaksi</p>
                      </div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
              @if($details->count() > 0)
              <tfoot class="border-t bg-muted/30">
                <tr>
                  <td colspan="2" class="p-4 align-middle font-semibold">Total</td>
                  <td class="p-4 align-middle text-right font-bold text-primary" x-text="formatRupiah({{ $totalProfit }})"></td>
                </tr>
              </tfoot>
              @endif
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
@endsection

@section('scripts')
  <script>
    function detailApp() {
      return {
        formatRupiah(value) {
          const n = Number(value || 0);
          return n.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        },
        
        formatMonth(monthString) {
          if (!monthString) return '';
          const [year, month] = monthString.split('-');
          const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          return `${months[parseInt(month) - 1]} ${year}`;
        }
      };
    }
  </script>
@endsection

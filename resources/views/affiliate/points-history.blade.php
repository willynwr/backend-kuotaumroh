@extends('layouts.affiliate')

@section('title', 'Riwayat Poin - Kuotaumroh.id')

@section('content')
<div x-data="pointsHistoryPage()">
    <!-- Shared Header -->
    <!-- Header handled by layout -->

    <main class="container mx-auto py-10 animate-fade-in px-4">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold tracking-tight">Riwayat Poin</h1>
            <p class="text-muted-foreground mt-2">Lihat semua aktivitas poin Anda</p>
        </div>

        <!-- Table Card -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6">
                <!-- Tabs & Filters -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <div class="flex gap-2 border-b w-full sm:w-auto">
                        <button @click="typeFilter = 'all'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="typeFilter === 'all' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground'">Semua</button>
                        <button @click="typeFilter = 'earned'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="typeFilter === 'earned' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground'">Diperoleh</button>
                        <button @click="typeFilter = 'spent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="typeFilter === 'spent' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground'">Digunakan</button>
                    </div>
                    <div class="relative w-full sm:w-auto">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari deskripsi" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Deskripsi</th>
                                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Tipe</th>
                                <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in filteredHistory" :key="item.id">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle text-muted-foreground" x-text="formatDate(item.timestamp)"></td>
                                    <td class="p-4 align-middle font-medium" x-text="item.description"></td>
                                    <td class="p-4 align-middle text-center">
                                        <span class="badge" :class="item.type === 'earned' ? 'badge-success' : 'badge-secondary'" x-text="item.type === 'earned' ? 'Dapat' : 'Pakai'"></span>
                                    </td>
                                    <td class="p-4 align-middle text-right font-semibold" :class="item.type === 'earned' ? 'text-primary' : 'text-destructive'" x-text="(item.type === 'earned' ? '+' : '-') + item.amount"></td>
                                </tr>
                            </template>
                            <template x-if="filteredHistory.length === 0">
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-muted-foreground">Tidak ada riwayat ditemukan</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast -->
    <div x-show="toastVisible" x-transition class="toast">
        <div class="font-semibold mb-1" x-text="toastTitle"></div>
        <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function pointsHistoryPage() {
        return {
            search: '',
            typeFilter: 'all',
            history: [],
            toastVisible: false,
            toastTitle: '',
            toastMessage: '',

            get filteredHistory() {
                return this.history.filter(h => {
                    const matchSearch = !this.search || h.description.toLowerCase().includes(this.search.toLowerCase());
                    const matchType = this.typeFilter === 'all' || h.type === this.typeFilter;
                    return matchSearch && matchType;
                });
            },

            formatDate(dateStr) {
                return new Date(dateStr).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            },

            init() {
                if (!requireRole(['freelance', 'affiliate'], true)) return;
                syncFreelanceIdFromUrl();
                // 

                this.history = [{
                        id: 1,
                        description: 'Agent Signup: Ahmad Fauzi',
                        amount: 50,
                        type: 'earned',
                        timestamp: '2026-01-10T10:30:00Z'
                    },
                    {
                        id: 2,
                        description: 'Agent Signup: Siti Rahmah',
                        amount: 50,
                        type: 'earned',
                        timestamp: '2026-01-08T14:15:00Z'
                    },
                    {
                        id: 3,
                        description: 'Klaim: Voucher OVO Rp 100.000',
                        amount: 500,
                        type: 'spent',
                        timestamp: '2026-01-08T09:00:00Z'
                    },
                    {
                        id: 4,
                        description: 'Agent Signup: Budi Santoso',
                        amount: 50,
                        type: 'earned',
                        timestamp: '2026-01-05T09:00:00Z'
                    },
                    {
                        id: 5,
                        description: 'Bonus Aktivitas',
                        amount: 100,
                        type: 'earned',
                        timestamp: '2026-01-01T00:00:00Z'
                    },
                ];
            }
        }
    }
</script>
@endpush

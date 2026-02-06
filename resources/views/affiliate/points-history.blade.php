@extends('layouts.affiliate')

@section('title', 'Riwayat Fee - Kuotaumroh.id')

@section('content')
<div x-data="pointsHistoryPage()">
    <!-- Shared Header -->
    <!-- Header handled by layout -->

    <main class="container mx-auto py-10 animate-fade-in px-4">
        <!-- Page Header -->
        <div class="mb-6 flex items-start gap-4">
            <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Riwayat Fee</h1>
                <p class="text-muted-foreground mt-1">Lihat semua aktivitas fee Anda</p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6">
                <!-- Tabs Navigation -->
                <div class="flex gap-2 border-b mb-6">
                    <button @click="activeTab = 'poin'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="activeTab === 'poin' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground'">Poin</button>
                    <button @click="activeTab = 'fee-agent'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="activeTab === 'fee-agent' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground'">Fee Agent</button>
                </div>

                <!-- Tab Content: Poin -->
                <div x-show="activeTab === 'poin'" x-cloak>
                    <!-- Total Poin Summary -->
                    <!-- <div class="mb-6 p-6 rounded-lg border bg-gradient-to-br from-primary/5 to-primary/10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-muted-foreground mb-1">Total Poin Tersedia</p>
                                <p class="text-3xl font-bold text-primary" x-text="totalPoints"></p>
                            </div>
                            <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div> -->

                    <!-- Filters -->
                    <div class="flex justify-end mb-6">
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
                                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in filteredHistory" :key="item.id">
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <td class="p-4 align-middle text-muted-foreground" x-text="formatDate(item.timestamp)"></td>
                                        <td class="p-4 align-middle font-medium" x-text="item.description"></td>
                                        <td class="p-4 align-middle text-center">
                                            <span class="badge" :class="item.type === 'earned' ? 'badge-success' : 'badge-secondary'" x-text="item.type === 'earned' ? 'Dapat' : 'Pakai'"></span>
                                        </td>
                                        <td class="p-4 align-middle text-right font-semibold" :class="item.type === 'earned' ? 'text-primary' : 'text-destructive'" x-text="(item.type === 'earned' ? '+' : '-') + item.amount"></td>
                                        <td class="p-4 align-middle text-right font-bold text-primary" x-text="getRunningTotal(index)"></td>
                                    </tr>
                                </template>
                                <template x-if="filteredHistory.length === 0">
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-muted-foreground">Belum ada riwayat poin</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Content: Fee Agent -->
                <div x-show="activeTab === 'fee-agent'" x-cloak>
                    <!-- Filters -->
                    <div class="flex justify-end mb-6">
                        <div class="relative w-full sm:w-auto">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" x-model="agentSearch" placeholder="Cari nama agent" class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Agent</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">No. HP</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Travel</th>
                                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Total Fee</th>
                                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="agent in filteredAgents" :key="agent.id">
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <td class="p-4 align-middle font-medium" x-text="agent.name"></td>
                                        <td class="p-4 align-middle text-muted-foreground" x-text="agent.phone"></td>
                                        <td class="p-4 align-middle" x-text="agent.travelName"></td>
                                        <td class="p-4 align-middle text-right font-semibold text-primary" x-text="formatRupiah(agent.totalFee)"></td>
                                        <td class="p-4 align-middle text-center">
                                            <button @click="viewAgentDetail(agent)" class="inline-flex items-center justify-center rounded-md border bg-transparent h-8 px-3 hover:bg-muted transition-colors text-sm">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="filteredAgents.length === 0">
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-muted-foreground">Tidak ada agent ditemukan</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Agent Detail Modal -->
    <div x-show="selectedAgent" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeAgentDetail()">
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-3xl max-h-[90vh] overflow-hidden animate-fade-in mx-4">
            <div class="p-6 border-b">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Detail Fee Agent</h2>
                        <p class="text-sm text-muted-foreground mt-1" x-show="selectedAgent">
                            <span x-text="selectedAgent?.name"></span> - <span x-text="selectedAgent?.travelName"></span>
                        </p>
                    </div>
                    <button @click="closeAgentDetail()" class="rounded-md p-2 hover:bg-muted transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]" x-show="selectedAgent">
                <div class="mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-muted-foreground">Total Fee</p>
                            <p class="font-semibold text-lg text-primary" x-text="selectedAgent ? formatRupiah(selectedAgent.totalFee) : ''"></p>
                        </div>
                        <div>
                            <p class="text-muted-foreground">No. HP</p>
                            <p class="font-medium" x-text="selectedAgent?.phone"></p>
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4">
                    <p class="font-medium mb-3">Rincian Fee Per Hari</p>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground text-sm">Tanggal</th>
                                    <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground text-sm">Deskripsi</th>
                                    <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground text-sm">Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(detail, idx) in selectedAgent?.feeDetails || []" :key="idx">
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <td class="p-3 align-middle text-sm text-muted-foreground" x-text="formatDate(detail.date)"></td>
                                        <td class="p-3 align-middle text-sm" x-text="detail.description"></td>
                                        <td class="p-3 align-middle text-right text-sm font-medium text-primary" x-text="formatRupiah(detail.amount)"></td>
                                    </tr>
                                </template>
                                <template x-if="!selectedAgent?.feeDetails || selectedAgent.feeDetails.length === 0">
                                    <tr>
                                        <td colspan="3" class="p-6 text-center text-muted-foreground text-sm">Belum ada rincian fee</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            activeTab: 'poin',
            search: '',
            agentSearch: '',
            history: [],
            agents: [],
            selectedAgent: null,
            toastVisible: false,
            toastTitle: '',
            toastMessage: '',

            get filteredHistory() {
                return this.history.filter(h => {
                    const matchSearch = !this.search || h.description.toLowerCase().includes(this.search.toLowerCase());
                    return matchSearch;
                });
            },

            get filteredAgents() {
                return this.agents.filter(agent => {
                    const matchSearch = !this.agentSearch || 
                        agent.name.toLowerCase().includes(this.agentSearch.toLowerCase()) ||
                        agent.travelName.toLowerCase().includes(this.agentSearch.toLowerCase());
                    return matchSearch;
                });
            },

            get totalPoints() {
                return this.history.reduce((total, item) => {
                    if (item.type === 'earned') {
                        return total + item.amount;
                    } else {
                        return total - item.amount;
                    }
                }, 0);
            },

            formatDate(dateStr) {
                return new Date(dateStr).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            },

            formatRupiah(amount) {
                return `Rp ${amount.toLocaleString('id-ID')}`;
            },

            viewAgentDetail(agent) {
                this.selectedAgent = agent;
            },

            closeAgentDetail() {
                this.selectedAgent = null;
            },

            getRunningTotal(index) {
                let total = 0;
                for (let i = 0; i <= index; i++) {
                    const item = this.filteredHistory[i];
                    if (item.type === 'earned') {
                        total += item.amount;
                    } else {
                        total -= item.amount;
                    }
                }
                return total;
            },

            init() {
                if (!requireRole(['freelance', 'affiliate'], true)) return;
                syncFreelanceIdFromUrl();
                // 

                // Initialize empty history - will be populated from database
                this.history = [];

                // Load agents data from controller
                this.agents = @json($agents ?? []);
            }
        }
    }
</script>
@endpush

@extends('layouts.freelance')

@section('title', 'Tukar Hadiah - Kuotaumroh.id')

@section('content')
<div x-data="rewardsPage()">
<!-- Header handled by layout -->

    <main class="container mx-auto py-10 animate-fade-in px-4">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold tracking-tight">Tukar Hadiah</h1>
            <p class="text-muted-foreground mt-2">Tukarkan poin Anda dengan berbagai hadiah menarik</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6 flex gap-2 border-b">
            <button @click="activeTab = 'catalog'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="activeTab === 'catalog' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Katalog Hadiah</button>
            <button @click="activeTab = 'history'" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors" :class="activeTab === 'history' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground'">Riwayat Klaim</button>
        </div>

        <!-- Catalog -->
        <div x-show="activeTab === 'catalog'" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <template x-for="reward in rewards" :key="reward.id">
                <div class="rounded-lg border bg-white shadow-sm overflow-hidden">
                    <div class="h-40 bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                        <svg class="h-16 w-16 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold" x-text="reward.name"></h3>
                        <p class="text-sm text-muted-foreground mt-1" x-text="reward.description"></p>
                        <div class="flex items-center justify-between mt-4">
                            <span class="font-bold text-primary" x-text="reward.pointsCost.toLocaleString('id-ID') + ' poin'"></span>
                            <button @click="openClaimModal(reward)" :disabled="pointsBalance < reward.pointsCost" class="h-9 px-4 rounded-md text-sm font-medium transition-colors" :class="pointsBalance >= reward.pointsCost ? 'bg-primary text-white hover:bg-primary/90' : 'bg-muted text-muted-foreground cursor-not-allowed'">
                                Klaim
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- History -->
        <div x-show="activeTab === 'history'" x-cloak class="rounded-lg border bg-white shadow-sm">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Hadiah</th>
                                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Poin</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                                <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="claim in claimHistory" :key="claim.id">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle font-medium" x-text="claim.rewardName"></td>
                                    <td class="p-4 align-middle text-center" x-text="claim.pointsCost.toLocaleString('id-ID')"></td>
                                    <td class="p-4 align-middle" x-text="formatDate(claim.claimDate)"></td>
                                    <td class="p-4 align-middle text-center">
                                        <span class="badge" :class="{'badge-warning': claim.status === 'PENDING', 'badge-success': claim.status === 'APPROVED', 'badge-destructive': claim.status === 'REJECTED'}" x-text="claim.status"></span>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="claimHistory.length === 0">
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-muted-foreground">Belum ada riwayat klaim</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Claim Modal -->
    <div x-show="claimModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="closeClaimModal()"></div>
        <div x-transition class="relative z-10 w-full max-w-md rounded-lg bg-white shadow-lg p-6">
            <h3 class="text-lg font-semibold text-slate-900" x-text="`Tukarkan ${selectedReward?.pointsCost?.toLocaleString('id-ID') || 0} poin dengan ${selectedReward?.name || ''}?`"></h3>
            <p class="mt-2 text-sm text-muted-foreground">Klaim akan diproses max 3x24 jam di hari kerja</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button @click="closeClaimModal()" class="h-9 px-4 rounded-md border text-sm font-medium text-slate-700 hover:bg-muted transition-colors">
                    Batal
                </button>
                <button @click="confirmClaim()" class="h-9 px-4 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/90 transition-colors">
                    Klaim
                </button>
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
    function rewardsPage() {
        return {
            activeTab: 'catalog',
            pointsBalance: 1250,
            rewards: [],
            claimHistory: [],
            claimModalOpen: false,
            selectedReward: null,
            toastVisible: false,
            toastTitle: '',
            toastMessage: '',

            formatDate(dateStr) {
                return new Date(dateStr).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            },

            showToast(title, message) {
                this.toastTitle = title;
                this.toastMessage = message;
                this.toastVisible = true;
                setTimeout(() => {
                    this.toastVisible = false;
                }, 3000);
            },

            openClaimModal(reward) {
                if (this.pointsBalance >= reward.pointsCost) {
                    this.selectedReward = reward;
                    this.claimModalOpen = true;
                }
            },

            closeClaimModal() {
                this.claimModalOpen = false;
                this.selectedReward = null;
            },

            confirmClaim() {
                const reward = this.selectedReward;
                if (reward && this.pointsBalance >= reward.pointsCost) {
                    this.pointsBalance -= reward.pointsCost;
                    this.claimHistory.unshift({
                        id: Date.now(),
                        rewardName: reward.name,
                        pointsCost: reward.pointsCost,
                        claimDate: new Date().toISOString(),
                        status: 'PENDING'
                    });
                    this.showToast('Berhasil!', 'Klaim hadiah sedang diproses');
                }
                this.closeClaimModal();
            },

            init() {
                if (!requireRole(['freelance', 'affiliate'], true)) return;
                syncFreelanceIdFromUrl();
                // renderHeader('rewards');

                this.rewards = [{
                        id: 1,
                        name: 'Voucher OVO Rp 100.000',
                        description: 'E-wallet voucher untuk berbagai kebutuhan',
                        pointsCost: 500
                    },
                    {
                        id: 2,
                        name: 'Voucher OVO Rp 500.000',
                        description: 'E-wallet voucher untuk berbagai kebutuhan',
                        pointsCost: 2000
                    },
                    {
                        id: 3,
                        name: 'Voucher Tokopedia Rp 250.000',
                        description: 'Belanja di Tokopedia',
                        pointsCost: 1000
                    },
                    {
                        id: 4,
                        name: 'Samsung Galaxy A55',
                        description: 'Smartphone Samsung terbaru',
                        pointsCost: 5000
                    },
                    {
                        id: 5,
                        name: 'iPhone 15',
                        description: 'iPhone terbaru dari Apple',
                        pointsCost: 15000
                    },
                    {
                        id: 6,
                        name: 'Laptop ASUS',
                        description: 'Laptop untuk produktivitas',
                        pointsCost: 20000
                    },
                ];

                this.claimHistory = [{
                    id: 1,
                    rewardName: 'Voucher OVO Rp 100.000',
                    pointsCost: 500,
                    claimDate: '2026-01-08',
                    status: 'APPROVED'
                }, ];
            }
        }
    }
</script>
@endpush

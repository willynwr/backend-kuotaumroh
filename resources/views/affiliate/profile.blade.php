@extends('layouts.affiliate')

@section('title', 'Profil Affiliate - Kuotaumroh.id')

@section('content')
<!-- Header handled by layout -->

<div x-data="profileApp()">
    <main class="container mx-auto py-10 animate-fade-in px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 flex items-start gap-4">
                <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div>
                    <h1 class="text-3xl font-bold">Profil Saya</h1>
                    <p class="text-muted-foreground mt-1">Informasi akun affiliate Anda</p>
                </div>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 space-y-6">

                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold border-b pb-2">Informasi Pribadi</h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Nama</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span x-text="user.name || '-'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Email</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span x-text="user.email || '-'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">No. HP</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span x-text="user.phone || '-'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Status</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span class="badge badge-success">Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold border-b pb-2">Informasi Referral</h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Kode Referral</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm font-mono">
                                    <span x-text="user.referralCode || '-'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Total Poin</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span class="font-bold text-primary" x-text="user.totalPoints?.toLocaleString('id-ID') + ' poin'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Agent Terekrut</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span x-text="user.agentsReferred || 0"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Bergabung Sejak</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span x-text="formatDate(user.joinDate)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Information -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold border-b pb-2">Informasi Bank (untuk penarikan poin)</h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">Nama Bank</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span x-text="user.bankName || '-'"></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-muted-foreground">No. Rekening</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm font-mono">
                                    <span x-text="user.accountNumber || '-'"></span>
                                </div>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium text-muted-foreground">Atas Nama</label>
                                <div class="flex h-10 w-full rounded-md border border-input bg-muted px-3 py-2 text-sm">
                                    <span x-text="user.accountName || '-'"></span>
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
                        <p class="mt-1">Data profil Anda saat ini tidak dapat diubah melalui halaman ini. Jika Anda memerlukan perubahan data, silakan hubungi administrator.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    function profileApp() {
        return {
            user: {},

            formatDate(dateStr) {
                if (!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            },

            init() {
                if (!requireRole(['freelance', 'affiliate'], true)) return;
                syncFreelanceIdFromUrl();
                // 

                const savedUser = getUser();
                this.user = {
                    name: savedUser?.name || 'Affiliate Demo',
                    email: savedUser?.email || 'affiliate@example.com',
                    phone: '081234567890',
                    referralCode: savedUser?.referralCode || 'AFF-DEMO-001',
                    totalPoints: 1250,
                    agentsReferred: 15,
                    joinDate: '2025-06-15T00:00:00Z',
                    bankName: 'BCA',
                    accountNumber: '1234567890',
                    accountName: 'Affiliate Demo'
                };
            }
        }
    }
</script>
@endpush

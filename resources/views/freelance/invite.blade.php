@extends('layouts.freelance')

@section('title', 'Ajak Agent - Kuotaumroh.id')

@section('content')
<div x-data="invitePage()">
<!-- Header handled by layout -->

    <main class="container mx-auto py-10 animate-fade-in px-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold tracking-tight">Ajak Agent</h1>
            <p class="text-muted-foreground mt-2">Bagikan link referral Anda dan dapatkan poin untuk setiap agent yang mendaftar</p>
        </div>

        <!-- Referral Stats -->
        <div class="grid gap-4 md:grid-cols-3 mb-6">
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <p class="text-sm text-muted-foreground">Total Agent Terekrut</p>
                <p class="text-3xl font-bold mt-1" x-text="stats.totalReferred"></p>
            </div>
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <p class="text-sm text-muted-foreground">Poin dari Referral</p>
                <p class="text-3xl font-bold mt-1 text-primary" x-text="stats.totalPoints"></p>
            </div>
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <p class="text-sm text-muted-foreground">Poin per Agent</p>
                <p class="text-3xl font-bold mt-1" x-text="stats.pointsPerReferral"></p>
            </div>
        </div>

        <!-- Referral Link Card -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Link Referral Anda</h3>

                <div class="space-y-4">
                    <!-- Referral Code -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-muted-foreground">Kode Referral</label>
                        <div class="flex gap-2">
                            <input type="text" readonly :value="referralCode" class="flex h-10 flex-1 rounded-md border border-input bg-muted px-3 py-2 text-sm font-mono">
                            <button @click="copyCode()" class="h-10 px-4 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Referral Link -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-muted-foreground">Link Pendaftaran</label>
                        <div class="flex gap-2">
                            <input type="text" readonly :value="referralLink" class="flex h-10 flex-1 rounded-md border border-input bg-muted px-3 py-2 text-sm">
                            <button @click="copyLink()" class="h-10 px-4 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
                                Salin Link
                            </button>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="pt-4 border-t">
                        <p class="text-sm font-medium mb-3">Bagikan via:</p>
                        <div class="flex gap-3">
                            <a :href="'https://wa.me/?text=' + encodeURIComponent(shareText)" target="_blank" class="h-10 px-4 inline-flex items-center gap-2 rounded-md border hover:bg-muted transition-colors text-sm font-medium">
                                <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                WhatsApp
                            </a>
                            <a :href="'https://t.me/share/url?url=' + encodeURIComponent(referralLink) + '&text=' + encodeURIComponent(shareText)" target="_blank" class="h-10 px-4 inline-flex items-center gap-2 rounded-md border hover:bg-muted transition-colors text-sm font-medium">
                                <svg class="h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                </svg>
                                Telegram
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="mt-6 rounded-lg border bg-white shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Cara Kerja</h3>
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="flex gap-3">
                        <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-primary">1</span>
                        </div>
                        <div>
                            <p class="font-medium">Bagikan Link</p>
                            <p class="text-sm text-muted-foreground">Kirim link referral ke calon agent</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-primary">2</span>
                        </div>
                        <div>
                            <p class="font-medium">Agent Mendaftar</p>
                            <p class="text-sm text-muted-foreground">Agent mendaftar melalui link Anda</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-primary">3</span>
                        </div>
                        <div>
                            <p class="font-medium">Dapatkan Poin</p>
                            <p class="text-sm text-muted-foreground">Terima 50 poin untuk setiap agent</p>
                        </div>
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

@push('scripts')
<script>
    function invitePage() {
        return {
            referralCode: 'AFF-DEMO-001',
            referralLink: '',
            shareText: '',
            stats: {
                totalReferred: 0,
                totalPoints: 0,
                pointsPerReferral: 50
            },
            toastVisible: false,
            toastTitle: '',
            toastMessage: '',

            showToast(title, message) {
                this.toastTitle = title;
                this.toastMessage = message;
                this.toastVisible = true;
                setTimeout(() => {
                    this.toastVisible = false;
                }, 3000);
            },

            copyCode() {
                navigator.clipboard.writeText(this.referralCode);
                this.showToast('Tersalin!', 'Kode referral berhasil disalin');
            },

            copyLink() {
                navigator.clipboard.writeText(this.referralLink);
                this.showToast('Tersalin!', 'Link referral berhasil disalin');
            },

            init() {
                if (!requireRole(['freelance', 'affiliate'], true)) return;

                syncFreelanceIdFromUrl();
                // renderHeader('invite');

                const user = getUser();
                this.referralCode = user?.referralCode || user?.link_referral || '';
                const freelanceId = user?.id;
                const type = (user?.role === 'affiliate' || user?.role === 'freelance') ? user.role : 'freelance';
                this.referralLink = freelanceId ? `${window.location.origin}/agent/?ref=${encodeURIComponent(`${type}:${freelanceId}`)}` : `${window.location.origin}/agent/`;
                this.shareText = 'Daftar sebagai Agent Kuotaumroh.id dan dapatkan penghasilan tambahan! Gunakan link: ' + this.referralLink;

                this.stats = {
                    totalReferred: 15,
                    totalPoints: 750,
                    pointsPerReferral: 50
                };
            }
        }
    }
</script>
@endpush

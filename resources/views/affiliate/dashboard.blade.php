@extends('layouts.affiliate')

@section('title', 'Dashboard Affiliate - Kuotaumroh.id')

@push('styles')
<style>
    @keyframes bounce-gentle {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
    }
    .animate-bounce-gentle {
      animation: bounce-gentle 2s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<!-- Alpine.js App -->
<div x-data="freelanceDashboard()">
<!-- Header handled by layout -->

    <!-- Main Content -->
    <main class="container mx-auto py-10 animate-fade-in px-4">
        <!-- Stats Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2 mb-12">
            <!-- Total Komisi Card -->
            <div>
                <div class="relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm h-full">
                    <!-- Background Decoration -->
                    <div class="pointer-events-none absolute right-0 top-0 h-32 w-32 -translate-y-1/3 translate-x-1/3 rounded-full bg-primary/5"></div>

                    <!-- Header -->
                    <div class="relative z-10 flex flex-row items-center justify-between p-4 pb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">
                            Total Komisi
                        </h3>
                        <div class="rounded-lg p-2 bg-primary/10 text-primary">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 p-4 pt-0">
                        <!-- MTD Section -->
                        <div class="mb-4">
                            <p class="text-xs font-bold uppercase text-slate-400" x-text="'Komisi MTD (' + stats.currentMonth + ')'"></p>
                            <p class="text-2xl font-extrabold text-primary tracking-tight" x-text="'Rp ' + (stats.commissionMTD || 0).toLocaleString('id-ID')"></p>
                        </div>

                        <!-- YTD Section -->
                        <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-400" x-text="'Komisi YTD (' + stats.currentYear + ')'"></p>
                                <p class="text-lg font-extrabold text-primary" x-text="'Rp ' + (stats.commissionYTD || 0).toLocaleString('id-ID')"></p>
                            </div>
                            <!-- Mini Chart -->
                            <div class="flex items-end gap-1 opacity-70">
                                <div class="h-3 w-2 rounded-t-sm bg-primary/20"></div>
                                <div class="h-5 w-2 rounded-t-sm bg-primary/30"></div>
                                <div class="h-4 w-2 rounded-t-sm bg-primary/40"></div>
                                <div class="h-7 w-2 rounded-t-sm bg-primary/60"></div>
                                <div class="h-8 w-2 rounded-t-sm bg-primary/80"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saldo Fee Card -->
            <div>
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary via-primary to-primary/90 shadow-lg hover:shadow-xl transition-all duration-300 h-full group">
                    <!-- Animated Background Decoration -->
                    <div class="pointer-events-none absolute inset-0 opacity-20">
                        <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/30 blur-2xl"></div>
                        <div class="absolute -left-8 -bottom-8 h-40 w-40 rounded-full bg-white/20 blur-2xl"></div>
                    </div>
                    
                    <!-- Sparkle Effect -->
                    <div class="pointer-events-none absolute right-4 top-4 h-2 w-2 rounded-full bg-white/60 animate-pulse"></div>
                    <div class="pointer-events-none absolute right-12 top-8 h-1.5 w-1.5 rounded-full bg-white/40 animate-pulse" style="animation-delay: 0.5s;"></div>

                    <!-- Header -->
                    <div class="relative z-10 flex flex-row items-center justify-between p-4 pb-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-white/90">
                            Saldo Fee
                        </h3>
                        <div class="rounded-lg p-2 bg-white/20 backdrop-blur-sm text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 px-4 pb-4 pt-0">
                        <!-- Main Balance -->
                        <div class="mb-2">
                            <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight drop-shadow-lg break-words"
                                x-text="stats.pointsBalance.toLocaleString('id-ID')"></div>
                            <div class="text-[10px] sm:text-xs font-semibold text-white/80">Poin Tersedia</div>
                        </div>

                        <!-- Progress to Next Reward -->
                        <div class="bg-white/15 backdrop-blur-sm rounded-lg p-2.5 border border-white/20">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[9px] font-bold text-white/90 uppercase tracking-wide">Progress Reward</span>
                                <span class="text-[9px] font-bold text-white" x-text="Math.min(100, Math.round((stats.pointsBalance / stats.nextRewardPoints) * 100)) + '%'"></span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="relative h-1.5 bg-white/20 rounded-full overflow-hidden backdrop-blur-sm">
                                <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-yellow-300 via-yellow-200 to-white rounded-full transition-all duration-700 ease-out shadow-lg"
                                    :style="'width: ' + Math.min(100, (stats.pointsBalance / stats.nextRewardPoints) * 100) + '%'">
                                    <div class="absolute inset-0 bg-white/30 animate-pulse"></div>
                                </div>
                            </div>
                            
                            <!-- Next Milestone & Total Earned -->
                            <div class="mt-1.5 space-y-1.5">
                                <div class="flex items-center justify-between text-[9px]">
                                    <div class="flex items-center gap-1">
                                        <svg class="h-2.5 w-2.5 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                        </svg>
                                        <span class="font-semibold text-white/90" x-text="stats.nextRewardName"></span>
                                    </div>
                                    <span class="font-bold text-yellow-300" x-text="stats.nextRewardPoints.toLocaleString('id-ID')"></span>
                                </div>
                                
                                <div class="flex items-center justify-between pt-1.5 border-t border-white/10">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[9px] font-bold uppercase text-white/60 tracking-wide">Total Diperoleh</p>
                                        <p class="text-xs font-extrabold text-white break-words"
                                            x-text="stats.totalPointsEarned.toLocaleString('id-ID') + ' poin'"></p>
                                    </div>
                                    <!-- Animated Mini Chart -->
                                    <div class="flex items-end gap-0.5 opacity-70">
                                        <div class="h-2.5 w-1.5 rounded-t-sm bg-white/40 animate-pulse"></div>
                                        <div class="h-3.5 w-1.5 rounded-t-sm bg-white/50 animate-pulse" style="animation-delay: 0.1s;"></div>
                                        <div class="h-3 w-1.5 rounded-t-sm bg-white/45 animate-pulse" style="animation-delay: 0.2s;"></div>
                                        <div class="h-5 w-1.5 rounded-t-sm bg-white/60 animate-pulse" style="animation-delay: 0.3s;"></div>
                                        <div class="h-6 w-1.5 rounded-t-sm bg-white/70 animate-pulse" style="animation-delay: 0.4s;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Downlines Card -->
            <div>
                <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full">
                    <!-- Header -->
                    <div class="flex flex-row items-center justify-between p-4 pb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Total Agen
                        </h3>
                        <div class="rounded-lg p-1.5 bg-primary/10 text-primary">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 pt-0">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-slate-900 tracking-tight" x-text="stats.totalDownlines + ' Agen'"></div>
                        </div>

                        <div class="mt-3 text-xs font-medium text-slate-500">
                            Agen Aktif Bulan Ini
                        </div>
                        <div class="text-lg font-bold text-slate-900" x-text="stats.activeAgentsThisMonth + ' Aktif'"></div>
                        <div class="mt-2 text-xs font-medium text-slate-500">
                            Agen Baru Bulan Ini
                        </div>
                        <div class="text-lg font-bold text-slate-900" x-text="stats.newAgentsThisMonth + ' Bergabung'"></div>
                    </div>
                </div>
            </div>

            <!-- Referral Card -->
            <div>
                <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full">
                    <div class="flex flex-row items-center justify-between p-4 pb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Link Referral
                        </h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="flex flex-row gap-4 items-start">
                            <div class="w-24 sm:w-24 shrink-0">
                                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(referralLink)"
                                    alt="QR Referral" class="w-full aspect-square rounded-lg border bg-white object-contain p-2">
                            </div>
                            <div class="space-y-2 sm:flex-1">
                                <input type="text" readonly :value="referralLink"
                                    class="flex h-9 w-full rounded-md border border-input bg-muted px-3 py-2 text-xs">
                                <div class="flex gap-2">
                                    <button @click="copyLink()"
                                        class="h-9 flex-1 px-3 bg-primary text-white rounded-md text-xs font-medium hover:bg-primary/90 transition-colors">
                                        Salin Link
                                    </button>
                                    <button @click="downloadQR(referralLink, 'QR-Affiliate')"
                                        class="h-9 flex-1 px-3 bg-white border border-slate-300 text-slate-700 rounded-md text-xs font-medium hover:bg-slate-50 transition-colors">
                                        Download QR
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500">Bagikan link ini untuk dapatkan bonus referral.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="space-y-8">
            <div class="flex items-center gap-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900">Menu Utama</h2>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
                <template x-for="item in menuItems" :key="item.id">
                    <a :href="item.href">
                        <div class="group flex h-48 cursor-pointer items-start justify-start rounded-2xl border shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                            :class="item.variant === 'primary'
                  ? 'border-primary bg-primary text-primary-foreground hover:bg-primary/90'
                  : 'border-slate-200 bg-white'">
                            <div class="flex flex-col items-center justify-start gap-3 p-6 text-center w-full">
                                <img x-show="item.id === 'order'" src="{{ asset('images/order.png') }}" alt="Pesanan"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'downlines'" src="{{ asset('images/agen.png') }}" alt="Agen"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'rewards'" src="{{ asset('images/hadiah.png') }}" alt="Hadiah"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'history'" src="{{ asset('images/point.png') }}" alt="Poin"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'transactions'" src="{{ asset('images/transaction.png') }}" alt="Transaksi"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'wallet'" src="{{ asset('images/wallet.png') }}" alt="Dompet"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'withdraw'" src="{{ asset('images/withdraw.png') }}" alt="Tarik Saldo"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <h3 class="text-xs font-bold uppercase tracking-wide leading-tight"
                                    :class="item.variant === 'primary' ? 'text-primary-foreground' : 'text-slate-700'"
                                    x-text="item.title"></h3>
                                <p x-show="item.id === 'rewards' && rewardsHighlightVisible"
                                    class="text-[11px] font-semibold text-slate-500"
                                    x-text="rewardsHighlights[rewardsHighlightIndex]"
                                    x-transition.opacity.duration.400ms></p>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>

    </main>

    <!-- Toast Notification -->
    <div x-show="toastVisible" x-transition class="toast">
        <div class="font-semibold mb-1" x-text="toastTitle"></div>
        <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function freelanceDashboard() {
        return {
            user: {
                name: '{{ $user->nama ?? "" }}',
                email: '{{ $user->email ?? "" }}',
                initials: ''
            },
            stats: {
                pointsBalance: {{ $stats['saldoFee'] ?? 0 }},
                totalPointsEarned: {{ $stats['totalFee'] ?? 0 }},
                totalDownlines: {{ $stats['totalAgents'] ?? 0 }},
                pendingClaims: 0,
                activeAgentsThisMonth: {{ $stats['activeAgentsThisMonth'] ?? 0 }},
                newAgentsThisMonth: {{ $stats['newAgentsThisMonth'] ?? 0 }},
                nextRewardPoints: 10000,
                nextRewardName: 'Voucher Pulsa 50K',
                commissionMTD: {{ $stats['commissionMTD'] ?? 0 }},
                commissionYTD: {{ $stats['commissionYTD'] ?? 0 }},
                currentMonth: '',
                currentYear: ''
            },
            portalType: '{{ $portalType ?? "affiliate" }}',
            affiliateId: '{{ $user->id ?? "" }}',
            linkReferral: '{{ $linkReferral ?? "" }}',
            referralCode: '{{ $user->ref_code ?? "" }}',
            referralLink: '{{ str_replace("portal.", "", url("/agent/" . ($linkReferral ?? ""))) }}',
            shareText: 'Daftar sebagai Agent Kuotaumroh.id di bawah referral saya dan dapatkan penghasilan tambahan! {{ str_replace("portal.", "", url("/agent/" . ($linkReferral ?? ""))) }}',
            menuItems: [{
                    id: 'order',
                    title: 'Pesanan Baru',
                    href: '{{ url("/dash/" . $linkReferral . "/order") }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />'
                },
                {
                    id: 'downlines',
                    title: 'Daftar Agent',
                    href: '{{ url("/dash/" . $linkReferral . "/downlines") }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />'
                },
                {
                    id: 'rewards',
                    title: 'Tukar Hadiah',
                    href: '{{ url("/dash/" . $linkReferral . "/rewards") }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />'
                },
                {
                    id: 'history',
                    title: 'Riwayat Fee',
                    href: '{{ url("/dash/" . $linkReferral . "/points-history") }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'
                },
                {
                    id: 'transactions',
                    title: 'Riwayat Transaksi',
                    href: '{{ url('/dash/' . $linkReferral . '/transactions') }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />'
                },
                {
                    id: 'wallet',
                    title: 'Dompet',
                    href: '{{ url('/dash/' . $linkReferral . '/wallet') }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />'
                },
                {
                    id: 'withdraw',
                    title: 'Tarik Saldo',
                    href: '{{ url('/dash/' . $linkReferral . '/withdraw') }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />'
                }
            ],
            toastVisible: false,
            toastTitle: '',
            toastMessage: '',
            rewardsHighlights: ['Voucher Pulsa', 'Voucher Internet', 'Umroh', 'Smartphone', 'Voucher Shopee'],
            rewardsHighlightIndex: 0,
            rewardsHighlightVisible: true,

            getInitials(name) {
                return name
                    .split(' ')
                    .map(n => n[0])
                    .join('')
                    .toUpperCase()
                    .slice(0, 2);
            },

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

            async downloadQR(url, filename) {
                try {
                    this.showToast('Mengunduh...', 'Sedang memproses QR Code');
                    // Tambahkan timestamp untuk menghindari cache
                    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=400x400&format=png&data=${encodeURIComponent(url)}`;
                    
                    const response = await fetch(qrUrl);
                    if (!response.ok) throw new Error('Gagal mengambil gambar QR');
                    
                    const blob = await response.blob();
                    const downloadUrl = window.URL.createObjectURL(blob);
                    
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = `${filename}.png`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(downloadUrl);
                    
                    this.showToast('Download Berhasil!', 'QR Code berhasil diunduh');
                } catch (error) {
                    console.error('Download failed:', error);
                    this.showToast('Gagal', 'Gagal mengunduh QR Code. Silakan coba lagi.');
                }
            },

            logout() {
                clearUser();
                localStorage.removeItem('token');
                window.location.href = 'login';
            },

            isSameMonth(a, b) {
                return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth();
            },

            getPortalType() {
                try {
                    const params = new URLSearchParams(window.location.search);
                    const hinted = params.get('type');
                    if (hinted === 'affiliate' || hinted === 'freelance') return hinted;
                } catch {}
                const role = getUserRole();
                if (role === 'affiliate' || role === 'freelance') return role;
                return 'freelance';
            },

            getCollectionName() {
                return this.portalType === 'affiliate' ? 'affiliates' : 'freelances';
            },

            async resolveFreelanceId() {
                this.portalType = this.getPortalType();
                const params = new URLSearchParams(window.location.search);
                const idFromUrl = parseInt(params.get('id'), 10);
                if (Number.isFinite(idFromUrl) && idFromUrl > 0) {
                    const current = getUser();
                    setUser({
                        ...current,
                        id: idFromUrl,
                        role: this.portalType
                    });
                    return idFromUrl;
                }

                const savedUser = getUser();
                if (savedUser?.id) return savedUser.id;

                const res = await apiFetch(apiUrl(this.getCollectionName()));
                if (!res.ok) return null;
                const json = await res.json();
                const data = Array.isArray(json) ? json : (json.data || []);
                if (!data.length) return null;

                if (savedUser?.email) {
                    const match = data.find(f => String(f.email).toLowerCase() === String(savedUser.email).toLowerCase());
                    if (match?.id) return match.id;
                }

                return null;
            },

            init() {
                // Set initials dari nama user
                if (this.user.name) {
                    this.user.initials = this.getInitials(this.user.name);
                }

                // Get current month and year for display
                const now = new Date();
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                this.stats.currentMonth = monthNames[now.getMonth()] + ' ' + now.getFullYear();
                this.stats.currentYear = now.getFullYear().toString();

                setInterval(() => {
                    this.rewardsHighlightVisible = false;
                    setTimeout(() => {
                        this.rewardsHighlightIndex = (this.rewardsHighlightIndex + 1) % this.rewardsHighlights.length;
                        this.rewardsHighlightVisible = true;
                    }, 300);
                }, 3000);

                // Data sudah tersedia dari controller, tidak perlu fetch API
            }
        }
    }
</script>
@endpush

@extends('layouts.affiliate')

@section('title', 'Dashboard Affiliate - Kuotaumroh.id')

@section('content')
<!-- Alpine.js App -->
<div x-data="freelanceDashboard()">
<!-- Header handled by layout -->

    <!-- Main Content -->
    <main class="container mx-auto py-10 animate-fade-in px-4">
        <!-- Stats Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">
            <!-- Points Card (Primary) -->
            <div>
                <div class="relative overflow-hidden rounded-2xl border-slate-200 bg-white shadow-sm h-full">
                    <!-- Background Decoration -->
                    <div
                        class="pointer-events-none absolute right-0 top-0 h-32 w-32 -translate-y-1/3 translate-x-1/3 rounded-full bg-primary/5">
                    </div>

                    <!-- Header -->
                    <div class="relative z-10 flex flex-row items-center justify-between p-4 pb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">
                            Saldo Poin
                        </h3>
                        <div class="rounded-lg p-2 bg-primary/10 text-primary">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="relative z-10 p-4 pt-0">
                        <div class="text-3xl font-extrabold text-primary tracking-tight"
                            x-text="stats.pointsBalance.toLocaleString('id-ID') + ' poin'"></div>

                        <!-- Summary Bar -->
                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                            <div>
                                <p class="text-xs font-bold uppercase text-slate-400">Total Poin Diperoleh</p>
                                <p class="text-lg font-extrabold text-primary"
                                    x-text="stats.totalPointsEarned.toLocaleString('id-ID') + ' poin'"></p>
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

            <!-- Referral Card -->
            <div>
                <div class="rounded-2xl border-slate-200 bg-white shadow-sm h-full">
                    <div class="flex flex-row items-center justify-between p-4 pb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Link Referral
                        </h3>
                    </div>
                    <div class="p-4 pt-0 h-full">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                            <div class="sm:w-28 sm:shrink-0">
                                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent(referralLink)"
                                    alt="QR Referral" class="w-full aspect-square rounded-lg border bg-white object-contain p-2">
                            </div>
                            <div class="space-y-3 sm:flex-1">
                                <!-- Referral Link -->
                                <div class="space-y-1.5 min-w-0">
                                    <label class="text-xs font-medium text-muted-foreground">Link Pendaftaran</label>
                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <input type="text" readonly :value="referralLink"
                                            class="flex h-9 w-full min-w-0 rounded-md border border-input bg-muted px-3 py-2 text-xs">
                                        <button @click="copyLink()"
                                            class="h-9 w-full px-3 bg-primary text-white rounded-md text-xs font-medium hover:bg-primary/90 transition-colors sm:w-auto">
                                            Salin
                                        </button>
                                    </div>
                                </div>

                                <!-- Share Buttons -->
                                <div class="pt-3 border-t">
                                    <p class="text-xs font-medium mb-2 text-slate-600">Bagikan via:</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a :href="'https://wa.me/?text=' + encodeURIComponent(shareText)" target="_blank"
                                            class="h-9 px-3 inline-flex items-center justify-center gap-2 rounded-md border hover:bg-muted transition-colors text-xs font-medium">
                                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                            </svg>
                                            WhatsApp
                                        </a>
                                        <a :href="'https://t.me/share/url?url=' + encodeURIComponent(referralLink) + '&text=' + encodeURIComponent(shareText)"
                                            target="_blank"
                                            class="h-9 px-3 inline-flex items-center justify-center gap-2 rounded-md border hover:bg-muted transition-colors text-xs font-medium">
                                            <svg class="h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                            </svg>
                                            Telegram
                                        </a>
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
                    </div>

                    <!-- Content -->
                    <div class="p-4 pt-0">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-slate-900 tracking-tight"
                                x-text="stats.totalDownlines + ' Agen'">
                            </div>
                        </div>

                        <div class="mt-3 text-xs font-medium text-slate-500">Agen Aktif Bulan Ini</div>
                        <div class="text-lg font-bold text-slate-900" x-text="stats.activeAgentsThisMonth + ' Aktif'">
                        </div>
                        <div class="mt-2 text-xs font-medium text-slate-500">Agen Baru Bulan Ini</div>
                        <div class="text-lg font-bold text-slate-900" x-text="stats.newAgentsThisMonth + ' Bergabung'">
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

            <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-3">
                <template x-for="item in menuItems" :key="item.id">
                    <a :href="item.href">
                        <div class="group flex h-48 cursor-pointer items-start justify-start rounded-2xl border shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                            :class="item.variant === 'primary'
                  ? 'border-primary bg-primary text-primary-foreground hover:bg-primary/90'
                  : 'border-slate-200 bg-white'">
                            <div class="flex flex-col items-center justify-start gap-3 p-6 text-center w-full">
                                <img x-show="item.id === 'downlines'" src="{{ asset('images/agen.png') }}" alt="Agen"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'rewards'" src="{{ asset('images/hadiah.png') }}" alt="Hadiah"
                                    class="h-24 w-24 object-contain transition-transform group-hover:scale-110">
                                <img x-show="item.id === 'history'" src="{{ asset('images/point.png') }}" alt="Poin"
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
                pointsBalance: 0,
                totalPointsEarned: 0,
                totalDownlines: {{ $stats['totalAgents'] ?? 0 }},
                pendingClaims: 0,
                activeAgentsThisMonth: {{ $stats['activeAgentsThisMonth'] ?? 0 }},
                newAgentsThisMonth: {{ $stats['newAgentsThisMonth'] ?? 0 }}
            },
            portalType: '{{ $portalType ?? "affiliate" }}',
            freelanceId: {{ $user->id ?? 'null' }},
            linkReferral: '{{ $linkReferral ?? "" }}',
            referralCode: '{{ $user->ref_code ?? "" }}',
            referralLink: '{{ url("/dash/" . ($linkReferral ?? "")) }}',
            shareText: 'Daftar sebagai Agent Kuotaumroh.id dan dapatkan penghasilan tambahan! Kunjungi dashboard saya: {{ url("/dash/" . ($linkReferral ?? "")) }}',
            menuItems: [{
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
                    title: 'Riwayat Poin',
                    href: '{{ url("/dash/" . $linkReferral . "/points-history") }}',
                    variant: 'default',
                    iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'
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

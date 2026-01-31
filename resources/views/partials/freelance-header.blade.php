<header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur" x-data="headerComponent()">
    <div class="container mx-auto flex h-16 items-center justify-between px-4">
        <!-- Logo -->
        <a href="{{ route('freelance.dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/LOGO.png') }}" alt="Kuotaumroh.id Logo" class="h-9 w-9 object-contain">
            <span class="text-xl font-semibold">Kuotaumroh.id</span>
        </a>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex h-10 items-center gap-2 rounded-full px-3 hover:bg-muted transition-colors">
                <span class="flex items-center gap-2 text-sm font-semibold">
                    <span x-show="headerUser.role === 'freelance' || headerUser.role === 'affiliate'"
                        class="inline-flex items-center gap-1">
                        <span x-text="headerUser.points.toLocaleString('id-ID')"></span>
                        <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="5" class="text-amber-200" fill="currentColor"></circle>
                        </svg>
                    </span>
                    <span x-show="headerUser.role === 'freelance' || headerUser.role === 'affiliate'"
                        class="text-slate-400">|</span>
                    <span class="hidden sm:inline" x-text="headerUser.name"></span>
                </span>
                <div
                    class="h-9 w-9 rounded-full bg-primary text-primary-foreground flex items-center justify-center text-sm font-medium">
                    <span x-text="headerUser.initials"></span>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" x-cloak
                class="dropdown-menu absolute right-0 mt-2 w-56 rounded-md border bg-white shadow-lg">
                <div class="px-3 py-3 text-sm">
                    <p class="font-medium" x-text="headerUser.name"></p>
                    <p class="text-xs text-muted-foreground" x-text="headerUser.email"></p>
                    <p class="text-xs text-primary font-medium pt-1" x-text="headerUser.agentCode"></p>
                </div>
                <div class="h-px bg-border"></div>
                <a href="#" @click.prevent="goToProfile()"
                    class="flex w-full items-center px-3 py-2 text-sm hover:bg-muted">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil
                </a>
                <div class="h-px bg-border"></div>
                <button @click="handleLogout()"
                    class="flex w-full items-center px-3 py-2 text-sm text-destructive hover:bg-muted">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar
                </button>
            </div>
        </div>
    </div>
</header>

<script>
    function headerComponent() {
        return {
            headerUser: {
                name: '',
                email: '',
                initials: '',
                agentCode: '',
                points: 0,
                role: ''
            },

            getInitials(name) {
                if (!name) return '';
                const parts = name.trim().split(' ');
                if (parts.length === 1) {
                    return parts[0].substring(0, 2).toUpperCase();
                }
                return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
            },

            handleLogout() {
                localStorage.removeItem('user');
                localStorage.removeItem('token');
                window.location.href = "{{ route('login') }}";
            },

            goToProfile() {
                // Get link_referral from current URL path
                const pathParts = window.location.pathname.split('/');
                // Check if we're on /dash/{link_referral}/* route
                if (pathParts[1] === 'dash' && pathParts[2]) {
                    const linkReferral = pathParts[2];
                    window.location.href = `/dash/${linkReferral}/profile`;
                } else {
                    // Fallback: try to get from user data
                    const user = typeof getUser === 'function' ? getUser() : JSON.parse(localStorage.getItem('user') || '{}');
                    if (user.linkReferral) {
                        window.location.href = `/dash/${user.linkReferral}/profile`;
                    } else {
                        alert('Link referral tidak ditemukan. Silakan login kembali.');
                        window.location.href = "{{ route('login') }}";
                    }
                }
            },

            init() {
                // Get user from localStorage (Migration Phase)
                // In full Laravel app, this should be injected via Blade from Auth::user()
                const user = typeof getUser === 'function' ? getUser() : JSON.parse(localStorage.getItem('user') || '{}');
                
                if (user) {
                    const rawPoints = user.pointsBalance ?? user.points ?? user.pointsCount ?? 0;
                    this.headerUser = {
                        name: user.name || 'Guest',
                        email: user.email || '',
                        agentCode: user.agentCode || '',
                        initials: this.getInitials(user.name || 'Guest'),
                        points: Number(rawPoints) || 0,
                        role: user.role || 'freelance'
                    };
                }
            }
        };
    }
</script>

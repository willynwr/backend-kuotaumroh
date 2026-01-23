<header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur" x-data="agentHeaderComponent()">
    <div class="container mx-auto flex h-16 items-center justify-between px-4">
        <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/LOGO.png') }}" alt="Logo" class="h-9 w-9 object-contain">
            <span class="text-xl font-semibold">Kuotaumroh.id</span>
        </a>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 rounded-full px-3 hover:bg-muted py-1">
                <div class="mr-2 font-bold text-primary" x-text="formatRupiah(headerUser.balance)"></div>
                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                    <span x-text="headerUser.initials"></span>
                </div>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white border rounded shadow-lg p-2 z-50">
                <div class="px-2 py-1 border-b mb-1">
                    <p class="font-bold" x-text="headerUser.name"></p>
                    <p class="text-xs text-gray-500" x-text="headerUser.agentCode"></p>
                </div>
                <button @click="handleLogout" class="w-full text-left px-2 py-1 text-red-500 hover:bg-red-50 rounded">Keluar</button>
            </div>
        </div>
    </div>
</header>

<script>
function agentHeaderComponent() {
    return {
        headerUser: { name: '', initials: '', balance: 0, agentCode: '' },
        init() {
            const u = JSON.parse(localStorage.getItem('user') || '{}');
            const user = u.name ? u : { name: 'Agent', balance: 3250000, agentCode: 'AGT-DEMO' }; // Dummy fallback
            this.headerUser = {
                name: user.name,
                initials: user.name.substring(0,2).toUpperCase(),
                balance: Number(user.balance || user.walletBalance || 0),
                agentCode: user.agentCode || ''
            };
        },
        formatRupiah(v) { return new Intl.NumberFormat('id-ID', {style:'currency', currency:'IDR', minimumFractionDigits:0}).format(v); },
        handleLogout() { 
            localStorage.clear(); 
            window.location.href = "{{ route('login') }}"; 
        }
    }
}
</script>

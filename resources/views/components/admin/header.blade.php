<!-- Header Component for Admin -->
<header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
  <div class="container mx-auto flex h-16 items-center justify-between px-4">
    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
      <img
        src="{{ asset('images/kabah.png') }}"
        alt="Kuotaumroh.id Logo"
        class="h-9 w-9 object-contain"
      >
      <span class="text-xl font-semibold">Kuotaumroh.id</span>
    </a>

    <!-- User Dropdown -->
    <div class="relative" x-data="{ open: false }">
      <button
        @click="open = !open"
        class="flex h-10 items-center gap-2 rounded-full px-3 hover:bg-muted transition-colors"
      >
        <span class="flex items-center gap-2 text-sm font-semibold">
          <span>{{ Auth::user()->name ?? 'Admin' }}</span>
        </span>
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-primary-foreground text-xs font-bold">
          {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
        </div>
        <svg class="h-4 w-4 text-muted-foreground transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <!-- Dropdown Menu -->
      <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute right-0 mt-2 w-56 rounded-md border bg-white shadow-lg"
        style="display: none;"
      >
        <div class="p-2">
          <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm hover:bg-muted transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Profil</span>
          </a>
          <hr class="my-2 border-slate-200">


            <button type="submit" class="w-full flex items-center gap-3 rounded-md px-3 py-2 text-sm hover:bg-muted transition-colors text-destructive">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span>Logout</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>

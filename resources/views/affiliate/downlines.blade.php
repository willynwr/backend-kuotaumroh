@extends('layouts.affiliate')

@section('title', 'Daftar Agent - Kuotaumroh.id')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Match Flatpickr to project's green theme */
    .flatpickr-calendar {
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        border: 1px solid hsl(var(--border));
    }

    .flatpickr-months {
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months,
    .flatpickr-current-month .numInputWrapper {
        font-weight: 600;
    }

    /* Selected and range dates - use project's primary green color */
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover,
    .flatpickr-day.selected.prevMonthDay,
    .flatpickr-day.startRange.prevMonthDay,
    .flatpickr-day.endRange.prevMonthDay,
    .flatpickr-day.selected.nextMonthDay,
    .flatpickr-day.startRange.nextMonthDay,
    .flatpickr-day.endRange.nextMonthDay {
        background: #10b981;
        border-color: #10b981;
    }

    .flatpickr-day.inRange {
        background: rgba(16, 185, 129, 0.15);
        border-color: transparent;
    }

    .flatpickr-day:hover:not(.selected):not(.startRange):not(.endRange) {
        background: hsl(var(--muted));
        border-color: hsl(var(--border));
    }

    .flatpickr-day.today {
        border-color: #10b981;
    }

    .flatpickr-day.today:hover,
    .flatpickr-day.today:focus {
        background: rgba(16, 185, 129, 0.1);
        border-color: #10b981;
    }

    /* Month navigation arrows */
    .flatpickr-months .flatpickr-prev-month:hover svg,
    .flatpickr-months .flatpickr-next-month:hover svg {
        fill: #10b981;
    }

    /* Weekday labels */
    .flatpickr-weekdays {
        background: hsl(var(--muted));
    }

    .flatpickr-weekday {
        color: hsl(var(--muted-foreground));
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div x-data="downlinesPage()">
<!-- Header handled by layout -->

    <main class="container mx-auto py-10 animate-fade-in px-4">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold tracking-tight">Daftar Agent</h1>
            <p class="text-muted-foreground mt-2">Agent yang terdaftar melalui referral Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid gap-4 md:grid-cols-3 mb-6">
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <p class="text-sm text-muted-foreground">Total Agent</p>
                <p class="text-3xl font-bold mt-1" x-text="stats.total"></p>
            </div>
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <p class="text-sm text-muted-foreground">Aktif Bulan Ini</p>
                <p class="text-3xl font-bold mt-1 text-primary" x-text="stats.activeThisMonth"></p>
            </div>
            <div class="rounded-lg border bg-white shadow-sm p-6">
                <p class="text-sm text-muted-foreground">Poin Diperoleh</p>
                <p class="text-3xl font-bold mt-1" x-text="stats.pointsEarned"></p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="rounded-lg border bg-white shadow-sm">
            <div class="p-6">
                <!-- Filters -->
                <div class="space-y-4 mb-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-lg font-semibold">Daftar Agent Terekrut</h3>
                        <button @click="openAddModal()"
                            class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 text-sm font-medium transition-colors">
                            Tambah Agent
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:justify-end">
                        <!-- Date Range Filter -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <input type="text" id="dateRangePicker" placeholder="Pilih Rentang Tanggal"
                                class="flex h-10 rounded-md border border-input bg-background pl-9 pr-8 py-2 text-sm w-full sm:w-[250px]"
                                readonly>
                            <button type="button" x-show="dateFrom || dateTo" @click="clearDateFilter()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-muted-foreground hover:text-foreground rounded-full hover:bg-muted transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Search -->
                        <div class="relative flex-1 sm:flex-none">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" x-model="search" placeholder="Cari nama/email"
                                class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
                        </div>

                        <!-- Status Filter -->
                        <select x-model="statusFilter" class="h-10 rounded-md border border-input bg-background px-3 text-sm">
                            <option value="all">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th @click="sortBy('name')"
                                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:text-foreground transition-colors">
                                    <div class="flex items-center gap-2">
                                        <span>Nama</span>
                                        <svg class="h-4 w-4" :class="sortColumn === 'name' ? 'text-primary' : 'text-muted-foreground/50'"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortColumn === 'name' && sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </div>
                                </th>
                                <th @click="sortBy('email')"
                                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:text-foreground transition-colors">
                                    <div class="flex items-center gap-2">
                                        <span>Email</span>
                                        <svg class="h-4 w-4" :class="sortColumn === 'email' ? 'text-primary' : 'text-muted-foreground/50'"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortColumn === 'email' && sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </div>
                                </th>
                                <th @click="sortBy('joinDate')"
                                    class="h-12 px-4 text-left align-middle font-medium text-muted-foreground cursor-pointer hover:text-foreground transition-colors">
                                    <div class="flex items-center gap-2">
                                        <span>Tanggal Daftar</span>
                                        <svg class="h-4 w-4"
                                            :class="sortColumn === 'joinDate' ? 'text-primary' : 'text-muted-foreground/50'" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortColumn === 'joinDate' && sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </div>
                                </th>
                                <th @click="sortBy('totalOrders')"
                                    class="h-12 px-4 text-center align-middle font-medium text-muted-foreground cursor-pointer hover:text-foreground transition-colors">
                                    <div class="flex items-center justify-center gap-2">
                                        <span>Total Order</span>
                                        <svg class="h-4 w-4"
                                            :class="sortColumn === 'totalOrders' ? 'text-primary' : 'text-muted-foreground/50'" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortColumn === 'totalOrders' && sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </div>
                                </th>
                                <th @click="sortBy('status')"
                                    class="h-12 px-4 text-center align-middle font-medium text-muted-foreground cursor-pointer hover:text-foreground transition-colors">
                                    <div class="flex items-center justify-center gap-2">
                                        <span>Status</span>
                                        <svg class="h-4 w-4"
                                            :class="sortColumn === 'status' ? 'text-primary' : 'text-muted-foreground/50'" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortColumn === 'status' && sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="agent in filteredAgents" :key="agent.id">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle font-medium" x-text="agent.name"></td>
                                    <td class="p-4 align-middle text-muted-foreground" x-text="agent.email"></td>
                                    <td class="p-4 align-middle" x-text="formatDate(agent.joinDate)"></td>
                                    <td class="p-4 align-middle text-center" x-text="agent.totalOrders"></td>
                                    <td class="p-4 align-middle text-center">
                                        <span class="badge" :class="agent.status === 'active' ? 'badge-success' : 'badge-secondary'"
                                            x-text="agent.status === 'active' ? 'Aktif' : 'Tidak Aktif'"></span>
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
    </main>

    <!-- Toast -->
    <div x-show="toastVisible" x-transition class="toast">
        <div class="font-semibold mb-1" x-text="toastTitle"></div>
        <div class="text-sm text-muted-foreground" x-text="toastMessage"></div>
    </div>

    <div x-show="addModalOpen" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="min-h-full w-full p-4 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" @click="closeAddModal()"></div>

            <div
                class="relative w-full max-w-6xl rounded-lg border bg-white shadow-lg overflow-hidden max-h-[calc(100dvh-2rem)] flex flex-col">
                <div class="flex items-center justify-between border-b px-6 py-4 shrink-0">
                    <div>
                        <h3 class="text-lg font-semibold">Tambah Agent</h3>
                        <p class="text-sm text-muted-foreground">Form sama seperti signup, kategori agent otomatis: Host</p>
                    </div>
                    <button @click="closeAddModal()"
                        class="h-9 w-9 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-6 overflow-y-auto flex-1">
                    <form id="addAgentForm" @submit.prevent="submitAddAgent()" class="space-y-6">
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="rounded-lg border bg-white shadow-sm p-6 space-y-4">
                                <h2 class="text-lg font-semibold border-b pb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.938 18.938A10.5 10.5 0 1012 21a10.5 10.5 0 007.938-2.062z" />
                                    </svg>
                                    Data PIC
                                </h2>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Email <span class="text-destructive">*</span></label>
                                    <input type="email" x-model="addFormData.email"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                        :class="addErrors.email && 'border-destructive focus:ring-destructive'">
                                    <p x-show="addErrors.email" class="text-xs text-destructive" x-text="addErrors.email"></p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Nama PIC <span class="text-destructive">*</span></label>
                                    <input type="text" x-model="addFormData.full_name"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                        :class="addErrors.full_name && 'border-destructive focus:ring-destructive'">
                                    <p x-show="addErrors.full_name" class="text-xs text-destructive" x-text="addErrors.full_name"></p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">No. HP <span class="text-destructive">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground text-sm">+62</span>
                                        <input type="tel" x-model="addFormData.phone"
                                            @input="addFormData.phone = addFormData.phone.replace(/[^0-9]/g, '')" placeholder="81xxx"
                                            class="flex h-10 w-full rounded-md border border-input bg-background pl-12 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                            :class="addErrors.phone && 'border-destructive focus:ring-destructive'">
                                    </div>
                                    <p class="text-xs text-muted-foreground">Format: 81xxxxxxxx (tanpa 0 atau +62)</p>
                                    <p x-show="addErrors.phone" class="text-xs text-destructive" x-text="addErrors.phone"></p>
                                </div>
                            </div>

                            <div class="rounded-lg border bg-white shadow-sm p-6 space-y-4">
                                <h2 class="text-lg font-semibold border-b pb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Detail Travel
                                </h2>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Nama Travel</label>
                                    <input type="text" x-model="addFormData.travel_name"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                </div>

                                <div class="space-y-2" x-data="{ travelTypeOpen: false }">
                                    <label class="text-sm font-medium">Jenis Travel</label>
                                    <div class="relative" @click.away="travelTypeOpen = false">
                                        <button type="button" @click="travelTypeOpen = !travelTypeOpen"
                                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                            <span :class="!addFormData.travel_type && 'text-muted-foreground'"
                                                x-text="addFormData.travel_type || 'Pilih jenis travel'"></span>
                                            <svg class="h-4 w-4 transition-transform" :class="travelTypeOpen && 'rotate-180'" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="travelTypeOpen" x-transition
                                            class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg"
                                            style="display: none;">
                                            <div class="py-1">
                                                <button type="button" @click="addFormData.travel_type = 'UMROH'; travelTypeOpen = false"
                                                    class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors"
                                                    :class="addFormData.travel_type === 'UMROH' && 'bg-primary/10 text-primary'">UMROH</button>
                                                <button type="button" @click="addFormData.travel_type = 'LEISURE'; travelTypeOpen = false"
                                                    class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors"
                                                    :class="addFormData.travel_type === 'LEISURE' && 'bg-primary/10 text-primary'">LEISURE</button>
                                                <button type="button"
                                                    @click="addFormData.travel_type = 'UMROH LEISURE'; travelTypeOpen = false"
                                                    class="w-full text-left px-3 py-2 text-sm hover:bg-muted transition-colors"
                                                    :class="addFormData.travel_type === 'UMROH LEISURE' && 'bg-primary/10 text-primary'">UMROH &
                                                    LEISURE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Total Traveller per Bulan</label>
                                    <input type="number" min="0" x-model="addFormData.travel_member"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium">Logo Travel (Opsional)</label>
                                    <input type="file" @change="handleFileUpload($event)"
                                        accept="image/png,image/jpeg,image/jpg,image/gif"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium file:cursor-pointer hover:file:text-primary transition-colors cursor-pointer"
                                        :class="addErrors.logo && 'border-destructive focus:ring-destructive'">
                                    <p x-show="addErrors.logo" class="text-xs text-destructive" x-text="addErrors.logo"></p>
                                    <p class="text-xs text-muted-foreground">Format: PNG, JPG, GIF. Maksimal 2MB</p>
                                    <div x-show="addLogoPreview" class="mt-2">
                                        <img :src="addLogoPreview" alt="Logo preview" class="h-24 w-24 object-contain border rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border bg-white shadow-sm p-6 space-y-4">
                            <h2 class="text-lg font-semibold border-b pb-2 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lokasi
                            </h2>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2" x-data="{ provinceDropdownOpen: false, provinceSearch: '' }"
                                    x-init="$watch('provinceDropdownOpen', value => { if (value) $nextTick(() => $refs.provinceSearchInput.focus()) })">
                                    <label class="text-sm font-medium">Pilih Provinsi <span class="text-destructive">*</span></label>
                                    <div class="relative" @click.away="provinceDropdownOpen = false">
                                        <button type="button" @click="provinceDropdownOpen = !provinceDropdownOpen"
                                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                            :class="addErrors.province && 'border-destructive focus:ring-destructive'">
                                            <span :class="!addFormData.province && 'text-muted-foreground'"
                                                x-text="addFormData.province || 'Pilih provinsi'"></span>
                                            <svg class="h-4 w-4 transition-transform" :class="provinceDropdownOpen && 'rotate-180'"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div x-show="provinceDropdownOpen" x-transition
                                            class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg"
                                            style="display:none;">
                                            <div class="p-2 border-b">
                                                <input type="text" x-ref="provinceSearchInput" x-model="provinceSearch" @click.stop
                                                    placeholder="Cari provinsi..."
                                                    class="w-full rounded-md border border-input px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                            </div>
                                            <div class="max-h-60 overflow-y-auto">
                                                <template
                                                    x-for="province in provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase()))"
                                                    :key="province">
                                                    <button type="button"
                                                        @click="addFormData.province = province; handleProvinceChange(); provinceDropdownOpen = false; provinceSearch = ''"
                                                        class="w-full px-3 py-2 text-left text-sm hover:bg-muted transition-colors"
                                                        :class="addFormData.province === province && 'bg-muted'" x-text="province"></button>
                                                </template>
                                                <div
                                                    x-show="provinces.filter(p => p.toLowerCase().includes(provinceSearch.toLowerCase())).length === 0"
                                                    class="px-3 py-2 text-sm text-muted-foreground">Tidak ada provinsi ditemukan</div>
                                            </div>
                                        </div>
                                    </div>
                                    <p x-show="addErrors.province" class="text-xs text-destructive" x-text="addErrors.province"></p>
                                </div>

                                <div class="space-y-2" x-data="{ cityDropdownOpen: false, citySearch: '' }"
                                    x-init="$watch('cityDropdownOpen', value => { if (value) $nextTick(() => $refs.citySearchInput.focus()) })">
                                    <label class="text-sm font-medium">Pilih Kota/Kab <span class="text-destructive">*</span></label>
                                    <div class="relative" @click.away="cityDropdownOpen = false">
                                        <button type="button" @click="cityDropdownOpen = !cityDropdownOpen"
                                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                            :class="addErrors.city && 'border-destructive focus:ring-destructive'">
                                            <span :class="!addFormData.city && 'text-muted-foreground'"
                                                x-text="addFormData.city || 'Pilih kota/kabupaten'"></span>
                                            <svg class="h-4 w-4 transition-transform" :class="cityDropdownOpen && 'rotate-180'" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div x-show="cityDropdownOpen" x-transition
                                            class="absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg"
                                            style="display:none;">
                                            <div class="p-2 border-b">
                                                <input type="text" x-ref="citySearchInput" x-model="citySearch" @click.stop
                                                    placeholder="Cari kota/kabupaten..."
                                                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                            </div>
                                            <div class="max-h-60 overflow-y-auto p-1">
                                                <template x-for="city in filteredCities(citySearch)" :key="city">
                                                    <button type="button"
                                                        @click="addFormData.city = city; handleCityChange(); cityDropdownOpen = false; citySearch = ''"
                                                        :class="addFormData.city === city && 'bg-primary/10 text-primary'"
                                                        class="w-full text-left px-3 py-2 text-sm rounded hover:bg-accent transition-colors"
                                                        x-text="city"></button>
                                                </template>
                                                <div x-show="filteredCities(citySearch).length === 0"
                                                    class="px-3 py-6 text-center text-sm text-muted-foreground">Tidak ada hasil ditemukan</div>
                                            </div>
                                        </div>
                                    </div>
                                    <p x-show="addErrors.city" class="text-xs text-destructive" x-text="addErrors.city"></p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium">Alamat Lengkap <span class="text-destructive">*</span></label>
                                    <div x-show="isGeocodingAddress" class="flex items-center gap-2 text-xs text-muted-foreground">
                                        <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Mencari lokasi...
                                    </div>
                                </div>
                                <textarea x-model="addFormData.address" @blur="geocodeAddress()" rows="4"
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                    :class="addErrors.address && 'border-destructive focus:ring-destructive'"></textarea>
                                <p x-show="addErrors.address" class="text-xs text-destructive" x-text="addErrors.address"></p>
                            </div>

                            <div class="space-y-2" x-show="addFormData.city">
                                <label class="text-sm font-medium">Tandai Lokasi di Peta</label>
                                <p class="text-xs text-muted-foreground">Klik pada peta untuk menandai lokasi Anda</p>

                                <div class="relative mb-2">
                                    <div class="relative">
                                        <input type="text" x-model="mapSearchQuery" @input="handleMapSearch()"
                                            @keydown.enter.prevent="handleMapEnter()"
                                            placeholder="Cari lokasi (contoh: Monas, Jalan Sudirman)"
                                            class="flex h-10 w-full rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-muted-foreground">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3" x-show="isSearchingMap">
                                            <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                                </circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div x-show="mapSearchResults.length > 0" @click.away="mapSearchResults = []"
                                        class="absolute z-10 w-full mt-1 bg-popover text-popover-foreground rounded-md border shadow-md max-h-60 overflow-y-auto"
                                        style="display: none;">
                                        <ul>
                                            <template x-for="(result, index) in mapSearchResults" :key="index">
                                                <li @click="selectMapLocation(result)"
                                                    class="px-3 py-2 text-sm hover:bg-accent hover:text-accent-foreground cursor-pointer border-b last:border-0">
                                                    <div class="font-medium" x-text="result.display_name.split(',')[0]"></div>
                                                    <div class="text-xs text-muted-foreground truncate" x-text="result.display_name"></div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                                <div id="addAgentMap" class="w-full h-80 rounded-md border border-input overflow-hidden"
                                    x-init="$nextTick(() => { if (addFormData.city && !mapInitialized) initializeMap(); })"></div>

                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Latitude</label>
                                        <input type="number" step="any" x-model.number="addFormData.latitude"
                                            @input="updateMapFromCoordinates()" placeholder="-6.xxxxx"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Longitude</label>
                                        <input type="number" step="any" x-model.number="addFormData.longitude"
                                            @input="updateMapFromCoordinates()" placeholder="106.xxxxx"
                                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="flex items-center justify-end gap-2 border-t bg-white px-6 py-4 shrink-0">
                    <button type="button" @click="closeAddModal()"
                        class="inline-flex items-center justify-center rounded-md border bg-transparent hover:bg-muted h-10 px-4 text-sm font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" form="addAgentForm" :disabled="isSubmitting"
                        class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">Simpan</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    function downlinesPage() {
        return {
            search: '',
            statusFilter: 'all',
            dateFrom: '',
            dateTo: '',
            sortColumn: 'joinDate',
            sortDirection: 'desc',
            agents: [],
            stats: {
                total: 0,
                activeThisMonth: 0,
                pointsEarned: 0
            },
            toastVisible: false,
            toastTitle: '',
            toastMessage: '',

            portalType: 'freelance',

            freelanceId: null,
            addModalOpen: false,
            isSubmitting: false,
            addFormData: {
                email: '',
                phone: '',
                full_name: '',
                travel_name: '',
                travel_type: '',
                travel_member: '',
                province: '',
                city: '',
                address: '',
                latitude: null,
                longitude: null
            },
            addErrors: {},
            addLogoFile: null,
            addLogoPreview: null,
            isGeocodingAddress: false,

            provinces: [],
            provinceCodes: new Map(),
            cities: [],
            cityCodes: new Map(),

            mapInstance: null,
            marker: null,
            mapInitialized: false,
            mapSearchQuery: '',
            mapSearchResults: [],
            isSearchingMap: false,
            mapSearchDebounce: null,

            showToast(title, message) {
                this.toastTitle = title;
                this.toastMessage = message;
                this.toastVisible = true;
                setTimeout(() => {
                    this.toastVisible = false;
                }, 3000);
            },

            openAddModal() {
                this.resetAddForm();
                this.addErrors = {};
                this.addModalOpen = true;
            },

            closeAddModal() {
                this.addModalOpen = false;
                this.isSubmitting = false;
                this.addErrors = {};
                this.destroyMap();
            },

            resetAddForm() {
                this.addFormData = {
                    email: '',
                    phone: '',
                    full_name: '',
                    travel_name: '',
                    travel_type: '',
                    travel_member: '',
                    province: '',
                    city: '',
                    address: '',
                    latitude: null,
                    longitude: null
                };
                this.addErrors = {};
                this.addLogoFile = null;
                this.addLogoPreview = null;
                this.isGeocodingAddress = false;
                this.mapSearchQuery = '';
                this.mapSearchResults = [];
                this.isSearchingMap = false;
                this.cities = [];
                this.cityCodes = new Map();
                this.destroyMap();
            },

            destroyMap() {
                try {
                    if (this.mapInstance) {
                        this.mapInstance.remove();
                    }
                } catch {}
                this.mapInstance = null;
                this.marker = null;
                this.mapInitialized = false;
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) {
                    this.showToast('Error', 'Ukuran logo maksimal 2MB');
                    event.target.value = '';
                    return;
                }

                const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    this.showToast('Error', 'Format logo harus PNG, JPG, atau GIF');
                    event.target.value = '';
                    return;
                }

                this.addLogoFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.addLogoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            async loadProvinces() {
                try {
                    const response = await fetch("{{ asset('wilayah/provinces.json') }}");
                    if (!response.ok) throw new Error('Gagal memuat provinsi');
                    const data = await response.json();
                    if (data && data.data) {
                        this.provinces = data.data.map(p => p.name).sort();
                        this.provinceCodes = new Map();
                        data.data.forEach(p => this.provinceCodes.set(p.name, p.code));
                    }
                } catch (e) {
                    console.error(e);
                    this.showToast('Error', 'Gagal memuat data provinsi');
                }
            },

            async loadCities(provinceCode) {
                try {
                    let response;
                    const localPath = `/wilayah/regencies-${provinceCode}.json`;
                    try {
                        response = await fetch(localPath);
                        if (!response.ok) throw new Error('Not in cache');
                    } catch {
                        response = await fetch(`https://wilayah.id/api/regencies/${provinceCode}.json`);
                    }
                    const data = await response.json();
                    if (data && data.data) {
                        this.cities = data.data.map(c => c.name).sort();
                        this.cityCodes = new Map();
                        data.data.forEach(c => this.cityCodes.set(c.name, c.code));
                    }
                } catch (e) {
                    console.error(e);
                    this.showToast('Error', 'Gagal memuat data kota/kabupaten');
                }
            },

            filteredCities(search) {
                if (!search) return this.cities;
                const searchLower = search.toLowerCase();
                return this.cities.filter(city => city.toLowerCase().includes(searchLower));
            },

            async handleProvinceChange() {
                this.addFormData.city = '';
                this.cities = [];
                if (!this.addFormData.province) return;
                const code = this.provinceCodes.get(this.addFormData.province);
                if (code) await this.loadCities(code);
            },

            async handleCityChange() {
                this.$nextTick(async () => {
                    if (!this.addFormData.city) return;
                    if (!this.mapInitialized) {
                        await this.initializeMap();
                    } else {
                        await this.recenterMapToCity();
                    }
                });
            },

            async recenterMapToCity() {
                if (!this.mapInstance || !this.addFormData.city) return;
                try {
                    const searchQuery = this.addFormData.province ?
                        `${this.addFormData.city}, ${this.addFormData.province}, Indonesia` :
                        `${this.addFormData.city}, Indonesia`;
                    const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1`;
                    const response = await fetch(geocodeUrl);
                    const data = await response.json();
                    if (data && data.length > 0) {
                        const centerLat = parseFloat(data[0].lat);
                        const centerLng = parseFloat(data[0].lon);
                        this.mapInstance.flyTo([centerLat, centerLng], 11, {
                            duration: 1.5
                        });
                    }
                } catch {}
            },

            async initializeMap() {
                if (this.mapInitialized) return;
                let centerLat = -2.5;
                let centerLng = 118.0;
                let zoomLevel = 5;

                try {
                    if (this.addFormData.city) {
                        try {
                            const searchQuery = this.addFormData.province ?
                                `${this.addFormData.city}, ${this.addFormData.province}, Indonesia` :
                                `${this.addFormData.city}, Indonesia`;
                            const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1`;
                            const response = await fetch(geocodeUrl);
                            const data = await response.json();
                            if (data && data.length > 0) {
                                centerLat = parseFloat(data[0].lat);
                                centerLng = parseFloat(data[0].lon);
                                zoomLevel = 11;
                            }
                        } catch {}
                    }

                    this.mapInstance = L.map('addAgentMap').setView([centerLat, centerLng], zoomLevel);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(this.mapInstance);

                    if (this.addFormData.latitude && this.addFormData.longitude) {
                        this.updateMarker(this.addFormData.latitude, this.addFormData.longitude);
                    }

                    this.mapInstance.on('click', (e) => {
                        const {
                            lat,
                            lng
                        } = e.latlng;
                        this.updateMarker(lat, lng);
                    });

                    this.mapInitialized = true;
                    setTimeout(() => {
                        try {
                            this.mapInstance.invalidateSize();
                        } catch {}
                    }, 100);
                } catch (e) {
                    console.error(e);
                    this.showToast('Error', 'Gagal memuat peta');
                }
            },

            handleMapSearch() {
                if (!this.mapSearchQuery || this.mapSearchQuery.length < 3) {
                    this.mapSearchResults = [];
                    return;
                }

                if (this.mapSearchDebounce) clearTimeout(this.mapSearchDebounce);
                this.mapSearchDebounce = setTimeout(async () => {
                    this.isSearchingMap = true;
                    try {
                        const query = `${this.mapSearchQuery}, Indonesia`;
                        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`);
                        if (response.ok) {
                            this.mapSearchResults = await response.json();
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isSearchingMap = false;
                    }
                }, 500);
            },

            async handleMapEnter() {
                if (this.mapSearchResults.length > 0) {
                    this.selectMapLocation(this.mapSearchResults[0]);
                    return;
                }
                if (!this.mapSearchQuery || this.mapSearchQuery.length < 3) return;
                if (this.mapSearchDebounce) clearTimeout(this.mapSearchDebounce);
                this.isSearchingMap = true;
                try {
                    const query = `${this.mapSearchQuery}, Indonesia`;
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`);
                    if (response.ok) {
                        const data = await response.json();
                        if (data && data.length > 0) {
                            this.selectMapLocation(data[0]);
                        } else {
                            this.showToast('Info', 'Lokasi tidak ditemukan');
                        }
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.isSearchingMap = false;
                }
            },

            selectMapLocation(result) {
                const lat = parseFloat(result.lat);
                const lon = parseFloat(result.lon);
                this.addFormData.latitude = lat;
                this.addFormData.longitude = lon;

                if (result.address && (result.address.road || result.address.village)) {
                    const street = result.address.road || result.address.village || result.display_name.split(',')[0];
                    if (!this.addFormData.address) {
                        this.addFormData.address = street;
                    }
                }

                if (this.mapInstance) {
                    this.mapInstance.flyTo([lat, lon], 16);
                    this.updateMarker(lat, lon);
                }

                this.mapSearchResults = [];
                this.mapSearchQuery = result.display_name;
            },

            async geocodeAddress() {
                if (!this.addFormData.address || this.addFormData.address.length < 5) return;
                if (!this.mapInstance) return;

                this.isGeocodingAddress = true;
                const queryItems = [this.addFormData.address];
                if (this.addFormData.city) queryItems.push(this.addFormData.city);
                if (this.addFormData.province) queryItems.push(this.addFormData.province);
                const query = queryItems.join(', ');

                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
                    const data = await response.json();
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        this.mapInstance.flyTo([lat, lon], 18);
                        this.updateMarker(lat, lon);
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.isGeocodingAddress = false;
                }
            },

            updateMapFromCoordinates() {
                const lat = parseFloat(this.addFormData.latitude);
                const lng = parseFloat(this.addFormData.longitude);
                if (!isNaN(lat) && !isNaN(lng) && this.mapInstance) {
                    this.updateMarker(lat, lng);
                    this.mapInstance.flyTo([lat, lng], 16);
                }
            },

            updateMarker(lat, lng) {
                if (!this.mapInstance) return;
                if (this.marker) {
                    this.mapInstance.removeLayer(this.marker);
                }
                const greenIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                this.marker = L.marker([lat, lng], {
                    icon: greenIcon
                }).addTo(this.mapInstance);
                this.addFormData.latitude = lat;
                this.addFormData.longitude = lng;
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

            getDownlineForeignKey() {
                return this.portalType === 'affiliate' ? 'affiliate_id' : 'freelance_id';
            },

            async resolveFreelanceId() {
                this.portalType = this.getPortalType();
                const savedUser = getUser();
                const params = new URLSearchParams(window.location.search);
                const idFromUrl = parseInt(params.get('id'), 10);
                if (Number.isFinite(idFromUrl) && idFromUrl > 0) {
                    setUser({
                        ...savedUser,
                        id: idFromUrl,
                        role: this.portalType
                    });
                    return idFromUrl;
                }

                if (savedUser?.id) return savedUser.id;
                if (!savedUser?.email) return null;

                const listRes = await apiFetch(apiUrl(this.getCollectionName()));
                if (!listRes.ok) return null;
                const listJson = await listRes.json();
                const list = Array.isArray(listJson) ? listJson : (listJson.data || []);
                const match = list.find(f => String(f.email).toLowerCase() === String(savedUser.email).toLowerCase());
                return match?.id || null;
            },

            async loadAgents() {
                const collection = this.getCollectionName();
                const nestedUrl = `${collection}/${this.freelanceId}/agents`;
                let data = [];
                const res = await apiFetch(apiUrl(nestedUrl));
                if (res.ok) {
                    const json = await res.json().catch(() => ({}));
                    data = Array.isArray(json) ? json : (json.data || []);
                } else {
                    const partnerRes = await apiFetch(apiUrl(`${collection}/${this.freelanceId}`));
                    if (partnerRes.ok) {
                        const partnerJson = await partnerRes.json().catch(() => ({}));
                        const partner = partnerJson.data || null;
                        if (partner && Array.isArray(partner.agents)) data = partner.agents;
                    }
                }

                if (!Array.isArray(data) || !data.length) {
                    const allRes = await apiFetch(apiUrl('agents'));
                    if (!allRes.ok) throw new Error('Gagal memuat daftar agent');
                    const allJson = await allRes.json().catch(() => ({}));
                    const all = Array.isArray(allJson) ? allJson : (allJson.data || []);
                    const key = this.getDownlineForeignKey();
                    data = all.filter(a => String(a?.[key] || '') === String(this.freelanceId));
                }

                const now = new Date();

                this.agents = data.map(a => ({
                    id: a.id,
                    name: a.nama_pic,
                    email: a.email,
                    joinDate: a.created_at,
                    totalOrders: 0,
                    status: a.is_active ? 'active' : 'inactive'
                }));

                this.stats.total = this.agents.length;
                this.stats.activeThisMonth = data.filter(a => {
                    const createdAt = new Date(a.created_at);
                    return a.is_active && createdAt.getFullYear() === now.getFullYear() && createdAt.getMonth() === now.getMonth();
                }).length;
                this.stats.pointsEarned = 0;
            },

            async submitAddAgent() {
                this.addErrors = {};
                if (!this.freelanceId) {
                    this.showToast('Error', 'Freelance tidak ditemukan');
                    return;
                }

                let hasError = false;
                if (!this.addFormData.email) {
                    this.addErrors.email = 'Email wajib diisi';
                    hasError = true;
                }
                if (!this.addFormData.full_name) {
                    this.addErrors.full_name = 'Nama PIC wajib diisi';
                    hasError = true;
                }
                if (!this.addFormData.phone) {
                    this.addErrors.phone = 'No. HP wajib diisi';
                    hasError = true;
                }
                if (!this.addFormData.province) {
                    this.addErrors.province = 'Provinsi wajib dipilih';
                    hasError = true;
                }
                if (!this.addFormData.city) {
                    this.addErrors.city = 'Kota/Kabupaten wajib dipilih';
                    hasError = true;
                }
                if (!this.addFormData.address) {
                    this.addErrors.address = 'Alamat lengkap wajib diisi';
                    hasError = true;
                }

                if (hasError) return;

                this.isSubmitting = true;
                try {
                    const buildFormData = () => {
                        const fd = new FormData();
                        fd.append('email', this.addFormData.email);
                        fd.append('kategori_agent', 'Host');
                        fd.append(this.getDownlineForeignKey(), String(this.freelanceId));
                        fd.append('nama_pic', this.addFormData.full_name);

                        let phone = String(this.addFormData.phone);
                        if (phone.startsWith('0')) phone = phone.substring(1);
                        if (phone.startsWith('62')) phone = phone.substring(2);
                        fd.append('no_hp', '62' + phone);

                        if (this.addFormData.travel_name) fd.append('nama_travel', this.addFormData.travel_name);
                        if (this.addFormData.travel_type) fd.append('jenis_travel', this.addFormData.travel_type);
                        if (this.addFormData.travel_member !== '' && this.addFormData.travel_member !== null) fd.append('total_traveller', String(this.addFormData.travel_member));

                        fd.append('provinsi', this.addFormData.province);
                        fd.append('kabupaten_kota', this.addFormData.city);
                        fd.append('alamat_lengkap', this.addFormData.address);

                        if (this.addFormData.latitude) fd.append('lat', String(this.addFormData.latitude));
                        if (this.addFormData.longitude) fd.append('long', String(this.addFormData.longitude));
                        if (this.addFormData.latitude && this.addFormData.longitude) {
                            fd.append('link_gmaps', `https://www.google.com/maps?q=${this.addFormData.latitude},${this.addFormData.longitude}`);
                        }

                        if (this.addLogoFile) fd.append('logo', this.addLogoFile);
                        return fd;
                    };

                    const collection = this.getCollectionName();
                    let res = await fetch(apiUrl(`${collection}/${this.freelanceId}/agents`), {
                        method: 'POST',
                        body: buildFormData()
                    });

                    if (res.status === 404 || res.status === 405) {
                        res = await fetch(apiUrl('agents'), {
                            method: 'POST',
                            body: buildFormData()
                        });
                    }

                    const json = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const msg = json?.message || 'Gagal menambahkan agent';
                        if (json?.errors && typeof json.errors === 'object') {
                            const mapped = {};
                            if (json.errors.email) mapped.email = json.errors.email[0];
                            if (json.errors.nama_pic) mapped.full_name = json.errors.nama_pic[0];
                            if (json.errors.no_hp) mapped.phone = json.errors.no_hp[0];
                            if (json.errors.provinsi) mapped.province = json.errors.provinsi[0];
                            if (json.errors.kabupaten_kota) mapped.city = json.errors.kabupaten_kota[0];
                            if (json.errors.alamat_lengkap) mapped.address = json.errors.alamat_lengkap[0];
                            if (json.errors.logo) mapped.logo = json.errors.logo[0];
                            this.addErrors = mapped;
                        }
                        this.showToast('Error', msg);
                        this.isSubmitting = false;
                        return;
                    }

                    this.showToast('Berhasil', 'Agent berhasil ditambahkan');
                    this.closeAddModal();
                    this.resetAddForm();
                    await this.loadAgents();
                } catch (e) {
                    console.error(e);
                    this.showToast('Error', 'Gagal menambahkan agent');
                } finally {
                    this.isSubmitting = false;
                }
            },

            get filteredAgents() {
                let filtered = this.agents.filter(a => {
                    const matchSearch = !this.search || a.name.toLowerCase().includes(this.search.toLowerCase()) || a.email.toLowerCase().includes(this.search.toLowerCase());
                    const matchStatus = this.statusFilter === 'all' || a.status === this.statusFilter;

                    // Date range filter
                    let matchDate = true;
                    if (this.dateFrom || this.dateTo) {
                        const joinDate = new Date(a.joinDate);
                        if (this.dateFrom) {
                            matchDate = matchDate && joinDate >= new Date(this.dateFrom);
                        }
                        if (this.dateTo) {
                            matchDate = matchDate && joinDate <= new Date(this.dateTo);
                        }
                    }

                    return matchSearch && matchStatus && matchDate;
                });

                // Sort
                return filtered.sort((a, b) => {
                    let aVal = a[this.sortColumn];
                    let bVal = b[this.sortColumn];

                    // Handle date sorting
                    if (this.sortColumn === 'joinDate') {
                        aVal = new Date(aVal);
                        bVal = new Date(bVal);
                    }

                    // Handle number sorting
                    if (typeof aVal === 'number' && typeof bVal === 'number') {
                        return this.sortDirection === 'asc' ? aVal - bVal : bVal - aVal;
                    }

                    // Handle string sorting
                    aVal = String(aVal).toLowerCase();
                    bVal = String(bVal).toLowerCase();

                    if (this.sortDirection === 'asc') {
                        return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
                    } else {
                        return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
                    }
                });
            },

            sortBy(column) {
                if (this.sortColumn === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortColumn = column;
                    this.sortDirection = 'desc';
                }
            },

            resetFilters() {
                this.search = '';
                this.statusFilter = 'all';
                this.dateFrom = '';
                this.dateTo = '';
            },

            clearDateFilter() {
                const picker = document.querySelector('#dateRangePicker')._flatpickr;
                if (picker) {
                    picker.clear();
                }
                this.dateFrom = '';
                this.dateTo = '';
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            },

            init() {
                this.portalType = this.getPortalType();
                if (!requireRole(['freelance', 'affiliate'], true)) return;
                

                // Initialize Flatpickr for date range picker
                this.$nextTick(() => {
                    flatpickr('#dateRangePicker', {
                        mode: 'range',
                        dateFormat: 'd M Y',
                        locale: {
                            rangeSeparator: ' - '
                        },
                        onChange: (selectedDates) => {
                            if (selectedDates.length === 2) {
                                // Format dates as YYYY-MM-DD for filtering
                                this.dateFrom = selectedDates[0].toISOString().split('T')[0];
                                this.dateTo = selectedDates[1].toISOString().split('T')[0];
                            } else if (selectedDates.length === 0) {
                                // Clear the filter
                                this.dateFrom = '';
                                this.dateTo = '';
                            }
                        },
                        onClose: (selectedDates, dateStr, instance) => {
                            // If only one date is selected, clear both
                            if (selectedDates.length === 1) {
                                instance.clear();
                                this.dateFrom = '';
                                this.dateTo = '';
                            }
                        }
                    });
                });

                (async () => {
                    await this.loadProvinces();
                    this.freelanceId = await this.resolveFreelanceId();
                    if (!this.freelanceId) {
                        this.showToast('Error', 'Freelance tidak ditemukan');
                        return;
                    }
                    try {
                        await this.loadAgents();
                    } catch (e) {
                        console.error(e);
                        this.showToast('Error', 'Gagal memuat daftar agent');
                    }
                })();
            }
        }
    }
</script>
@endpush

@extends('layouts.freelance')

@section('title', 'Daftar Agent - Kuotaumroh.id')

@push('styles')
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

    /* Map lock styles */
    .map-locked {
        cursor: default !important;
        pointer-events: none;
    }
    
    .map-locked * {
        cursor: default !important;
    }

    [x-cloak] {
        display: none !important;
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
        <div class="mb-6 flex items-start gap-4">
            <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-input bg-background hover:bg-muted transition-colors" title="Kembali">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Daftar Agent</h1>
                <p class="text-muted-foreground mt-1">Agent yang terdaftar melalui referral Anda</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-6">
            <div class="rounded-lg border bg-white shadow-sm p-3 sm:p-6">
                <p class="text-xs sm:text-sm text-muted-foreground">Total Agent</p>
                <p class="text-xl sm:text-3xl font-bold mt-1" x-text="stats.total"></p>
            </div>
            <div class="rounded-lg border bg-white shadow-sm p-3 sm:p-6">
                <p class="text-xs sm:text-sm text-muted-foreground">Aktif Bulan Ini</p>
                <p class="text-xl sm:text-3xl font-bold mt-1 text-primary" x-text="stats.activeThisMonth"></p>
            </div>
            <div class="rounded-lg border bg-white shadow-sm p-3 sm:p-6">
                <p class="text-xs sm:text-sm text-muted-foreground">Poin Diperoleh</p>
                <p class="text-xl sm:text-3xl font-bold mt-1" x-text="stats.pointsEarned"></p>
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
                            class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 text-sm font-medium transition-colors w-full sm:w-auto">
                            Tambah Agent
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 sm:justify-end">
                        <!-- Date Range Filter -->
                        <div class="relative w-full sm:w-auto">
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
                        <div class="relative w-full sm:w-auto">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" x-model="search" placeholder="Cari nama/email"
                                class="flex h-10 rounded-md border border-input bg-background pl-9 pr-3 py-2 text-sm w-full sm:w-[200px]">
                        </div>

                        <!-- Status Filter -->
                        <select x-model="statusFilter" class="h-10 rounded-md border border-input bg-background px-3 text-sm w-full sm:w-auto">
                            <option value="all">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto -mx-6 sm:mx-0">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b whitespace-nowrap">
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-center align-middle font-medium text-muted-foreground text-sm">Status</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-center align-middle font-medium text-muted-foreground text-sm">Aksi</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Nama PIC</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Email</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Kategori Agent</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">No. HP</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Nama Travel</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Jenis Travel</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-right align-middle font-medium text-muted-foreground text-sm">Total Travel/Bulan</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-center align-middle font-medium text-muted-foreground text-sm">Logo</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Provinsi</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Kota/Kab</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Detail Alamat</th>
                                <th class="h-10 sm:h-12 px-2 sm:px-4 text-left align-middle font-medium text-muted-foreground text-sm">Koordinat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="agent in filteredAgents" :key="agent.id">
                                <tr class="border-b transition-colors hover:bg-muted/50 whitespace-nowrap">
                                    <td class="p-2 sm:p-4 align-middle text-center">
                                        <span class="inline-flex items-center rounded-full px-2 sm:px-2.5 py-0.5 text-xs font-medium" :class="{
                                            'bg-green-100 text-green-800': agent.status === 'approve',
                                            'bg-red-100 text-red-800': agent.status === 'reject',
                                            'bg-yellow-100 text-yellow-800': agent.status === 'pending'
                                        }" x-text="agent.status === 'approve' ? 'Approve' : (agent.status === 'reject' ? 'Reject' : 'Pending')"></span>
                                    </td>
                                    <td class="p-2 sm:p-4 align-middle text-center">
                                        <button @click="openAgentDetail(agent)" class="text-sm text-primary hover:underline">Detail</button>
                                    </td>
                                    <td class="p-2 sm:p-4 align-middle font-medium text-sm" x-text="agent.nama_pic"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.email"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.kategori_agent || 'Host'"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.no_hp || '-'"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.nama_travel || '-'"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.jenis_travel || '-'"></td>
                                    <td class="p-2 sm:p-4 align-middle text-right text-sm" x-text="(agent.total_traveller ?? 0).toLocaleString('id-ID')"></td>
                                    <td class="p-2 sm:p-4 align-middle text-center">
                                        <button x-show="agent.logo" @click="viewLogo(agent.logo)" class="text-sm text-primary hover:underline">Lihat</button>
                                        <span x-show="!agent.logo" class="text-muted-foreground text-sm">-</span>
                                    </td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.provinsi || '-'"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.kabupaten_kota || '-'"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm" x-text="agent.alamat_lengkap || '-'"></td>
                                    <td class="p-2 sm:p-4 align-middle text-muted-foreground text-sm">
                                        <template x-if="agent.lat && agent.long">
                                            <a :href="`https://www.google.com/maps?q=${agent.lat},${agent.long}`" target="_blank" class="text-primary hover:underline" x-text="`${agent.lat}, ${agent.long}`"></a>
                                        </template>
                                        <span x-show="!agent.lat || !agent.long" class="text-muted-foreground">-</span>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="filteredAgents.length === 0">
                                <tr>
                                    <td colspan="14" class="p-8 text-center text-muted-foreground text-sm">Tidak ada agent ditemukan</td>
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

    <!-- Modal Detail Agent -->
    <div x-show="detailModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeDetailModal()">
        <div class="min-h-full w-full p-4 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50" @click="closeDetailModal()"></div>
            
            <div class="relative w-full max-w-4xl rounded-lg border bg-white shadow-lg overflow-hidden max-h-[calc(100dvh-2rem)] flex flex-col">
                <div class="flex items-center justify-between border-b px-6 py-4 shrink-0">
                    <h3 class="text-lg font-semibold">Detail Agent</h3>
                    <button @click="closeDetailModal()" class="h-9 w-9 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="px-6 py-6 overflow-y-auto flex-1" x-show="selectedAgent">
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Data PIC -->
                        <div class="rounded-lg border bg-white shadow-sm p-6 space-y-4">
                            <h4 class="text-base font-semibold border-b pb-2">Data PIC</h4>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-muted-foreground">Nama PIC</p>
                                    <p class="font-semibold" x-text="selectedAgent?.nama_pic || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-muted-foreground">Email</p>
                                    <p class="font-semibold" x-text="selectedAgent?.email || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-muted-foreground">No. HP</p>
                                    <p class="font-semibold" x-text="selectedAgent?.no_hp || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-muted-foreground">Kategori Agent</p>
                                    <p class="font-semibold" x-text="selectedAgent?.kategori_agent || 'Host'"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detail Travel -->
                        <div class="rounded-lg border bg-white shadow-sm p-6 space-y-4">
                            <h4 class="text-base font-semibold border-b pb-2">Detail Travel</h4>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-muted-foreground">Nama Travel</p>
                                    <p class="font-semibold" x-text="selectedAgent?.nama_travel || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-muted-foreground">Jenis Travel</p>
                                    <p class="font-semibold" x-text="selectedAgent?.jenis_travel || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-muted-foreground">Total Traveller/Bulan</p>
                                    <p class="font-semibold" x-text="(selectedAgent?.total_traveller ?? 0).toLocaleString('id-ID')"></p>
                                </div>
                                <div>
                                    <p class="text-muted-foreground">Logo Travel</p>
                                    <template x-if="selectedAgent?.logo">
                                        <button @click="viewLogo(selectedAgent.logo)" class="text-sm text-primary hover:underline font-semibold">Lihat Logo</button>
                                    </template>
                                    <p x-show="!selectedAgent?.logo" class="font-semibold">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lokasi -->
                    <div class="rounded-lg border bg-white shadow-sm p-6 space-y-4 mt-6">
                        <h4 class="text-base font-semibold border-b pb-2">Lokasi</h4>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-sm text-muted-foreground">Provinsi</p>
                                <p class="font-semibold" x-text="selectedAgent?.provinsi || '-'"></p>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Kota/Kabupaten</p>
                                <p class="font-semibold" x-text="selectedAgent?.kabupaten_kota || '-'"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Alamat Lengkap</p>
                            <p class="font-semibold" x-text="selectedAgent?.alamat_lengkap || '-'"></p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Koordinat</p>
                            <template x-if="selectedAgent?.lat && selectedAgent?.long">
                                <a :href="`https://www.google.com/maps?q=${selectedAgent.lat},${selectedAgent.long}`" target="_blank" class="text-primary hover:underline font-semibold" x-text="`${selectedAgent.lat}, ${selectedAgent.long}`"></a>
                            </template>
                            <p x-show="!selectedAgent?.lat || !selectedAgent?.long" class="font-semibold">-</p>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="rounded-lg border bg-white shadow-sm p-6 space-y-4 mt-6">
                        <h4 class="text-base font-semibold border-b pb-2">Status & Informasi</h4>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <p class="text-sm text-muted-foreground">Status Agent</p>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
                                    'bg-green-100 text-green-800': selectedAgent?.status === 'approve',
                                    'bg-red-100 text-red-800': selectedAgent?.status === 'reject',
                                    'bg-yellow-100 text-yellow-800': selectedAgent?.status === 'pending'
                                }" x-text="selectedAgent?.status === 'approve' ? 'Approve' : (selectedAgent?.status === 'reject' ? 'Reject' : 'Pending')"></span>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">Tanggal Bergabung</p>
                                <p class="font-semibold" x-text="selectedAgent?.created_at ? new Date(selectedAgent.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-2 border-t bg-white px-6 py-4 shrink-0">
                    <button @click="closeDetailModal()" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 text-sm font-medium transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
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
                    <form id="addAgentForm" @submit.prevent="showConfirmModal()" class="space-y-6">
                        @csrf
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

                                <!-- Map Lock/Unlock Button -->
                                <div class="mb-2">
                                    <button type="button" @click="toggleMapLock()" 
                                        class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-md border transition-colors"
                                        :class="mapLocked ? 'bg-muted text-muted-foreground border-input hover:bg-muted/80' : 'bg-primary text-primary-foreground border-primary hover:bg-primary/90'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mapLocked">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mapLocked">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        <span x-text="mapLocked ? 'Klik untuk Geser Peta' : 'Kunci Peta'"></span>
                                    </button>
                                </div>

                                <!-- Map Search -->
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

                                    <!-- Search Results Dropdown -->
                                    <div x-show="mapSearchResults.length > 0" @click.away="mapSearchResults = []"
                                        class="absolute z-40 w-full mt-1 bg-white rounded-lg border border-gray-200 shadow-xl max-h-80 overflow-y-auto"
                                        style="display: none;">
                                        <div class="py-1">
                                            <template x-for="(result, index) in mapSearchResults" :key="index">
                                                <button type="button" @click="selectMapLocation(result)"
                                                    class="w-full text-left px-4 py-3 hover:bg-green-50 transition-colors border-b border-gray-100 last:border-0 group">
                                                    <div class="flex items-start gap-3">
                                                        <!-- Location Icon -->
                                                        <div class="flex-shrink-0 mt-0.5">
                                                            <svg class="h-5 w-5 text-green-600 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                        <!-- Text Content -->
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-medium text-gray-900 group-hover:text-green-700 mb-0.5" 
                                                                x-text="result.name || (result.description ? result.description.split(',')[0] : result.display_name)"></div>
                                                            <div class="text-xs text-gray-500 truncate leading-relaxed" 
                                                                x-text="result.description || result.display_name"></div>
                                                        </div>
                                                        <!-- Arrow Icon -->
                                                        <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Container with Overlay -->
                                <div class="relative">
                                    <div id="addAgentMap" class="w-full h-80 rounded-md border border-input overflow-hidden bg-gray-100"
                                        x-init="$nextTick(() => { if (addFormData.city && !mapInitialized) initializeMap(); })"></div>
                                    
                                    <!-- Locked Overlay -->
                                    <div x-show="mapLocked" 
                                        @click="toggleMapLock()"
                                        class="absolute inset-0 bg-black/10 backdrop-blur-[1px] rounded-md cursor-pointer flex items-center justify-center transition-opacity hover:bg-black/20 group">
                                        <div class="bg-white/95 px-6 py-4 rounded-lg shadow-lg border border-primary/20 text-center max-w-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <p class="text-sm font-semibold text-foreground mb-1">Peta Dikunci</p>
                                            <p class="text-xs text-muted-foreground leading-relaxed">Peta tidak akan bergerak saat Anda scroll halaman. Klik di sini untuk mengaktifkan peta agar bisa digeser dan di-zoom.</p>
                                        </div>
                                    </div>
                                </div>

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

    <!-- Modal Konfirmasi -->
    <div x-show="confirmModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4"
        @click.self="confirmModalOpen = false">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-semibold">Konfirmasi Data Agent</h3>
                <button @click="confirmModalOpen = false" class="h-9 w-9 inline-flex items-center justify-center rounded-md hover:bg-muted transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4 space-y-3 max-h-[60vh] overflow-y-auto">
                <p class="text-sm text-muted-foreground mb-4">Pastikan data berikut sudah benar sebelum menyimpan:</p>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b">
                        <span class="font-medium text-muted-foreground">Email:</span>
                        <span class="font-semibold" x-text="addFormData.email"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="font-medium text-muted-foreground">Nama PIC:</span>
                        <span class="font-semibold" x-text="addFormData.full_name"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="font-medium text-muted-foreground">No. HP:</span>
                        <span class="font-semibold" x-text="'+62' + addFormData.phone"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b" x-show="addFormData.travel_name">
                        <span class="font-medium text-muted-foreground">Nama Travel:</span>
                        <span class="font-semibold" x-text="addFormData.travel_name || '-'"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b" x-show="addFormData.travel_type">
                        <span class="font-medium text-muted-foreground">Jenis Travel:</span>
                        <span class="font-semibold" x-text="addFormData.travel_type || '-'"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="font-medium text-muted-foreground">Provinsi:</span>
                        <span class="font-semibold" x-text="addFormData.province"></span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="font-medium text-muted-foreground">Kota/Kab:</span>
                        <span class="font-semibold" x-text="addFormData.city"></span>
                    </div>
                    <div class="py-2 border-b">
                        <span class="font-medium text-muted-foreground">Alamat:</span>
                        <p class="mt-1 text-foreground" x-text="addFormData.address"></p>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="font-medium text-muted-foreground">Kategori Agent:</span>
                        <span class="font-semibold text-primary">Host</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 border-t px-6 py-4">
                <button type="button" @click="confirmModalOpen = false"
                    class="inline-flex items-center justify-center rounded-md border bg-transparent hover:bg-muted h-10 px-4 text-sm font-medium transition-colors">
                    Batal
                </button>
                <button type="button" @click="submitAddAgent()" :disabled="isSubmitting"
                    class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 text-sm font-medium transition-colors disabled:opacity-50">
                    <span x-show="!isSubmitting">Ya, Simpan</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Google Maps JavaScript API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initGoogleMapsDownlinesFreelance" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Google Maps callback
    function initGoogleMapsDownlinesFreelance() {
        console.log('Google Maps API loaded for freelance downlines');
        window.googleMapsLoadedDownlinesFreelance = true;
    }
</script>

<script>
    function downlinesPage() {
        return {
            search: '',
            statusFilter: 'all',
            dateFrom: '',
            dateTo: '',
            sortColumn: 'joinDate',
            sortDirection: 'desc',
            agents: {!! json_encode($agents) !!},
            confirmModalOpen: false,
            stats: {
                total: {{ $stats['totalAgents'] ?? 0 }},
                activeThisMonth: {{ $stats['activeAgentsThisMonth'] ?? 0 }},
                pointsEarned: {{ $stats['saldoFee'] ?? ($user->saldo_fee ?? 0) }}
            },
            toastVisible: false,
            toastTitle: '',
            toastMessage: '',

            detailModalOpen: false,
            selectedAgent: null,

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
            mapLocked: true, // Lock map by default to prevent accidental scrolling
            placesService: null, // Places Service instance
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

            async openAddModal() {
                console.log('Opening add modal, current provinces count:', this.provinces.length);
                this.resetAddForm();
                this.addErrors = {};
                // Ensure provinces are loaded
                if (this.provinces.length === 0) {
                    console.log('Provinces empty, loading...');
                    await this.loadProvinces();
                    console.log('Provinces loaded:', this.provinces.length);
                }
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
                    const response = await fetch('/wilayah/provinces.json');
                    if (!response.ok) throw new Error('Gagal memuat provinsi');
                    const data = await response.json();
                    if (data && data.data) {
                        this.provinces = data.data.map(p => p.name).sort();
                        this.provinceCodes = new Map();
                        data.data.forEach(p => this.provinceCodes.set(p.name, p.code));
                        console.log('Loaded', this.provinces.length, 'provinces');
                    }
                } catch (e) {
                    console.error('Error loading provinces:', e);
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
                        console.log('Loaded', this.cities.length, 'cities for province', provinceCode);
                    }
                } catch (e) {
                    console.error('Error loading cities:', e);
                    this.showToast('Error', 'Gagal memuat data kota/kabupaten');
                    this.cities = [];
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

            toggleMapLock() {
                this.mapLocked = !this.mapLocked;
                
                if (this.mapInstance) {
                    const mapContainer = document.getElementById('addAgentMap');
                    
                    if (this.mapLocked) {
                        // Lock the map
                        this.mapInstance.setOptions({ 
                            draggable: false,
                            zoomControl: false,
                            scrollwheel: false,
                            disableDoubleClickZoom: true
                        });
                        if (mapContainer) mapContainer.classList.add('map-locked');
                        
                        this.showToast('Peta Dikunci', 'Peta tidak akan bergerak saat Anda scroll. Lokasi Anda aman!');
                    } else {
                        // Unlock the map
                        this.mapInstance.setOptions({ 
                            draggable: true,
                            zoomControl: true,
                            scrollwheel: true,
                            disableDoubleClickZoom: false
                        });
                        if (mapContainer) mapContainer.classList.remove('map-locked');
                        
                        this.showToast('Peta Aktif', 'Anda sekarang bisa menggeser dan zoom peta');
                    }
                }
            },

            async recenterMapToCity() {
                if (!this.mapInstance || !this.addFormData.city || typeof google === 'undefined') return;
                try {
                    const searchQuery = this.addFormData.province ?
                        `${this.addFormData.city}, ${this.addFormData.province}, Indonesia` :
                        `${this.addFormData.city}, Indonesia`;
                    
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ address: searchQuery }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            const location = results[0].geometry.location;
                            this.mapInstance.panTo(location);
                            this.mapInstance.setZoom(11);
                        }
                    });
                } catch {}
            },

            async initializeMap() {
                if (this.mapInitialized) return;
                
                // Wait for Google Maps to load
                let attempts = 0;
                while (typeof google === 'undefined' && attempts < 50) {
                    await new Promise(resolve => setTimeout(resolve, 100));
                    attempts++;
                }
                
                if (typeof google === 'undefined') {
                    console.error('Google Maps failed to load');
                    return;
                }
                
                let centerLat = -2.5;
                let centerLng = 118.0;
                let zoomLevel = 5;

                try {
                    if (this.addFormData.city) {
                        try {
                            const searchQuery = this.addFormData.province ?
                                `${this.addFormData.city}, ${this.addFormData.province}, Indonesia` :
                                `${this.addFormData.city}, Indonesia`;
                            
                            const geocoder = new google.maps.Geocoder();
                            const result = await new Promise((resolve) => {
                                geocoder.geocode({ address: searchQuery }, (results, status) => {
                                    if (status === 'OK' && results[0]) {
                                        resolve(results[0].geometry.location);
                                    } else {
                                        resolve(null);
                                    }
                                });
                            });
                            
                            if (result) {
                                centerLat = result.lat();
                                centerLng = result.lng();
                                zoomLevel = 11;
                            }
                        } catch {}
                    }

                    const mapContainer = document.getElementById('addAgentMap');
                    this.mapInstance = new google.maps.Map(mapContainer, {
                        center: { lat: centerLat, lng: centerLng },
                        zoom: zoomLevel,
                        draggable: !this.mapLocked,
                        zoomControl: !this.mapLocked,
                        scrollwheel: !this.mapLocked,
                        disableDoubleClickZoom: this.mapLocked,
                        mapTypeControl: false,
                        streetViewControl: false,
                        fullscreenControl: true
                    });

                    // Initialize Places Service for better search results
                    this.placesService = new google.maps.places.PlacesService(this.mapInstance);

                    // Apply map-locked class if locked
                    if (this.mapLocked && mapContainer) {
                        mapContainer.classList.add('map-locked');
                    }

                    if (this.addFormData.latitude && this.addFormData.longitude) {
                        this.updateMarker(this.addFormData.latitude, this.addFormData.longitude);
                    }

                    this.mapInstance.addListener('click', (e) => {
                        if (!this.mapLocked) {
                            const lat = e.latLng.lat();
                            const lng = e.latLng.lng();
                            this.updateMarker(lat, lng);
                        } else {
                            this.mapLocked = true;
                            this.showToast('Peta Dikunci', 'Klik tombol Kunci Peta untuk mengaktifkan peta');
                        }
                    });

                    this.mapInitialized = true;
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

                // Debounce search
                if (this.mapSearchDebounce) clearTimeout(this.mapSearchDebounce);

                this.mapSearchDebounce = setTimeout(() => {
                    if (typeof google === 'undefined' || !google.maps) {
                        console.error('Google Maps not loaded');
                        return;
                    }
                    
                    // Wait for map to be initialized
                    if (!this.mapInstance) {
                        console.warn('Map not initialized yet, waiting...');
                        setTimeout(() => this.handleMapSearch(), 500);
                        return;
                    }
                    
                    this.isSearchingMap = true;
                    
                    // Build search query with city and province context
                    let searchQuery = this.mapSearchQuery;
                    if (this.addFormData.city) {
                        searchQuery += `, ${this.addFormData.city}`;
                    }
                    if (this.addFormData.province) {
                        searchQuery += `, ${this.addFormData.province}`;
                    }
                    searchQuery += ', Indonesia';
                    
                    console.log('Searching for:', searchQuery);
                    
                    // Use Places Service if available
                    if (this.placesService) {
                        console.log('Using Places API');
                        
                        const request = {
                            query: searchQuery,
                            fields: ['name', 'formatted_address', 'geometry', 'place_id']
                        };
                        
                        this.placesService.findPlaceFromQuery(request, (results, status) => {
                            console.log('findPlaceFromQuery status:', status);
                            
                            if (status === google.maps.places.PlacesServiceStatus.OK && results && results.length > 0) {
                                // Success with Places API!
                                this.mapSearchResults = results.map(r => ({
                                    place_id: r.place_id,
                                    description: r.formatted_address,
                                    display_name: r.formatted_address,
                                    geometry: r.geometry,
                                    name: r.name || r.formatted_address.split(',')[0]
                                }));
                                console.log('Places API success:', this.mapSearchResults.length, 'results');
                                this.isSearchingMap = false;
                            } else if (status === google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
                                // Try textSearch for broader results
                                console.log('findPlaceFromQuery zero results, trying textSearch');
                                this.placesService.textSearch(request, (results, status) => {
                                    if (status === google.maps.places.PlacesServiceStatus.OK && results && results.length > 0) {
                                        this.mapSearchResults = results.slice(0, 10).map(r => ({
                                            place_id: r.place_id,
                                            description: r.formatted_address,
                                            display_name: r.formatted_address,
                                            geometry: r.geometry,
                                            name: r.name || r.formatted_address.split(',')[0]
                                        }));
                                        console.log('textSearch success:', this.mapSearchResults.length);
                                        this.isSearchingMap = false;
                                    } else {
                                        console.log('textSearch failed, using Geocoding');
                                        this.useGeocodingSearch(searchQuery);
                                    }
                                });
                            } else {
                                console.log('Places API error:', status, '- using Geocoding fallback');
                                this.useGeocodingSearch(searchQuery);
                            }
                        });
                    } else {
                        // Places Service not available
                        console.log('Places Service not initialized, using Geocoding API');
                        this.useGeocodingSearch(searchQuery);
                    }
                }, 500);
            },
            
            useGeocodingSearch(searchQuery) {
                const geocoder = new google.maps.Geocoder();
                
                geocoder.geocode({ 
                    address: searchQuery,
                    region: 'id'
                }, (results, status) => {
                    console.log('Geocoding status:', status, 'Results:', results?.length || 0);
                    this.isSearchingMap = false;
                    
                    if (status === 'OK' && results && results.length > 0) {
                        this.mapSearchResults = results.slice(0, 10).map(r => {
                            // Extract better name from address components
                            let name = '';
                            
                            // Try to get POI, premise, or street address as name
                            const nameComponent = r.address_components?.find(c => 
                                c.types.includes('point_of_interest') ||
                                c.types.includes('premise') ||
                                c.types.includes('street_address') ||
                                c.types.includes('route')
                            );
                            
                            if (nameComponent) {
                                name = nameComponent.long_name;
                            } else {
                                // Fallback: use first part of formatted address
                                name = r.formatted_address.split(',')[0];
                            }
                            
                            return {
                                place_id: r.place_id,
                                description: r.formatted_address,
                                display_name: r.formatted_address,
                                geometry: r.geometry,
                                name: name
                            };
                        });
                        
                        console.log('Geocoding results:', this.mapSearchResults.length);
                    } else {
                        console.log('No geocoding results');
                        this.mapSearchResults = [];
                    }
                });
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
                    if (typeof google === 'undefined') return;
                    
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ address: `${this.mapSearchQuery}, Indonesia` }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            this.selectMapLocation(results[0]);
                        } else {
                            this.showToast('Info', 'Lokasi tidak ditemukan');
                        }
                        this.isSearchingMap = false;
                    });
                } catch (e) {
                    console.error(e);
                    this.isSearchingMap = false;
                }
            },

            selectMapLocation(result) {
                if (typeof google === 'undefined' || !this.mapInstance) return;

                // Check if result has geometry (from Geocoder)
                if (result.geometry && result.geometry.location) {
                    const location = result.geometry.location;
                    const lat = typeof location.lat === 'function' ? location.lat() : location.lat;
                    const lng = typeof location.lng === 'function' ? location.lng() : location.lng;

                    this.addFormData.latitude = lat;
                    this.addFormData.longitude = lng;

                    this.mapInstance.panTo(location);
                    this.mapInstance.setZoom(16);
                    this.updateMarker(lat, lng);

                    this.mapSearchResults = [];
                    if (result.formatted_address) {
                        this.mapSearchQuery = result.formatted_address;
                    }
                } 
                // Check if result is from Places API (has place_id)
                else if (result.place_id) {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ placeId: result.place_id }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            const location = results[0].geometry.location;
                            const lat = location.lat();
                            const lng = location.lng();

                            this.addFormData.latitude = lat;
                            this.addFormData.longitude = lng;

                            this.mapInstance.panTo(location);
                            this.mapInstance.setZoom(16);
                            this.updateMarker(lat, lng);

                            this.mapSearchResults = [];
                            this.mapSearchQuery = result.description;
                        }
                    });
                }
            },

            async geocodeAddress() {
                if (!this.addFormData.address || this.addFormData.address.length < 5) return;
                if (!this.mapInstance || typeof google === 'undefined') return;

                this.isGeocodingAddress = true;
                const queryItems = [this.addFormData.address];
                if (this.addFormData.city) queryItems.push(this.addFormData.city);
                if (this.addFormData.province) queryItems.push(this.addFormData.province);
                const query = queryItems.join(', ');

                try {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ address: query }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            const location = results[0].geometry.location;
                            const lat = location.lat();
                            const lng = location.lng();
                            
                            this.mapInstance.panTo(location);
                            this.mapInstance.setZoom(18);
                            this.updateMarker(lat, lng);
                        }
                        this.isGeocodingAddress = false;
                    });
                } catch (e) {
                    console.error(e);
                    this.isGeocodingAddress = false;
                }
            },

            updateMapFromCoordinates() {
                const lat = parseFloat(this.addFormData.latitude);
                const lng = parseFloat(this.addFormData.longitude);
                if (!isNaN(lat) && !isNaN(lng) && this.mapInstance && typeof google !== 'undefined') {
                    this.updateMarker(lat, lng);
                    const position = new google.maps.LatLng(lat, lng);
                    this.mapInstance.panTo(position);
                    this.mapInstance.setZoom(16);
                }
            },

            updateMarker(lat, lng) {
                if (!this.mapInstance || typeof google === 'undefined') return;
                
                const position = new google.maps.LatLng(lat, lng);
                
                // Strategy: UPDATE existing marker position instead of creating new one
                if (this.marker) {
                    console.log('Updating existing marker position to:', lat, lng);
                    this.marker.setPosition(position);
                    this.marker.setAnimation(google.maps.Animation.DROP);
                } else {
                    // Create new marker ONLY if none exists
                    console.log('Creating first marker at:', lat, lng);
                    this.marker = new google.maps.Marker({
                        position: position,
                        map: this.mapInstance,
                        draggable: true,
                        animation: google.maps.Animation.DROP
                    });
                    
                    // Update coordinates when marker is dragged (add listener only once)
                    this.marker.addListener('dragend', (e) => {
                        this.addFormData.latitude = e.latLng.lat();
                        this.addFormData.longitude = e.latLng.lng();
                    });
                }
                
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

            viewLogo(logoPath) {
                if (!logoPath) return;
                // Paksa gunakan absolute URL dengan origin
                const baseUrl = window.location.origin;
                const fullUrl = `${baseUrl}/storage/${logoPath}`;
                console.log('Opening logo:', fullUrl);
                window.open(fullUrl, '_blank');
            },

            openFileModal(fileUrl) {
                if (!fileUrl) return;
                console.log('Original fileUrl:', fileUrl);
                
                // Jika sudah URL lengkap (http/https), buka langsung
                if (fileUrl.startsWith('http://') || fileUrl.startsWith('https://')) {
                    console.log('Opening full URL:', fileUrl);
                    window.open(fileUrl, '_blank');
                    return;
                }
                
                // Buat URL absolute untuk menghindari relative path
                let url;
                if (fileUrl.startsWith('/')) {
                    url = window.location.origin + fileUrl;
                } else {
                    url = window.location.origin + '/storage/' + fileUrl;
                }
                
                console.log('Final URL:', url);
                window.open(url, '_blank');
            },

            openAgentDetail(agent) {
                this.selectedAgent = agent;
                this.detailModalOpen = true;
            },

            closeDetailModal() {
                this.detailModalOpen = false;
                this.selectedAgent = null;
            },

            showConfirmModal() {
                this.addErrors = {};

                // Validasi
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

                // Show confirmation modal
                this.confirmModalOpen = true;
            },

            async submitAddAgent() {
                this.confirmModalOpen = false;
                this.isSubmitting = true;

                this.isSubmitting = true;
                try {
                    // Build FormData
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    formData.append('email', this.addFormData.email);
                    formData.append('nama_pic', this.addFormData.full_name);

                    // Format phone number
                    let phone = String(this.addFormData.phone);
                    if (phone.startsWith('0')) phone = phone.substring(1);
                    if (phone.startsWith('62')) phone = phone.substring(2);
                    formData.append('no_hp', '62' + phone);

                    // Optional fields
                    if (this.addFormData.travel_name) {
                        formData.append('nama_travel', this.addFormData.travel_name);
                    }
                    if (this.addFormData.travel_type) {
                        formData.append('jenis_travel', this.addFormData.travel_type);
                    }
                    if (this.addFormData.travel_member !== '' && this.addFormData.travel_member !== null) {
                        formData.append('total_traveller', String(this.addFormData.travel_member));
                    }

                    // Location fields
                    formData.append('provinsi', this.addFormData.province);
                    formData.append('kabupaten_kota', this.addFormData.city);
                    formData.append('alamat_lengkap', this.addFormData.address);

                    if (this.addFormData.latitude) {
                        formData.append('lat', String(this.addFormData.latitude));
                    }
                    if (this.addFormData.longitude) {
                        formData.append('long', String(this.addFormData.longitude));
                    }
                    if (this.addFormData.latitude && this.addFormData.longitude) {
                        formData.append('link_gmaps', `https://www.google.com/maps?q=${this.addFormData.latitude},${this.addFormData.longitude}`);
                    }

                    // Logo file
                    if (this.addLogoFile) {
                        formData.append('logo', this.addLogoFile);
                    }

                    // Submit to Laravel route
                    const response = await fetch('{{ route("dash.downlines.store-agent", ["link_referral" => $linkReferral]) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const json = await response.json();

                    if (!response.ok) {
                        const msg = json?.message || 'Gagal menambahkan agent';
                        
                        // Handle validation errors
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
                    
                    // Reload page to show new agent
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    
                } catch (e) {
                    console.error('Error submitting agent:', e);
                    this.showToast('Error', 'Gagal menambahkan agent. Silakan coba lagi.');
                } finally {
                    this.isSubmitting = false;
                }
            },

            get filteredAgents() {
                if (!Array.isArray(this.agents)) return [];
                
                let filtered = this.agents.filter(a => {
                    const matchSearch = !this.search || 
                        (a.nama_pic && a.nama_pic.toLowerCase().includes(this.search.toLowerCase())) || 
                        (a.email && a.email.toLowerCase().includes(this.search.toLowerCase()));
                    const matchStatus = this.statusFilter === 'all' || a.status === this.statusFilter;

                    // Date range filter
                    let matchDate = true;
                    if (this.dateFrom || this.dateTo) {
                        const joinDate = new Date(a.created_at);
                        if (this.dateFrom) {
                            matchDate = matchDate && joinDate >= new Date(this.dateFrom);
                        }
                        if (this.dateTo) {
                            matchDate = matchDate && joinDate <= new Date(this.dateTo);
                        }
                    }

                    return matchSearch && matchStatus && matchDate;
                });

                return filtered;
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
                renderHeader('downlines');

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
                    
                    // Hitung stats langsung dari agents
                    if (Array.isArray(this.agents)) {
                        const now = new Date();
                        this.stats.total = this.agents.length;
                        this.stats.activeThisMonth = this.agents.filter(a => {
                            const createdAt = new Date(a.created_at);
                            return a.status === 'approve' && 
                                   createdAt.getFullYear() === now.getFullYear() && 
                                   createdAt.getMonth() === now.getMonth();
                        }).length;
                    }
                })();
            }
        }
    }
</script>
@endpush

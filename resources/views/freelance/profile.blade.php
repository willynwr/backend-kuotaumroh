@extends('layouts.freelance')

@section('title', 'Profil Freelance - Kuotaumroh.id')

@section('content')
<!-- Header handled by layout -->

<div x-data="profileApp()">
    <main class="container mx-auto py-8 animate-fade-in px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section with Gradient -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <button onclick="history.back()" class="inline-flex items-center justify-center w-12 h-12 rounded-xl border-2 border-gray-200 bg-white hover:bg-gray-50 hover:border-primary transition-all shadow-sm" title="Kembali">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="flex-1">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-primary to-green-600 bg-clip-text text-transparent">Profil Saya</h1>
                        <p class="text-gray-600 mt-2 text-lg">Kelola informasi akun freelance Anda</p>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="flex flex-col justify-center items-center py-32">
                <div class="relative">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200"></div>
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-primary border-t-transparent absolute top-0"></div>
                </div>
                <p class="mt-4 text-gray-600 font-medium">Memuat data profil...</p>
            </div>

            <!-- Error State -->
            <div x-show="error && !loading" class="rounded-xl border-2 border-red-200 bg-red-50 p-6 mb-6 shadow-sm">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-red-900 mb-1">Terjadi Kesalahan</h3>
                        <p class="text-red-700" x-text="error"></p>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div x-show="!loading && !error" class="space-y-6">
                <!-- Stats Cards with Modern Design -->
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Total Poin Card -->
                    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-emerald-100 text-sm font-medium mb-1">Total Poin</p>
                            <h3 class="text-4xl font-bold text-white mb-2" x-text="formatCurrency(user.saldo_fee)">0</h3>
                            <p class="text-emerald-100 text-sm">Saldo tersedia untuk ditarik</p>
                        </div>
                    </div>

                    <!-- Total Fee Card -->
                    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Fee</p>
                            <h3 class="text-4xl font-bold text-white mb-2" x-text="formatCurrency(user.total_fee)">0</h3>
                            <p class="text-blue-100 text-sm">Akumulasi fee keseluruhan</p>
                        </div>
                    </div>

                    <!-- Agents Recruited Card -->
                    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Agent Terekrut</p>
                            <h3 class="text-4xl font-bold text-white mb-2" x-text="user.agents_recruited || 0">0</h3>
                            <p class="text-purple-100 text-sm">
                                <span x-text="user.active_agents || 0">0</span> agent aktif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Main Profile Card with Modern Design -->
                <div class="rounded-2xl border border-gray-200 bg-white shadow-xl overflow-hidden">
                    <!-- Card Header with Gradient -->
                    <div class="bg-gradient-to-r from-primary to-green-600 px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white" x-text="user.nama || 'Loading...'">Loading...</h2>
                                <p class="text-white text-opacity-90 mt-1">Freelance Kuotaumroh.id</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <!-- Personal Information -->
                        <div class="space-y-5">
                            <div class="flex items-center gap-3 pb-3 border-b-2 border-gray-100">
                                <div class="p-2 bg-primary bg-opacity-10 rounded-lg">
                                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Informasi Pribadi</h3>
                            </div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Nama Lengkap</label>
                                    <div class="flex items-center h-12 w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2 text-base font-medium text-gray-800 hover:border-primary transition-colors">
                                        <span x-text="user.nama || '-'">-</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Email</label>
                                    <div class="flex items-center h-12 w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2 text-base font-medium text-gray-800 hover:border-primary transition-colors">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span x-text="user.email || '-'">-</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">No. WhatsApp</label>
                                    <div class="flex items-center h-12 w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2 text-base font-medium text-gray-800 hover:border-primary transition-colors">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span x-text="user.no_wa || '-'">-</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Status Akun</label>
                                    <div class="flex items-center h-12">
                                        <span :class="user.is_active ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300'" 
                                              class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold border-2 shadow-sm">
                                            <span class="w-2.5 h-2.5 rounded-full mr-2 animate-pulse" :class="user.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                            <span x-text="user.is_active ? 'Aktif' : 'Tidak Aktif'">-</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="space-y-5">
                            <div class="flex items-center gap-3 pb-3 border-b-2 border-gray-100">
                                <div class="p-2 bg-blue-500 bg-opacity-10 rounded-lg">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Informasi Lokasi</h3>
                            </div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Provinsi</label>
                                    <div class="flex items-center h-12 w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2 text-base font-medium text-gray-800 hover:border-blue-500 transition-colors">
                                        <span x-text="user.provinsi || '-'">-</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Kabupaten/Kota</label>
                                    <div class="flex items-center h-12 w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2 text-base font-medium text-gray-800 hover:border-blue-500 transition-colors">
                                        <span x-text="user.kab_kota || '-'">-</span>
                                    </div>
                                </div>
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Alamat Lengkap</label>
                                    <div class="flex items-start min-h-[80px] w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-3 text-base font-medium text-gray-800 hover:border-blue-500 transition-colors">
                                        <svg class="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span x-text="user.alamat_lengkap || '-'">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Referral Information -->
                        <div class="space-y-5">
                            <div class="flex items-center gap-3 pb-3 border-b-2 border-gray-100">
                                <div class="p-2 bg-purple-500 bg-opacity-10 rounded-lg">
                                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Informasi Referral</h3>
                            </div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Link Referral</label>
                                    <div class="relative">
                                        <a :href="`/agent/${user.link_referral}`" 
                                           target="_blank"
                                           class="flex items-center h-12 w-full rounded-xl border-2 border-purple-200 bg-purple-50 px-4 py-2 text-base font-mono font-semibold text-purple-800 hover:border-purple-400 hover:bg-purple-100 transition-all group cursor-pointer">
                                            <svg class="h-5 w-5 text-purple-500 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            <span x-text="user.link_referral || '-'" class="flex-1">-</span>
                                            <svg class="h-5 w-5 text-purple-500 ml-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                        <button @click="copyReferralLink()" 
                                                class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm hover:shadow-md flex items-center gap-1.5">
                                            <svg x-show="!copied" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg x-show="copied" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span x-text="copied ? 'Tersalin!' : 'Salin'">Salin</span>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Klik untuk membuka halaman signup agent dengan link referral Anda
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Bergabung Sejak</label>
                                    <div class="flex items-center h-12 w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2 text-base font-medium text-gray-800 hover:border-purple-500 transition-colors">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span x-text="formatDate(user.date_register)">-</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">ID Freelance</label>
                                    <div class="flex items-center h-12 w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-2 text-base font-mono font-semibold text-gray-800 hover:border-purple-500 transition-colors">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                        <span x-text="user.id || '-'">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Notice with Modern Design -->
                <div class="rounded-2xl border-2 border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 shadow-sm">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-blue-900 text-lg mb-1">Informasi Penting</h4>
                            <p class="text-blue-800 leading-relaxed">Data profil Anda saat ini tidak dapat diubah melalui halaman ini. Jika Anda memerlukan perubahan data, silakan hubungi administrator melalui WhatsApp atau email support.</p>
                        </div>
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
            loading: true,
            error: null,
            copied: false,

            formatDate(dateStr) {
                if (!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            },

            formatCurrency(value) {
                if (!value && value !== 0) return '0';
                return new Intl.NumberFormat('id-ID').format(value);
            },

            async copyReferralLink() {
                try {
                    const fullUrl = `${window.location.origin}/agent/${this.user.link_referral}`;
                    await navigator.clipboard.writeText(fullUrl);
                    this.copied = true;
                    
                    // Reset copied state after 2 seconds
                    setTimeout(() => {
                        this.copied = false;
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = `${window.location.origin}/agent/${this.user.link_referral}`;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        this.copied = true;
                        setTimeout(() => {
                            this.copied = false;
                        }, 2000);
                    } catch (err2) {
                        console.error('Fallback copy failed:', err2);
                    }
                    document.body.removeChild(textArea);
                }
            },

            async fetchProfile() {
                try {
                    this.loading = true;
                    this.error = null;

                    // Get link_referral from URL path: /dash/{link_referral}/profile
                    const pathParts = window.location.pathname.split('/');
                    const linkReferral = pathParts[2]; // /dash/{link_referral}/profile
                    
                    if (!linkReferral) {
                        this.error = 'Link referral tidak ditemukan di URL. Silakan akses melalui dashboard.';
                        this.loading = false;
                        return;
                    }

                    const response = await fetch(`/api/freelance/profile/${linkReferral}`);
                    
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data profil');
                    }

                    const result = await response.json();
                    this.user = result.data;
                    this.loading = false;
                } catch (err) {
                    console.error('Error fetching profile:', err);
                    this.error = err.message || 'Terjadi kesalahan saat mengambil data profil';
                    this.loading = false;
                }
            },

            init() {
                if (!requireRole(['freelance', 'affiliate'], true)) return;
                syncFreelanceIdFromUrl();
                
                this.fetchProfile();
            }
        }
    }
</script>
@endpush

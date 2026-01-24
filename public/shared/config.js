/**
 * Konfigurasi API Terpusat
 * Kuotaumroh.id Portal
 * 
 * File ini berisi konfigurasi URL API yang digunakan di seluruh aplikasi.
 * Ubah nilai API_BASE_URL di sini untuk mengganti URL API di semua file.
 */

// ===========================
// KONFIGURASI UTAMA
// ===========================

/**
 * Base URL untuk API Backend
 * 
 * PENTING: Selalu gunakan proxy Laravel (same-origin) untuk keamanan dan menghindari CORS
 * Proxy Laravel akan meneruskan request ke external API
 * 
 * Ini akan:
 * 1. Mengatasi CORS issues
 * 2. Menyembunyikan endpoint external API dari user
 * 3. Memungkinkan auth middleware di Laravel
 */
const API_BASE_URL = 'https://tokodigi.id'; // Sementara menggunakan tokodigi.id

/**
 * Base URL untuk API endpoints
 * Otomatis menambahkan /api di belakang base URL
 */
const API_URL = `${API_BASE_URL}/api`;

/**
 * Base URL untuk storage/file uploads
 * Digunakan untuk mengakses file yang diupload (foto, dokumen, dll)
 */
const STORAGE_URL = `${API_BASE_URL}/storage`;

// ===========================
// HELPER FUNCTIONS
// ===========================

/**
 * Membuat URL lengkap untuk API endpoint
 * @param {string} endpoint - Endpoint path (contoh: '/agents', '/affiliates')
 * @returns {string} URL lengkap
 * 
 * Contoh penggunaan:
 * apiUrl('/agents') => 'agents'
 */
function apiUrl(endpoint) {
    // Hapus leading slash jika ada
    const cleanEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
    return `${API_URL}/${cleanEndpoint}`;
}

/**
 * Membuat URL lengkap untuk storage file
 * @param {string} path - Path file di storage
 * @returns {string} URL lengkap
 * 
 * Contoh penggunaan:
 * storageUrl('uploads/foto.jpg') => 'http://127.0.0.1:8000/storage/uploads/foto.jpg'
 */
function storageUrl(path) {
    // Hapus leading slash jika ada
    const cleanPath = path.startsWith('/') ? path.slice(1) : path;
    return `${STORAGE_URL}/${cleanPath}`;
}

/**
 * Membuat fetch request dengan headers default
 * @param {string} url - URL endpoint
 * @param {Object} options - Fetch options
 * @returns {Promise} Fetch promise
 * 
 * Contoh penggunaan:
 * apiFetch(apiUrl('/agents'), { method: 'GET' })
 */
async function apiFetch(url, options = {}) {
    const defaultHeaders = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    };

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...options.headers,
        },
    };

    try {
        const response = await fetch(url, config);
        return response;
    } catch (error) {
        console.error('API Fetch Error:', error);
        throw error;
    }
}

// ===========================
// EXPORT (untuk ES6 modules)
// ===========================

// Jika menggunakan ES6 modules, uncomment baris di bawah:
// export { API_BASE_URL, API_URL, STORAGE_URL, apiUrl, storageUrl, apiFetch };

# Konfigurasi API Terpusat

## 📋 Deskripsi

File `config.js` adalah file konfigurasi terpusat untuk mengelola URL API di seluruh aplikasi. Dengan file ini, Anda hanya perlu mengubah URL API di satu tempat saja.

## 🚀 Cara Penggunaan

### 1. Load File Config di HTML

Tambahkan script `config.js` **SEBELUM** script lain yang menggunakan API:

```html
<head>
  <!-- Load config terlebih dahulu -->
  <script src="../shared/config.js"></script>
  
  <!-- Kemudian load script lain -->
  <script src="../shared/api.js"></script>
  <script src="../shared/utils.js"></script>
</head>
```

### 2. Menggunakan Helper Functions

#### a. Untuk API Endpoints

```javascript
// ❌ JANGAN seperti ini (hardcoded):
fetch('agents')

// ✅ GUNAKAN seperti ini:
fetch(apiUrl('agents'))
// atau
fetch(apiUrl('/agents'))  // dengan atau tanpa leading slash
```

#### b. Untuk Storage/File URLs

```javascript
// ❌ JANGAN seperti ini:
const imageUrl = `http://127.0.0.1:8000/storage/${path}`;

// ✅ GUNAKAN seperti ini:
const imageUrl = storageUrl(path);
```

#### c. Menggunakan apiFetch Helper

```javascript
// GET request
const response = await apiFetch(apiUrl('agents'));
const data = await response.json();

// POST request
const response = await apiFetch(apiUrl('agents'), {
  method: 'POST',
  body: JSON.stringify({ nama: 'John Doe' })
});
```

### 3. Contoh Implementasi Lengkap

```javascript
// Fetch data affiliates
async function loadAffiliates() {
  try {
    const response = await apiFetch(apiUrl('affiliates'));
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error loading affiliates:', error);
  }
}

// Create new agent
async function createAgent(agentData) {
  try {
    const response = await apiFetch(apiUrl('agents'), {
      method: 'POST',
      body: JSON.stringify(agentData)
    });
    const result = await response.json();
    return result;
  } catch (error) {
    console.error('Error creating agent:', error);
  }
}

// Display image from storage
function displayImage(imagePath) {
  const img = document.createElement('img');
  img.src = storageUrl(imagePath);
  document.body.appendChild(img);
}
```

## 🔧 Mengganti URL API

### Development ke Production

Untuk mengganti dari development ke production, edit file `shared/config.js`:

```javascript
// DEVELOPMENT
const API_BASE_URL = 'http://127.0.0.1:8000';

// PRODUCTION
const API_BASE_URL = 'https://api.kuotaumroh.id';
```

Setelah mengubah `API_BASE_URL`, **SEMUA** file yang menggunakan `apiUrl()`, `storageUrl()`, dan `apiFetch()` akan otomatis menggunakan URL baru.

## 📝 Variabel yang Tersedia

| Variabel | Deskripsi | Contoh |
|----------|-----------|--------|
| `API_BASE_URL` | Base URL server | `http://127.0.0.1:8000` |
| `API_URL` | Base URL untuk API endpoints | `http://127.0.0.1:8000/api` |
| `STORAGE_URL` | Base URL untuk storage files | `http://127.0.0.1:8000/storage` |

## 🛠️ Helper Functions

| Function | Parameter | Return | Contoh |
|----------|-----------|--------|--------|
| `apiUrl(endpoint)` | String endpoint path | String URL lengkap | `apiUrl('agents')` → `agents` |
| `storageUrl(path)` | String file path | String URL lengkap | `storageUrl('uploads/foto.jpg')` → `http://127.0.0.1:8000/storage/uploads/foto.jpg` |
| `apiFetch(url, options)` | URL dan fetch options | Promise | `apiFetch(apiUrl('agents'))` |

## ✅ Checklist Migrasi

Untuk mengupdate file yang masih menggunakan hardcoded URL:

- [ ] Tambahkan `<script src="../shared/config.js"></script>` di `<head>`
- [ ] Ganti semua `'...'` dengan `apiUrl('...')`
- [ ] Ganti semua `'http://127.0.0.1:8000/storage/...'` dengan `storageUrl('...')`
- [ ] Test semua fungsi yang menggunakan API

## 🎯 Best Practices

1. **Selalu load config.js pertama kali** sebelum script lain
2. **Gunakan helper functions** (`apiUrl`, `storageUrl`, `apiFetch`) daripada hardcode URL
3. **Jangan modifikasi** variabel `API_BASE_URL` di file lain
4. **Test di development** sebelum deploy ke production

## 🐛 Troubleshooting

### Error: apiUrl is not defined

**Penyebab**: File `config.js` belum di-load atau di-load setelah script yang menggunakannya.

**Solusi**: Pastikan `<script src="../shared/config.js"></script>` ada di `<head>` dan berada **SEBELUM** script lain.

### API URL tidak berubah setelah edit config.js

**Penyebab**: Browser masih menggunakan cache lama.

**Solusi**: Hard refresh browser (Ctrl + Shift + R) atau clear cache.

## 📞 Support

Jika ada pertanyaan atau masalah, silakan hubungi tim development.

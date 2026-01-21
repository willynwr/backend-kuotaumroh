# 🚀 Quick Reference - Config API

## Setup (1 menit)

### 1. Load config.js di HTML
```html
<head>
  <!-- Load PERTAMA sebelum script lain -->
  <script src="../shared/config.js"></script>
</head>
```

## Penggunaan

### ✅ BENAR
```javascript
// API Endpoints
fetch(apiUrl('agents'))
fetch(apiUrl('affiliates'))
fetch(apiUrl('agents/123'))

// Storage Files
img.src = storageUrl('uploads/foto.jpg')
iframe.src = storageUrl('documents/file.pdf')

// Dengan apiFetch helper
const response = await apiFetch(apiUrl('agents'));
const data = await response.json();
```

### ❌ SALAH (Jangan Hardcode!)
```javascript
// JANGAN seperti ini:
fetch('agents')
img.src = 'http://127.0.0.1:8000/storage/uploads/foto.jpg'
```

## Ganti URL API (Production)

Edit `shared/config.js`:
```javascript
// Development
const API_BASE_URL = 'http://127.0.0.1:8000';

// Production
const API_BASE_URL = 'https://api.kuotaumroh.id';
```

Selesai! Semua file otomatis pakai URL baru. 🎉

## Helper Functions

| Function | Contoh | Hasil |
|----------|--------|-------|
| `apiUrl('agents')` | `apiUrl('agents')` | `agents` |
| `storageUrl('foto.jpg')` | `storageUrl('uploads/foto.jpg')` | `http://127.0.0.1:8000/storage/uploads/foto.jpg` |
| `apiFetch(url, options)` | `apiFetch(apiUrl('agents'))` | Fetch dengan headers default |

## Contoh Lengkap

```javascript
// GET
async function loadData() {
  const response = await apiFetch(apiUrl('agents'));
  return await response.json();
}

// POST
async function createData(data) {
  const response = await apiFetch(apiUrl('agents'), {
    method: 'POST',
    body: JSON.stringify(data)
  });
  return await response.json();
}

// Display Image
function showImage(path) {
  img.src = storageUrl(path);
}
```

## Troubleshooting

**Error: apiUrl is not defined**
→ Pastikan `config.js` sudah di-load di `<head>`

**URL tidak berubah**
→ Hard refresh browser (Ctrl + Shift + R)

---

📖 Dokumentasi lengkap: `shared/CONFIG_README.md`
🎯 Contoh interaktif: `shared/config-example.html`

# 📦 Konfigurasi API Terpusat - Summary

## ✅ File yang Sudah Dibuat

### 1. **shared/config.js** - File Konfigurasi Utama
File ini berisi:
- `API_BASE_URL` - Base URL server (http://127.0.0.1:8000)
- `API_URL` - URL untuk API endpoints (/api)
- `STORAGE_URL` - URL untuk storage files (/storage)
- `apiUrl(endpoint)` - Helper function untuk membuat URL API
- `storageUrl(path)` - Helper function untuk membuat URL storage
- `apiFetch(url, options)` - Helper function untuk fetch dengan headers default

### 2. **shared/CONFIG_README.md** - Dokumentasi Lengkap
Dokumentasi komprehensif yang berisi:
- Cara penggunaan lengkap
- Contoh kode
- Best practices
- Troubleshooting
- Checklist migrasi

### 3. **shared/QUICK_REFERENCE.md** - Panduan Cepat
Quick reference guide yang singkat berisi:
- Setup 1 menit
- Contoh penggunaan
- Cara ganti URL
- Troubleshooting singkat

### 4. **shared/config-example.html** - Contoh Interaktif
File HTML interaktif yang mendemonstrasikan:
- Cara load config.js
- Penggunaan apiUrl()
- Penggunaan storageUrl()
- Fetch data dari API
- POST data ke API
- Contoh kode lengkap

### 5. **shared/api.js** - Updated
File API service layer yang sudah diupdate untuk menggunakan config.js

### 6. **admin/users.html** - Contoh Implementasi
File yang sudah diupdate sebagai contoh implementasi:
- Load config.js di head
- Menggunakan storageUrl() untuk file storage
- Menggunakan apiFetch() dan apiUrl() untuk API calls

## 🎯 Cara Menggunakan

### Langkah 1: Load Config di HTML
```html
<head>
  <!-- Load config.js PERTAMA -->
  <script src="../shared/config.js"></script>
  
  <!-- Script lain setelahnya -->
  <script src="../shared/utils.js"></script>
</head>
```

### Langkah 2: Ganti Hardcoded URL

**SEBELUM:**
```javascript
fetch('agents')
img.src = 'http://127.0.0.1:8000/storage/foto.jpg'
```

**SESUDAH:**
```javascript
fetch(apiUrl('agents'))
img.src = storageUrl('foto.jpg')
```

### Langkah 3: Ganti URL API (Saat Deploy)

Edit `shared/config.js`:
```javascript
// Development
const API_BASE_URL = 'http://127.0.0.1:8000';

// Production
const API_BASE_URL = 'https://api.kuotaumroh.id';
```

## 📋 File yang Perlu Diupdate

Berdasarkan hasil grep search, file-file berikut masih menggunakan hardcoded URL dan perlu diupdate:

### Priority High (Sering Digunakan)
- [ ] `signup.html` - 1 instance
- [ ] `freelance/dashboard.html` - 5 instances
- [ ] `freelance/downlines.html` - 6 instances
- [ ] `agent/dashboard.html` - 1 instance
- [ ] `agent/profile.html` - 1 instance

### Priority Medium
- [ ] `ref.html` - 2 instances
- [ ] `callback.html` - 4 instances
- [ ] `admin/users.html` - ✅ Sudah diupdate sebagian (masih ada ~30 instances lagi)

### Priority Low (Testing/Development)
- [ ] `dash/test/index.html` - 2 instances

## 🔄 Template Update untuk File Lain

Untuk mengupdate file lain, ikuti template ini:

### 1. Tambahkan config.js di head
```html
<head>
  <!-- Load config.js PERTAMA -->
  <script src="../shared/config.js"></script>
</head>
```

### 2. Find & Replace
Gunakan find & replace di editor:

**Find:**
```
'
```

**Replace with:**
```
apiUrl('
```

Jangan lupa tambahkan `'` di akhir endpoint!

**Find:**
```
'http://127.0.0.1:8000/storage/
```

**Replace with:**
```
storageUrl('
```

### 3. Update Fetch Calls
**SEBELUM:**
```javascript
const response = await fetch('agents', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
  body: JSON.stringify(data)
});
```

**SESUDAH:**
```javascript
const response = await apiFetch(apiUrl('agents'), {
  method: 'POST',
  body: JSON.stringify(data)
});
```

## 🎉 Keuntungan

1. **Ganti URL Sekali Saja** - Edit 1 file, semua file terupdate
2. **Lebih Mudah Deploy** - Tinggal ganti `API_BASE_URL` saat deploy
3. **Kode Lebih Bersih** - Tidak ada hardcoded URL di mana-mana
4. **Mudah Maintenance** - Centralized configuration
5. **Konsisten** - Semua file pakai URL yang sama

## 📚 Resources

- **Dokumentasi Lengkap**: `shared/CONFIG_README.md`
- **Quick Reference**: `shared/QUICK_REFERENCE.md`
- **Contoh Interaktif**: `shared/config-example.html`
- **File Config**: `shared/config.js`

## 🚀 Next Steps

1. ✅ File config sudah dibuat
2. ✅ Dokumentasi sudah dibuat
3. ✅ Contoh implementasi sudah dibuat
4. ⏳ Update file-file lain yang masih hardcoded
5. ⏳ Test semua fungsi
6. ⏳ Deploy ke production

## 💡 Tips

- Selalu load `config.js` **PERTAMA** sebelum script lain
- Gunakan `apiUrl()` untuk API endpoints
- Gunakan `storageUrl()` untuk file storage
- Gunakan `apiFetch()` untuk fetch dengan headers default
- Test di development sebelum deploy

---

**Dibuat pada**: 2026-01-18
**Status**: ✅ Ready to Use
**Maintenance**: Update `API_BASE_URL` saat deploy ke production

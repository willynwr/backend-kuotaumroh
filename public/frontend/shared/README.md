# 📁 Shared Folder - Konfigurasi API Terpusat

Folder ini berisi file-file konfigurasi dan utility yang digunakan di seluruh aplikasi.

## 📦 File Konfigurasi API

### 🎯 File Utama

| File | Deskripsi | Kapan Digunakan |
|------|-----------|-----------------|
| **config.js** | File konfigurasi API terpusat | ✅ **WAJIB** di-load di setiap HTML |
| **api.js** | Service layer untuk API calls | Optional, untuk fungsi API kompleks |
| **utils.js** | Utility functions | Optional, helper functions umum |

### 📚 Dokumentasi

| File | Deskripsi | Untuk Siapa |
|------|-----------|-------------|
| **CONFIG_README.md** | Dokumentasi lengkap | Developer yang ingin detail |
| **QUICK_REFERENCE.md** | Panduan cepat | Developer yang butuh cepat |
| **CONFIG_SUMMARY.md** | Summary & status | Project manager / lead |

### 🎨 Contoh & Tools

| File | Deskripsi | Cara Pakai |
|------|-----------|------------|
| **config-example.html** | Contoh interaktif | Buka di browser |
| **migration-helper.js** | Script scan hardcoded URL | `node migration-helper.js` |

## 🚀 Quick Start (5 Menit)

### 1. Load Config di HTML
```html
<head>
  <!-- Load PERTAMA sebelum script lain -->
  <script src="../shared/config.js"></script>
</head>
```

### 2. Gunakan Helper Functions
```javascript
// API Endpoints
fetch(apiUrl('agents'))           // ✅ BENAR
fetch(apiUrl('affiliates'))       // ✅ BENAR

// Storage Files
img.src = storageUrl('foto.jpg')  // ✅ BENAR

// Fetch dengan Headers
const res = await apiFetch(apiUrl('agents'))  // ✅ BENAR
```

### 3. Ganti URL API (Production)
Edit `config.js`:
```javascript
const API_BASE_URL = 'https://api.kuotaumroh.id';
```

Selesai! 🎉

## 📖 Dokumentasi Lengkap

### Untuk Developer Baru
1. Baca **QUICK_REFERENCE.md** (5 menit)
2. Lihat **config-example.html** di browser
3. Mulai coding!

### Untuk Developer Berpengalaman
1. Baca **CONFIG_README.md** untuk detail
2. Gunakan **migration-helper.js** untuk scan file lama
3. Update file yang masih hardcoded

### Untuk Project Manager
1. Baca **CONFIG_SUMMARY.md** untuk status
2. Check progress migrasi
3. Monitor deployment

## 🔧 Tools

### Migration Helper
Scan file yang masih hardcoded:
```bash
cd shared
node migration-helper.js
```

Output:
- List file dengan hardcoded URL
- Jumlah hardcoded per file
- Priority berdasarkan jumlah
- Status config.js di setiap file

## ⚠️ Penting!

### DO ✅
- Selalu load `config.js` PERTAMA
- Gunakan `apiUrl()` untuk API
- Gunakan `storageUrl()` untuk storage
- Test sebelum deploy

### DON'T ❌
- Jangan hardcode URL
- Jangan load config.js setelah script lain
- Jangan edit `API_BASE_URL` di file lain
- Jangan skip testing

## 🎯 Status Migrasi

### ✅ Sudah Selesai
- [x] config.js dibuat
- [x] Dokumentasi lengkap
- [x] Contoh implementasi
- [x] Migration helper script
- [x] admin/users.html (partial)

### ⏳ Dalam Progress
- [ ] Update semua file HTML
- [ ] Update semua file JS
- [ ] Testing lengkap

### 📋 Belum Dimulai
- [ ] Deploy ke staging
- [ ] Deploy ke production

## 📞 Support

Jika ada pertanyaan:
1. Cek **QUICK_REFERENCE.md** dulu
2. Baca **CONFIG_README.md** untuk detail
3. Lihat **config-example.html** untuk contoh
4. Tanya tim development

## 🔗 Quick Links

- [Quick Reference](QUICK_REFERENCE.md) - Panduan cepat
- [Full Documentation](CONFIG_README.md) - Dokumentasi lengkap
- [Summary & Status](CONFIG_SUMMARY.md) - Status project
- [Interactive Example](config-example.html) - Contoh interaktif

---

**Last Updated**: 2026-01-18
**Version**: 1.0.0
**Status**: ✅ Ready to Use

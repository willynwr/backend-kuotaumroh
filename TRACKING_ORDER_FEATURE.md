# Fitur Tracking Order

## Overview
Fitur ini memungkinkan user untuk melacak status pembayaran dan melihat invoice menggunakan External Payment ID.

## Cara Kerja

### 1. Header Button
- Tombol "Lacak Pesanan" ditambahkan di header welcome page (sebelah kanan)
- Icon: Clipboard dengan checklist
- Responsive: tampilkan icon saja di mobile, full text di desktop

### 2. Modal Pop-up
Ketika user klik tombol, muncul modal dengan:
- Input field untuk External Payment ID
- Validasi input (required)
- Error handling untuk ID tidak ditemukan
- Loading state saat proses tracking

### 3. API Endpoint
**Route:** `GET /api/pembayaran/{external_payment_id}/status`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "external_payment_id": "PAY-20240201-ABC123",
    "status": "paid",
    "status_pembayaran": "SUKSES",
    "metode_pembayaran": "QRIS",
    "total_pembayaran": 50000,
    "created_at": "2024-02-01T10:00:00.000000Z"
  }
}
```

### 4. Redirect ke Invoice
Setelah payment ditemukan, user otomatis diarahkan ke:
```
/invoice/{external_payment_id}
```

## File yang Dimodifikasi

### 1. resources/views/welcome.blade.php
- Tambah button "Lacak Pesanan" di header
- Tambah modal pop-up untuk input Payment ID
- Tambah Alpine.js component `trackingOrder()`
- Handler untuk submit tracking form

### 2. routes/api.php
- Tambah route baru: `GET /api/pembayaran/{external_payment_id}/status`

### 3. app/Http/Controllers/Api/UmrohPaymentController.php
- Tambah method `getPaymentStatusByExternalId()`
- Query pembayaran by external_payment_id
- Return payment status & metadata

## Testing

### Test Case 1: Payment ID Valid
1. Buka welcome page
2. Klik "Lacak Pesanan"
3. Masukkan Payment ID yang valid (contoh: dari database pembayaran)
4. Klik "Lacak Pesanan"
5. **Expected:** Redirect ke invoice page dengan data pembayaran

### Test Case 2: Payment ID Tidak Ditemukan
1. Buka welcome page
2. Klik "Lacak Pesanan"
3. Masukkan Payment ID yang tidak ada
4. Klik "Lacak Pesanan"
5. **Expected:** Error message "ID Pembayaran tidak ditemukan"

### Test Case 3: Input Kosong
1. Buka welcome page
2. Klik "Lacak Pesanan"
3. Klik "Lacak Pesanan" tanpa input
4. **Expected:** Error message "Mohon masukkan ID Pembayaran"

## UI/UX Features

### Modal Design
- ✅ Gradient header (primary to teal)
- ✅ Icon badge di header
- ✅ Info banner untuk panduan user
- ✅ Auto-focus input saat modal dibuka
- ✅ Smooth transitions (fade & scale)
- ✅ Click outside to close
- ✅ ESC key to close (browser default)

### Button States
- ✅ Normal state: "Lacak Pesanan"
- ✅ Loading state: "Mencari..." + spinner
- ✅ Disabled saat loading atau input kosong

### Error Handling
- ✅ 404: Payment tidak ditemukan
- ✅ Network error: Gagal koneksi ke server
- ✅ Validation: Input wajib diisi

## Database Schema
Menggunakan table `pembayaran` dengan kolom:
- `id` (primary key)
- `external_payment_id` (unique identifier dari payment gateway)
- `status` 
- `status_pembayaran`
- `metode_pembayaran`
- `total_pembayaran`
- `created_at`

## Notes
- External Payment ID adalah ID yang diberikan oleh payment gateway (QRIS, dll)
- Format ID bisa bervariasi tergantung provider (contoh: PAY-YYYYMMDD-XXXXX)
- Invoice page akan menampilkan data berdasarkan payment ID yang ditemukan
- Jika payment belum sukses, invoice akan menampilkan status "Menunggu Pembayaran"

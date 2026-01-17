# API Produk Endpoints

Base URL: `http://your-domain.com/api`

## 1. GET All Produk
**Endpoint:** `GET /produk`

**Description:** Mengambil semua data produk

**Response Success (200):**
```json
{
    "success": true,
    "message": "Data produk berhasil diambil",
    "data": [
        {
            "id": 1,
            "nama_paket": "Paket Umroh Premium",
            "tipe_paket": "Premium",
            "masa_aktif": 30,
            "total_kuota": 10240,
            "kuota_utama": 5120,
            "kuota_bonus": 5120,
            "telp": 100,
            "sms": 100,
            "harga_modal": 5000000,
            "harga_eup": 6000000,
            "persentase_margin_star": 10.50,
            "margin_star": 500000,
            "margin_total": 1000000,
            "fee_travel": 200000,
            "persentase_fee_travel": 3.33,
            "persentase_fee_affiliate": 2.00,
            "fee_affiliate": 120000,
            "persentase_fee_host": 1.50,
            "fee_host": 90000,
            "harga_tp_travel": 5800000,
            "harga_tp_host": 5900000,
            "poin": 100,
            "profit": 300000,
            "created_at": "2026-01-17T07:00:00.000000Z",
            "updated_at": "2026-01-17T07:00:00.000000Z"
        }
    ]
}
```

---

## 2. GET Produk by ID
**Endpoint:** `GET /produk/{id}`

**Description:** Mengambil data produk berdasarkan ID

**Response Success (200):**
```json
{
    "success": true,
    "message": "Data produk berhasil diambil",
    "data": {
        "id": 1,
        "nama_paket": "Paket Umroh Premium",
        "tipe_paket": "Premium",
        ...
    }
}
```

**Response Error (404):**
```json
{
    "success": false,
    "message": "Produk tidak ditemukan"
}
```

---

## 3. POST Create Produk
**Endpoint:** `POST /produk`

**Description:** Membuat produk baru

**Request Body:**
```json
{
    "nama_paket": "Paket Umroh Premium",
    "tipe_paket": "Premium",
    "masa_aktif": 30,
    "total_kuota": 10240,
    "kuota_utama": 5120,
    "kuota_bonus": 5120,
    "telp": 100,
    "sms": 100,
    "harga_modal": 5000000,
    "harga_eup": 6000000,
    "persentase_margin_star": 10.50,
    "margin_star": 500000,
    "margin_total": 1000000,
    "fee_travel": 200000,
    "persentase_fee_travel": 3.33,
    "persentase_fee_affiliate": 2.00,
    "fee_affiliate": 120000,
    "persentase_fee_host": 1.50,
    "fee_host": 90000,
    "harga_tp_travel": 5800000,
    "harga_tp_host": 5900000,
    "poin": 100,
    "profit": 300000
}
```

**Required Fields:**
- `nama_paket` (string, max 255)
- `tipe_paket` (string, max 255)
- `masa_aktif` (integer, min 1)
- `total_kuota` (integer, min 0)
- `kuota_utama` (integer, min 0)
- `kuota_bonus` (integer, min 0)
- `harga_modal` (integer, min 0)
- `harga_eup` (integer, min 0)

**Optional Fields:**
- `telp` (integer, min 0)
- `sms` (integer, min 0)
- `persentase_margin_star` (numeric, 0-100)
- `margin_star` (integer, min 0)
- `margin_total` (integer, min 0)
- `fee_travel` (integer, min 0)
- `persentase_fee_travel` (numeric, 0-100)
- `persentase_fee_affiliate` (numeric, 0-100)
- `fee_affiliate` (integer, min 0)
- `persentase_fee_host` (numeric, 0-100)
- `fee_host` (integer, min 0)
- `harga_tp_travel` (integer, min 0)
- `harga_tp_host` (integer, min 0)
- `poin` (integer, min 0)
- `profit` (integer, min 0)

**Response Success (201):**
```json
{
    "success": true,
    "message": "Produk berhasil ditambahkan",
    "data": {
        "id": 1,
        "nama_paket": "Paket Umroh Premium",
        ...
    }
}
```

**Response Error (422):**
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "nama_paket": ["The nama paket field is required."],
        "harga_modal": ["The harga modal must be at least 0."]
    }
}
```

---

## 4. POST Update Produk
**Endpoint:** `POST /produk/{id}`

**Description:** Update data produk berdasarkan ID

**Request Body:** (Semua field optional, kirim hanya yang ingin diupdate)
```json
{
    "nama_paket": "Paket Umroh Premium Updated",
    "harga_eup": 6500000
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Produk berhasil diupdate",
    "data": {
        "id": 1,
        "nama_paket": "Paket Umroh Premium Updated",
        "harga_eup": 6500000,
        ...
    }
}
```

**Response Error (404):**
```json
{
    "success": false,
    "message": "Produk tidak ditemukan"
}
```

---

## 5. DELETE Produk
**Endpoint:** `DELETE /produk/{id}`

**Description:** Menghapus produk berdasarkan ID

**Response Success (200):**
```json
{
    "success": true,
    "message": "Produk berhasil dihapus"
}
```

**Response Error (404):**
```json
{
    "success": false,
    "message": "Produk tidak ditemukan"
}
```

---

## Field Descriptions

| Field | Type | Description |
|-------|------|-------------|
| `nama_paket` | string | Nama paket produk |
| `tipe_paket` | string | Tipe/kategori paket |
| `masa_aktif` | integer | Masa aktif dalam hari |
| `total_kuota` | integer | Total kuota dalam MB/GB |
| `kuota_utama` | integer | Kuota utama dalam MB/GB |
| `kuota_bonus` | integer | Kuota bonus dalam MB/GB |
| `telp` | integer | Jumlah menit telepon |
| `sms` | integer | Jumlah SMS |
| `harga_modal` | integer | Harga modal dalam Rupiah |
| `harga_eup` | integer | Harga EUP dalam Rupiah |
| `persentase_margin_star` | decimal | Persentase margin star (0-100) |
| `margin_star` | integer | Margin star dalam Rupiah |
| `margin_total` | integer | Margin total dalam Rupiah |
| `fee_travel` | integer | Fee travel dalam Rupiah |
| `persentase_fee_travel` | decimal | Persentase fee travel (0-100) |
| `persentase_fee_affiliate` | decimal | Persentase fee affiliate (0-100) |
| `fee_affiliate` | integer | Fee affiliate dalam Rupiah |
| `persentase_fee_host` | decimal | Persentase fee host (0-100) |
| `fee_host` | integer | Fee host dalam Rupiah |
| `harga_tp_travel` | integer | Harga TP travel dalam Rupiah |
| `harga_tp_host` | integer | Harga TP host dalam Rupiah |
| `poin` | integer | Jumlah poin |
| `profit` | integer | Profit dalam Rupiah |

---

## Error Responses

**500 Internal Server Error:**
```json
{
    "success": false,
    "message": "Gagal mengambil data produk",
    "error": "Error message details"
}
```

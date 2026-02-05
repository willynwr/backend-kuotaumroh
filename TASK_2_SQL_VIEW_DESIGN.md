# Task 2: SQL View Design - Agent Pricing Views

## 📋 Overview

Berdasarkan Gap Analysis (Task 1) dan klarifikasi dari user:
- **Super Host hanya untuk STORE** - tidak perlu bulk pricing
- **VIEW tidak perlu diupdate** - semua VIEW sudah sesuai business rule

## ✅ Kesimpulan

**TIDAK ADA UPDATE VIEW DIPERLUKAN**

| VIEW | Kategori | Bulk | Store | Status |
|------|----------|------|-------|--------|
| `v_pembelian_paket_kuotaumroh` | Super Host | ❌ | ✅ | ✅ OK |
| `v_pembelian_paket_agent_travel` | Referral, Non Referral | ✅ | ✅ | ✅ OK |
| `v_pembelian_paket_agent_travel_host` | Host | ✅ | ❌ | ✅ OK |

## Business Rules Final

| Kategori | Bulk Order | Store | Fee Travel | Fee Affiliate |
|----------|------------|-------|------------|---------------|
| **Super Host** | ❌ Tidak bisa | ✅ Yes | ✅ Dapat | ❌ Tidak |
| **Referral** | ✅ Yes | ✅ Yes | ✅ Dapat | ✅ Dapat |
| **Non Referral** | ✅ Yes | ✅ Yes | ✅ Dapat | ❌ Tidak |
| **Host** | ✅ Yes | ❌ Tidak | ✅ Dapat | ❌ Tidak |

**Next:** Task 3 & 4 - Backend Logic (enforce restrictions)

---

## 🔄 VIEW yang Perlu Diupdate

### `v_pembelian_paket_kuotaumroh` (Super Host)

**Masalah:**
- Tidak ada kolom bulk pricing: `bulk_final_fee_travel`, `bulk_final_fee_affiliate`, `bulk_harga_beli`, `bulk_potensi_profit`, `bulk_harga_rekomendasi`
- Super Host (AGT00001) tidak bisa bulk order dengan benar

**Kolom yang Perlu Ditambahkan:**
- `bulk_final_fee_travel` - Fee untuk travel agent (dari produk_default/produk_agent_travel)
- `bulk_final_fee_affiliate` - Set ke 0 (Super Host tidak punya affiliate)
- `bulk_harga_beli` - `harga_komersial - bulk_final_fee_travel`
- `bulk_potensi_profit` - `= bulk_final_fee_travel`
- `bulk_harga_rekomendasi` - `= harga_komersial`

---

## 📝 SQL Design

### Updated `v_pembelian_paket_kuotaumroh`

```sql
CREATE OR REPLACE VIEW v_pembelian_paket_kuotaumroh AS
WITH agent AS (
    SELECT 
        p.id AS agent_id,
        p.affiliate_id AS affiliate_id 
    FROM agent p 
    WHERE p.kategori_agent = 'Super Host' 
      AND p.is_active = 1
),
produk AS (
    SELECT 
        produk.id AS produk_id,
        produk.provider AS provider,
        produk.tipe_paket AS tipe_paket,
        produk.promo AS promo,
        produk.masa_aktif AS masa_aktif,
        produk.total_kuota AS total_kuota,
        produk.kuota_utama AS kuota_utama,
        produk.kuota_bonus AS kuota_bonus,
        produk.telp AS telp,
        produk.sms AS sms,
        produk.harga_api AS harga_api,
        produk.harga_komersial AS harga_komersial 
    FROM produk 
    WHERE produk.is_active = 1
),
layer1 AS (
    SELECT 
        p.agent_id AS agent_id,
        p.affiliate_id AS affiliate_id,
        q.produk_id AS produk_id,
        q.provider AS provider,
        q.tipe_paket AS tipe_paket,
        q.promo AS promo,
        q.masa_aktif AS masa_aktif,
        q.total_kuota AS total_kuota,
        q.kuota_utama AS kuota_utama,
        q.kuota_bonus AS kuota_bonus,
        q.telp AS telp,
        q.sms AS sms,
        q.harga_api AS harga_api,
        q.harga_komersial AS harga_komersial 
    FROM agent p 
    JOIN produk q
),
layer2 AS (
    SELECT 
        p.agent_id AS agent_id,
        p.affiliate_id AS affiliate_id,
        p.produk_id AS produk_id,
        p.provider AS provider,
        p.tipe_paket AS tipe_paket,
        p.promo AS promo,
        p.masa_aktif AS masa_aktif,
        p.total_kuota AS total_kuota,
        p.kuota_utama AS kuota_utama,
        p.kuota_bonus AS kuota_bonus,
        p.telp AS telp,
        p.sms AS sms,
        p.harga_api AS harga_api,
        p.harga_komersial AS harga_komersial,
        -- BULK FEE (NEW)
        COALESCE(q.bulk_final_fee_travel, r.bulk_final_fee_travel) AS bulk_final_fee_travel,
        -- MANDIRI/STORE FEE
        COALESCE(q.final_diskon, r.final_diskon) AS final_diskon,
        COALESCE(q.mandiri_final_fee_travel, r.mandiri_final_fee_travel) AS mandiri_final_fee_travel,
        COALESCE(q.mandiri_final_fee_affiliate, r.mandiri_final_fee_affiliate) AS mandiri_final_fee_affiliate 
    FROM layer1 p 
    LEFT JOIN produk_agent_travel q 
        ON p.produk_id = q.produk_id 
        AND p.agent_id = q.agent_id 
        AND p.affiliate_id = q.affiliate_id
    LEFT JOIN produk_default r 
        ON p.produk_id = r.produk_id
)
SELECT 
    p.agent_id AS agent_id,
    p.affiliate_id AS affiliate_id,
    p.produk_id AS produk_id,
    p.provider AS provider,
    p.tipe_paket AS tipe_paket,
    p.promo AS promo,
    p.masa_aktif AS masa_aktif,
    p.total_kuota AS total_kuota,
    p.kuota_utama AS kuota_utama,
    p.kuota_bonus AS kuota_bonus,
    p.telp AS telp,
    p.sms AS sms,
    p.harga_api AS harga_api,
    p.harga_komersial AS harga_komersial,
    -- BULK PRICING (NEW COLUMNS)
    p.bulk_final_fee_travel AS bulk_final_fee_travel,
    0 AS bulk_final_fee_affiliate,  -- Super Host tidak punya affiliate, jadi selalu 0
    (p.harga_komersial - p.bulk_final_fee_travel) AS bulk_harga_beli,
    p.bulk_final_fee_travel AS bulk_potensi_profit,
    p.harga_komersial AS bulk_harga_rekomendasi,
    -- STORE/TOKO PRICING (EXISTING)
    p.final_diskon AS final_diskon,
    p.mandiri_final_fee_travel AS mandiri_final_fee_travel,
    p.mandiri_final_fee_affiliate AS mandiri_final_fee_affiliate,
    (CASE WHEN p.final_diskon > 0 THEN p.harga_komersial ELSE 0 END) AS toko_harga_coret,
    (p.harga_komersial - p.final_diskon) AS toko_harga_jual,
    p.final_diskon AS toko_hemat 
FROM layer2 p;
```

---

## 📊 Perbandingan Struktur VIEW

### Kolom yang Sama di Semua VIEW

| Kolom | Tipe | Deskripsi |
|-------|------|-----------|
| `agent_id` | varchar(10) | ID Agent |
| `affiliate_id` | varchar(10) | ID Affiliate |
| `produk_id` | varchar(20) | ID Produk |
| `provider` | varchar(20) | Provider (TSEL, XL, dll) |
| `tipe_paket` | varchar(100) | Tipe paket |
| `promo` | varchar(20) | Label promo |
| `masa_aktif` | int | Masa aktif (hari) |
| `total_kuota` | varchar(20) | Total kuota |
| `kuota_utama` | varchar(20) | Kuota utama |
| `kuota_bonus` | varchar(20) | Kuota bonus |
| `telp` | varchar(20) | Kuota telp |
| `sms` | varchar(20) | Kuota SMS |
| `harga_api` | int | Harga dari API |
| `harga_komersial` | int | Harga komersial (display) |

### Kolom Bulk Pricing

| Kolom | kuotaumroh (Super Host) | agent_travel (Referral/Non Ref) | agent_travel_host (Host) |
|-------|-------------------------|----------------------------------|--------------------------|
| `bulk_final_fee_travel` | ✅ (NEW) | ✅ | ❌ |
| `bulk_final_fee_affiliate` | ✅ (NEW, = 0) | ✅ | ❌ |
| `bulk_final_fee_host` | ❌ | ❌ | ✅ |
| `bulk_harga_beli` | ✅ (NEW) | ✅ | ✅ |
| `bulk_potensi_profit` | ✅ (NEW) | ✅ | ✅ |
| `bulk_harga_rekomendasi` | ✅ (NEW) | ✅ | ✅ |

### Kolom Store/Toko Pricing

| Kolom | kuotaumroh (Super Host) | agent_travel (Referral/Non Ref) | agent_travel_host (Host) |
|-------|-------------------------|----------------------------------|--------------------------|
| `final_diskon` | ✅ | ✅ | ❌ |
| `mandiri_final_fee_travel` | ✅ | ✅ | ❌ |
| `mandiri_final_fee_affiliate` | ✅ | ✅ | ❌ |
| `toko_harga_coret` | ✅ | ✅ | ❌ |
| `toko_harga_jual` | ✅ | ✅ | ❌ |
| `toko_hemat` | ✅ | ✅ | ❌ |

---

## 🧪 Validation Query

Setelah VIEW diupdate, jalankan query ini untuk validasi:

```sql
-- Validasi Super Host bulk pricing
SELECT 
    agent_id,
    produk_id,
    bulk_harga_beli,
    bulk_harga_rekomendasi,
    bulk_potensi_profit,
    bulk_final_fee_travel,
    bulk_final_fee_affiliate
FROM v_pembelian_paket_kuotaumroh
WHERE agent_id = 'AGT00001'
LIMIT 5;

-- Compare dengan agent_travel untuk Referral
SELECT 
    agent_id,
    produk_id,
    bulk_harga_beli,
    bulk_harga_rekomendasi,
    bulk_potensi_profit,
    bulk_final_fee_travel,
    bulk_final_fee_affiliate
FROM v_pembelian_paket_agent_travel
WHERE agent_id LIKE 'AGT%'
LIMIT 5;
```

---

## ⚠️ Important Notes

1. **Backward Compatibility**
   - Kolom lama (`final_diskon`, `mandiri_final_fee_*`, `toko_*`) tetap dipertahankan
   - Hanya menambah kolom baru, tidak mengubah/menghapus yang ada

2. **Super Host Affiliate Fee**
   - `bulk_final_fee_affiliate` di-hardcode ke `0` karena Super Host tidak punya affiliate
   - Ini konsisten dengan business rule

3. **Rollback Plan**
   - Simpan SQL VIEW lama sebelum update
   - Jika ada issue, bisa rollback dengan CREATE OR REPLACE VIEW ke versi lama

---

## 📁 Migration File

Migration akan dibuat di Task 5. File akan berisi:
1. Backup VIEW definition lama (dalam comment)
2. CREATE OR REPLACE VIEW dengan kolom baru
3. Validation query

---

## ✅ Summary Task 2

| Item | Status |
|------|--------|
| SQL Design untuk `v_pembelian_paket_kuotaumroh` | ✅ Done |
| Kolom mapping documented | ✅ Done |
| Validation query ready | ✅ Done |
| Backward compatibility ensured | ✅ Done |

**Next:** Task 3 - Backend Logic (jika ada adjustment yang diperlukan)

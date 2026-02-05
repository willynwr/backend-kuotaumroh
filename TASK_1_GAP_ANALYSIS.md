# Task 1: Gap Analysis - Agent Kategori & Fee System

## 📊 Hasil Audit (Task 0)

### DATABASE VIEWS yang Ada
| VIEW | Kategori | Kolom Fee | Kolom Store |
|------|----------|-----------|-------------|
| `v_pembelian_paket_kuotaumroh` | Super Host | `mandiri_final_fee_travel/affiliate` | ✅ `toko_harga_coret/jual/hemat` |
| `v_pembelian_paket_agent_travel` | Referral, Non Referral | `bulk_final_fee_travel/affiliate` | ✅ `toko_harga_coret/jual/hemat` |
| `v_pembelian_paket_agent_travel_host` | Host | `bulk_final_fee_host` | ❌ Tidak ada |

### TABEL SUMBER
- `produk_default` - Default pricing untuk semua produk
- `produk_agent_travel` - Custom pricing per agent/affiliate (override default)

---

## 🎯 Business Rules yang Harus Dipenuhi

| Kategori | Akses Toko | Bulk Order | Fee Travel | Fee Affiliate | Notes |
|----------|------------|------------|------------|---------------|-------|
| **Super Host** (AGT00001) | ✅ Yes | ✅ Yes | ✅ Dapat | ❌ Tidak | Affiliate tidak ada |
| **Referral** | ✅ Yes | ✅ Yes | ✅ Dapat | ✅ Dapat | Affiliate dapat fee |
| **Non Referral** | ✅ Yes | ✅ Yes | ✅ Dapat | ❌ Tidak | affiliate_id = AFT00001 |
| **Host** | ❌ No | ✅ Yes | ✅ Dapat | ❌ Tidak | Beli via affiliate |

---

## 🔍 Gap Analysis per View

### 1. VIEW `v_pembelian_paket_kuotaumroh` (Super Host)

**SQL Logic Saat Ini:**
```sql
WHERE p.kategori_agent = 'Super Host' AND p.is_active = 1
```

**Kolom Fee:**
- `mandiri_final_fee_travel` - ✅ Ada, dari produk_agent_travel atau produk_default
- `mandiri_final_fee_affiliate` - ✅ Ada (tetapi tidak dipakai karena Super Host tidak punya affiliate)

**Kolom Store:**
- `toko_harga_coret` - ✅ Ada (harga_komersial jika final_diskon > 0, else 0)
- `toko_harga_jual` - ✅ Ada (harga_komersial - final_diskon)
- `toko_hemat` - ✅ Ada (= final_diskon)

**GAP:**
- ❌ Tidak ada kolom `bulk_harga_beli`, `bulk_harga_rekomendasi`, `bulk_potensi_profit` untuk bulk order
- ❌ Tidak ada kolom `bulk_final_fee_travel`, `bulk_final_fee_affiliate` untuk bulk order

**Rekomendasi:**
- Tambahkan kolom bulk pricing seperti di v_pembelian_paket_agent_travel

---

### 2. VIEW `v_pembelian_paket_agent_travel` (Referral + Non Referral)

**SQL Logic Saat Ini:**
```sql
WHERE p.kategori_agent IN ('Referral', 'Non Referral') AND p.is_active = 1

-- Fee logic dengan CASE untuk Non Referral (AFT00001):
CASE WHEN affiliate_id = 'AFT00001' THEN 0 ELSE bulk_final_fee_travel END
CASE WHEN affiliate_id = 'AFT00001' THEN 0 ELSE bulk_final_fee_affiliate END
```

**Kolom Fee (Bulk):**
- `bulk_final_fee_travel` - ✅ Ada (0 jika AFT00001)
- `bulk_final_fee_affiliate` - ✅ Ada (0 jika AFT00001)
- `bulk_harga_beli` - ✅ Ada (harga_komersial - bulk_final_fee_travel)
- `bulk_potensi_profit` - ✅ Ada (= bulk_final_fee_travel)
- `bulk_harga_rekomendasi` - ✅ Ada (= harga_komersial)

**Kolom Fee (Store/Mandiri):**
- `mandiri_final_fee_travel` - ✅ Ada (0 jika AFT00001)
- `mandiri_final_fee_affiliate` - ✅ Ada (0 jika AFT00001)

**Kolom Store:**
- `toko_harga_coret` - ✅ Ada
- `toko_harga_jual` - ✅ Ada
- `toko_hemat` - ✅ Ada

**GAP:** ✅ Tidak ada gap - VIEW sudah lengkap

**ISSUE POTENSIAL:**
- Saat ini VIEW mengasumsikan Non Referral = `affiliate_id = 'AFT00001'`
- Jika business rule berubah (Non Referral berdasarkan `kategori_agent`), perlu update

---

### 3. VIEW `v_pembelian_paket_agent_travel_host` (Host)

**SQL Logic Saat Ini:**
```sql
WHERE p.kategori_agent = 'Host' AND p.is_active = 1

-- Fee logic:
CASE WHEN affiliate_id = 'AFT00001' THEN 0 ELSE bulk_final_fee_host END
```

**Kolom Fee (Bulk):**
- `bulk_final_fee_host` - ✅ Ada
- `bulk_harga_beli` - ✅ Ada (harga_komersial - bulk_final_fee_host)
- `bulk_potensi_profit` - ✅ Ada (= bulk_final_fee_host)
- `bulk_harga_rekomendasi` - ✅ Ada (= harga_komersial)

**Kolom Store:**
- ❌ **Tidak ada** (sesuai business rule: Host tidak punya toko)

**GAP:** ✅ Tidak ada gap - VIEW sudah sesuai business rule

---

## 🔧 Gap Analysis di Backend Service

### PackagePricingService.php

**Status Saat Ini:**
| Method | Status | Notes |
|--------|--------|-------|
| `getAgentKategori()` | ✅ OK | Query ke tabel agent |
| `getAgentInfo()` | ✅ OK | Return kategori + affiliate_id |
| `getAgentViewTable()` | ✅ OK | Routing ke view berdasarkan kategori |
| `agentHasStore()` | ✅ OK | Return false untuk Host |
| `shouldAffiliateReceiveFee()` | ✅ OK | Return false untuk Non Referral, Host, Super Host |
| `getBulkCatalogForAgent()` | ⚠️ Issue | Perlu handle Super Host case |
| `getStoreCatalogForAgent()` | ✅ OK | Sudah throw error untuk Host |

**GAP di Backend:**

1. **`getBulkCatalogForAgent()` untuk Super Host**
   - VIEW `v_pembelian_paket_kuotaumroh` tidak punya kolom bulk pricing
   - Method akan fail atau return data tidak lengkap
   - **Solusi:** Update VIEW atau handle di backend

2. **Fee Affiliate Calculation**
   - Saat ini fee_affiliate dihitung dari `bulk_final_fee_affiliate` di VIEW
   - Backend sudah handle dengan `shouldAffiliateReceiveFee()` untuk override ke 0
   - **Status:** ✅ OK (double protection: VIEW + Backend)

---

### BulkPaymentService.php

**Status Saat Ini:**
| Section | Status | Notes |
|---------|--------|-------|
| Price List Building | ✅ OK | Menggunakan `bulk_harga_beli` dari VIEW |
| Pricing Details Storage | ✅ OK | Simpan ke `detail_pesanan` |
| Fee Affiliate Calculation | ✅ OK | Menggunakan `shouldAffiliateReceiveFee()` |
| Store Payment | ✅ OK | Menggunakan `toko_harga_jual` dari VIEW |

**GAP:** ✅ Tidak ada gap di BulkPaymentService

---

## ✅ KESIMPULAN GAP ANALYSIS

### Gap yang Perlu Diperbaiki:

| # | Gap | Severity | Solusi |
|---|-----|----------|--------|
| 1 | VIEW `v_pembelian_paket_kuotaumroh` tidak punya bulk pricing columns | 🔴 HIGH | Update VIEW |

### Item yang Sudah OK:

1. ✅ VIEW `v_pembelian_paket_agent_travel` sudah lengkap (Referral + Non Referral)
2. ✅ VIEW `v_pembelian_paket_agent_travel_host` sudah sesuai (Host, no store)
3. ✅ Backend `shouldAffiliateReceiveFee()` sudah benar
4. ✅ Backend `agentHasStore()` sudah benar
5. ✅ BulkPaymentService sudah handle fee_affiliate dengan benar

### Rekomendasi Minimal Change:

**Option A: Update VIEW di Database**
- Update `v_pembelian_paket_kuotaumroh` untuk include bulk pricing columns
- Pro: Konsisten dengan VIEW lain
- Con: Perlu akses ke DB production

**Option B: Handle di Backend (PackagePricingService)**
- Untuk Super Host, calculate bulk pricing di backend dari kolom yang ada
- Pro: Tidak perlu update VIEW
- Con: Logic tersebar di 2 tempat

**Rekomendasi: Option A** - Update VIEW untuk konsistensi

---

## 📝 Next: Task 2 - SQL View Design

Berdasarkan gap analysis, perlu update 1 VIEW:
- `v_pembelian_paket_kuotaumroh` → Tambah bulk pricing columns

# Summary Update - Unique Constraint & Indexes

## Tanggal: 17 Januari 2026

### Perubahan yang Sudah Dilakukan

#### 1. **Controller Updates**
✅ **AffiliateController**
- Ditambahkan unique validation untuk `no_wa`
- Store: `'no_wa' => 'required|string|unique:affiliates,no_wa'`
- Update: `'no_wa' => 'string|unique:affiliates,no_wa,' . $id`

✅ **FreelanceController**
- Ditambahkan unique validation untuk `no_wa`
- Store: `'no_wa' => 'required|string|unique:freelances,no_wa'`
- Update: `'no_wa' => 'string|unique:freelances,no_wa,' . $id`

✅ **AgentController**
- Ditambahkan unique validation untuk `no_hp`
- Store: `'no_hp' => 'required|string|unique:agents,no_hp'`
- Update: `'no_hp' => 'string|unique:agents,no_hp,' . $id`
- Ditambahkan validasi custom untuk memastikan agent hanya terhubung ke affiliate ATAU freelance

#### 2. **Migration File**
✅ File: `2026_01_17_094059_add_unique_no_wa_and_indexes.php`

**Unique Constraints:**
- `affiliates.no_wa` - UNIQUE
- `freelances.no_wa` - UNIQUE
- `agents.no_hp` - UNIQUE

**Indexes untuk Optimasi Query:**

**Tabel Affiliates:**
- `email` - INDEX
- `provinsi` - INDEX
- `kab_kota` - INDEX
- `is_active` - INDEX
- `date_register` - INDEX

**Tabel Freelances:**
- `email` - INDEX
- `provinsi` - INDEX
- `kab_kota` - INDEX
- `is_active` - INDEX
- `date_register` - INDEX

**Tabel Agents:**
- `email` - INDEX
- `affiliate_id` - INDEX
- `freelance_id` - INDEX
- `kategori_agent` - INDEX
- `provinsi` - INDEX
- `kabupaten_kota` - INDEX
- `is_active` - INDEX
- `status` - INDEX
- `date_approve` - INDEX

### ⚠️ Catatan Penting untuk Migration

Migration file sudah dibuat dengan safety check untuk mengecek apakah constraint/index sudah ada sebelum menambahkannya.

**Jika migration gagal karena data duplikat:**

1. **Cek data duplikat di tabel affiliates:**
```sql
SELECT no_wa, COUNT(*) as count 
FROM affiliates 
GROUP BY no_wa 
HAVING count > 1;
```

2. **Cek data duplikat di tabel freelances:**
```sql
SELECT no_wa, COUNT(*) as count 
FROM freelances 
GROUP BY no_wa 
HAVING count > 1;
```

3. **Cek data duplikat di tabel agents:**
```sql
SELECT no_hp, COUNT(*) as count 
FROM agents 
GROUP BY no_hp 
HAVING count > 1;
```

4. **Bersihkan data duplikat** sebelum menjalankan migration:
   - Hapus atau update data yang duplikat
   - Pastikan setiap no_wa/no_hp adalah unique

5. **Jalankan migration:**
```bash
php artisan migrate
```

### Manfaat Indexes

**Performa Query yang Lebih Cepat:**
- Query dengan WHERE clause pada kolom yang di-index akan jauh lebih cepat
- JOIN operations akan lebih efisien
- Sorting (ORDER BY) akan lebih cepat

**Contoh Query yang Akan Lebih Cepat:**
```php
// Filter by provinsi
Affiliate::where('provinsi', 'DKI Jakarta')->get();

// Filter by is_active
Agent::where('is_active', true)->get();

// Filter by kategori_agent
Agent::where('kategori_agent', 'Referral')->get();

// Join dengan foreign key
Agent::with('affiliate')->where('affiliate_id', 1)->get();
```

### Testing Setelah Migration

Setelah migration berhasil, test dengan:

1. **Create Affiliate dengan no_wa yang sama:**
```bash
# Ini harus gagal dengan error unique constraint
curl -X POST http://127.0.0.1:8000/api/affiliates \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Test",
    "email": "test@example.com",
    "no_wa": "081234567890",
    ...
  }'
```

2. **Test performance dengan query besar:**
```php
// Sebelum index: lambat
// Setelah index: cepat
Agent::where('provinsi', 'DKI Jakarta')
     ->where('is_active', true)
     ->where('kategori_agent', 'Referral')
     ->get();
```

### Rollback (Jika Diperlukan)

Jika perlu rollback migration:
```bash
php artisan migrate:rollback --step=1
```

Ini akan menghapus semua unique constraints dan indexes yang ditambahkan.

---

## Checklist

- [x] Update AffiliateController validation
- [x] Update FreelanceController validation
- [x] Update AgentController validation
- [x] Buat migration file dengan unique constraints
- [x] Buat migration file dengan indexes
- [x] Tambahkan safety check di migration
- [ ] **Bersihkan data duplikat (jika ada)**
- [ ] **Jalankan migration**
- [ ] Test unique constraint
- [ ] Test query performance

---

## Next Steps

1. **Cek apakah ada data duplikat** di database
2. **Bersihkan data duplikat** jika ada
3. **Jalankan migration:** `php artisan migrate`
4. **Test endpoints** untuk memastikan unique constraint berfungsi
5. **Monitor performance** query setelah indexes ditambahkan

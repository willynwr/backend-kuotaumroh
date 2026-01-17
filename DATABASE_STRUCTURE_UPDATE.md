# Database Structure Update - Affiliate & Freelance

## Tanggal Update: 17 Januari 2026

### Perubahan Database

#### 1. Tabel Baru: `affiliates`
Tabel untuk menyimpan data affiliate.

**Kolom:**
- `id` - Primary Key
- `nama` - Nama affiliate
- `email` - Email (unique)
- `no_wa` - Nomor WhatsApp
- `provinsi` - Provinsi
- `kab_kota` - Kabupaten/Kota
- `alamat_lengkap` - Alamat lengkap (text)
- `date_register` - Tanggal registrasi (date)
- `is_active` - Status aktif/tidak aktif (boolean, default: true)
- `link_referal` - Link referral (unique)
- `created_at` - Timestamp dibuat
- `updated_at` - Timestamp diupdate

#### 2. Tabel Baru: `freelances`
Tabel untuk menyimpan data freelance.

**Kolom:**
- `id` - Primary Key
- `nama` - Nama freelance
- `email` - Email (unique)
- `no_wa` - Nomor WhatsApp
- `provinsi` - Provinsi
- `kab_kota` - Kabupaten/Kota
- `alamat_lengkap` - Alamat lengkap (text)
- `date_register` - Tanggal registrasi (date)
- `is_active` - Status aktif/tidak aktif (boolean, default: true)
- `link_referal` - Link referral (unique)
- `created_at` - Timestamp dibuat
- `updated_at` - Timestamp diupdate

#### 3. Update Tabel: `agents`

**Kolom Ditambahkan:**
- `affiliate_id` - Foreign key ke tabel affiliates (nullable)
- `freelance_id` - Foreign key ke tabel freelances (nullable)
- `kategori_agent` - Enum: 'Referral' atau 'Host'

**Kolom Dihapus:**
- `jenis_agent` - Kolom ini sudah tidak digunakan

**Relasi:**
- Agent bisa berasal dari Affiliate ATAU Freelance (salah satu, nullable)
- Jika Affiliate/Freelance dihapus, Agent terkait akan ikut terhapus (cascade delete)

### Struktur Relasi

```
Affiliate (1) -----> (N) Agent
Freelance (1) -----> (N) Agent
```

**Catatan:**
- Satu Agent hanya bisa terhubung ke SATU Affiliate ATAU SATU Freelance (tidak keduanya)
- `affiliate_id` dan `freelance_id` keduanya nullable
- Agent memiliki `kategori_agent` yang menentukan apakah dia Referral atau Host

### Model Laravel

#### Affiliate Model
```php
// app/Models/Affiliate.php
- Fillable: nama, email, no_wa, provinsi, kab_kota, alamat_lengkap, date_register, is_active, link_referal
- Relasi: hasMany(Agent::class)
```

#### Freelance Model
```php
// app/Models/Freelance.php
- Fillable: nama, email, no_wa, provinsi, kab_kota, alamat_lengkap, date_register, is_active, link_referal
- Relasi: hasMany(Agent::class)
```

#### Agent Model (Updated)
```php
// app/Models/Agent.php
- Fillable ditambahkan: affiliate_id, freelance_id, kategori_agent
- Fillable dihapus: jenis_agent
- Relasi ditambahkan: 
  - belongsTo(Affiliate::class)
  - belongsTo(Freelance::class)
```

### Migration Files
1. `2026_01_17_084611_create_affiliates_table.php`
2. `2026_01_17_084613_create_freelances_table.php`
3. `2026_01_17_084617_update_agents_table_for_affiliate_freelance.php`

### Cara Penggunaan

#### Membuat Affiliate Baru
```php
$affiliate = Affiliate::create([
    'nama' => 'John Doe',
    'email' => 'john@example.com',
    'no_wa' => '081234567890',
    'provinsi' => 'DKI Jakarta',
    'kab_kota' => 'Jakarta Selatan',
    'alamat_lengkap' => 'Jl. Contoh No. 123',
    'date_register' => now(),
    'is_active' => true,
    'link_referal' => 'https://example.com/ref/john123',
]);
```

#### Membuat Freelance Baru
```php
$freelance = Freelance::create([
    'nama' => 'Jane Smith',
    'email' => 'jane@example.com',
    'no_wa' => '081234567891',
    'provinsi' => 'Jawa Barat',
    'kab_kota' => 'Bandung',
    'alamat_lengkap' => 'Jl. Contoh No. 456',
    'date_register' => now(),
    'is_active' => true,
    'link_referal' => 'https://example.com/ref/jane456',
]);
```

#### Membuat Agent dari Affiliate
```php
$agent = Agent::create([
    'affiliate_id' => $affiliate->id,
    'freelance_id' => null,
    'kategori_agent' => 'Referral',
    'email' => 'agent@example.com',
    'nama_pic' => 'Agent Name',
    // ... kolom lainnya
]);
```

#### Membuat Agent dari Freelance
```php
$agent = Agent::create([
    'affiliate_id' => null,
    'freelance_id' => $freelance->id,
    'kategori_agent' => 'Host',
    'email' => 'agent2@example.com',
    'nama_pic' => 'Agent Name 2',
    // ... kolom lainnya
]);
```

#### Mengakses Relasi
```php
// Mendapatkan semua agent dari affiliate
$agents = $affiliate->agents;

// Mendapatkan affiliate dari agent
$affiliate = $agent->affiliate;

// Mendapatkan freelance dari agent
$freelance = $agent->freelance;
```

### Validasi yang Disarankan

Saat membuat/update Agent, pastikan:
1. Hanya salah satu dari `affiliate_id` atau `freelance_id` yang diisi (tidak keduanya)
2. `kategori_agent` harus diisi dengan nilai 'Referral' atau 'Host'
3. Jika `affiliate_id` diisi, pastikan Affiliate dengan ID tersebut ada dan aktif
4. Jika `freelance_id` diisi, pastikan Freelance dengan ID tersebut ada dan aktif

### Rollback

Jika perlu rollback, jalankan:
```bash
php artisan migrate:rollback --step=3
```

Ini akan menghapus 3 migration terakhir (affiliates, freelances, dan update agents).

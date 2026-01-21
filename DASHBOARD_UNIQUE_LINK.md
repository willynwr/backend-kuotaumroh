# Dashboard Unique Link Implementation

## Overview
Sistem dashboard unique per user untuk Affiliate dan Freelance menggunakan `link_referral` sebagai identifier unik.

## URL Structure
- **Format**: `dash/{link_referral}`
- **Example Affiliate**: `http://localhost/dash/AFF-ABC123`
- **Example Freelance**: `http://localhost/dash/FRL-XYZ789`

## Cara Kerja

### 1. Routing (routes/web.php)
```php
Route::prefix('dash')->name('dash.')->group(function () {
    Route::get('/{link_referral}', [DashboardController::class, 'show'])->name('show');
});
```

### 2. Controller (app/Http/Controllers/DashboardController.php)
Controller akan:
1. Cari `link_referral` di table `affiliates`
2. Jika tidak ditemukan, cari di table `freelances`
3. Return view yang sesuai dengan data user
4. Jika tidak ditemukan di kedua table, tampilkan 404

### 3. Views
- **Affiliate**: `resources/views/affiliate/dashboard.blade.php`
- **Freelance**: `resources/views/freelance/dashboard.blade.php`

### 4. Data yang Dikirim ke View
```php
[
    'user' => $affiliate/$freelance,  // Model instance
    'linkReferral' => $linkReferral,  // String link_referral dari URL
    'portalType' => 'affiliate'/'freelance'  // Type portal
]
```

## Fitur Dashboard

### Data yang Ditampilkan
1. **Stats Card**:
   - Saldo Poin
   - Total Poin Diperoleh
   - Total Agen
   - Agen Aktif Bulan Ini
   - Agen Baru Bulan Ini

2. **Referral Card**:
   - QR Code untuk link dashboard
   - Link dashboard unique: `dash/{link_referral}`
   - Tombol share ke WhatsApp & Telegram

3. **Menu Grid**:
   - Daftar Agent
   - Tukar Hadiah
   - Riwayat Poin

### JavaScript Features
- Auto-load data user dari controller
- Fetch agents data via API
- Generate QR code untuk link dashboard
- Copy to clipboard functionality
- Social media sharing

## API Endpoints (Optional)

### Get Affiliate Data
```
GET /api/dash/affiliate/{link_referral}
```

### Get Freelance Data
```
GET /api/dash/freelance/{link_referral}
```

Response:
```json
{
    "success": true,
    "data": {
        "affiliate/freelance": {...},
        "stats": {
            "total_agents": 10,
            "active_agents": 8,
            "pending_agents": 2
        },
        "agents": [...]
    }
}
```

## Database Requirements

### Kolom yang Digunakan
- `affiliates.link_referral` (varchar, unique)
- `affiliates.is_active` (boolean)
- `freelances.link_referral` (varchar, unique)
- `freelances.is_active` (boolean)

### Catatan
- Pastikan `link_referral` sudah terisi untuk semua user
- Pastikan `link_referral` unique per table
- Only active users (`is_active = true`) dapat mengakses dashboard

## Testing

### Manual Testing
1. Dapatkan `link_referral` dari database:
   ```sql
   SELECT id, nama, link_referral FROM affiliates LIMIT 5;
   SELECT id, nama, link_referral FROM freelances LIMIT 5;
   ```

2. Akses dashboard via browser:
   ```
   http://localhost/dash/{link_referral_yang_didapat}
   ```

3. Verifikasi:
   - Dashboard terbuka dengan data yang benar
   - QR Code muncul
   - Stats menampilkan data akurat
   - Link referral bisa di-copy
   - Share buttons berfungsi

### Expected Behavior
- ✅ Valid link_referral → Dashboard muncul
- ✅ Invalid link_referral → 404 error
- ✅ Inactive user → 404 error
- ✅ Dashboard menampilkan data sesuai user

## Migration from Old System

### Old System (Query Parameter)
```
/freelance/dashboard?id=123&type=freelance
/affiliate/dashboard?id=456&type=affiliate
```

### New System (Path Parameter)
```
/dash/FRL-ABC123  (untuk freelance)
/dash/AFF-XYZ789  (untuk affiliate)
```

### Benefits
1. ✅ URL lebih clean dan user-friendly
2. ✅ Lebih aman (tidak expose ID)
3. ✅ Shareable (setiap user punya link unique)
4. ✅ SEO friendly
5. ✅ Easier to remember

## Maintenance

### Update link_referral
Jika perlu update `link_referral`:
```php
$affiliate = Affiliate::find($id);
$affiliate->link_referral = 'NEW-LINK';
$affiliate->save();
```

### Bulk Generation
Jika ada user tanpa `link_referral`:
```php
// Di tinker atau migration
Affiliate::whereNull('link_referral')->each(function($affiliate) {
    $affiliate->link_referral = 'AFF-' . strtoupper(Str::random(8));
    $affiliate->save();
});

Freelance::whereNull('link_referral')->each(function($freelance) {
    $freelance->link_referral = 'FRL-' . strtoupper(Str::random(8));
    $freelance->save();
});
```

## Security Considerations
1. Validasi `is_active = true` untuk akses
2. `link_referral` harus unique
3. Rate limiting bisa ditambahkan jika perlu
4. Logging akses dashboard untuk monitoring

## Future Enhancements
1. Custom domain per user (e.g., `nama.kuotaumroh.id`)
2. Dashboard analytics
3. Custom theme per user
4. Vanity URLs (user bisa custom link_referral sendiri)
5. QR Code dengan logo branding

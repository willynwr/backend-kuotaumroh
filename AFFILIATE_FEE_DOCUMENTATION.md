# Dokumentasi Fee Affiliate

## Overview
Sistem untuk melacak komisi affiliate dari setiap transaksi yang dilakukan agent.

## Struktur Database

### Tabel: `pembayaran`
Kolom baru yang ditambahkan:
- `fee_affiliate` (INTEGER, DEFAULT 0) - Komisi untuk affiliate jika agent punya affiliate

### Lokasi Kolom
```
... → harga_modal → harga_jual → profit → fee_affiliate → metode_pembayaran ...
```

## Logic Perhitungan Fee Affiliate

### 1. Individual Payment (Store/Publik)
**Source**: VIEW `v_pembelian_paket_agent_travel` atau `v_pembelian_paket_toko_agent`
```
fee_affiliate = SUM(mandiri_final_fee_affiliate) dari semua item
```

### 2. Bulk Payment
**Source**: VIEW `v_pembelian_paket_agent_travel` atau `v_pembelian_paket_affiliate`
```
fee_affiliate = SUM(bulk_final_fee_affiliate) dari semua item
```

### 3. Kondisi Penting
- **HANYA** agent yang punya `affiliate_id` (tidak NULL) yang akan mendapat fee_affiliate
- Agent tanpa affiliate: `fee_affiliate = 0`
- Jika `totalFeeAffiliate` dari pricing = 0: `fee_affiliate = 0`

## Flow Diagram

```
┌─────────────────────────────────────────────────┐
│ Agent melakukan transaksi                        │
│ (Individual atau Bulk Payment)                   │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────┐
│ BulkPaymentService.storeLocalPaymentRecord()    │
│ - Ambil pricing dari VIEW                       │
│ - Hitung totalFeeAffiliate                      │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────┐
│ Cek apakah agent punya affiliate_id?            │
└────────┬───────────────────────────┬────────────┘
         │ Ya                        │ Tidak
         ▼                           ▼
┌────────────────────┐      ┌────────────────────┐
│ fee_affiliate =    │      │ fee_affiliate = 0  │
│ totalFeeAffiliate  │      │                    │
└────────┬───────────┘      └─────────┬──────────┘
         │                            │
         └──────────┬─────────────────┘
                    ▼
         ┌────────────────────┐
         │ Simpan ke DB       │
         │ pembayaran table   │
         └────────────────────┘
```

## Implementasi Code

### 1. Migration
**File**: `database/migrations/2026_02_02_000001_add_fee_affiliate_to_pembayaran_table.php`
```php
Schema::table('pembayaran', function (Blueprint $table) {
    $table->integer('fee_affiliate')->default(0)->after('profit');
});
```

### 2. Model Update
**File**: `app/Models/Pembayaran.php`
```php
protected $fillable = [
    // ... existing fields
    'profit',
    'fee_affiliate', // NEW
    'metode_pembayaran',
    // ...
];
```

### 3. Service Logic
**File**: `app/Services/Payment/BulkPaymentService.php`

#### Calculate Total Fee dari Pricing Details
```php
// BULK PRICING
foreach ($pricingDetails as $item) {
    $totalFeeAffiliate += (int) ($item['bulk_final_fee_affiliate'] ?? 0);
}

// INDIVIDUAL PRICING
foreach ($pricingDetails as $item) {
    $totalFeeAffiliate += (int) ($item['mandiri_final_fee_affiliate'] ?? 0);
}
```

#### Check Agent Affiliate Relationship
```php
$feeAffiliate = 0;
$agentId = $userId ?? $request['agent_id'] ?? $request['affiliate_id'] ?? null;

if ($agentId && $totalFeeAffiliate > 0) {
    $agent = Agent::where('id', $agentId)->first();
    
    if ($agent && $agent->affiliate_id) {
        $feeAffiliate = $totalFeeAffiliate;
    }
}
```

#### Save to Database
```php
$createData = [
    // ... existing fields
    'profit' => $totalProfit ?? 0,
    'fee_affiliate' => $feeAffiliate ?? 0,
    // ...
];

$payment = Pembayaran::create($createData);
```

## Test Cases

### Test 1: Agent dengan Affiliate
**Kondisi:**
- Agent ID: AGT00008
- Agent.affiliate_id: AFT00015 (not null)
- Transaksi: 2 paket @ Rp 50.000 fee affiliate

**Expected Result:**
```
fee_affiliate = 50000 + 50000 = 100000
```

### Test 2: Agent tanpa Affiliate (Host)
**Kondisi:**
- Agent ID: AGT00001
- Agent.affiliate_id: NULL
- Transaksi: 2 paket @ Rp 50.000 fee affiliate

**Expected Result:**
```
fee_affiliate = 0
```

### Test 3: Pricing Detail tanpa Fee Affiliate
**Kondisi:**
- Agent ID: AGT00008
- Agent.affiliate_id: AFT00015
- Transaksi: bulk_final_fee_affiliate = 0

**Expected Result:**
```
fee_affiliate = 0
```

## Logging

Service akan log setiap kalkulasi fee_affiliate:

```
💳 Affiliate fee assigned
   agent_id: AGT00008
   affiliate_id: AFT00015
   fee_affiliate: 100000
```

atau

```
⚠️ Agent has no affiliate, fee_affiliate = 0
   agent_id: AGT00001
   has_affiliate: false
```

## Query untuk Monitoring

### Cek pembayaran dengan fee_affiliate
```sql
SELECT 
    id,
    agent_id,
    profit,
    fee_affiliate,
    status_pembayaran,
    created_at
FROM pembayaran
WHERE fee_affiliate > 0
ORDER BY created_at DESC;
```

### Total fee_affiliate per agent
```sql
SELECT 
    agent_id,
    COUNT(*) as total_transaksi,
    SUM(fee_affiliate) as total_fee_affiliate,
    SUM(profit) as total_profit
FROM pembayaran
WHERE status_pembayaran = 'SUCCESS'
GROUP BY agent_id
ORDER BY total_fee_affiliate DESC;
```

### Agent dengan affiliate dan fee
```sql
SELECT 
    a.id,
    a.nama_travel,
    a.affiliate_id,
    COUNT(p.id) as total_transaksi,
    SUM(p.fee_affiliate) as total_fee_affiliate
FROM agent a
LEFT JOIN pembayaran p ON p.agent_id = a.id
WHERE a.affiliate_id IS NOT NULL
GROUP BY a.id, a.nama_travel, a.affiliate_id
ORDER BY total_fee_affiliate DESC;
```

## Catatan Penting

1. **Fee affiliate TIDAK otomatis ditambahkan ke saldo affiliate**
   - Kolom `fee_affiliate` hanya untuk tracking/audit
   - Update saldo affiliate dilakukan oleh observer atau cron job terpisah

2. **Hanya untuk transaksi SUCCESS**
   - Fee affiliate dihitung sejak awal
   - Tapi update ke saldo affiliate hanya saat status = SUCCESS

3. **Kompatibilitas dengan sistem lama**
   - Default value = 0, tidak akan error untuk data lama
   - Migration berjalan tanpa downtime

4. **Relasi Agent-Affiliate**
   - Agent dengan affiliate_id = NULL → Host Agent (fee = 0)
   - Agent dengan affiliate_id = AFTxxxx → Referal Agent (fee > 0)

## Files Modified

1. ✅ `database/migrations/2026_02_02_000001_add_fee_affiliate_to_pembayaran_table.php` - CREATED
2. ✅ `app/Models/Pembayaran.php` - Updated fillable
3. ✅ `app/Services/Payment/BulkPaymentService.php` - Added fee calculation logic

## Migration Status

```bash
php artisan migrate:status
```

Result: Migration `2026_02_02_000001_add_fee_affiliate_to_pembayaran_table` - [6] Ran ✅

## Next Steps (Optional Future Enhancement)

1. **Affiliate Dashboard**
   - Tampilkan total fee_affiliate yang didapat dari agents
   - Breakdown per agent dan per transaksi

2. **Auto Update Saldo Affiliate**
   - Observer di Pembayaran model
   - Saat status berubah ke SUCCESS, update saldo affiliate

3. **Report & Analytics**
   - Monthly fee_affiliate report
   - Commission trend chart

4. **Withdrawal System for Affiliate**
   - Affiliate bisa withdraw fee yang sudah terkumpul
   - Similar dengan Agent withdraw system

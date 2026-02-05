# INVOICE AGENT DISPLAY FIX

## Problem
Ketika customer checkout dari store agent, invoice menampilkan "Kuotaumroh.id" sebagai travel agent, bukan nama agent store tersebut.

## Solution

### 1. Backend - BulkPaymentService.php
File: `app/Services/Payment/BulkPaymentService.php`

**Update di method `getLocalPaymentDetail()` (line ~1170):**
- Menambahkan query untuk mendapatkan data agent dari database
- Mengirim `agent_name` dan `agent_phone` di response

```php
// Ambil data agent untuk invoice (dari relasi atau agent_id)
$agentName = 'Kuotaumroh.id';
$agentPhone = '+62 812-3456-7890';

if ($payment->agent_id) {
    $agent = Agent::find($payment->agent_id);
    if ($agent) {
        $agentName = $agent->nama_pic ?? 'Kuotaumroh.id';
        $agentPhone = $agent->no_hp ?? '+62 812-3456-7890';
    }
}
```

**Response structure now includes:**
```php
'agent_id' => $payment->agent_id,
'agent_name' => $agentName,        // NEW
'agent_phone' => $agentPhone,      // NEW
```

### 2. Frontend - invoice.blade.php
File: `resources/views/invoice.blade.php`

**Existing code already handles agent data (line ~913-915):**
```javascript
this.invoice.agent.name = item.agent_name || 'Kuotaumroh.id';
this.invoice.agent.pic = 'Kuotaumroh.id'; 
this.invoice.agent.phone = item.agent_phone || '+62 812-3456-7890';
```

## Data Flow

### Store Checkout Flow:
1. Customer checkout dari `/store/{linkReferal}` atau `/dash/{linkReferal}`
2. `createIndividualPayment()` dipanggil dengan `agent_id` atau `ref_code`
3. Controller `UmrohPaymentController@createIndividualPayment`:
   - Resolve `agent_id` dari `ref_code` (link_referal)
   - Pass `_agent_id` ke service
4. Service `BulkPaymentService@createIndividualPayment`:
   - Call external API
   - Save to local DB via `storeLocalPaymentRecord()` dengan `agent_id`
5. Invoice fetch data via `getLocalPaymentDetail()`:
   - Query agent data from database
   - Return `agent_name` dan `agent_phone`
6. Invoice view display agent info:
   - `invoice.agent.name` = nama_pic agent
   - `invoice.agent.phone` = no_hp agent

## Testing

### Test Store Checkout:
1. Buka store agent: `/dash/{linkReferal}` (contoh: `/dash/agent-faiz`)
2. Pilih paket, isi nomor HP, checkout
3. Setelah payment SUCCESS, buka invoice
4. **Expected:** Travel Agent = nama PIC agent tersebut
5. **Expected:** Phone = nomor HP agent tersebut

### Test Homepage Checkout (Default):
1. Buka homepage: `/`
2. Pilih paket, isi nomor HP, checkout
3. Setelah payment, buka invoice
4. **Expected:** Travel Agent = "Kuotaumroh.id" (default)

## Database Schema
Table `pembayaran`:
- `agent_id` (string nullable) - Stores AGTxxxxx ID from store checkout

Table `agent`:
- `id` (string primary) - Custom ID format AGTxxxxx
- `nama_pic` (string) - PIC name untuk display
- `no_hp` (string) - Phone number untuk display

## Notes
- Bulk invoice (batch) sudah handle agent data dari field `agent_name` di response
- Individual invoice (store) sekarang juga sudah handle dengan query ke table agent
- Default tetap "Kuotaumroh.id" jika tidak ada agent_id atau agent tidak ditemukan

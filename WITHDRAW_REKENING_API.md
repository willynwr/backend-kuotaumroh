# API Withdraw & Rekening - Kuotaumroh Backend

## Table Structure

### Table: `rekenings`
- `id` - Primary key
- `agent_id` - Foreign key ke `agents.id`
- `nama_rekening` - Nama pemilik rekening
- `bank` - Nama bank
- `nomor_rekening` - Nomor rekening
- `timestamps`

### Table: `withdraws`
- `id` - Primary key
- `agent_id` - Foreign key ke `agents.id`
- `rekening_id` - Foreign key ke `rekenings.id`
- `jumlah` - Integer (minimum 100000)
- `keterangan` - Text nullable
- `status` - String (default: 'pending')
- `date_approve` - Date nullable
- `timestamps`

## API Endpoints

### Rekening

#### 1. Get List Rekening
```
GET /agent/rekenings?agent_id={agent_id}
```
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "agent_id": 1,
      "nama_rekening": "Ahmad Fauzi",
      "bank": "BCA",
      "nomor_rekening": "1234567890",
      "created_at": "2026-01-22T10:00:00.000000Z",
      "updated_at": "2026-01-22T10:00:00.000000Z"
    }
  ]
}
```

#### 2. Tambah Rekening
```
POST /agent/rekenings
Content-Type: application/json
```
**Request Body:**
```json
{
  "agent_id": 1,
  "nama_rekening": "Ahmad Fauzi",
  "bank": "BCA",
  "nomor_rekening": "1234567890"
}
```
**Response:**
```json
{
  "success": true,
  "message": "Rekening berhasil ditambahkan",
  "data": {
    "id": 1,
    "agent_id": 1,
    "nama_rekening": "Ahmad Fauzi",
    "bank": "BCA",
    "nomor_rekening": "1234567890",
    "created_at": "2026-01-22T10:00:00.000000Z",
    "updated_at": "2026-01-22T10:00:00.000000Z"
  }
}
```

#### 3. Hapus Rekening
```
DELETE /agent/rekenings/{id}
```
**Response:**
```json
{
  "success": true,
  "message": "Rekening berhasil dihapus"
}
```

### Withdraw

#### 1. Get List Withdraw
```
GET /agent/withdraws?agent_id={agent_id}
```
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "agent_id": 1,
      "rekening_id": 1,
      "jumlah": 500000,
      "keterangan": "Penarikan saldo",
      "status": "pending",
      "date_approve": null,
      "created_at": "2026-01-22T10:00:00.000000Z",
      "updated_at": "2026-01-22T10:00:00.000000Z",
      "agent": { ... },
      "rekening": { ... }
    }
  ]
}
```

#### 2. Ajukan Withdraw
```
POST /agent/withdraws
Content-Type: application/json
```
**Request Body:**
```json
{
  "agent_id": 1,
  "rekening_id": 1,
  "jumlah": 500000,
  "keterangan": "Penarikan saldo"
}
```
**Response:**
```json
{
  "success": true,
  "message": "Permintaan penarikan berhasil diajukan",
  "data": {
    "id": 1,
    "agent_id": 1,
    "rekening_id": 1,
    "jumlah": 500000,
    "keterangan": "Penarikan saldo",
    "status": "pending",
    "date_approve": null,
    "created_at": "2026-01-22T10:00:00.000000Z",
    "updated_at": "2026-01-22T10:00:00.000000Z",
    "agent": { ... },
    "rekening": { ... }
  }
}
```

## Models & Relations

### Agent Model
```php
public function rekenings()
{
    return $this->hasMany(Rekening::class);
}

public function withdraws()
{
    return $this->hasMany(Withdraw::class);
}
```

### Rekening Model
```php
public function agent()
{
    return $this->belongsTo(Agent::class);
}

public function withdraws()
{
    return $this->hasMany(Withdraw::class);
}
```

### Withdraw Model
```php
public function agent()
{
    return $this->belongsTo(Agent::class);
}

public function rekening()
{
    return $this->belongsTo(Rekening::class);
}
```

## Migration Commands
```bash
# Jalankan migration
php artisan migrate

# Rollback jika diperlukan
php artisan migrate:rollback
```

## Notes
- Minimum penarikan: Rp 100.000
- Status withdraw default: 'pending'
- Foreign key cascade delete: Jika agent/rekening dihapus, withdraw/rekening terkait akan ikut terhapus
- CSRF token diperlukan untuk semua POST/DELETE request
- Agent ID saat ini hardcoded di controller (TODO: gunakan auth/session yang sebenarnya)

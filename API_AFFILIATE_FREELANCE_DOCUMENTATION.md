# API Documentation - Affiliate & Freelance

## Base URL
```
http://127.0.0.1:8000/api
```

---

## AFFILIATE ENDPOINTS

### 1. Get All Affiliates
**Endpoint:** `GET /affiliates`

**Response:**
```json
{
  "message": "Affiliates retrieved successfully",
  "data": [
    {
      "id": 1,
      "nama": "John Doe",
      "email": "john@example.com",
      "no_wa": "081234567890",
      "provinsi": "DKI Jakarta",
      "kab_kota": "Jakarta Selatan",
      "alamat_lengkap": "Jl. Contoh No. 123",
      "date_register": "2026-01-17",
      "is_active": true,
      "link_referal": "https://example.com/ref/john123",
      "created_at": "2026-01-17T08:00:00.000000Z",
      "updated_at": "2026-01-17T08:00:00.000000Z",
      "agents": []
    }
  ]
}
```

---

### 2. Get Affiliate by ID
**Endpoint:** `GET /affiliates/{id}`

**Response:**
```json
{
  "message": "Affiliate retrieved successfully",
  "data": {
    "id": 1,
    "nama": "John Doe",
    "email": "john@example.com",
    "no_wa": "081234567890",
    "provinsi": "DKI Jakarta",
    "kab_kota": "Jakarta Selatan",
    "alamat_lengkap": "Jl. Contoh No. 123",
    "date_register": "2026-01-17",
    "is_active": true,
    "link_referal": "https://example.com/ref/john123",
    "created_at": "2026-01-17T08:00:00.000000Z",
    "updated_at": "2026-01-17T08:00:00.000000Z",
    "agents": []
  }
}
```

---

### 3. Create New Affiliate
**Endpoint:** `POST /affiliates`

**Request Body:**
```json
{
  "nama": "John Doe",
  "email": "john@example.com",
  "no_wa": "081234567890",
  "provinsi": "DKI Jakarta",
  "kab_kota": "Jakarta Selatan",
  "alamat_lengkap": "Jl. Contoh No. 123",
  "date_register": "2026-01-17",
  "is_active": true,
  "link_referal": "https://example.com/ref/john123"
}
```

**Required Fields:**
- `nama` (string, max 255)
- `email` (email, unique)
- `no_wa` (string)
- `provinsi` (string)
- `kab_kota` (string)
- `alamat_lengkap` (string)
- `link_referal` (string, unique)

**Optional Fields:**
- `date_register` (date, default: today)
- `is_active` (boolean, default: true)

**Response:**
```json
{
  "message": "Affiliate successfully created",
  "data": {
    "id": 1,
    "nama": "John Doe",
    "email": "john@example.com",
    ...
  }
}
```

---

### 4. Update Affiliate
**Endpoint:** `PUT /affiliates/{id}`

**Request Body:**
```json
{
  "nama": "John Doe Updated",
  "is_active": false
}
```

**All fields are optional**

**Response:**
```json
{
  "message": "Affiliate successfully updated",
  "data": {
    "id": 1,
    "nama": "John Doe Updated",
    ...
  }
}
```

---

### 5. Delete Affiliate
**Endpoint:** `DELETE /affiliates/{id}`

**Response (Success):**
```json
{
  "message": "Affiliate successfully deleted"
}
```

**Response (Has Agents):**
```json
{
  "message": "Cannot delete affiliate. There are 3 agent(s) associated with this affiliate.",
  "agent_count": 3
}
```

---

### 6. Activate Affiliate
**Endpoint:** `POST /affiliates/{id}/activate`

**Response:**
```json
{
  "message": "Affiliate successfully activated",
  "data": {
    "id": 1,
    "is_active": true,
    ...
  }
}
```

---

### 7. Deactivate Affiliate
**Endpoint:** `POST /affiliates/{id}/deactivate`

**Response:**
```json
{
  "message": "Affiliate successfully deactivated",
  "data": {
    "id": 1,
    "is_active": false,
    ...
  }
}
```

---

### 8. Get All Agents for Affiliate
**Endpoint:** `GET /affiliates/{id}/agents`

**Response:**
```json
{
  "message": "Agents retrieved successfully",
  "data": [
    {
      "id": 1,
      "affiliate_id": 1,
      "freelance_id": null,
      "kategori_agent": "Referral",
      "email": "agent@example.com",
      ...
    }
  ]
}
```

---

## FREELANCE ENDPOINTS

### 1. Get All Freelances
**Endpoint:** `GET /freelances`

**Response:**
```json
{
  "message": "Freelances retrieved successfully",
  "data": [
    {
      "id": 1,
      "nama": "Jane Smith",
      "email": "jane@example.com",
      "no_wa": "081234567891",
      "provinsi": "Jawa Barat",
      "kab_kota": "Bandung",
      "alamat_lengkap": "Jl. Contoh No. 456",
      "date_register": "2026-01-17",
      "is_active": true,
      "link_referal": "https://example.com/ref/jane456",
      "created_at": "2026-01-17T08:00:00.000000Z",
      "updated_at": "2026-01-17T08:00:00.000000Z",
      "agents": []
    }
  ]
}
```

---

### 2. Get Freelance by ID
**Endpoint:** `GET /freelances/{id}`

**Response:**
```json
{
  "message": "Freelance retrieved successfully",
  "data": {
    "id": 1,
    "nama": "Jane Smith",
    ...
  }
}
```

---

### 3. Create New Freelance
**Endpoint:** `POST /freelances`

**Request Body:**
```json
{
  "nama": "Jane Smith",
  "email": "jane@example.com",
  "no_wa": "081234567891",
  "provinsi": "Jawa Barat",
  "kab_kota": "Bandung",
  "alamat_lengkap": "Jl. Contoh No. 456",
  "date_register": "2026-01-17",
  "is_active": true,
  "link_referal": "https://example.com/ref/jane456"
}
```

**Required Fields:**
- `nama` (string, max 255)
- `email` (email, unique)
- `no_wa` (string)
- `provinsi` (string)
- `kab_kota` (string)
- `alamat_lengkap` (string)
- `link_referal` (string, unique)

**Optional Fields:**
- `date_register` (date, default: today)
- `is_active` (boolean, default: true)

**Response:**
```json
{
  "message": "Freelance successfully created",
  "data": {
    "id": 1,
    "nama": "Jane Smith",
    ...
  }
}
```

---

### 4. Update Freelance
**Endpoint:** `PUT /freelances/{id}`

**Request Body:**
```json
{
  "nama": "Jane Smith Updated",
  "is_active": false
}
```

**All fields are optional**

**Response:**
```json
{
  "message": "Freelance successfully updated",
  "data": {
    "id": 1,
    "nama": "Jane Smith Updated",
    ...
  }
}
```

---

### 5. Delete Freelance
**Endpoint:** `DELETE /freelances/{id}`

**Response (Success):**
```json
{
  "message": "Freelance successfully deleted"
}
```

**Response (Has Agents):**
```json
{
  "message": "Cannot delete freelance. There are 2 agent(s) associated with this freelance.",
  "agent_count": 2
}
```

---

### 6. Activate Freelance
**Endpoint:** `POST /freelances/{id}/activate`

**Response:**
```json
{
  "message": "Freelance successfully activated",
  "data": {
    "id": 1,
    "is_active": true,
    ...
  }
}
```

---

### 7. Deactivate Freelance
**Endpoint:** `POST /freelances/{id}/deactivate`

**Response:**
```json
{
  "message": "Freelance successfully deactivated",
  "data": {
    "id": 1,
    "is_active": false,
    ...
  }
}
```

---

### 8. Get All Agents for Freelance
**Endpoint:** `GET /freelances/{id}/agents`

**Response:**
```json
{
  "message": "Agents retrieved successfully",
  "data": [
    {
      "id": 2,
      "affiliate_id": null,
      "freelance_id": 1,
      "kategori_agent": "Host",
      "email": "agent2@example.com",
      ...
    }
  ]
}
```

---

## UPDATED AGENT ENDPOINTS

### Create Agent (Updated)
**Endpoint:** `POST /agents`

**Request Body:**
```json
{
  "email": "agent@example.com",
  "affiliate_id": 1,
  "freelance_id": null,
  "kategori_agent": "Referral",
  "nama_pic": "Agent Name",
  "no_hp": "081234567890",
  "provinsi": "DKI Jakarta",
  "kabupaten_kota": "Jakarta Pusat",
  "alamat_lengkap": "Jl. Agent No. 789",
  "nama_travel": "Travel ABC",
  "jenis_travel": "Umroh"
}
```

**Required Fields:**
- `email` (email)
- `kategori_agent` (enum: 'Referral' or 'Host')
- `nama_pic` (string)
- `no_hp` (string)
- `provinsi` (string)
- `kabupaten_kota` (string)
- `alamat_lengkap` (string)

**Important:**
- Either `affiliate_id` OR `freelance_id` must be provided (not both)
- `affiliate_id` must exist in affiliates table
- `freelance_id` must exist in freelances table

**Validation Errors:**
```json
{
  "message": "Agent hanya bisa terhubung ke Affiliate ATAU Freelance, tidak keduanya"
}
```

```json
{
  "message": "Agent harus terhubung ke Affiliate atau Freelance"
}
```

---

## Error Responses

### 404 Not Found
```json
{
  "message": "Affiliate not found"
}
```

### 422 Validation Error
```json
{
  "email": [
    "The email has already been taken."
  ],
  "link_referal": [
    "The link referal has already been taken."
  ]
}
```

---

## Testing Examples

### Using cURL

#### Create Affiliate
```bash
curl -X POST http://127.0.0.1:8000/api/affiliates \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "John Doe",
    "email": "john@example.com",
    "no_wa": "081234567890",
    "provinsi": "DKI Jakarta",
    "kab_kota": "Jakarta Selatan",
    "alamat_lengkap": "Jl. Contoh No. 123",
    "link_referal": "https://example.com/ref/john123"
  }'
```

#### Create Freelance
```bash
curl -X POST http://127.0.0.1:8000/api/freelances \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Jane Smith",
    "email": "jane@example.com",
    "no_wa": "081234567891",
    "provinsi": "Jawa Barat",
    "kab_kota": "Bandung",
    "alamat_lengkap": "Jl. Contoh No. 456",
    "link_referal": "https://example.com/ref/jane456"
  }'
```

#### Create Agent from Affiliate
```bash
curl -X POST http://127.0.0.1:8000/api/agents \
  -H "Content-Type: application/json" \
  -d '{
    "email": "agent@example.com",
    "affiliate_id": 1,
    "kategori_agent": "Referral",
    "nama_pic": "Agent Name",
    "no_hp": "081234567890",
    "provinsi": "DKI Jakarta",
    "kabupaten_kota": "Jakarta Pusat",
    "alamat_lengkap": "Jl. Agent No. 789"
  }'
```

#### Get All Affiliates
```bash
curl http://127.0.0.1:8000/api/affiliates
```

#### Activate Affiliate
```bash
curl -X POST http://127.0.0.1:8000/api/affiliates/1/activate
```

---

## Notes

1. **Cascade Delete**: Jika Affiliate atau Freelance dihapus, semua Agent yang terhubung akan ikut terhapus secara otomatis di database level.

2. **Soft Delete Protection**: Di controller level, ada proteksi untuk mencegah penghapusan Affiliate/Freelance yang masih memiliki Agent.

3. **Default Values**: 
   - `date_register` default: tanggal hari ini
   - `is_active` default: true

4. **Unique Constraints**:
   - `email` harus unique per tabel
   - `link_referal` harus unique per tabel

5. **Agent Relationship**:
   - Agent hanya bisa terhubung ke SATU Affiliate ATAU SATU Freelance
   - Tidak boleh keduanya atau tidak ada sama sekali

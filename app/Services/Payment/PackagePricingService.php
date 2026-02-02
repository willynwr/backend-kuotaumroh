<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\DB;

/**
 * Package Pricing Service
 * 
 * Query paket harga dari VIEW DB kuotaumroh.
 * Mendukung pricing berbeda per user (affiliate_id, agent_id) dan context (bulk vs store).
 * 
 * Rules:
 * - Affiliate (AFTxxx) -> v_pembelian_paket_affiliate
 * - Agent (AGTxxx) -> v_pembelian_paket_travel_agent
 * - Admin (ADMxxx) -> v_pembelian_paket_kuotaumroh
 */
class PackagePricingService
{
    /**
     * Detect role dari ID format
     * 
     * @param string $id affiliate_id, agent_id, atau admin_id
     * @return string 'affiliate' | 'agent' | 'admin' | null
     */
    public function detectRole(string $id): ?string
    {
        $prefix = substr($id, 0, 3);
        
        return match ($prefix) {
            'AFT' => 'affiliate',
            'AGT' => 'agent',
            'ADM' => 'admin',
            default => null,
        };
    }

    /**
     * Validate format ID sesuai role
     * 
     * @param string $id
     * @param string $role 'affiliate' | 'agent' | 'admin'
     * @return bool
     */
    public function validateRoleId(string $id, string $role): bool
    {
        $prefix = substr($id, 0, 3);
        $expected = match ($role) {
            'affiliate' => 'AFT',
            'agent' => 'AGT',
            'admin' => 'ADM',
            default => null,
        };
        
        return $prefix === $expected;
    }

    /**
     * Get VIEW table name berdasarkan role
     * 
     * @param string $role
     * @return string VIEW table name
     */
    protected function getViewTableName(string $role): string
    {
        return match ($role) {
            'affiliate' => 'v_pembelian_paket_affiliate',
            'agent' => 'v_pembelian_paket_agent_travel',
            'admin' => 'v_pembelian_paket_kuotaumroh',
            default => throw new \InvalidArgumentException("Unknown role: {$role}"),
        };
    }

    /**
     * Get WHERE column name untuk filter by ID
     * 
     * @param string $role
     * @return string column name (affiliate_id, agent_id, dll)
     */
    protected function getIdColumnName(string $role): string
    {
        return match ($role) {
            'affiliate' => 'affiliate_id',
            'agent' => 'agent_id',
            'admin' => 'agent_id', // Admin juga pakai agent_id tapi dengan prefix ADM
            default => throw new \InvalidArgumentException("Unknown role: {$role}"),
        };
    }

    /**
     * Generate nama paket dari data VIEW
     * Format: "Provider TotalKuota - MasaAktif Hari" atau "Provider TipePaket - MasaAktif Hari"
     * 
     * @param array $row Data dari VIEW
     * @return string Nama paket yang di-generate
     */
    protected function generatePackageName(array $row): string
    {
        $provider = $row['provider'] ?? 'Unknown';
        $totalKuota = $row['total_kuota'] ?? null;
        $tipePaket = $row['tipe_paket'] ?? $row['sub_type'] ?? null;
        $masaAktif = $row['masa_aktif'] ?? $row['days'] ?? null;
        
        $nameParts = [$provider];
        
        // Tambah kuota atau tipe paket
        if ($totalKuota) {
            $nameParts[] = $totalKuota;
        } elseif ($tipePaket) {
            $nameParts[] = $tipePaket;
        }
        
        // Tambah masa aktif
        if ($masaAktif) {
            $nameParts[] = "{$masaAktif} Hari";
        }
        
        return implode(' - ', $nameParts);
    }

    /**
     * Map row data dari VIEW ke format response catalog
     * Untuk context BULK (affiliate, agent, admin)
     * 
     * @param array $row Data dari VIEW
     * @param string $role Untuk determine field mana yang dipake
     * @return array Mapped data
     */
    protected function mapBulkCatalogRow(array $row, string $role): array
    {
        // Generate name jika tidak ada di VIEW
        $generatedName = $this->generatePackageName($row);
        
        $mapped = [
            'id' => $row['produk_id'] ?? $row['package_id'] ?? $row['id'] ?? null,
            'package_id' => $row['produk_id'] ?? $row['package_id'] ?? $row['id'] ?? null,
            'name' => $row['nama_paket'] ?? $row['package_name'] ?? $generatedName,
            'packageName' => $row['nama_paket'] ?? $row['package_name'] ?? $generatedName,
            'type' => $row['provider'] ?? $row['type'] ?? null,
            'provider' => $row['provider'] ?? $row['type'] ?? null,
            'sub_type' => $row['tipe_paket'] ?? $row['sub_type'] ?? null,
            'tipe_paket' => $row['tipe_paket'] ?? $row['sub_type'] ?? null,
            'days' => $row['masa_aktif'] ?? $row['days'] ?? null,
            'masa_aktif' => $row['masa_aktif'] ?? $row['days'] ?? null,
            // PENTING: quota = kuota_utama (49 GB), BUKAN total_kuota (50 GB)
            'quota' => $row['kuota_utama'] ?? $row['quota'] ?? null,
            'kuota_utama' => $row['kuota_utama'] ?? null,
            'total_kuota' => $row['total_kuota'] ?? null,
            'telp' => $row['telp'] ?? null,
            'sms' => $row['sms'] ?? null,
            // PENTING: bonus = kuota_bonus (1 GB Transit)
            'bonus' => $row['kuota_bonus'] ?? $row['bonus'] ?? null,
            'kuota_bonus' => $row['kuota_bonus'] ?? null,
            'is_active' => '1',
            
            // ===== BULK PRICING FIELDS =====
            // Harga coret (recommended)
            'price_app' => (int) ($row['bulk_harga_rekomendasi'] ?? 0),
            'bulk_harga_rekomendasi' => (int) ($row['bulk_harga_rekomendasi'] ?? 0),
            
            // Harga beli (yang dibayar ke supplier)
            'price' => (int) ($row['bulk_harga_beli'] ?? 0),
            'bulk_harga_beli' => (int) ($row['bulk_harga_beli'] ?? 0),
            
            // Potensi Profit (PENTING: harus ada untuk semua role)
            'bulk_potensi_profit' => (int) ($row['bulk_potensi_profit'] ?? 0),
            'profit' => (int) ($row['bulk_potensi_profit'] ?? 0),
            
            // Legacy field mapping for compatibility
            'price_bulk' => (int) ($row['bulk_harga_beli'] ?? 0),
            'price_customer' => (int) ($row['bulk_harga_rekomendasi'] ?? 0),
            'harga' => (int) ($row['bulk_harga_beli'] ?? 0),
        ];
        
        // Tambah role-specific profit naming untuk affiliate/agent (tapi bukan admin)
        if ($role !== 'admin' && isset($row['bulk_potensi_profit'])) {
            // Role-specific profit naming
            if ($role === 'affiliate') {
                $mapped['profit_affiliate'] = (int) $row['bulk_potensi_profit'];
            } elseif ($role === 'agent') {
                $mapped['profit_agent'] = (int) $row['bulk_potensi_profit'];
            }
        }
        
        // Optional: promo field
        if (isset($row['promo'])) {
            $mapped['promo'] = $row['promo'];
        }
        
        return $mapped;
    }

    /**
     * Map row data dari VIEW ke format response catalog
     * Untuk context STORE PUBLIK (individu, agent only)
     * 
     * @param array $row Data dari VIEW (v_pembelian_paket_travel_agent)
     * @return array Mapped data
     */
    protected function mapStoreCatalogRow(array $row): array
    {
        // Generate name jika tidak ada di VIEW
        $generatedName = $this->generatePackageName($row);
        
        $mapped = [
            'id' => $row['produk_id'] ?? $row['package_id'] ?? $row['id'] ?? null,
            'package_id' => $row['produk_id'] ?? $row['package_id'] ?? $row['id'] ?? null,
            'name' => $row['nama_paket'] ?? $row['package_name'] ?? $generatedName,
            'packageName' => $row['nama_paket'] ?? $row['package_name'] ?? $generatedName,
            'type' => $row['provider'] ?? $row['type'] ?? null,
            'provider' => $row['provider'] ?? $row['type'] ?? null,
            'sub_type' => $row['tipe_paket'] ?? $row['sub_type'] ?? null,
            'tipe_paket' => $row['tipe_paket'] ?? $row['sub_type'] ?? null,
            'days' => $row['masa_aktif'] ?? $row['days'] ?? null,
            'masa_aktif' => $row['masa_aktif'] ?? $row['days'] ?? null,
            // PENTING: quota = kuota_utama (49 GB), BUKAN total_kuota (50 GB)
            'quota' => $row['kuota_utama'] ?? $row['quota'] ?? null,
            'kuota_utama' => $row['kuota_utama'] ?? null,
            'total_kuota' => $row['total_kuota'] ?? null,
            'telp' => $row['telp'] ?? null,
            'sms' => $row['sms'] ?? null,
            // PENTING: bonus = kuota_bonus (1 GB Transit)
            'bonus' => $row['kuota_bonus'] ?? $row['bonus'] ?? null,
            'kuota_bonus' => $row['kuota_bonus'] ?? null,
            'is_active' => '1',
            
            // ===== STORE PRICING FIELDS (INDIVIDU VIA TOKO) =====
            // Harga coret (strikethrough price untuk display)
            'price_app' => (int) ($row['toko_harga_coret'] ?? 0),
            'toko_harga_coret' => (int) ($row['toko_harga_coret'] ?? 0),
            
            // Harga jual customer (harga final yang dibayar)
            'price' => (int) ($row['toko_harga_jual'] ?? 0),
            'toko_harga_jual' => (int) ($row['toko_harga_jual'] ?? 0),
            
            // Hemat/diskon (selisih coret - jual)
            'hemat' => (int) ($row['toko_hemat'] ?? 0),
            'toko_hemat' => (int) ($row['toko_hemat'] ?? 0),
            
            // Profit untuk pembelian individu
            'profit_agent' => (int) ($row['mandiri_final_fee_travel'] ?? 0),
            'profit_affiliate' => (int) ($row['mandiri_final_fee_affiliate'] ?? 0),
            'mandiri_final_fee_travel' => (int) ($row['mandiri_final_fee_travel'] ?? 0),
            'mandiri_final_fee_affiliate' => (int) ($row['mandiri_final_fee_affiliate'] ?? 0),
        ];
        
        // Optional: promo field
        if (isset($row['promo'])) {
            $mapped['promo'] = $row['promo'];
        }
        
        return $mapped;
    }

    /**
     * Get bulk catalog untuk AFFILIATE
     * 
     * @param string $affiliateId Format: AFTxxx
     * @return array Array of packages dengan bulk pricing
     */
    public function getBulkCatalogForAffiliate(string $affiliateId): array
    {
        if (!$this->validateRoleId($affiliateId, 'affiliate')) {
            throw new \InvalidArgumentException("Invalid affiliate ID format: {$affiliateId}");
        }

        $rows = DB::table('v_pembelian_paket_affiliate')
            ->where('affiliate_id', $affiliateId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapBulkCatalogRow((array) $row, 'affiliate'), $rows);
    }

    /**
     * Get bulk catalog untuk AGENT
     * 
     * @param string $agentId Format: AGTxxx
     * @return array Array of packages dengan bulk pricing
     */
    public function getBulkCatalogForAgent(string $agentId): array
    {
        if (!$this->validateRoleId($agentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$agentId}");
        }

        $rows = DB::table('v_pembelian_paket_agent_travel')
            ->where('agent_id', $agentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapBulkCatalogRow((array) $row, 'agent'), $rows);
    }

    /**
     * Get bulk catalog untuk ADMIN
     * 
     * @param string $adminAgentId Format: ADMxxx (admin_id atau agent_id dengan prefix ADM)
     * @return array Array of packages dengan bulk pricing (tanpa profit)
     */
    public function getBulkCatalogForAdmin(string $adminAgentId): array
    {
        if (!$this->validateRoleId($adminAgentId, 'admin')) {
            throw new \InvalidArgumentException("Invalid admin ID format: {$adminAgentId}");
        }

        $rows = DB::table('v_pembelian_paket_kuotaumroh')
            ->where('agent_id', $adminAgentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapBulkCatalogRow((array) $row, 'admin'), $rows);
    }

    /**
     * Get store (public/individu) catalog untuk AGENT
     * Digunakan untuk toko publik agent (link share / public store)
     * 
     * @param string $agentId Format: AGTxxx
     * @return array Array of packages dengan store pricing (toko_harga_*)
     */
    public function getStoreCatalogForAgent(string $agentId): array
    {
        if (!$this->validateRoleId($agentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$agentId}");
        }

        $rows = DB::table('v_pembelian_paket_agent_travel')
            ->where('agent_id', $agentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapStoreCatalogRow((array) $row), $rows);
    }

    /**
     * Get harga untuk multiple items (untuk bulk payment)
     * Lookup price dari VIEW per item berdasarkan role
     * 
     * @param string $role 'affiliate' | 'agent' | 'admin'
     * @param string $userId affiliate_id, agent_id, atau admin_id
     * @param array $packageIds Array of package_id yang dicari harganya
     * @return array Assoc array [package_id => ['bulk_harga_beli' => ..., 'bulk_harga_rekomendasi' => ..., ...]]
     */
    public function getBulkPricesForItems(string $role, string $userId, array $packageIds): array
    {
        if (!in_array($role, ['affiliate', 'agent', 'admin'])) {
            throw new \InvalidArgumentException("Invalid role: {$role}");
        }

        $viewTable = $this->getViewTableName($role);
        $idColumn = $this->getIdColumnName($role);

        // Base columns yang ada di semua VIEW
        $selectColumns = [
            'produk_id',
            'bulk_harga_beli',
            'bulk_harga_rekomendasi',
        ];
        
        // Affiliate dan agent punya profit field
        if ($role !== 'admin') {
            $selectColumns[] = 'bulk_potensi_profit';
        }
        
        // Hanya AGENT yang punya bulk_final_fee_affiliate (untuk affiliate dari agent)
        // Affiliate tidak punya kolom ini di VIEW-nya
        if ($role === 'agent') {
            $selectColumns[] = 'bulk_final_fee_affiliate';
        }

        $rows = DB::table($viewTable)
            ->where($idColumn, $userId)
            ->whereIn('produk_id', $packageIds)
            ->select($selectColumns)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[$row->produk_id] = [
                'package_id' => $row->produk_id,
                'bulk_harga_beli' => (int) $row->bulk_harga_beli,
                'bulk_harga_rekomendasi' => (int) $row->bulk_harga_rekomendasi,
                'bulk_potensi_profit' => (int) ($row->bulk_potensi_profit ?? 0),
                'bulk_final_fee_affiliate' => (int) ($row->bulk_final_fee_affiliate ?? 0), // 0 jika tidak ada (affiliate/admin)
            ];
        }

        return $result;
    }

    /**
     * Get harga untuk single item di store (untuk individu payment)
     * 
     * @param string $agentId Format: AGTxxx
     * @param string $packageId Package ID yang dicari
     * @return array|null Array dengan toko_harga_*, atau null jika tidak ada
     */
    public function getStorePriceForItem(string $agentId, string $packageId): ?array
    {
        if (!$this->validateRoleId($agentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$agentId}");
        }

        $row = DB::table('v_pembelian_paket_agent_travel')
            ->where('agent_id', $agentId)
            ->where('produk_id', $packageId)
            ->select([
                'produk_id',
                'toko_harga_coret',
                'toko_harga_jual',
                'toko_hemat',
                'mandiri_final_fee_travel',
                'mandiri_final_fee_affiliate',
            ])
            ->first();

        if (!$row) {
            return null;
        }

        return [
            'package_id' => $row->produk_id,
            'toko_harga_coret' => (int) $row->toko_harga_coret,
            'toko_harga_jual' => (int) $row->toko_harga_jual,
            'toko_hemat' => (int) $row->toko_hemat,
            'mandiri_final_fee_travel' => (int) $row->mandiri_final_fee_travel,
            'mandiri_final_fee_affiliate' => (int) $row->mandiri_final_fee_affiliate,
        ];
    }
}

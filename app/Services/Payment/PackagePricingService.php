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
     * Special agent ID yang menggunakan v_pembelian_paket_kuotaumroh
     * Tanpa affiliate, tanpa profit/fee calculation
     */
    const SPECIAL_AGENT_ID = 'AGT00001';

    /**
     * Check if agent is the special AGT00001
     * 
     * @param string $agentId
     * @return bool
     */
    public function isSpecialAgent(string $agentId): bool
    {
        return $agentId === self::SPECIAL_AGENT_ID;
    }

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
     * Check if user exists in VIEW database
     * 
     * Untuk user baru yang belum di-setup di VIEW, akan return false
     * sehingga bisa fallback ke produk_default
     * 
     * @param string $role 'affiliate' | 'agent' | 'admin'
     * @param string $userId ID user (AFTxxx, AGTxxx, ADMxxx)
     * @return bool true jika user ada di VIEW, false jika tidak
     */
    public function checkUserExistsInView(string $role, string $userId): bool
    {
        try {
            $viewTable = $this->getViewTableName($role);
            $idColumn = $this->getIdColumnName($role);
            
            $exists = DB::table($viewTable)
                ->where($idColumn, $userId)
                ->exists();
            
            return $exists;
        } catch (\Exception $e) {
            // Jika VIEW tidak ada atau error, return false
            return false;
        }
    }

    /**
     * Get catalog dari produk_default (fallback untuk user baru)
     * 
     * Join dengan table produk untuk dapat info lengkap produk
     * Mapping fee/profit dari produk_default ke format VIEW
     * 
     * @param string $context 'bulk' | 'store'
     * @return array Array of packages dengan pricing dari produk_default
     */
    public function getCatalogFromDefault(string $context = 'bulk'): array
    {
        $rows = DB::table('produk_default as pd')
            ->join('produk as p', 'pd.produk_id', '=', 'p.id')
            ->select([
                'pd.produk_id',
                'pd.agent_id',
                'pd.affiliate_id',
                // Bulk pricing fields
                'pd.bulk_fee_travel',
                'pd.bulk_persentase_fee_travel',
                'pd.bulk_final_fee_travel',
                'pd.bulk_fee_affiliate',
                'pd.bulk_persentase_fee_affiliate',
                'pd.bulk_final_fee_affiliate',
                // Store/toko pricing fields
                'pd.diskon_toko',
                'pd.persentase_diskon_toko',
                'pd.final_diskon',
                // Mandiri (individual) pricing fields
                'pd.mandiri_fee_travel',
                'pd.mandiri_persentase_fee_travel',
                'pd.mandiri_final_fee_travel',
                'pd.mandiri_fee_affiliate',
                'pd.mandiri_persentase_fee_affiliate',
                'pd.mandiri_final_fee_affiliate',
                // Product info from produk table
                'p.provider',
                'p.nama_paket',
                'p.tipe_paket',
                'p.masa_aktif',
                'p.total_kuota',
                'p.kuota_utama',
                'p.kuota_bonus',
                'p.telp',
                'p.sms',
                'p.promo',
                'p.price_bulk',
                'p.price_customer',
                'p.harga_komersial',
            ])
            ->orderByRaw("CASE WHEN p.promo IS NOT NULL AND p.promo != '' THEN 0 ELSE 1 END")
            ->orderBy('p.nama_paket')
            ->get()
            ->toArray();

        if ($context === 'store') {
            return array_map(fn($row) => $this->mapDefaultToStoreCatalog((array) $row), $rows);
        }
        
        return array_map(fn($row) => $this->mapDefaultToBulkCatalog((array) $row), $rows);
    }

    /**
     * Map produk_default row ke format BULK catalog (sama seperti VIEW)
     * 
     * @param array $row Data dari produk_default JOIN produk
     * @return array Mapped data format bulk catalog
     */
    protected function mapDefaultToBulkCatalog(array $row): array
    {
        $generatedName = $this->generatePackageName($row);
        
        // Calculate bulk pricing
        // bulk_harga_beli = price_bulk (dari tabel produk)
        // bulk_harga_rekomendasi = price_customer (dari tabel produk)
        // bulk_potensi_profit = bulk_final_fee_travel (dari produk_default)
        $bulkHargaBeli = (int) ($row['price_bulk'] ?? 0);
        $bulkHargaRekomendasi = (int) ($row['price_customer'] ?? 0);
        $bulkPotensiProfit = (int) ($row['bulk_final_fee_travel'] ?? 0);
        
        return [
            'id' => $row['produk_id'] ?? null,
            'package_id' => $row['produk_id'] ?? null,
            'name' => $row['nama_paket'] ?? $generatedName,
            'packageName' => $row['nama_paket'] ?? $generatedName,
            'type' => $row['provider'] ?? null,
            'provider' => $row['provider'] ?? null,
            'sub_type' => $row['tipe_paket'] ?? null,
            'tipe_paket' => $row['tipe_paket'] ?? null,
            'days' => $row['masa_aktif'] ?? null,
            'masa_aktif' => $row['masa_aktif'] ?? null,
            'quota' => $row['kuota_utama'] ?? null,
            'kuota_utama' => $row['kuota_utama'] ?? null,
            'total_kuota' => $row['total_kuota'] ?? null,
            'telp' => $row['telp'] ?? null,
            'sms' => $row['sms'] ?? null,
            'bonus' => $row['kuota_bonus'] ?? null,
            'kuota_bonus' => $row['kuota_bonus'] ?? null,
            'is_active' => '1',
            'promo' => $row['promo'] ?? null,
            
            // Source flag untuk debugging
            '_source' => 'produk_default',
            
            // ===== BULK PRICING FIELDS =====
            'price_app' => $bulkHargaRekomendasi,
            'bulk_harga_rekomendasi' => $bulkHargaRekomendasi,
            'price' => $bulkHargaBeli,
            'bulk_harga_beli' => $bulkHargaBeli,
            'bulk_potensi_profit' => $bulkPotensiProfit,
            'profit' => $bulkPotensiProfit,
            'bulk_final_fee_affiliate' => (int) ($row['bulk_final_fee_affiliate'] ?? 0),
            
            // Legacy field mapping
            'price_bulk' => $bulkHargaBeli,
            'price_customer' => $bulkHargaRekomendasi,
            'harga' => $bulkHargaBeli,
        ];
    }

    /**
     * Map produk_default row ke format STORE catalog (untuk toko agent)
     * 
     * @param array $row Data dari produk_default JOIN produk
     * @return array Mapped data format store catalog
     */
    protected function mapDefaultToStoreCatalog(array $row): array
    {
        $generatedName = $this->generatePackageName($row);
        
        // Calculate store pricing
        // toko_harga_coret = harga_komersial atau price_customer
        // toko_harga_jual = price_customer - final_diskon
        // toko_hemat = final_diskon
        $tokoHargaCoret = (int) ($row['harga_komersial'] ?? $row['price_customer'] ?? 0);
        $finalDiskon = (int) ($row['final_diskon'] ?? 0);
        $tokoHargaJual = (int) ($row['price_customer'] ?? 0);
        $tokoHemat = $finalDiskon;
        
        return [
            'id' => $row['produk_id'] ?? null,
            'package_id' => $row['produk_id'] ?? null,
            'name' => $row['nama_paket'] ?? $generatedName,
            'packageName' => $row['nama_paket'] ?? $generatedName,
            'type' => $row['provider'] ?? null,
            'provider' => $row['provider'] ?? null,
            'sub_type' => $row['tipe_paket'] ?? null,
            'tipe_paket' => $row['tipe_paket'] ?? null,
            'days' => $row['masa_aktif'] ?? null,
            'masa_aktif' => $row['masa_aktif'] ?? null,
            'quota' => $row['kuota_utama'] ?? null,
            'kuota_utama' => $row['kuota_utama'] ?? null,
            'total_kuota' => $row['total_kuota'] ?? null,
            'telp' => $row['telp'] ?? null,
            'sms' => $row['sms'] ?? null,
            'bonus' => $row['kuota_bonus'] ?? null,
            'kuota_bonus' => $row['kuota_bonus'] ?? null,
            'is_active' => '1',
            'promo' => $row['promo'] ?? null,
            
            // Source flag untuk debugging
            '_source' => 'produk_default',
            
            // ===== STORE PRICING FIELDS =====
            'price_app' => $tokoHargaCoret,
            'toko_harga_coret' => $tokoHargaCoret,
            'price' => $tokoHargaJual,
            'toko_harga_jual' => $tokoHargaJual,
            'hemat' => $tokoHemat,
            'toko_hemat' => $tokoHemat,
            
            // Profit untuk individual purchase
            'profit_agent' => (int) ($row['mandiri_final_fee_travel'] ?? 0),
            'profit_affiliate' => (int) ($row['mandiri_final_fee_affiliate'] ?? 0),
            'mandiri_final_fee_travel' => (int) ($row['mandiri_final_fee_travel'] ?? 0),
            'mandiri_final_fee_affiliate' => (int) ($row['mandiri_final_fee_affiliate'] ?? 0),
        ];
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
     * Jika affiliate belum ada di VIEW, fallback ke produk_default
     * 
     * @param string $affiliateId Format: AFTxxx
     * @return array Array of packages dengan bulk pricing
     */
    public function getBulkCatalogForAffiliate(string $affiliateId): array
    {
        if (!$this->validateRoleId($affiliateId, 'affiliate')) {
            throw new \InvalidArgumentException("Invalid affiliate ID format: {$affiliateId}");
        }

        // Check if affiliate exists in VIEW, if not use produk_default
        if (!$this->checkUserExistsInView('affiliate', $affiliateId)) {
            \Log::info("📦 Affiliate {$affiliateId} not found in VIEW, using produk_default");
            return $this->getCatalogFromDefault('bulk');
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
     * Jika agent belum ada di VIEW, fallback ke produk_default
     * 
     * @param string $agentId Format: AGTxxx
     * @return array Array of packages dengan bulk pricing
     */
    public function getBulkCatalogForAgent(string $agentId): array
    {
        if (!$this->validateRoleId($agentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$agentId}");
        }

        // Special case: AGT00001 uses v_pembelian_paket_kuotaumroh
        if ($this->isSpecialAgent($agentId)) {
            return $this->getBulkCatalogForSpecialAgent($agentId);
        }

        // Check if agent exists in VIEW, if not use produk_default
        if (!$this->checkUserExistsInView('agent', $agentId)) {
            \Log::info("📦 Agent {$agentId} not found in VIEW, using produk_default");
            return $this->getCatalogFromDefault('bulk');
        }

        $rows = DB::table('v_pembelian_paket_agent_travel')
            ->where('agent_id', $agentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapBulkCatalogRow((array) $row, 'agent'), $rows);
    }

    /**
     * Get bulk catalog untuk SPECIAL AGENT (AGT00001)
     * Uses v_pembelian_paket_kuotaumroh, no profit/fee
     * 
     * Price mapping:
     * - harga_coret = bulk_harga_rekomendasi
     * - harga_beli = bulk_harga_beli
     * 
     * @param string $agentId Must be AGT00001
     * @return array Array of packages
     */
    protected function getBulkCatalogForSpecialAgent(string $agentId): array
    {
        $rows = DB::table('v_pembelian_paket_kuotaumroh')
            ->where('agent_id', $agentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapBulkCatalogRowForSpecialAgent((array) $row), $rows);
    }

    /**
     * Map row for special agent bulk catalog (no profit/fee)
     * 
     * @param array $row Data from v_pembelian_paket_kuotaumroh
     * @return array Mapped data
     */
    protected function mapBulkCatalogRowForSpecialAgent(array $row): array
    {
        $generatedName = $this->generatePackageName($row);
        
        return [
            'id' => $row['produk_id'] ?? null,
            'package_id' => $row['produk_id'] ?? null,
            'name' => $generatedName,
            'packageName' => $generatedName,
            'type' => $row['provider'] ?? null,
            'provider' => $row['provider'] ?? null,
            'sub_type' => $row['tipe_paket'] ?? null,
            'tipe_paket' => $row['tipe_paket'] ?? null,
            'days' => $row['masa_aktif'] ?? null,
            'masa_aktif' => $row['masa_aktif'] ?? null,
            'quota' => $row['kuota_utama'] ?? null,
            'kuota_utama' => $row['kuota_utama'] ?? null,
            'total_kuota' => $row['total_kuota'] ?? null,
            'telp' => $row['telp'] ?? null,
            'sms' => $row['sms'] ?? null,
            'bonus' => $row['kuota_bonus'] ?? null,
            'kuota_bonus' => $row['kuota_bonus'] ?? null,
            'is_active' => '1',
            
            // BULK PRICING - harga_coret = bulk_harga_rekomendasi, harga_beli = bulk_harga_beli
            'price_app' => (int) ($row['bulk_harga_rekomendasi'] ?? 0),
            'bulk_harga_rekomendasi' => (int) ($row['bulk_harga_rekomendasi'] ?? 0),
            'price' => (int) ($row['bulk_harga_beli'] ?? 0),
            'bulk_harga_beli' => (int) ($row['bulk_harga_beli'] ?? 0),
            
            // NO PROFIT/FEE for special agent
            'bulk_potensi_profit' => 0,
            'profit' => 0,
            'bulk_final_fee_affiliate' => 0,
            
            // Legacy fields
            'price_bulk' => (int) ($row['bulk_harga_beli'] ?? 0),
            'price_customer' => (int) ($row['bulk_harga_rekomendasi'] ?? 0),
            'harga' => (int) ($row['bulk_harga_beli'] ?? 0),
            
            'promo' => $row['promo'] ?? null,
        ];
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
     * Jika agent belum ada di VIEW, fallback ke produk_default
     * 
     * @param string $agentId Format: AGTxxx
     * @return array Array of packages dengan store pricing (toko_harga_*)
     */
    public function getStoreCatalogForAgent(string $agentId): array
    {
        if (!$this->validateRoleId($agentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$agentId}");
        }

        // Special case: AGT00001 uses v_pembelian_paket_kuotaumroh
        if ($this->isSpecialAgent($agentId)) {
            return $this->getStoreCatalogForSpecialAgent($agentId);
        }

        // Check if agent exists in VIEW, if not use produk_default
        if (!$this->checkUserExistsInView('agent', $agentId)) {
            \Log::info("🏪 Agent {$agentId} not found in VIEW for store, using produk_default");
            return $this->getCatalogFromDefault('store');
        }

        $rows = DB::table('v_pembelian_paket_agent_travel')
            ->where('agent_id', $agentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapStoreCatalogRow((array) $row), $rows);
    }

    /**
     * Get store catalog untuk SPECIAL AGENT (AGT00001)
     * Uses v_pembelian_paket_kuotaumroh, no profit/fee
     * 
     * Price mapping:
     * - harga_coret = toko_harga_coret
     * - harga_beli = toko_harga_jual
     * 
     * @param string $agentId Must be AGT00001
     * @return array Array of packages
     */
    protected function getStoreCatalogForSpecialAgent(string $agentId): array
    {
        $rows = DB::table('v_pembelian_paket_kuotaumroh')
            ->where('agent_id', $agentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        return array_map(fn($row) => $this->mapStoreCatalogRowForSpecialAgent((array) $row), $rows);
    }

    /**
     * Map row for special agent store catalog (no profit/fee)
     * 
     * @param array $row Data from v_pembelian_paket_kuotaumroh
     * @return array Mapped data
     */
    protected function mapStoreCatalogRowForSpecialAgent(array $row): array
    {
        $generatedName = $this->generatePackageName($row);
        
        return [
            'id' => $row['produk_id'] ?? null,
            'package_id' => $row['produk_id'] ?? null,
            'name' => $generatedName,
            'packageName' => $generatedName,
            'type' => $row['provider'] ?? null,
            'provider' => $row['provider'] ?? null,
            'sub_type' => $row['tipe_paket'] ?? null,
            'tipe_paket' => $row['tipe_paket'] ?? null,
            'days' => $row['masa_aktif'] ?? null,
            'masa_aktif' => $row['masa_aktif'] ?? null,
            'quota' => $row['kuota_utama'] ?? null,
            'kuota_utama' => $row['kuota_utama'] ?? null,
            'total_kuota' => $row['total_kuota'] ?? null,
            'telp' => $row['telp'] ?? null,
            'sms' => $row['sms'] ?? null,
            'bonus' => $row['kuota_bonus'] ?? null,
            'kuota_bonus' => $row['kuota_bonus'] ?? null,
            'is_active' => '1',
            
            // STORE PRICING - harga_coret = toko_harga_coret, harga_beli = toko_harga_jual
            'price_app' => (int) ($row['toko_harga_coret'] ?? 0),
            'toko_harga_coret' => (int) ($row['toko_harga_coret'] ?? 0),
            'price' => (int) ($row['toko_harga_jual'] ?? 0),
            'toko_harga_jual' => (int) ($row['toko_harga_jual'] ?? 0),
            'hemat' => (int) ($row['toko_hemat'] ?? 0),
            'toko_hemat' => (int) ($row['toko_hemat'] ?? 0),
            
            // NO PROFIT/FEE for special agent
            'profit_agent' => 0,
            'profit_affiliate' => 0,
            'mandiri_final_fee_travel' => 0,
            'mandiri_final_fee_affiliate' => 0,
            
            'promo' => $row['promo'] ?? null,
        ];
    }

    /**
     * Get harga untuk multiple items (untuk bulk payment)
     * Lookup price dari VIEW per item berdasarkan role
     * 
     * Jika user tidak ada di VIEW, fallback ke produk_default
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

        // Special case: AGT00001 uses v_pembelian_paket_kuotaumroh, no profit/fee
        if ($role === 'agent' && $this->isSpecialAgent($userId)) {
            return $this->getBulkPricesForSpecialAgent($userId, $packageIds);
        }

        // Check if user exists in VIEW, if not use produk_default
        if (!$this->checkUserExistsInView($role, $userId)) {
            \Log::info("💰 User {$userId} ({$role}) not found in VIEW for pricing, using produk_default");
            return $this->getBulkPricesFromDefault($packageIds);
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
     * Get bulk prices dari produk_default (fallback)
     * 
     * @param array $packageIds Package IDs to lookup
     * @return array Assoc array [package_id => pricing data]
     */
    protected function getBulkPricesFromDefault(array $packageIds): array
    {
        $rows = DB::table('produk_default as pd')
            ->join('produk as p', 'pd.produk_id', '=', 'p.id')
            ->whereIn('pd.produk_id', $packageIds)
            ->select([
                'pd.produk_id',
                'p.price_bulk',
                'p.price_customer',
                'pd.bulk_final_fee_travel',
                'pd.bulk_final_fee_affiliate',
            ])
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[$row->produk_id] = [
                'package_id' => $row->produk_id,
                'bulk_harga_beli' => (int) $row->price_bulk,
                'bulk_harga_rekomendasi' => (int) $row->price_customer,
                'bulk_potensi_profit' => (int) ($row->bulk_final_fee_travel ?? 0),
                'bulk_final_fee_affiliate' => (int) ($row->bulk_final_fee_affiliate ?? 0),
                '_source' => 'produk_default',
            ];
        }

        return $result;
    }

    /**
     * Get bulk prices for SPECIAL AGENT (AGT00001)
     * Uses v_pembelian_paket_kuotaumroh, no profit/fee
     * 
     * @param string $agentId Must be AGT00001
     * @param array $packageIds Package IDs to lookup
     * @return array Assoc array [package_id => pricing data]
     */
    protected function getBulkPricesForSpecialAgent(string $agentId, array $packageIds): array
    {
        $rows = DB::table('v_pembelian_paket_kuotaumroh')
            ->where('agent_id', $agentId)
            ->whereIn('produk_id', $packageIds)
            ->select(['produk_id', 'bulk_harga_beli', 'bulk_harga_rekomendasi'])
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[$row->produk_id] = [
                'package_id' => $row->produk_id,
                'bulk_harga_beli' => (int) $row->bulk_harga_beli,
                'bulk_harga_rekomendasi' => (int) $row->bulk_harga_rekomendasi,
                'bulk_potensi_profit' => 0, // No profit for special agent
                'bulk_final_fee_affiliate' => 0, // No affiliate fee for special agent
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

        // Special case: AGT00001 uses v_pembelian_paket_kuotaumroh
        if ($this->isSpecialAgent($agentId)) {
            return $this->getStorePriceForSpecialAgent($agentId, $packageId);
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

    /**
     * Get store price for SPECIAL AGENT (AGT00001)
     * Uses v_pembelian_paket_kuotaumroh, no profit/fee
     * 
     * Price mapping:
     * - harga_coret = toko_harga_coret
     * - harga_beli = toko_harga_jual
     * 
     * @param string $agentId Must be AGT00001
     * @param string $packageId Package ID to lookup
     * @return array|null Pricing data or null if not found
     */
    protected function getStorePriceForSpecialAgent(string $agentId, string $packageId): ?array
    {
        $row = DB::table('v_pembelian_paket_kuotaumroh')
            ->where('agent_id', $agentId)
            ->where('produk_id', $packageId)
            ->select(['produk_id', 'toko_harga_coret', 'toko_harga_jual', 'toko_hemat'])
            ->first();

        if (!$row) {
            return null;
        }

        return [
            'package_id' => $row->produk_id,
            'toko_harga_coret' => (int) $row->toko_harga_coret,
            'toko_harga_jual' => (int) $row->toko_harga_jual,
            'toko_hemat' => (int) $row->toko_hemat,
            'mandiri_final_fee_travel' => 0, // No profit for special agent
            'mandiri_final_fee_affiliate' => 0, // No affiliate fee for special agent
        ];
    }
}

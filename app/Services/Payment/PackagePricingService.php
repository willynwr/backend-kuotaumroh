<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\DB;

/**
 * Package Pricing Service
 * 
 * Query paket harga dari VIEW DB kuotaumroh.
 * Mendukung pricing berbeda per user (affiliate_id, agent_id) dan context (bulk vs store).
 * 
 * Agent Category Rules:
 * - Super Host (AGT00001) → v_pembelian_paket_kuotaumroh (no affiliate fee)
 * - Referral → v_pembelian_paket_agent_travel (agent fee + affiliate fee)
 * - Non Referral → v_pembelian_paket_agent_travel (agent fee + NO affiliate fee)
 * - Host → v_pembelian_paket_agent_travel_host (no store, bulk only)
 * 
 * Affiliate Order:
 * - v_pembelian_paket_affiliate sudah tidak ada
 * - Affiliate order via v_pembelian_paket_agent_travel dengan filter affiliate_id
 * 
 * Admin:
 * - Admin (ADMxxx) → v_pembelian_paket_kuotaumroh
 */
class PackagePricingService
{
    /**
     * Special agent ID yang menggunakan v_pembelian_paket_kuotaumroh
     * Tanpa affiliate, tanpa profit/fee calculation
     */
    const SPECIAL_AGENT_ID = 'AGT00001';

    /**
     * Default affiliate ID untuk Non-Referral agents (tidak dapat fee)
     */
    const DEFAULT_AFFILIATE_ID = 'AFT00001';

    /**
     * Agent kategori constants
     */
    const KATEGORI_SUPER_HOST = 'Super Host';
    const KATEGORI_REFERRAL = 'Referral';
    const KATEGORI_NON_REFERRAL = 'Non Referral';
    const KATEGORI_HOST = 'Host';

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
     * Get agent kategori from database
     * 
     * @param string $agentId Format: AGTxxx
     * @return string|null 'Super Host' | 'Referral' | 'Non Referral' | 'Host' | null
     */
    public function getAgentKategori(string $agentId): ?string
    {
        $agent = DB::table('agent')
            ->where('id', $agentId)
            ->select('kategori_agent')
            ->first();
        
        return $agent?->kategori_agent;
    }

    /**
     * Get agent info (kategori + affiliate_id)
     * 
     * @param string $agentId Format: AGTxxx
     * @return array|null ['kategori_agent' => ..., 'affiliate_id' => ...]
     */
    public function getAgentInfo(string $agentId): ?array
    {
        $agent = DB::table('agent')
            ->where('id', $agentId)
            ->select('id', 'kategori_agent', 'affiliate_id')
            ->first();
        
        if (!$agent) {
            return null;
        }
        
        return [
            'id' => $agent->id,
            'kategori_agent' => $agent->kategori_agent,
            'affiliate_id' => $agent->affiliate_id,
        ];
    }

    /**
     * Get VIEW table name berdasarkan agent kategori
     * 
     * - Super Host → v_pembelian_paket_kuotaumroh (STORE ONLY, no bulk)
     * - Referral → v_pembelian_paket_agent_travel
     * - Non Referral → v_pembelian_paket_agent_travel
     * - Host → v_pembelian_paket_agent_travel_host (BULK ONLY, no store)
     * 
     * @param string $agentId Format: AGTxxx
     * @param string $context 'bulk' | 'store'
     * @return string VIEW table name
     * @throws \InvalidArgumentException if invalid context for kategori
     */
    public function getAgentViewTable(string $agentId, string $context = 'bulk'): string
    {
        $kategori = $this->getAgentKategori($agentId);
        
        if (!$kategori) {
            // Fallback: jika agent tidak ditemukan, gunakan default view
            \Log::warning("Agent {$agentId} not found in database, using default view");
            return 'v_pembelian_paket_agent_travel';
        }
        
        return match ($kategori) {
            // Super Host: STORE ONLY - tidak bisa bulk
            self::KATEGORI_SUPER_HOST => $context === 'bulk' 
                ? throw new \InvalidArgumentException("Super Host agents can only use store, not bulk orders")
                : 'v_pembelian_paket_kuotaumroh',
            self::KATEGORI_REFERRAL => 'v_pembelian_paket_agent_travel',
            self::KATEGORI_NON_REFERRAL => 'v_pembelian_paket_agent_travel',
            // Host: BULK ONLY - tidak punya store
            self::KATEGORI_HOST => $context === 'store' 
                ? throw new \InvalidArgumentException("Host agents do not have store access")
                : 'v_pembelian_paket_agent_travel_host',
            default => 'v_pembelian_paket_agent_travel',
        };
    }

    /**
     * Check if agent has store (toko) access
     * 
     * Only Super Host, Referral, and Non Referral have store
     * Host agents do NOT have store
     * 
     * @param string $agentId Format: AGTxxx
     * @return bool
     */
    public function agentHasStore(string $agentId): bool
    {
        $kategori = $this->getAgentKategori($agentId);
        
        return in_array($kategori, [
            self::KATEGORI_SUPER_HOST,
            self::KATEGORI_REFERRAL,
            self::KATEGORI_NON_REFERRAL,
        ]);
    }

    /**
     * Check if affiliate should receive fee from agent's transaction
     * 
     * Rules:
     * - Referral agents → Affiliate receives fee
     * - Non Referral agents (affiliate_id = AFT00001) → Affiliate does NOT receive fee
     * - Host agents → Affiliate does NOT receive fee (Host keeps all fee)
     * - Super Host → No affiliate fee
     * 
     * @param string $agentId Format: AGTxxx
     * @return bool true if affiliate should receive fee
     */
    public function shouldAffiliateReceiveFee(string $agentId): bool
    {
        $info = $this->getAgentInfo($agentId);
        
        if (!$info) {
            return false;
        }
        
        // Super Host tidak ada affiliate fee
        if ($info['kategori_agent'] === self::KATEGORI_SUPER_HOST) {
            return false;
        }

        // Non Referral tidak dapat fee
        if ($info['kategori_agent'] === self::KATEGORI_NON_REFERRAL) {
            return false;
        }

        // Host tidak memberikan fee ke affiliate (Host keeps all fee)
        if ($info['kategori_agent'] === self::KATEGORI_HOST) {
            return false;
        }

        // Hanya Referral yang dapat affiliate fee
        return true;
    }

    /**
     * Check if agent is Non Referral (registered via /agent, affiliated to AFT00001)
     * 
     * @param string $agentId Format: AGTxxx
     * @return bool
     */
    public function isNonReferralAgent(string $agentId): bool
    {
        $kategori = $this->getAgentKategori($agentId);
        return $kategori === self::KATEGORI_NON_REFERRAL;
    }

    /**
     * Check if agent is Host (no store, only data storage)
     * 
     * @param string $agentId Format: AGTxxx
     * @return bool
     */
    public function isHostAgent(string $agentId): bool
    {
        $kategori = $this->getAgentKategori($agentId);
        return $kategori === self::KATEGORI_HOST;
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
     * NOTE: v_pembelian_paket_affiliate sudah tidak ada.
     * Untuk affiliate, gunakan v_pembelian_paket_agent_travel dengan filter affiliate_id.
     * 
     * @param string $role
     * @return string VIEW table name
     * @deprecated For agent, use getAgentViewTable() instead
     */
    protected function getViewTableName(string $role): string
    {
        return match ($role) {
            'affiliate' => 'v_pembelian_paket_agent_travel', // Query by affiliate_id
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
     * NOTE: Tabel produk tidak punya price_bulk/price_customer.
     * Harga dihitung dari: bulk_harga_beli = harga_komersial - bulk_final_fee_travel
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
        
        // Calculate bulk pricing (same formula as VIEW)
        // bulk_harga_beli = harga_komersial - bulk_final_fee_travel
        // bulk_harga_rekomendasi = harga_komersial
        // bulk_potensi_profit = bulk_final_fee_travel
        $hargaKomersial = (int) ($row['harga_komersial'] ?? 0);
        $bulkFeeTravel = (int) ($row['bulk_final_fee_travel'] ?? 0);
        
        $bulkHargaBeli = $hargaKomersial - $bulkFeeTravel;
        $bulkHargaRekomendasi = $hargaKomersial;
        $bulkPotensiProfit = $bulkFeeTravel;
        
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
     * NOTE: VIEW v_pembelian_paket_affiliate sudah tidak ada lagi.
     * Affiliate sekarang order via agent's VIEW (v_pembelian_paket_agent_travel).
     * 
     * Flow:
     * 1. Query dari v_pembelian_paket_agent_travel dengan affiliate_id
     * 2. Distinct by produk_id menggunakan subquery untuk avoid GROUP BY issue
     * 3. Fallback ke produk_default jika tidak ada
     * 
     * @param string $affiliateId Format: AFTxxx
     * @return array Array of packages dengan bulk pricing
     */
    public function getBulkCatalogForAffiliate(string $affiliateId): array
    {
        if (!$this->validateRoleId($affiliateId, 'affiliate')) {
            throw new \InvalidArgumentException("Invalid affiliate ID format: {$affiliateId}");
        }

        // Ambil produk_id yang unik untuk affiliate ini
        // Karena bisa ada banyak agent dengan affiliate yang sama, kita ambil data dari agent pertama
        $subQuery = DB::table('v_pembelian_paket_agent_travel')
            ->where('affiliate_id', $affiliateId)
            ->select('produk_id')
            ->distinct();
        
        $produkIds = $subQuery->pluck('produk_id')->toArray();
        
        // Jika tidak ada produk untuk affiliate ini, fallback ke produk_default
        if (empty($produkIds)) {
            \Log::info("📦 Affiliate {$affiliateId} not found in agent VIEW, using produk_default");
            return $this->getCatalogFromDefault('bulk');
        }

        // Query detail produk dari agent pertama yang ter-affiliate
        // Karena pricing sama untuk semua agent dengan affiliate yang sama
        $rows = DB::table('v_pembelian_paket_agent_travel')
            ->where('affiliate_id', $affiliateId)
            ->whereIn('produk_id', $produkIds)
            ->select([
                'produk_id',
                'provider',
                'tipe_paket',
                'promo',
                'masa_aktif',
                'total_kuota',
                'kuota_utama',
                'kuota_bonus',
                'telp',
                'sms',
                'bulk_harga_beli',
                'bulk_harga_rekomendasi',
                'bulk_potensi_profit',
            ])
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();
        
        // Deduplicate by produk_id (keep first occurrence)
        $uniqueRows = [];
        $seenProdukIds = [];
        foreach ($rows as $row) {
            $row = (array) $row;
            if (!in_array($row['produk_id'], $seenProdukIds)) {
                $seenProdukIds[] = $row['produk_id'];
                $uniqueRows[] = $row;
            }
        }

        return array_map(fn($row) => $this->mapBulkCatalogRow($row, 'affiliate'), $uniqueRows);
    }

    /**
     * Get bulk catalog untuk AFFILIATE ordering for specific AGENT (termasuk Host)
     * 
     * Digunakan ketika affiliate ingin order untuk agent tertentu.
     * Khusus untuk Host agent yang tidak punya store, affiliate bisa order bulk untuk mereka.
     * 
     * @param string $affiliateId Format: AFTxxx (affiliate yang melakukan order)
     * @param string $targetAgentId Format: AGTxxx (agent yang akan menerima paket)
     * @return array Array of packages dengan bulk pricing
     * @throws \InvalidArgumentException if invalid IDs or agent not affiliated
     */
    public function getBulkCatalogForAffiliateTargetAgent(string $affiliateId, string $targetAgentId): array
    {
        if (!$this->validateRoleId($affiliateId, 'affiliate')) {
            throw new \InvalidArgumentException("Invalid affiliate ID format: {$affiliateId}");
        }
        if (!$this->validateRoleId($targetAgentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$targetAgentId}");
        }

        // Verify agent belongs to this affiliate
        $agentInfo = $this->getAgentInfo($targetAgentId);
        if (!$agentInfo || $agentInfo['affiliate_id'] !== $affiliateId) {
            throw new \InvalidArgumentException("Agent {$targetAgentId} is not affiliated with {$affiliateId}");
        }

        $kategori = $agentInfo['kategori_agent'];
        
        // Super Host cannot use bulk
        if ($kategori === self::KATEGORI_SUPER_HOST) {
            throw new \InvalidArgumentException("Super Host agents can only use store, not bulk orders");
        }

        // Determine VIEW based on agent kategori
        $viewTable = match ($kategori) {
            self::KATEGORI_HOST => 'v_pembelian_paket_agent_travel_host',
            default => 'v_pembelian_paket_agent_travel',
        };

        \Log::info("📦 Affiliate {$affiliateId} ordering for Agent {$targetAgentId} (kategori: {$kategori}) → VIEW: {$viewTable}");

        $rows = DB::table($viewTable)
            ->where('agent_id', $targetAgentId)
            ->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
            ->get()
            ->toArray();

        if (empty($rows)) {
            \Log::info("📦 Agent {$targetAgentId} not found in VIEW, using produk_default");
            return $this->getCatalogFromDefault('bulk');
        }

        return array_map(fn($row) => $this->mapBulkCatalogRow((array) $row, 'affiliate'), $rows);
    }

    /**
     * Get bulk prices for AFFILIATE ordering for specific AGENT (termasuk Host)
     * 
     * @param string $affiliateId Format: AFTxxx
     * @param string $targetAgentId Format: AGTxxx
     * @param array $packageIds Array of package_id
     * @return array Assoc array [package_id => pricing data]
     * @throws \InvalidArgumentException if invalid IDs or agent not affiliated
     */
    public function getBulkPricesForAffiliateTargetAgent(string $affiliateId, string $targetAgentId, array $packageIds): array
    {
        if (!$this->validateRoleId($affiliateId, 'affiliate')) {
            throw new \InvalidArgumentException("Invalid affiliate ID format: {$affiliateId}");
        }
        if (!$this->validateRoleId($targetAgentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$targetAgentId}");
        }

        // Verify agent belongs to this affiliate
        $agentInfo = $this->getAgentInfo($targetAgentId);
        if (!$agentInfo || $agentInfo['affiliate_id'] !== $affiliateId) {
            throw new \InvalidArgumentException("Agent {$targetAgentId} is not affiliated with {$affiliateId}");
        }

        $kategori = $agentInfo['kategori_agent'];
        
        // Super Host cannot use bulk
        if ($kategori === self::KATEGORI_SUPER_HOST) {
            throw new \InvalidArgumentException("Super Host agents can only use store, not bulk orders");
        }

        // Determine VIEW and fee column based on agent kategori
        $viewTable = match ($kategori) {
            self::KATEGORI_HOST => 'v_pembelian_paket_agent_travel_host',
            default => 'v_pembelian_paket_agent_travel',
        };

        // Base columns
        $selectColumns = [
            'produk_id',
            'bulk_harga_beli',
            'bulk_harga_rekomendasi',
            'bulk_potensi_profit',
        ];
        
        // Host uses bulk_final_fee_host, others use bulk_final_fee_affiliate
        if ($kategori === self::KATEGORI_HOST) {
            $selectColumns[] = 'bulk_final_fee_host';
        } else {
            $selectColumns[] = 'bulk_final_fee_affiliate';
        }

        $rows = DB::table($viewTable)
            ->where('agent_id', $targetAgentId)
            ->whereIn('produk_id', $packageIds)
            ->select($selectColumns)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            // Affiliate fee is always 0 for Host (Host keeps all fee)
            // For Referral, use bulk_final_fee_affiliate
            // For Non Referral, affiliate fee = 0
            $affiliateFee = 0;
            if ($kategori === self::KATEGORI_REFERRAL && isset($row->bulk_final_fee_affiliate)) {
                $affiliateFee = (int) $row->bulk_final_fee_affiliate;
            }
            
            $result[$row->produk_id] = [
                'package_id' => $row->produk_id,
                'bulk_harga_beli' => (int) $row->bulk_harga_beli,
                'bulk_harga_rekomendasi' => (int) $row->bulk_harga_rekomendasi,
                'bulk_potensi_profit' => (int) ($row->bulk_potensi_profit ?? 0),
                'bulk_final_fee_affiliate' => $affiliateFee,
            ];
        }

        return $result;
    }

    /**
     * Get bulk catalog untuk AGENT
     * 
     * Jika agent belum ada di VIEW, fallback ke produk_default
     * 
     * VIEW mapping berdasarkan kategori_agent:
     * - Super Host → NOT ALLOWED (store only)
     * - Referral, Non Referral → v_pembelian_paket_agent_travel
     * - Host → v_pembelian_paket_agent_travel_host
     * 
     * @param string $agentId Format: AGTxxx
     * @return array Array of packages dengan bulk pricing
     * @throws \InvalidArgumentException if Super Host tries bulk
     */
    public function getBulkCatalogForAgent(string $agentId): array
    {
        if (!$this->validateRoleId($agentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$agentId}");
        }

        // Super Host (AGT00001) cannot use bulk - store only
        $kategori = $this->getAgentKategori($agentId);
        if ($kategori === self::KATEGORI_SUPER_HOST) {
            throw new \InvalidArgumentException("Super Host agents can only use store, not bulk orders. Use getStoreCatalogForAgent() instead.");
        }

        // Get the correct VIEW table based on agent kategori
        $viewTable = $this->getAgentViewTable($agentId, 'bulk');
        
        \Log::info("📦 Agent {$agentId} (kategori: {$kategori}) → VIEW: {$viewTable}");

        // Check if agent exists in VIEW, if not use produk_default
        if (!$this->checkUserExistsInView('agent', $agentId)) {
            \Log::info("📦 Agent {$agentId} not found in VIEW, using produk_default");
            return $this->getCatalogFromDefault('bulk');
        }

        $rows = DB::table($viewTable)
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
     * Jika agent belum ada di VIEW, fallback ke produk_default
     * 
     * VIEW mapping berdasarkan kategori_agent:
     * - Super Host (AGT00001) → v_pembelian_paket_kuotaumroh
     * - Referral, Non Referral → v_pembelian_paket_agent_travel
     * - Host → NOT ALLOWED (Host tidak punya toko, hanya bisa bulk)
     * 
     * @param string $agentId Format: AGTxxx
     * @return array Array of packages dengan store pricing (toko_harga_*)
     * @throws \InvalidArgumentException If agent is Host (no store access)
     */
    public function getStoreCatalogForAgent(string $agentId): array
    {
        if (!$this->validateRoleId($agentId, 'agent')) {
            throw new \InvalidArgumentException("Invalid agent ID format: {$agentId}");
        }

        // Check if this agent has store access (Host agents don't have store)
        if (!$this->agentHasStore($agentId)) {
            $kategori = $this->getAgentKategori($agentId);
            throw new \InvalidArgumentException("Agent {$agentId} (kategori: {$kategori}) does not have store access");
        }

        // Special case: Super Host (AGT00001) uses v_pembelian_paket_kuotaumroh
        if ($this->isSpecialAgent($agentId)) {
            return $this->getStoreCatalogForSpecialAgent($agentId);
        }

        // Get the correct VIEW table based on agent kategori
        $viewTable = $this->getAgentViewTable($agentId, 'store');
        $kategori = $this->getAgentKategori($agentId);
        
        \Log::info("🏪 Agent {$agentId} (kategori: {$kategori}) → VIEW: {$viewTable}");

        // Check if agent exists in VIEW, if not use produk_default
        if (!$this->checkUserExistsInView('agent', $agentId)) {
            \Log::info("🏪 Agent {$agentId} not found in VIEW for store, using produk_default");
            return $this->getCatalogFromDefault('store');
        }

        $rows = DB::table($viewTable)
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
     * @throws \InvalidArgumentException if Super Host tries bulk
     */
    public function getBulkPricesForItems(string $role, string $userId, array $packageIds): array
    {
        if (!in_array($role, ['affiliate', 'agent', 'admin'])) {
            throw new \InvalidArgumentException("Invalid role: {$role}");
        }

        // Super Host cannot use bulk - store only
        if ($role === 'agent') {
            $kategori = $this->getAgentKategori($userId);
            if ($kategori === self::KATEGORI_SUPER_HOST) {
                throw new \InvalidArgumentException("Super Host agents can only use store, not bulk orders");
            }
        }

        // Determine if this agent should have zero affiliate fee (Non Referral / Host)
        $forceZeroAffiliateFee = false;
        if ($role === 'agent') {
            $forceZeroAffiliateFee = !$this->shouldAffiliateReceiveFee($userId);
            if ($forceZeroAffiliateFee) {
                $kategori = $this->getAgentKategori($userId);
                \Log::info("💰 Agent {$userId} (kategori: {$kategori}) - Forcing affiliate fee to 0");
            }
        }

        // Check if user exists in VIEW, if not use produk_default
        if (!$this->checkUserExistsInView($role, $userId)) {
            \Log::info("💰 User {$userId} ({$role}) not found in VIEW for pricing, using produk_default");
            return $this->getBulkPricesFromDefault($packageIds, $forceZeroAffiliateFee);
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
            // Affiliate fee: 0 if Non Referral or Super Host, else from VIEW
            $affiliateFee = $forceZeroAffiliateFee 
                ? 0 
                : (int) ($row->bulk_final_fee_affiliate ?? 0);
            
            $result[$row->produk_id] = [
                'package_id' => $row->produk_id,
                'bulk_harga_beli' => (int) $row->bulk_harga_beli,
                'bulk_harga_rekomendasi' => (int) $row->bulk_harga_rekomendasi,
                'bulk_potensi_profit' => (int) ($row->bulk_potensi_profit ?? 0),
                'bulk_final_fee_affiliate' => $affiliateFee,
            ];
        }

        return $result;
    }

    /**
     * Get bulk prices dari produk_default (fallback)
     * 
     * NOTE: Tabel produk tidak punya price_bulk/price_customer.
     * Harga dihitung dari: bulk_harga_beli = harga_komersial - bulk_final_fee_travel
     * 
     * @param array $packageIds Package IDs to lookup
     * @param bool $forceZeroAffiliateFee Set to true for Non Referral/Host agents
     * @return array Assoc array [package_id => pricing data]
     */
    protected function getBulkPricesFromDefault(array $packageIds, bool $forceZeroAffiliateFee = false): array
    {
        $rows = DB::table('produk_default as pd')
            ->join('produk as p', 'pd.produk_id', '=', 'p.id')
            ->whereIn('pd.produk_id', $packageIds)
            ->select([
                'pd.produk_id',
                'p.harga_komersial',
                'pd.bulk_final_fee_travel',
                'pd.bulk_final_fee_affiliate',
            ])
            ->get();

        $result = [];
        foreach ($rows as $row) {
            // Affiliate fee: 0 if forced (Non Referral/Host), else from produk_default
            $affiliateFee = $forceZeroAffiliateFee 
                ? 0 
                : (int) ($row->bulk_final_fee_affiliate ?? 0);
            
            // Calculate pricing (same formula as VIEW)
            $hargaKomersial = (int) ($row->harga_komersial ?? 0);
            $bulkFeeTravel = (int) ($row->bulk_final_fee_travel ?? 0);
            
            $result[$row->produk_id] = [
                'package_id' => $row->produk_id,
                'bulk_harga_beli' => $hargaKomersial - $bulkFeeTravel,
                'bulk_harga_rekomendasi' => $hargaKomersial,
                'bulk_potensi_profit' => $bulkFeeTravel,
                'bulk_final_fee_affiliate' => $affiliateFee,
                '_source' => 'produk_default',
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

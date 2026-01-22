<?php

namespace App\Services\Payment;

/**
 * QRIS Dynamic Service
 * 
 * Mengkonversi QRIS statis menjadi QRIS dinamis dengan nominal tertentu.
 * Berdasarkan implementasi dari tokodigi.
 */
class QrisDynamicService
{
    /**
     * QRIS Static string (dari config)
     */
    protected string $qrisStatic;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->qrisStatic = config('payment.qris_static', '');
    }

    /**
     * Generate QRIS Dynamic dengan nominal tertentu
     *
     * @param string $nominal Nominal pembayaran (contoh: "10000")
     * @param bool $withFee Apakah menambahkan convenience fee
     * @param string $feeType 'r' = rupiah(flat), 'p' = percentage
     * @param string $feeValue Nilai fee
     * @return string QRIS string baru dengan CRC
     */
    public function generate(
        string $nominal,
        bool $withFee = false,
        string $feeType = '',
        string $feeValue = ''
    ): string {
        $qris = $this->qrisStatic;

        // Hapus CRC lama (tag 63) jika ada
        if (substr($qris, -8, 4) === '6304') {
            $qris = substr($qris, 0, -8);
        } elseif (substr($qris, -4) === '6304') {
            $qris = substr($qris, 0, -4);
        }

        // Ubah static -> dynamic (01 tag value from 11 to 12)
        $qris = str_replace('010211', '010212', $qris);

        // Parse top-level TLV
        $emv = $this->parseEmv($qris);

        // Set tag 54 (Amount)
        $nominalFloat = (float) str_replace(',', '', $nominal);
        if ($nominalFloat > 0) {
            $emv['54'] = number_format($nominalFloat, 2, '.', '');
        } else {
            unset($emv['54']);
        }

        // Convenience fee (tag 55)
        if ($withFee && $feeType && $feeValue) {
            if ($feeType === 'r') {
                $emv['55'] = ['02' => $feeValue];
            } elseif ($feeType === 'p') {
                $emv['55'] = ['03' => $feeValue];
            }
        }

        // Urutkan tag lexicographically
        ksort($emv);

        // Build kembali EMV
        $qrisNew = $this->buildEmv($emv);

        // Tambah CRC tag 63
        $crc = $this->calculateCRC16($qrisNew . '6304');
        $qrisNew .= '6304' . $crc;

        return $qrisNew;
    }

    /**
     * Set QRIS static string
     */
    public function setQrisStatic(string $qris): self
    {
        $this->qrisStatic = $qris;
        return $this;
    }

    /**
     * Get QRIS static string
     */
    public function getQrisStatic(): string
    {
        return $this->qrisStatic;
    }

    /**
     * Parse EMV TLV string into associative array
     */
    protected function parseEmv(string $data): array
    {
        $i = 0;
        $lenTotal = strlen($data);
        $parsed = [];

        while ($i < $lenTotal) {
            if ($i + 4 > $lenTotal) break;

            $id = substr($data, $i, 2);
            $i += 2;

            $len = intval(substr($data, $i, 2));
            $i += 2;

            if ($i + $len > $lenTotal) break;

            $val = substr($data, $i, $len);
            $i += $len;

            $nested = $this->tryParseNested($val);
            $parsed[$id] = $nested !== null ? $nested : $val;
        }

        return $parsed;
    }

    /**
     * Try parse nested TLV
     */
    protected function tryParseNested(string $val): ?array
    {
        $i = 0;
        $len = strlen($val);
        $out = [];

        while ($i < $len) {
            if ($i + 4 > $len) return null;

            $id = substr($val, $i, 2);
            $i += 2;

            $l = intval(substr($val, $i, 2));
            $i += 2;

            if ($i + $l > $len) return null;

            $v = substr($val, $i, $l);
            $i += $l;

            $deep = $this->tryParseNested($v);
            $out[$id] = $deep !== null ? $deep : $v;
        }

        return $out;
    }

    /**
     * Build EMV TLV string from array
     */
    protected function buildEmv(array $tags): string
    {
        $result = '';

        foreach ($tags as $id => $value) {
            if (is_array($value)) {
                ksort($value);
                $inner = $this->buildEmv($value);
                $len = sprintf('%02d', strlen($inner));
                $result .= $id . $len . $inner;
            } else {
                $valStr = (string) $value;
                $len = sprintf('%02d', strlen($valStr));
                $result .= $id . $len . $valStr;
            }
        }

        return $result;
    }

    /**
     * Calculate CRC-16/CCITT-FALSE
     */
    protected function calculateCRC16(string $str): string
    {
        $crc = 0xFFFF;
        $length = strlen($str);

        for ($i = 0; $i < $length; $i++) {
            $crc ^= ord($str[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                if (($crc & 0x8000) !== 0) {
                    $crc = (($crc << 1) & 0xFFFF) ^ 0x1021;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc & 0xFFFF), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Generate QR Code image URL from QRIS string
     * Menggunakan Google Charts API atau library QR
     */
    public function generateQRCodeUrl(string $qrisString, int $size = 300): string
    {
        // Menggunakan Google Charts API (gratis, simple)
        $encodedQris = urlencode($qrisString);
        return "https://chart.googleapis.com/chart?chs={$size}x{$size}&cht=qr&chl={$encodedQris}&choe=UTF-8";
    }

    /**
     * Validate QRIS format
     */
    public function validateQris(string $qris): bool
    {
        // Basic validation
        if (strlen($qris) < 20) {
            return false;
        }

        // Check if starts with valid EMV header
        if (substr($qris, 0, 4) !== '0002') {
            return false;
        }

        // Check CRC
        if (strlen($qris) >= 8 && substr($qris, -8, 4) === '6304') {
            $dataWithoutCrc = substr($qris, 0, -4);
            $providedCrc = substr($qris, -4);
            $calculatedCrc = $this->calculateCRC16(substr($qris, 0, -4));
            
            return strtoupper($providedCrc) === $calculatedCrc;
        }

        return true; // No CRC to validate
    }
}

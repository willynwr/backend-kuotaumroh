# SALDO RESET AUTOMATION

## Overview
Sistem otomatis untuk reset `saldo_bulan` dan `saldo_tahun` pada tabel `agent`.

## Kolom Saldo Agent

| Kolom | Deskripsi | Reset |
|-------|-----------|-------|
| `saldo` | Saldo permanent, akumulasi profit total | Tidak pernah reset |
| `saldo_bulan` | Profit bulan ini | Reset setiap tanggal 1 jam 00:01 WIB |
| `saldo_tahun` | Profit tahun ini | Reset setiap 1 Januari jam 00:02 WIB |

## Scheduled Tasks

### 1. Reset Saldo Bulanan
**Command:** `php artisan agent:reset-monthly-saldo`
**Jadwal:** Setiap tanggal 1 jam 00:01 WIB
**Fungsi:** Set `saldo_bulan` semua agent menjadi 0

### 2. Reset Saldo Tahunan
**Command:** `php artisan agent:reset-yearly-saldo`
**Jadwal:** Setiap 1 Januari jam 00:02 WIB
**Fungsi:** Set `saldo_tahun` semua agent menjadi 0

## Cara Kerja

1. **Increment Otomatis (PembayaranObserver)**
   - Ketika pembayaran status = SUCCESS
   - `saldo`, `saldo_bulan`, dan `saldo_tahun` otomatis increment sesuai profit

2. **Reset Otomatis (Scheduler)**
   - `saldo_bulan` reset ke 0 setiap awal bulan
   - `saldo_tahun` reset ke 0 setiap awal tahun
   - `saldo` tidak pernah direset (permanent)

## Menjalankan Scheduler

Untuk menjalankan scheduler Laravel, tambahkan cron job:

```bash
* * * * * cd /path/to/backend-kuotaumroh && php artisan schedule:run >> /dev/null 2>&1
```

Atau untuk development, jalankan manual:
```bash
php artisan schedule:work
```

## Manual Testing

```bash
# Reset saldo bulan (testing)
php artisan agent:reset-monthly-saldo

# Reset saldo tahun (testing)
php artisan agent:reset-yearly-saldo

# Lihat jadwal scheduler
php artisan schedule:list
```

## Monitoring

Setiap reset akan dicatat di log:
- File: `storage/logs/laravel.log`
- Event: "Saldo bulan berhasil direset" / "Saldo tahun berhasil direset"
- Data: total agent yang direset, timestamp

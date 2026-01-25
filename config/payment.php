<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk sistem pembayaran Kuotaumroh.id
    |
    */

    /*
    |--------------------------------------------------------------------------
    | External API URL
    |--------------------------------------------------------------------------
    |
    | URL API eksternal kuotaumroh.id untuk proxy requests
    | Digunakan oleh BulkPaymentService untuk forward request
    |
    */
    'external_api_url' => env('EXTERNAL_API_URL', 'https://kuotaumroh.id/api'),

    /*
    |--------------------------------------------------------------------------
    | Tokodigi Payment API URL
    |--------------------------------------------------------------------------
    |
    | URL API tokodigi.id untuk verifikasi pembayaran
    | Endpoint: /umroh/payment?id={payment_id}
    |
    */
    'tokodigi_api_url' => env('TOKODIGI_API_URL', 'https://tokodigi.id'),

    /*
    |--------------------------------------------------------------------------
    | QRIS Static String
    |--------------------------------------------------------------------------
    |
    | QRIS statis yang akan dikonversi ke dinamis dengan nominal tertentu.
    | Ganti dengan QRIS merchant Anda.
    |
    */
    'qris_static' => env('QRIS_STATIC', '00020101021126760024ID.CO.SPEEDCASH.MERCHANT01189360081530001781290215ID10250017812990303UKE51440014ID.CO.QRIS.WWW0215ID10254070371220303UKE5204597853033605802ID5909TOKO DIGI6008SIDOARJO61056125362410509S299296470117202512041259391230703A0163043D6C'),

    /*
    |--------------------------------------------------------------------------
    | Platform Fee
    |--------------------------------------------------------------------------
    |
    | Biaya platform per transaksi (dalam rupiah)
    |
    */
    'platform_fee' => env('PAYMENT_PLATFORM_FEE', 0),

    /*
    |--------------------------------------------------------------------------
    | Payment Expired Minutes
    |--------------------------------------------------------------------------
    |
    | Waktu expired pembayaran dalam menit
    |
    */
    'expired_minutes' => env('PAYMENT_EXPIRED_MINUTES', 15),

    /*
    |--------------------------------------------------------------------------
    | QRIS Merchant Portal
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk mengakses merchant portal QRIS
    | Digunakan untuk verifikasi pembayaran
    |
    */
    'qris_merchant' => [
        'url' => env('QRIS_MERCHANT_URL', 'https://merchant.qris.interactive.co.id'),
        'cookie_file' => env('QRIS_MERCHANT_COOKIE', storage_path('app/qris_cookie.txt')),
        'token_file' => env('QRIS_MERCHANT_TOKEN', storage_path('app/qris_token.txt')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk notifikasi pembayaran
    |
    */
    'notifications' => [
        'telegram' => [
            'enabled' => env('TELEGRAM_NOTIFICATION_ENABLED', false),
            'bot_token' => env('TELEGRAM_BOT_TOKEN'),
            'chat_id' => env('TELEGRAM_CHAT_ID'),
        ],
        'whatsapp' => [
            'enabled' => env('WHATSAPP_NOTIFICATION_ENABLED', false),
            'api_url' => env('WHATSAPP_API_URL'),
            'api_key' => env('WHATSAPP_API_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Digipos Integration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan Digipos (aktivasi paket)
    |
    */
    'digipos' => [
        'host' => env('DIGIPOS_HOST', 'http://localhost'),
        'user' => env('DIGIPOS_USER', ''),
        'payment_method' => env('DIGIPOS_PAYMENT_METHOD', 'LINKAJA'),
        'pin' => env('DIGIPOS_PIN', ''),
    ],
];

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    |
    | Ini adalah Server Key dari akun Midtrans kamu.
    |
    */
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    |
    | Ini adalah Client Key dari akun Midtrans kamu.
    |
    */
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Apakah Menggunakan Production Mode
    |--------------------------------------------------------------------------
    |
    | Jika true, sistem akan menggunakan server production Midtrans.
    | Jika false, sistem menggunakan server sandbox (testing).
    |
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Fitur Sanitasi Data
    |--------------------------------------------------------------------------
    |
    | Jika true, data akan otomatis disanitasi sebelum transaksi.
    |
    */
    'is_sanitized' => true,

    /*
    |--------------------------------------------------------------------------
    | Fitur 3D Secure
    |--------------------------------------------------------------------------
    |
    | 3D Secure untuk kartu kredit. Disarankan true untuk keamanan tambahan.
    |
    */
    'is_3ds' => true,
];

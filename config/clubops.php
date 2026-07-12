<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ClubOps Key Escrow (Password Recovery)
    |--------------------------------------------------------------------------
    |
    | Zero-trust encryption requires the club master key to be escrowed with
    | a server-side key for password recovery. If not set, falls back to
    | APP_KEY for escrow (less secure but functional).
    |
    | Generate a strong random key:
    |   php -r "echo base64_encode(random_bytes(32));"
    |
    */
    'escrow_key' => env('CLUBOPS_ESCROW_KEY'),

];

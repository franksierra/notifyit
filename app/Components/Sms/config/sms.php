<?php

return [

    'driver' => env('SMS_DRIVER', 'null'),

    'eclipsoft' => [
        'endpoint' => env('ECLIPSOFT_URL', ''),
        'service' => env('ECLIPSOFT_SERVICE', ''),
        'emitter' => env('ECLIPSOFT_EMITTER', ''),
        'login' => env('ECLIPSOFT_LOGIN', ''),
        'pwd' => env('ECLIPSOFT_PASSWORD', ''),
        'reference' => env('ECLIPSOFT_REFERENCE', ''),
        'pc_name' => env('ECLIPSOFT_PC_NAME', ''),
    ],
];

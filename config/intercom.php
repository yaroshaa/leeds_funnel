<?php

return [
    'api_uri' => 'https://api.intercom.io',
    'access_token' => env('INTERCOM_ACCESS_TOKEN', ''),
    'app_id' => env('INTERCOM_APP_ID', ''),
    'programme_level' => [
        'Bachelor’s' => 10432,
        'Master’s' => 10433,
        'PhD' => 10434,
    ]
];

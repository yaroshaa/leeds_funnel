<?php

return [
    'api_key' => env('SENDGRID_API_KEY', ''),

    'templates' => [
        'vip' => 'd-3821a7c48f4c4bae99cca83fd2a09bc3',
        'non_vip' => 'd-d6284302a1d04c349ce213bd08cfc1c9',
        'lost_contact' => 'd-9c328488cc884141ba7ecd255ac39969',
    ],

    'categories' => [
        'vip' => 'Ask to fill form - VIP',
        'non_vip' => 'Ask for WhatsApp - Non-VIP',
        'lost_contact' => '[LEAD] Lost contact',
    ],

    'from' => [
        'email' => 'community@eduopinions.com',
        'name' => 'EDUopinions Community'
    ]
];

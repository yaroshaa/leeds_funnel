<?php

return [
    'vpa_tracker' => [
        'id' => env('GOOGLE_VPA_SPREADSHEET_ID', ''),
        'list' => env('GOOGLE_VPA_SPREADSHEET_LIST', ''),
    ],
    'leads_data' => [
        'id' => env('GOOGLE_LEADS_DATA_SPREADSHEET_ID', ''),
        'list' => env('GOOGLE_LEADS_DATA_SPREADSHEET_LIST', ''),
    ],
];

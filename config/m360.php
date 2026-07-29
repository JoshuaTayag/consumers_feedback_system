<?php
// config/m360.php

return [
    'base_url'   => env('M360_BASE_URL', 'https://api.m360.com.ph/v4/sms/send'),
    'app_key'    => env('M360_APP_KEY'),
    'app_secret' => env('M360_APP_SECRET'),
    'sender_id'  => env('M360_SENDER_ID'),
];
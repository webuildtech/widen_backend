<?php

return [
    'default' => env('LITECOM_DEFAULT', 'primary'),

    'connections' => [
        'primary' => [
            'base_url' => env('LITECOM_PRIMARY_BASE_URL'),
            'token'    => env('LITECOM_PRIMARY_TOKEN'),
            'timeout'  => (int) env('LITECOM_PRIMARY_TIMEOUT', 10),
            'retries'  => (int) env('LITECOM_PRIMARY_RETRIES', 2),

            'mqtt_host' => env('LITECOM_PRIMARY_MQTT_HOST'),
            'mqtt_port' => env('LITECOM_PRIMARY_MQTT_PORT'),
            'mqtt_username' => env('LITECOM_PRIMARY_MQTT_USERNAME'),
            'mqtt_password' => env('LITECOM_PRIMARY_MQTT_PASSWORD'),
        ],

        'secondary' => [
            'base_url' => env('LITECOM_SECONDARY_BASE_URL'),
            'token'    => env('LITECOM_SECONDARY_TOKEN'),
            'timeout'  => (int) env('LITECOM_SECONDARY_TIMEOUT', 10),
            'retries'  => (int) env('LITECOM_SECONDARY_RETRIES', 2),

            'mqtt_host' => env('LITECOM_SECONDARY_MQTT_HOST'),
            'mqtt_port' => env('LITECOM_SECONDARY_MQTT_PORT'),
            'mqtt_username' => env('LITECOM_SECONDARY_MQTT_USERNAME'),
            'mqtt_password' => env('LITECOM_SECONDARY_MQTT_PASSWORD'),
        ],
    ],
];

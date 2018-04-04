<?php
return [
    'api_key' => env('BLOCKCHAIN_API_KEY', null),

    'local_service_port' => env('BLOCKCHAIN_LOCAL_SERVICE_PORT', 3000),

    'wallet_table_name' => 'blockchain_wallets',

    'transaction_table_name' => 'blockchain_transactions',

    'wallet_parent_model' => App\User::class,

    'callback_url_base' => env('APP_URL') . '/blockchain',
];
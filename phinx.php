<?php
return [
    'paths' => [
        'migrations' => './src/Database/Migrations',  // Directory for migration files
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog', // Table to track migrations
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'ttms',
            'user' => 'root',
            'pass' => 'root',             
            'port' => 3306,
            'charset' => 'utf8',
        ],
    ],
];

<?php
return [
    'host' => 'localhost',
    'dbname' => 'photosphere',
    'username' => 'root',
    'password' => '',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
];

// define(DB_HOST, 'localhost');
// define(DB_NAME, 'photosphere');
// define(DB_USERNAME, 'root');
// define(DB_PASS, '');

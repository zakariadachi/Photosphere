<?php

final class Database
{
    private static ?PDO $connection = null;
    
    private function __construct() {}
    
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require_once __DIR__ . '/../../config/database.php';
            
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            
            try {
                self::$connection = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$connection;
    }
}

<?php
declare(strict_types=1);

namespace Core\Utils;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/app.php';
            $dbConfig = $config['db'];
            $dsn = "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};";
            try {
                self::$instance = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                error_log($e->getMessage());
                throw new Exception("Erreur de connexion à la base de données.");
            }
        }
        return self::$instance;
    }
}

<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

/**
 * Database
 *
 * Point unique de connexion à PostgreSQL (pattern Singleton).
 * Aucune autre classe du projet ne doit faire "new PDO(...)" directement :
 * tout passe par Database::getConnection().
 */
final class Database
{
    private static ?PDO $connection = null;

    // Adapte ces valeurs à ton environnement local.
    private const HOST = 'localhost';
    private const PORT = '5432';
    private const DBNAME = 'ecommerce_devoir1';
    private const USER = 'postgres';
    private const PASSWORD = 'postgres';

    // Constructeur privé : personne ne peut faire "new Database()".
    private function __construct()
    {
    }

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                self::HOST,
                self::PORT,
                self::DBNAME
            );

            try {
                self::$connection = new PDO(
                    $dsn,
                    self::USER,
                    self::PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException('Connexion à PostgreSQL impossible : ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}

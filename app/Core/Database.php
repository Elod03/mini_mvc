<?php

namespace Mini\Core;

use PDO;

class Database
{
    /** @var PDO */
    private $dbh;
    private static $_instance;

    private function __construct()
    {
        $configData = parse_ini_file(__DIR__ . '/../config.ini');

        try {
            $this->dbh = new PDO(
                "mysql:host={$configData['DB_HOST']};dbname={$configData['DB_NAME']};charset=utf8",
                $configData['DB_USERNAME'],
                $configData['DB_PASSWORD'],
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_PERSISTENT => true
                )
            );
        } catch (\Exception $exception) {
            echo 'Erreur de connexion...<br>';
            echo $exception->getMessage() . '<br>';
            echo '<pre>';
            echo $exception->getTraceAsString();
            echo '</pre>';
            exit;
        }
    }

    public static function getPDO()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new Database();
        }

        // Vérifier si la connexion est toujours valide
        try {
            // Tester la connexion avec une requête simple
            self::$_instance->dbh->query('SELECT 1');
        } catch (\PDOException $e) {
            // Si la connexion est perdue, en créer une nouvelle
            if ($e->getCode() == 'HY000' || strpos($e->getMessage(), 'gone away') !== false) {
                self::$_instance = new Database();
            } else {
                throw $e;
            }
        }

        return self::$_instance->dbh;
    }

    public static function testConnection()
    {
        try {
            $pdo = self::getPDO();
            $stmt = $pdo->query('SELECT 1 as test');
            $result = $stmt->fetch();
            return $result['test'] == 1;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
<?php
/**
 * Database Connection Class
 * NGO Donor Management System
 * Supports MySQL and PostgreSQL
 */

class Database {
    private static $instance = null;
    private $connection;
    private $driver;
    
    private function __construct() {
        $config = require_once APP_PATH . '/config/database.php';
        
        $this->driver = $config['driver'] ?? 'mysql';
        
        if ($this->driver === 'pgsql') {
            // PostgreSQL connection
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $config['host'],
                $config['port'],
                $config['database']
            );
        } else {
            // MySQL connection (default)
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset'] ?? 'utf8mb4'
            );
        }
        
        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function getDriver() {
        return $this->driver;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function lastInsertId() {
        if ($this->driver === 'pgsql') {
            $result = $this->connection->query('SELECT LASTVAL()');
            return $result->fetchColumn();
        }
        return $this->connection->lastInsertId();
    }
    
    public function quote($value) {
        return $this->connection->quote($value);
    }
}

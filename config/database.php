<?php
class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=stock_manager;charset=utf8mb4',
                    'root',
                    '',
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                die("Failed to connect to database");
            }
        }
        return self::$instance;
    }
}
?>
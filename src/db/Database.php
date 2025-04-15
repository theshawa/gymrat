<?php

class Database
{
    private static null|Database $instance = null;
    private null|PDO $conn = null;

    private $CONFIG = [
        "host" => "mysql_db",
        "username" => "root",
        "password" => "123456",
        "dbname" => "gymrat",
    ];

    private function __construct()
    {
        try {
            $tz = (new DateTime('now', new DateTimeZone('Asia/Colombo')))->format('P');
            $this->conn = new PDO(
                "mysql:host=" . $this->CONFIG['host'] . ";dbname=" . $this->CONFIG['dbname'],
                $this->CONFIG['username'],
                $this->CONFIG['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone='$tz'"
                ]
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException  $e) {
            die("database connection failed: " . $e->getMessage());
        }
    }

    public static function get_conn()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}

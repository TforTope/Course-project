Author: TOLU_OLUSEGU
Date: 5/26/2026
File: database.php
Description:

<?php

class Database
{
    private string $host = "localhost";
    private string $db_name = "eventhub_db";
    private string $username = "root";
    private string $password = "";
    public ?PDO $conn = null;

    public function connect(): ?PDO
    {
        try {
            $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                    $this->username,
                    $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conn;
        } catch (PDOException $exception) {
            echo json_encode([
                    "error" => "Database connection failed",
                    "message" => $exception->getMessage()
            ]);

            return null;
        }
    }
}
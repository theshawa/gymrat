<?php

require_once __DIR__ . "/Database.php";

abstract class Model
{
    protected $table;
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::get_conn();
    }
}

<?php
abstract class BaseModel
{
    protected PDO $conn;
    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }
    abstract public function getAll();
}
?>
<?php

class Database {
    private string $host = 'localhost';//string untuk mencegah kesalahannya menurut saya biar 
    //tidak ke database supaya tidak di kira program data database jadi di ubah ke tipe data string
    private string $db_name = 'auukganjil';
    private string $username = 'root';
    private string $password = '';
    private ?PDO $conn = null;

    public function __construct()
    {
        try{
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $options =[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn=new PDO($dsn, $this->username,$this->password,$options);
        } catch(PDOException $e){
            die("Koneksi database gagal: ". $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}

?>
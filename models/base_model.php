<?php
abstract class BaseModel//cetakan induk untuk model-model lain yang berinteraksi dengan database. Kelas ini menyediakan properti dan metode dasar yang dapat digunakan oleh model-model turunan untuk mengakses database melalui objek PDO.
{
    protected PDO $conn;//properti yang menyimpan objek PDO untuk koneksi database. Properti ini akan digunakan oleh model-model turunan untuk menjalankan query SQL dan berinteraksi dengan database.
    public function __construct(PDO $db)//konstruktor yang menerima objek PDO sebagai parameter. Objek PDO ini digunakan untuk mengatur koneksi ke database dan disimpan dalam properti $conn.
    {
        $this->conn = $db;//menyimpan objek PDO yang diterima sebagai parameter ke dalam properti $conn. Dengan demikian, model-model turunan dapat menggunakan properti ini untuk menjalankan query SQL dan berinteraksi dengan database.
    }
    abstract public function getAll();//metode abstrak yang harus diimplementasikan oleh model-model turunan. Metode ini bertujuan untuk mengambil semua data dari tabel yang sesuai dengan model tersebut. Implementasi spesifik dari metode ini akan berbeda-beda tergantung pada model turunan yang bersangkutan.
}
?>
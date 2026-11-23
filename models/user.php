<?php
require_once __DIR__ . '/base_model.php';//memanggil file base_model.php yang berisi kelas abstrak BaseModel. Kelas User akan mewarisi properti dan metode dari BaseModel untuk berinteraksi dengan database melalui objek PDO.

class User extends BaseModel//pewarisan kelas User dari kelas abstrak BaseModel. Dengan mewarisi BaseModel, kelas User dapat menggunakan properti dan metode yang telah didefinisikan dalam BaseModel untuk berinteraksi dengan database melalui objek PDO.
{
    private ?int $id_user = null;
    private string $username = '';
    private string $password = '';

    public function __construct(PDO $db)//konstruktor yang menerima objek PDO sebagai parameter. Objek PDO ini digunakan untuk mengatur koneksi ke database dan disimpan dalam properti $conn.
    {
        parent::__construct($db);//konstruktor dari kelas induk BaseModel dipanggil untuk menginisialisasi properti $conn dengan objek PDO yang diterima sebagai parameter. Dengan demikian, kelas User dapat menggunakan properti $conn untuk berinteraksi dengan database.
    }

    public function getIdUser(): ?int//metode getter untuk properti $id_user. Metode ini mengembalikan nilai dari properti $id_user, yang merupakan ID unik pengguna dalam database. Nilai ini dapat berupa integer atau null jika belum diatur.
    {
        return $this->id_user;//metode getter untuk properti $id_user. Metode ini mengembalikan nilai dari properti $id_user, yang merupakan ID unik pengguna dalam database. Nilai ini dapat berupa integer atau null jika belum diatur.
    }

    public function setIdUser(?int $id_user): void//metode setter untuk properti $id_user. Metode ini menerima parameter $id_user yang dapat berupa integer atau null, dan menetapkan nilai tersebut ke properti $id_user. Dengan demikian, ID unik pengguna dapat diatur melalui metode ini.
    {
        $this->id_user = $id_user;//metode setter untuk properti $id_user. Metode ini menerima parameter $id_user yang dapat berupa integer atau null, dan menetapkan nilai tersebut ke properti $id_user. Dengan demikian, ID unik pengguna dapat diatur melalui metode ini.
    }

    public function getUsername(): string//metode getter untuk properti $username. Metode ini mengembalikan nilai dari properti $username, yang merupakan nama pengguna dalam database.
    {
        return $this->username;//metode getter untuk properti $username. Metode ini mengembalikan nilai dari properti $username, yang merupakan nama pengguna dalam database.
    }

    public function setUsername(string $username): void//metode setter untuk properti $username. Metode ini menerima parameter $username yang merupakan string, dan menetapkan nilai tersebut ke properti $username. Dengan demikian, nama pengguna dapat diatur melalui metode ini.
    {
        $this->username = trim($username);//metode setter untuk properti $username. Metode ini menerima parameter $username yang merupakan string, dan menetapkan nilai tersebut ke properti $username. Dengan demikian, nama pengguna dapat diatur melalui metode ini.
    }

    public function getPassword(): string//metode getter untuk properti $password. Metode ini mengembalikan nilai dari properti $password, yang merupakan kata sandi pengguna dalam database.
    {
        return $this->password;
    }

    public function setPassword(string $password): void//metode setter untuk properti $password. Metode ini menerima parameter $password yang merupakan string, dan menetapkan nilai tersebut ke properti $password. Dengan demikian, kata sandi pengguna dapat diatur melalui metode ini.
    {
        $this->password = $password;
    }

    public function getAll(): array//metode untuk mengambil semua data pengguna dari tabel users dalam database. Metode ini menjalankan query SQL untuk memilih kolom id_user dan username dari tabel users, mengurutkan hasilnya berdasarkan id_user secara ascending, dan mengembalikan hasilnya sebagai array.
    {
        $stmt = $this->conn->query("SELECT id_user, username FROM users ORDER BY id_user ASC");//menjalankan query SQL untuk memilih kolom id_user dan username dari tabel users, mengurutkan hasilnya berdasarkan id_user secara ascending, dan menyimpan hasilnya dalam objek statement PDO.
        return $stmt->fetchAll();//mengambil semua hasil dari objek statement PDO dan mengembalikannya sebagai array. Array ini berisi semua data pengguna yang diambil dari tabel users dalam database.
    }

    public function authenticate(): ?array//metode untuk mengautentikasi pengguna berdasarkan nama pengguna dan kata sandi yang diberikan. Metode ini menjalankan query SQL untuk mencari pengguna dengan nama pengguna yang sesuai, memverifikasi kata sandi yang diberikan dengan hash yang tersimpan di database, dan mengembalikan data pengguna jika autentikasi berhasil. Jika autentikasi gagal, metode ini mengembalikan null.
    {
        $sql = "SELECT id_user, username, password FROM users WHERE username = :username LIMIT 1";//menyiapkan query SQL untuk memilih kolom id_user, username, dan password dari tabel users di mana username sesuai dengan parameter yang diberikan. Query ini membatasi hasilnya hanya satu baris (LIMIT 1) untuk memastikan hanya satu pengguna yang dikembalikan.
        $stmt = $this->conn->prepare($sql);//menyiapkan pernyataan SQL menggunakan metode prepare() dari objek PDO. Ini memungkinkan penggunaan parameter yang aman untuk mencegah serangan SQL injection.
        $stmt->execute([':username' => $this->username]);//menjalankan pernyataan SQL yang telah disiapkan dengan menggantikan parameter :username dengan nilai dari properti $username. Ini memastikan bahwa nama pengguna yang diberikan digunakan dalam query untuk mencari pengguna yang sesuai di database.
        $user = $stmt->fetch();//mengambil hasil dari pernyataan SQL yang telah dijalankan dan menyimpannya dalam variabel $user. Variabel ini akan berisi data pengguna yang sesuai dengan nama pengguna yang diberikan, atau false jika tidak ada pengguna yang ditemukan.

        if ($user && password_verify($this->password, $user['password'])) {//memeriksa apakah data pengguna ditemukan dan memverifikasi kata sandi yang diberikan dengan hash yang tersimpan di database menggunakan fungsi password_verify(). Jika autentikasi berhasil, metode ini mengembalikan data pengguna.
            return $user;
        }

        return null;//metode ini mengembalikan null jika autentikasi gagal, yang berarti nama pengguna atau kata sandi tidak sesuai dengan data yang tersimpan di database.
    }
}
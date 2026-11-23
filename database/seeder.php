<?php
require_once __DIR__ . '/../config/database.php';//memanggil file database.php yang berisi kelas Database untuk mengatur koneksi ke database MySQL.
$database = new Database();
$db = $database->getConnection();

try{
    //buat akun admin
    $passwordHash = password_hash('guru123', PASSWORD_DEFAULT);//menggunakan fungsi password_hash() untuk mengamankan kata sandi 'guru123' sebelum disimpan di database. Fungsi ini menghasilkan hash yang aman menggunakan algoritma default (saat ini bcrypt), sehingga kata sandi asli tidak disimpan secara langsung, meningkatkan keamanan akun admin.
    $stmtUser = $db->prepare("INSERT IGNORE INTO users (username, password) VALUES (:username, :password)");//menyiapkan pernyataan SQL untuk menyisipkan akun admin ke dalam tabel users. Kata kunci INSERT IGNORE digunakan untuk menghindari kesalahan jika akun admin sudah ada.
    $stmtUser->execute([
        ':username' => 'admin',//menyisipkan akun admin dengan nama pengguna 'admin' ke dalam tabel users.
        ':password' => $passwordHash//menyisipkan kata sandi yang telah di-hash ke dalam tabel users.
    ]);

    //isi data awal kelas
    $daftarkelas =[
        ['XI PPLG', 'PPLG'],
        ['XI DKV', 'DKV'],
        ['XI MPLB', 'MPLB'],
    ];

    $stmtkelas = $db->prepare("INSERT IGNORE INTO kelas (nama_kelas, jurusan) VALUES (:nama_kelas, :jurusan)");//menyiapkan pernyataan SQL untuk menyisipkan data awal kelas ke dalam tabel kelas. Kata kunci INSERT IGNORE digunakan untuk menghindari kesalahan jika data kelas sudah ada.
    foreach ($daftarkelas as $kelas) {
        $stmtkelas->execute([
            ':nama_kelas' => $kelas[0],
            ':jurusan' => $kelas[1]
        ]);
    }
    echo "Seeder berhasil dijalankan. username: <b>admin</b>, password: <b>guru123</b>";
} catch (PDOException $e) {
    echo "Seeder gagal dijalankan: " . htmlspecialchars($e->getMessage());
}
?>
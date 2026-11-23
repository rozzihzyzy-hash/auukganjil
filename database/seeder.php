<?php
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$db = $database->getConnection();

try{
    //buat akun admin
    $passwordHash = password_hash('guru123', PASSWORD_DEFAULT);
    $stmtUser = $db->prepare("INSERT IGNORE INTO users (username, password) VALUES (:username, :password)");
    $stmtUser->execute([
        ':username' => 'admin',
        ':password' => $passwordHash
    ]);

    //isi data awal kelas
    $daftarkelas =[
        ['XI PPLG', 'PPLG'],
        ['XI DKV', 'DKV'],
        ['XI MPLB', 'MPLB'],
    ];

    $stmtkelas = $db->prepare("INSERT IGNORE INTO kelas (nama_kelas, jurusan) VALUES (:nama_kelas, :jurusan)");
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
CREATE DATABASE IF NOT EXISTS auukganjil
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE auukganjil;
--Tabel kelas-- karena induk
CREATE TABLE kelas (
    id_kelas INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(20) NOT NULL UNIQUE,--wajib diisi
    jurusan VARCHAR(50) NOT NULL--wajib diisi
) ENGINE=InnoDB;

--tabels siswa karena habisa kelas dia butuh fk kelas--
CREATE TABLE siswa (
    nisn CHAR(10) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,--wajib diisi
    alamat VARCHAR(255) NULL,
    id_kelas INT NOT NULL,--wajib diisi
    CONSTRAINT fk_siswa_kelas
        FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE presensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn CHAR(10) NOT NULL,--wajib diisi
    tanggal_waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,--wajib diisi
    CONSTRAINT fk_presensi_siswa
        FOREIGN KEY (nisn) REFERENCES siswa(nisn)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,--wajib diisi
    password VARCHAR(255) NOT NULL--wajib diisi
) ENGINE=InnoDB;--untuk pengolaan data dan aman dari kerusanakan, anti lemot, di akses banyak orang, dan dihubungkan dengan tabel lain

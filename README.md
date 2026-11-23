
# Aplikasi Presensi Siswa Berbasis RFID (SMK TI Airlangga Samarinda)

Aplikasi web berbasis PHP Native (OOP & PDO) dan database MariaDB untuk mencatat dan merekap kehadiran siswa melalui pembacaan kartu RFID (NISN 10 digit) secara real-time.

## Fitur Utama
- **Login & Logout Administrator**: Autentikasi menggunakan `password_hash` dan `password_verify` dengan proteksi `$_SESSION`.
- **Kelola Kelas (CRUD)**: Tambah, tampil, ubah, dan hapus kelas dengan validasi nama kelas unik serta pencegahan hapus jika kelas masih memiliki siswa.
- **Kelola Siswa (CRUD & Pencarian)**: Tambah, tampil (`INNER JOIN` kelas), ubah, hapus, serta pencarian berdasarkan nama atau NISN (validasi 10 digit angka).
- **Simulasi Tap Kartu RFID**: Input 10 digit NISN untuk mencatat kehadiran dengan waktu tap otomatis dari database.
- **Rekap Presensi (`INNER JOIN` 3 Tabel)**: Menampilkan data kehadiran hari ini secara bawaan, filter tanggal dan kelas, pencarian nama/NISN, serta hapus log salah catat.

## Cara Instalasi
1. Salin folder proyek `auukganjil` ke dalam folder web server (`C:\Users\Asus\Desktop\laragon\www\auukganjil`).
2. Jalankan layanan **Apache** dan **MySQL/MariaDB** di Laragon.
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`), masuk ke tab **SQL**, lalu jalankan perintah dari file `database/schema.sql` untuk membuat database `auukganjil` beserta tabel-tabelnya.
4. Sesuaikan kredensial database pada `config/database.php` jika diperlukan.
5. Jalankan seeder dengan membuka `http://localhost/auukganjil/database/seeder.php` di browser untuk mengisi akun admin dan data awal kelas.
6. Buka aplikasi di `http://localhost/auukganjil/`.

## Akun Demo Administrator
- **Username**: `admin`
- **Password**: `guru123`

## Gambar ERD (Entity Relationship Diagram)
![ERD Aplikasi Presensi Siswa](docs/erd.png)

## Tabel Hasil Pengujian
| No | Skenario Uji | Hasil yang Diharapkan | Status | Bukti |
| :- | :--- | :--- | :-: | :--- |
| 1 | Login dengan password salah | Tetap di halaman login, muncul pesan kesalahan | Berhasil | ![Uji 1](docs/uji1_login_salah.png) |
| 2 | Buka halaman tanpa login lewat URL langsung | Diarahkan ke halaman login | Berhasil | ![Uji 2](docs/uji2_tanpalogin_1.png) |
| 3 | Tambah siswa dengan NISN 9 digit | Validasi gagal, pesan muncul, isian tidak hilang | Berhasil | ![Uji 3](docs/uji3_nisn_9digit.png) |
| 4 | Tambah siswa dengan NISN yang sudah ada | Ditolak dengan pesan NISN sudah terdaftar | Berhasil | ![Uji 4](docs/uji4_nisn_kembar.png) |
| 5 | Simulasi tap dengan NISN yang tidak terdaftar | Ditolak dengan pesan yang jelas | Berhasil | ![Uji 5](docs/uji5_tap_tidak_terdaftar.png) |
| 6 | Simulasi tap dengan NISN valid | Muncul di rekap hari ini, dengan waktu terisi otomatis | Berhasil | ![Uji 6](docs/uji6_tap_valid.png) |
| 7 | Hapus kelas yang masih memiliki siswa | Ditolak dengan pesan yang mudah dipahami | Berhasil | ![Uji 7](docs/uji7_hapus_kelas_berisi_siswa.png) |
| 8 | Cari siswa berdasarkan sebagian nama | Hanya siswa yang sesuai yang tampil | Berhasil | ![Uji 8](docs/uji8_cari_siswa.png) |
| 9 | Uji tampilan aplikasi pada smartphone | Tata letak dan tabel responsif di layar mobile | Berhasil | ![Uji 9](docs/tampilan_dari_smartphone.png) |

## Tangkapan Layar Halaman Utama

### 1. Halaman Login
![Halaman Login](docs/halaman_login.png)

### 2. Halaman Rekap Presensi
![Halaman Rekap Presensi](docs/halaman_rekap.png)

### 3. Halaman Simulasi Tap Kartu
![Halaman Simulasi Tap Kartu](docs/halaman_tap.png)

### 4. Halaman Kelola Kelas
![Halaman Kelola Kelas](docs/halaman_kelas.png)

### 5. Halaman Kelola Siswa
![Halaman Kelola Siswa](docs/halaman_siswa.png)
<?php
require_once __DIR__ . '/base_model.php';

class Siswa extends BaseModel
{
    private string $nisn = '';
    private string $nama = '';
    private ?string $alamat = null;
    private int $id_kelas = 0;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function getNisn(): string
    {
        return $this->nisn;
    }

    public function setNisn(string $nisn): void
    {
        $this->nisn = trim($nisn);
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $this->nama = trim($nama);
    }

    public function getAlamat(): ?string
    {
        return $this->alamat;
    }

    public function setAlamat(?string $alamat): void
    {
        $trimmed = $alamat !== null ? trim($alamat) : '';
        $this->alamat = $trimmed === '' ? null : $trimmed;
    }

    public function getIdKelas(): int
    {
        return $this->id_kelas;
    }

    public function setIdKelas(int $id_kelas): void
    {
        $this->id_kelas = $id_kelas;
    }

    public function getAll(): array
    {
        $sql = "SELECT s.nisn, s.nama, s.alamat, s.id_kelas, k.nama_kelas, k.jurusan
                FROM siswa s
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                ORDER BY s.nama ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function search(string $keyword): array
    {
        $sql = "SELECT s.nisn, s.nama, s.alamat, s.id_kelas, k.nama_kelas, k.jurusan
                FROM siswa s
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                WHERE s.nama LIKE :nama OR s.nisn LIKE :nisn
                ORDER BY s.nama ASC";
        $stmt = $this->conn->prepare($sql);
        $pattern = '%' . trim($keyword) . '%';
        $stmt->execute([
            ':nama' => $pattern,
            ':nisn' => $pattern
        ]);
        return $stmt->fetchAll();
    }

    public function getByNisn(string $nisn): ?array
    {
        $sql = "SELECT s.nisn, s.nama, s.alamat, s.id_kelas, k.nama_kelas
                FROM siswa s
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                WHERE s.nisn = :nisn
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':nisn' => $nisn]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function isNisnExists(string $nisn, ?string $exclude_nisn = null): bool
    {
        if ($exclude_nisn !== null) {
            $sql = "SELECT COUNT(*) FROM siswa WHERE nisn = :nisn AND nisn != :exclude_nisn";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nisn'         => trim($nisn),
                ':exclude_nisn' => $exclude_nisn
            ]);
        } else {
            $sql = "SELECT COUNT(*) FROM siswa WHERE nisn = :nisn";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':nisn' => trim($nisn)]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(): bool
    {
        $sql = "INSERT INTO siswa (nisn, nama, alamat, id_kelas)
                VALUES (:nisn, :nama, :alamat, :id_kelas)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nisn'     => $this->nisn,
            ':nama'     => $this->nama,
            ':alamat'   => $this->alamat,
            ':id_kelas' => $this->id_kelas
        ]);
    }

    public function update(string $old_nisn): bool
    {
        $sql = "UPDATE siswa
                SET nisn = :nisn, nama = :nama, alamat = :alamat, id_kelas = :id_kelas
                WHERE nisn = :old_nisn";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nisn'     => $this->nisn,
            ':nama'     => $this->nama,
            ':alamat'   => $this->alamat,
            ':id_kelas' => $this->id_kelas,
            ':old_nisn' => $old_nisn
        ]);
    }

    public function delete(string $nisn): bool
    {
        $sql = "DELETE FROM siswa WHERE nisn = :nisn";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':nisn' => $nisn]);
    }
}
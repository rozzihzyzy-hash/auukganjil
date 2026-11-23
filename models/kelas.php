<?php
require_once __DIR__ . '/base_model.php';

class Kelas extends BaseModel
{
    private ?int $id_kelas = null;
    private string $nama_kelas = '';
    private string $jurusan = '';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function getIdKelas(): ?int
    {
        return $this->id_kelas;
    }

    public function setIdKelas(?int $id_kelas): void
    {
        $this->id_kelas = $id_kelas;
    }

    public function getNamaKelas(): string
    {
        return $this->nama_kelas;
    }

    public function setNamaKelas(string $nama_kelas): void
    {
        $this->nama_kelas = trim($nama_kelas);
    }

    public function getJurusan(): string
    {
        return $this->jurusan;
    }

    public function setJurusan(string $jurusan): void
    {
        $this->jurusan = trim($jurusan);
    }

    public function getAll(): array
    {
        $sql = "SELECT id_kelas, nama_kelas, jurusan FROM kelas ORDER BY nama_kelas ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT id_kelas, nama_kelas, jurusan FROM kelas WHERE id_kelas = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function isNamaKelasExists(string $nama_kelas, ?int $exclude_id = null): bool
    {
        if ($exclude_id !== null) {
            $sql = "SELECT COUNT(*) FROM kelas WHERE nama_kelas = :nama_kelas AND id_kelas != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':nama_kelas' => trim($nama_kelas),
                ':id'         => $exclude_id
            ]);
        } else {
            $sql = "SELECT COUNT(*) FROM kelas WHERE nama_kelas = :nama_kelas";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':nama_kelas' => trim($nama_kelas)]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    public function hasSiswa(int $id_kelas): bool
    {
        $sql = "SELECT COUNT(*) FROM siswa WHERE id_kelas = :id_kelas";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_kelas' => $id_kelas]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(): bool
    {
        $sql = "INSERT INTO kelas (nama_kelas, jurusan) VALUES (:nama_kelas, :jurusan)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nama_kelas' => $this->nama_kelas,
            ':jurusan'    => $this->jurusan
        ]);
    }

    public function update(): bool
    {
        $sql = "UPDATE kelas SET nama_kelas = :nama_kelas, jurusan = :jurusan WHERE id_kelas = :id_kelas";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nama_kelas' => $this->nama_kelas,
            ':jurusan'    => $this->jurusan,
            ':id_kelas'   => $this->id_kelas
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM kelas WHERE id_kelas = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
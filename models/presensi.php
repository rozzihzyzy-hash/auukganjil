<?php
require_once __DIR__ . '/base_model.php';

class Presensi extends BaseModel
{
    private ?int $id = null;
    private string $nisn = '';
    private string $tanggal_waktu = '';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNisn(): string
    {
        return $this->nisn;
    }

    public function setNisn(string $nisn): void
    {
        $this->nisn = trim($nisn);
    }

    public function getTanggalWaktu(): string
    {
        return $this->tanggal_waktu;
    }

    public function setTanggalWaktu(string $tanggal_waktu): void
    {
        $this->tanggal_waktu = $tanggal_waktu;
    }

    public function getAll(): array
    {
        $sql = "SELECT p.id, p.nisn, p.tanggal_waktu, s.nama, k.nama_kelas
                FROM presensi p
                INNER JOIN siswa s ON p.nisn = s.nisn
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                ORDER BY p.tanggal_waktu DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getFiltered(string $tanggal = '', int $id_kelas = 0, string $keyword = ''): array
    {
        $sql = "SELECT p.id, p.nisn, p.tanggal_waktu, s.nama, k.nama_kelas
                FROM presensi p
                INNER JOIN siswa s ON p.nisn = s.nisn
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                WHERE 1=1";

        $params = [];

        if ($tanggal !== '') {
            $sql .= " AND DATE(p.tanggal_waktu) = :tanggal";
            $params[':tanggal'] = $tanggal;
        }

        if ($id_kelas > 0) {
            $sql .= " AND s.id_kelas = :id_kelas";
            $params[':id_kelas'] = $id_kelas;
        }

        if ($keyword !== '') {
            $sql .= " AND (s.nama LIKE :nama OR p.nisn LIKE :nisn)";
            $pattern = '%' . trim($keyword) . '%';
            $params[':nama'] = $pattern;
            $params[':nisn'] = $pattern;
        }

        $sql .= " ORDER BY p.tanggal_waktu DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT id, nisn, tanggal_waktu FROM presensi WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function tapKartu(): bool
    {
        // Aplikasi hanya mengirim nisn; id dan tanggal_waktu diisi otomatis oleh database
        $sql = "INSERT INTO presensi (nisn) VALUES (:nisn)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':nisn' => $this->nisn]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM presensi WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
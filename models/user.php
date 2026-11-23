<?php
require_once __DIR__ . '/base_model.php';

class User extends BaseModel
{
    private ?int $id_user = null;
    private string $username = '';
    private string $password = '';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): void
    {
        $this->id_user = $id_user;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = trim($username);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT id_user, username FROM users ORDER BY id_user ASC");
        return $stmt->fetchAll();
    }

    public function authenticate(): ?array
    {
        $sql = "SELECT id_user, username, password FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $this->username]);
        $user = $stmt->fetch();

        if ($user && password_verify($this->password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
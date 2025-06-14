<?php
class PasswordReset
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Genera y guarda un token seguro
    public function create(string $email, string $token): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO password_resets (email, token, created_at)
             VALUES (?, ?, NOW())"
        );
        return $stmt->execute([$email, password_hash($token, PASSWORD_DEFAULT)]);
    }

    // Verifica token válido (no caducado: 1 hora)
    public function verify(string $email, string $token): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT token, created_at
             FROM password_resets
             WHERE email = ?
             ORDER BY created_at DESC
             LIMIT 1"
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        if (!$row) return false;
        // Caducidad 1 hora
        if (time() - strtotime($row['created_at']) > 3600) return false;
        return password_verify($token, $row['token']);
    }

    // Limpia los tokens para un email
    public function clear(string $email): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM password_resets WHERE email = ?"
        );
        $stmt->execute([$email]);
    }
}

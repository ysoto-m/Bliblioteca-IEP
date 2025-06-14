<?php
// models/User.php

class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Busca un usuario por email; devuelve array o null
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Crea un nuevo usuario con contraseña hasheada.
     * @return bool true si se insertó correctamente
     */
    public function create(string $nombre, string $email, string $password, string $rol = 'lector'): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario (nombre, email, contraseña, rol)
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$nombre, $email, $hash, $rol]);
    }
}

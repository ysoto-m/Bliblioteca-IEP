<?php
// models/Notificacion.php

class Notificacion
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function allFor(int $usuario_id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM notificacion
             WHERE usuario_id = ?
             ORDER BY creado_en DESC"
        );
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll();
    }

    public function countUnread(int $usuario_id): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM notificacion
             WHERE usuario_id = ? AND leido = 0"
        );
        $stmt->execute([$usuario_id]);
        return (int)$stmt->fetchColumn();
    }

    public function create(int $usuario_id, string $mensaje): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO notificacion (usuario_id, mensaje) VALUES (?, ?)"
        );
        return $stmt->execute([$usuario_id, $mensaje]);
    }

    public function markAsRead(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE notificacion SET leido = 1 WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}

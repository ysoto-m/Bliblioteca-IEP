<?php
// models/Reserva.php

class Reserva
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Inserta una reserva al final de la cola para un libro.
     * Verifica que el usuario no esté ya en la cola.
     */
    public function create(int $usuario_id, int $libro_id): bool
    {
        if ($this->existe($usuario_id, $libro_id)) {
            return false; // Ya está en la cola
        }

        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(MAX(prioridad), 0) + 1
             FROM reserva
             WHERE libro_id = ?"
        );
        $stmt->execute([$libro_id]);
        $prioridad = (int)$stmt->fetchColumn();

        $stmt2 = $this->pdo->prepare(
            "INSERT INTO reserva
             (usuario_id, libro_id, fecha_reserva, prioridad)
             VALUES (?, ?, CURDATE(), ?)"
        );
        return $stmt2->execute([$usuario_id, $libro_id, $prioridad]);
    }

    /**
     * Verifica si un usuario ya tiene reserva para un libro
     */
    public function existe(int $usuario_id, int $libro_id): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM reserva
             WHERE usuario_id = ? AND libro_id = ?"
        );
        $stmt->execute([$usuario_id, $libro_id]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Devuelve la cola de espera para un libro,
     * ordenada por prioridad ascendente y fecha.
     */
    public function getQueue(int $libro_id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.id, r.usuario_id, r.libro_id, r.fecha_reserva, r.prioridad,
                    u.nombre
             FROM reserva r
             JOIN usuario u ON r.usuario_id = u.id
             WHERE r.libro_id = ?
             ORDER BY r.prioridad ASC, r.fecha_reserva ASC"
        );
        $stmt->execute([$libro_id]);
        return $stmt->fetchAll();
    }

    /**
     * Elimina una reserva por ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM reserva WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene al primer usuario en la cola para un libro
     */
    public function getPrimeroEnCola($libro_id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM reserva WHERE libro_id = ? ORDER BY fecha_reserva ASC LIMIT 1"
        );
        $stmt->execute([$libro_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Retorna todas las reservas activas de un usuario
     */
    public function getByUsuario(int $usuario_id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.id, r.libro_id, r.fecha_reserva, l.titulo
             FROM reserva r
             JOIN libro l ON r.libro_id = l.id
             WHERE r.usuario_id = ?
             ORDER BY r.fecha_reserva DESC"
        );
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

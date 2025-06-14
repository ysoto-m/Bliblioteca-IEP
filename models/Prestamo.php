<?php
// models/Prestamo.php

class Prestamo
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Devuelve todos los préstamos activos (no devueltos).
     */
    public function getActive(): array
    {
        $stmt = $this->pdo->query(
            "SELECT p.id, p.usuario_id, p.libro_id, p.fecha_prestamo, p.due_date, l.titulo
               FROM prestamo p
               JOIN libro l ON p.libro_id = l.id
              WHERE p.devuelto = 0
              ORDER BY p.fecha_prestamo DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un préstamo por su ID.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM prestamo WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea un nuevo préstamo con fecha de devolución automática a +14 días.
     */
    public function create(int $usuario_id, int $libro_id): bool
    {
        $dueDate = date('Y-m-d', strtotime('+14 days'));
        $stmt = $this->pdo->prepare(
            "INSERT INTO prestamo (usuario_id, libro_id, fecha_prestamo, due_date, devuelto)
             VALUES (?, ?, CURDATE(), ?, 0)"
        );
        return $stmt->execute([$usuario_id, $libro_id, $dueDate]);
    }

    /**
     * Marca un préstamo como devuelto.
     */
    public function markReturned(int $prestamo_id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE prestamo SET devuelto = 1 WHERE id = ?"
        );
        return $stmt->execute([$prestamo_id]);
    }

    /**
     * Obtiene los préstamos cuyo due_date coincide con $date.
     */
    public function getDueLoans(string $date): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.libro_id, l.titulo
               FROM prestamo p
               JOIN libro l ON p.libro_id = l.id
              WHERE p.due_date = ? AND p.devuelto = 0"
        );
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrestamosActivos($libro_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM prestamo WHERE libro_id = ? AND devuelto = 0");
        $stmt->execute([$libro_id]);
        return $stmt->fetchAll();
    }

    public function crearPrestamo($usuario_id, $libro_id)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO prestamo (usuario_id, libro_id, fecha_prestamo, devuelto)
            VALUES (?, ?, ?, 0)"
        );
        $stmt->execute([
            $usuario_id,
            $libro_id,
            date('Y-m-d')
        ]);
    }

    public function getByUsuario($usuario_id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, l.titulo
            FROM prestamo p
            JOIN libro l ON p.libro_id = l.id
            WHERE p.usuario_id = ?
            ORDER BY p.fecha_prestamo DESC"
        );
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }






    public function tienePrestamoActivo($usuario_id, $libro_id): bool {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM prestamo WHERE usuario_id = ? AND libro_id = ? AND devuelto = 0"
        );
        $stmt->execute([$usuario_id, $libro_id]);
        return (bool) $stmt->fetchColumn();
    }

}
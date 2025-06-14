<?php
// models/Libro.php

class Libro
{

    public function verificarBaja($libro_id) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM bajalibro WHERE libro_id = ?");
        $stmt->execute([$libro_id]);
        return $stmt->fetch() ? true : false;
    }

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        return $this->pdo
            ->query("SELECT * FROM libro ORDER BY id DESC")
            ->fetchAll();
    }

    public function search(string $q): array
    {
        $sql  = "SELECT * FROM libro
                 WHERE titulo LIKE :like OR autor LIKE :like
                 ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['like' => "%{$q}%"]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM libro WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function existeIsbn(string $isbn): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM libro WHERE isbn = ?");
        $stmt->execute([$isbn]);
        return $stmt->fetchColumn() > 0;
    }

    public function create(array $data): bool
    {
        if (strlen($data['titulo']) > 255 || strlen($data['autor']) > 255 || strlen($data['isbn']) > 50) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO libro (titulo, autor, isbn, ano_publicacion, disponible)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['titulo'],
            $data['autor'],
            $data['isbn'],
            $data['ano_publicacion'],
            $data['disponible'] ? 1 : 0,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE libro
             SET titulo = ?, autor = ?, isbn = ?, ano_publicacion = ?, disponible = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['titulo'],
            $data['autor'],
            $data['isbn'],
            $data['ano_publicacion'],
            $data['disponible'] ? 1 : 0,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM libro WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getDisponibles(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM libro WHERE disponible IN (1,2)");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchDisponibles(string $q): array
    {
        $q = "%$q%";
        $stmt = $this->pdo->prepare("
            SELECT * FROM libro
            WHERE disponible IN (1,2)
            AND (titulo LIKE ? OR autor LIKE ? OR isbn LIKE ?)
        ");
        $stmt->execute([$q, $q, $q]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function estaDadoDeBaja(int $libro_id): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM bajalibro WHERE libro_id = ?");
        $stmt->execute([$libro_id]);
        return (bool) $stmt->fetch();
    }

    public function darDeBaja($id, $motivo)
    {
        $stmt = $this->pdo->prepare("UPDATE libro SET disponible = 2 WHERE id = ?");
        $stmt->execute([$id]);

        $stmt = $this->pdo->prepare(
            "INSERT INTO bajalibro (libro_id, motivo, fecha_baja) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $id,
            $motivo,
            date('Y-m-d')
        ]);
    }
    public function marcarNoDisponible($id)
    {
        $stmt = $this->pdo->prepare("UPDATE libro SET disponible = 2 WHERE id = ?");
        $stmt->execute([$id]);
    }
    

    public function getVisiblesParaLectores(): array {
        $stmt = $this->pdo->query(
            "SELECT * FROM libro
             WHERE id NOT IN (SELECT libro_id FROM bajalibro)
             ORDER BY id DESC"
        );
        return $stmt->fetchAll();
    }

}
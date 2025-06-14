<?php
// controllers/PrestamoController.php

require_once __DIR__ . '/../models/Prestamo.php';
require_once __DIR__ . '/../models/Libro.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Notificacion.php';

class PrestamoController
{
    private Prestamo $model;
    private Libro $libroModel;
    private Reserva $resModel;

    public function __construct(PDO $pdo)
    {
        $this->model       = new Prestamo($pdo);
        $this->libroModel  = new Libro($pdo);
        $this->resModel    = new Reserva($pdo);
    }

    /**
     * Lista los préstamos activos.
     */
    public function index(): void
    {
        // Sólo usuarios autenticados
        if (! isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $prestamos = $this->model->getActive();
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/prestamos/list.php';
        include __DIR__ . '/../views/footer.php';
    }

    /**
     * Acción para prestar un libro.
     */
    public function prestar(): void
    {
        // Solo bibliotecarios
        if (! isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'bibliotecario') {
            header('HTTP/1.1 403 Forbidden');
            echo "Acceso denegado.";
            exit;
        }

        global $notifModel;
        $usuario_id = $_SESSION['user']['id'];
        $libro_id   = intval($_GET['id'] ?? 0);

        // 1) Crear préstamo
        $this->model->create($usuario_id, $libro_id);

        // 2) Marcar libro como no disponible
        $stmt = $this->libroModel->pdo->prepare(
            "UPDATE libro SET disponible = 0 WHERE id = ?"
        );
        $stmt->execute([$libro_id]);

        // 3) Obtener título del libro
        $stmt2 = $this->libroModel->pdo->prepare(
            "SELECT titulo FROM libro WHERE id = ?"
        );
        $stmt2->execute([$libro_id]);
        $titulo = $stmt2->fetchColumn();

        // 4) Crear notificación interna
        $notifModel->create(
            $usuario_id,
            "Has prestado “{$titulo}”. Debes devolverlo antes de " . date('Y-m-d', strtotime('+14 days')) . "."
        );

        // --- Envío de correo (comentado hasta configurar) ---
        /*
        require_once __DIR__ . '/../config/mail.php';
        $emailConfig = require __DIR__ . '/../config/mail.php';

        $userEmailStmt = $this->libroModel->pdo->prepare(
            "SELECT email FROM usuario WHERE id = ?"
        );
        $userEmailStmt->execute([$usuario_id]);
        $email = $userEmailStmt->fetchColumn();

        $headers = "From: {$emailConfig['from_name']} <{$emailConfig['from_email']}>";
        mail(
            $email,
            "Has prestado un libro",
            "Has prestado el libro: {$titulo}. Devuélvelo antes de " . date('Y-m-d', strtotime('+14 days')) . ".",
            $headers
        );
        */

        header('Location: index.php?action=prestamos&msg=prestado');
        exit;
    }

    /**
     * Acción para devolver un libro.
     */
    public function devolver(): void
    {
        // Solo bibliotecarios
        if (! isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'bibliotecario') {
            header('HTTP/1.1 403 Forbidden');
            echo "Acceso denegado.";
            exit;
        }

        global $notifModel;
        $prestamo_id = intval($_GET['id'] ?? 0);

        // 1) Obtener datos del préstamo
        $prestamo = $this->model->getById($prestamo_id);
        if (! $prestamo) {
            http_response_code(404);
            echo "Préstamo no encontrado.";
            exit;
        }

        $libro_id = $prestamo['libro_id'];

        // 2) Marcar devuelto
        $this->model->markReturned($prestamo_id);

        // 3) Marcar libro disponible
        $stmt = $this->libroModel->pdo->prepare(
            "UPDATE libro SET disponible = 1 WHERE id = ?"
        );
        $stmt->execute([$libro_id]);

        // 4) Atender cola de reservas
        $siguiente = $this->resModel->popNext($libro_id);
        if ($siguiente) {
            // Notificar al siguiente usuario
            $notifModel->create(
                $siguiente['usuario_id'],
                "El libro “{$siguiente['titulo']}” está disponible para ti. Puedes pedirlo en Préstamos."
            );
        }

        header('Location: index.php?action=prestamos&msg=devuelto');
        exit;
    }
}

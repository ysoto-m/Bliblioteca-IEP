<?php
// controllers/LibroController.php

require_once __DIR__ . '/../models/Libro.php';
require_once __DIR__ . '/../models/Reserva.php';

class LibroController
{
    private PDO   $pdo;
    private Libro $model;
    private Reserva $resModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo      = $pdo;
        $this->model    = new Libro($pdo);
        $this->resModel = new Reserva($pdo);
    }

    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $libros = $q !== ''
            ? $this->model->searchDisponibles($q)
            : $this->model->getDisponibles();

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/libros/list.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function crear(): void
    {
        if (! isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'bibliotecario') {
            header('HTTP/1.1 403 Forbidden'); echo "Acceso denegado."; exit;
        }

        global $notifModel;
        $currentUserId = $_SESSION['user']['id'];

        $errors = [];
        $data   = [
            'titulo'=>'','autor'=>'','isbn'=>'','ano_publicacion'=>'','disponible'=>1
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo'          => trim($_POST['titulo'] ?? ''),
                'autor'           => trim($_POST['autor']  ?? ''),
                'isbn'            => trim($_POST['isbn']   ?? ''),
                'ano_publicacion' => trim($_POST['ano_publicacion'] ?? ''),
                'disponible'      => isset($_POST['disponible']) ? 1 : 0,
            ];
            // ... validaciones idénticas a las tuyas ...

            if (empty($errors)) {
                $this->model->create($data);
                $notifModel->create(
                    $currentUserId,
                    "Se creó el libro «{$data['titulo']}» (ISBN: {$data['isbn']})"
                );
                header('Location: index.php?action=list&msg=creado');
                exit;
            }
        }

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/libros/form.php';
        include __DIR__ . '/../views/footer.php';
    }

    public function editar(): void
    {
        if (! isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'bibliotecario') {
            header('HTTP/1.1 403 Forbidden'); echo "Acceso denegado."; exit;
        }

        global $notifModel;
        $currentUserId = $_SESSION['user']['id'];

        $id    = intval($_GET['id'] ?? 0);
        $libro = $this->model->getById($id);
        if (! $libro) {
            http_response_code(404); echo "Libro no encontrado"; exit;
        }

        $errors = [];
        $data   = [
            'titulo'          => $libro['titulo'],
            'autor'           => $libro['autor'],
            'isbn'            => $libro['isbn'],
            'ano_publicacion' => $libro['ano_publicacion'],
            'disponible'      => $libro['disponible'],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo'          => trim($_POST['titulo'] ?? ''),
                'autor'           => trim($_POST['autor']  ?? ''),
                'isbn'            => trim($_POST['isbn']   ?? ''),
                'ano_publicacion' => trim($_POST['ano_publicacion'] ?? ''),
                'disponible'      => isset($_POST['disponible']) ? 1 : 0,
            ];
            // ... validaciones idénticas a las tuyas ...

            if (empty($errors)) {
                $this->model->update($id, $data);
                $notifModel->create(
                    $currentUserId,
                    "Se actualizó el libro ID {$id}: «{$data['titulo']}» (ISBN: {$data['isbn']})"
                );
                header("Location: index.php?action=list&msg=actualizado");
                exit;
            }
        }

        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/libros/form.php';
        include __DIR__ . '/../views/footer.php';

    }

    public function eliminar(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'bibliotecario') {
            header('HTTP/1.1 403 Forbidden'); echo "Acceso denegado."; exit;
        }

        global $notifModel;
        $currentUserId = $_SESSION['user']['id'];

        $id = intval($_GET['id'] ?? 0);
        $libro = $this->model->getById($id);

        if (!$libro) {
            http_response_code(404); echo "Libro no encontrado."; exit;
        }

        // Usar método del modelo para realizar la baja
        $this->model->darDeBaja($id, "Baja realizada por bibliotecario ID $currentUserId");

        // Notificación interna
        $notifModel->create(
            $currentUserId,
            "Se dio de baja el libro ID {$id}: «{$libro['titulo']}» (ISBN: {$libro['isbn']})"
        );

        header('Location: index.php?action=mantenimiento&msg=eliminado');
        exit;
    }


    /** ÚNICO método reservar: inserta en la cola y notifica */
    public function reservar(): void
    {
        if (! isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        global $notifModel;
        $currentUserId = $_SESSION['user']['id'];
        $libro_id      = intval($_GET['id'] ?? 0);

        // Inserta en la cola de espera
        $this->resModel->create($currentUserId, $libro_id);

        // Notifica al usuario
        $notifModel->create(
            $currentUserId,
            "Te has añadido a la cola de espera del libro ID {$libro_id}."
        );

        header('Location: index.php?action=list&msg=reserva');
        exit;
    }
    public function mantenimiento(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'bibliotecario') {
            header('HTTP/1.1 403 Forbidden');
            echo "Acceso denegado.";
            exit;
        }
    
        $libros = $this->model->getAll();
        
        // Para el formulario de agregar libro
        $errors = [];
        $data = [
            'titulo' => '', 'autor' => '', 'isbn' => '',
            'ano_publicacion' => '', 'disponible' => 1
        ];
        foreach ($libros as &$libro) {
            $libro['estado'] = 'Disponible';
            if (!$libro['disponible']) {
                $libro['estado'] = $this->model->estaDadoDeBaja($libro['id']) ? 'Dado de baja' : 'Prestado';
            }
        }
        unset($libro); // rompe la referencia
        
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/libros/mantenimiento.php';
        include __DIR__ . '/../views/footer.php';
    }
    public function obtener(): void
    {
        if (!isset($_SESSION['user'])) {
            header('HTTP/1.1 403 Forbidden'); echo "Acceso denegado."; exit;
        }

        $id = intval($_GET['id'] ?? 0);
        $libro = $this->model->getById($id);

        if (!$libro || !$libro['disponible']) {
            http_response_code(404); echo "Libro no disponible."; exit;
        }

        // Registrar el préstamo
        global $prestamoModel;
        if ($prestamoModel->tienePrestamoActivo($_SESSION['user']['id'], $id)) {
            echo "Ya tienes este libro en préstamo.";
            exit;
        }

        $prestamoModel->create($_SESSION['user']['id'], $id);

        // Marcar como no disponible
        $this->model->marcarNoDisponible($id);

        header('Location: index.php?action=misprestamos&msg=obtenido');
        exit;
    }




}

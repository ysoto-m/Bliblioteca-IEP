<?php
// index.php — Front-controller principal

session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/LibroController.php';
require_once __DIR__ . '/models/Notificacion.php';
require_once __DIR__ . '/models/Reserva.php';
require_once __DIR__ . '/models/Prestamo.php';

// Instanciamos controladores y modelos
$auth         = new AuthController($pdo);
$controller   = new LibroController($pdo);
$notifModel   = new Notificacion($pdo);
$reservaModel = new Reserva($pdo);
$prestamoModel = new Prestamo($pdo);

// Usuario actual (o null)
$currentUserId = $_SESSION['user']['id'] ?? null;
$currentUser   = $_SESSION['user']         ?? null;

// Acción por defecto
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'register':
        $auth->register();
        break;

    case 'login':
        $auth->login();
        break;

    case 'logout':
        $auth->logout();
        break;

    case 'forgot':
        $auth->forgot();
        break;

    case 'reset':
        $auth->reset();
        break;

    case 'list':
        $controller->index();
        break;

    case 'mantenimiento':
        $controller->mantenimiento();
         break;     

    case 'editar':
        $controller->editar();
        break;

    case 'crear':
        $controller->crear();
        break;

    case 'reservar':
        $controller->reservar();
        break;

    case 'obtener':
        $controller->obtener();
        break;

    case 'eliminar':
        $controller->eliminar();
        break;
        
    case 'misprestamos':
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/prestamos/misprestamos.php';
    include __DIR__ . '/views/footer.php';
    break;

    case 'verNotifs':
        $notifs = $notifModel->allFor($currentUserId);
        include __DIR__ . '/views/header.php';
        include __DIR__ . '/views/notifs.php';
        include __DIR__ . '/views/footer.php';
        break;

    case 'marcarLeido':
        $nid = intval($_GET['id'] ?? 0);
        $notifModel->markAsRead($nid);
        header('Location: index.php?action=verNotifs');
        exit;

    
    case 'misreservas':
    $reservas = $reservaModel->getByUsuario($currentUserId);
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/reservas/misreservas.php';
    include __DIR__ . '/views/footer.php';
    break;

    case 'misprestamos':
    require_once __DIR__ . '/models/Prestamo.php';
    $prestamoModel = new Prestamo($pdo);
    $prestamos = $prestamoModel->getByUsuario($currentUserId);
    include __DIR__ . '/views/header.php';
    include __DIR__ . '/views/prestamos/misprestamos.php';
    include __DIR__ . '/views/footer.php';
    break;

    case 'cancelarReserva':
        $id = intval($_GET['id'] ?? 0);
        $reservaModel->delete($id);
        header('Location: index.php?action=misreservas&msg=cancelada');
        exit;
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada.";
        break;
}

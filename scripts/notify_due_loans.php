<?php
require __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Prestamo.php';

$notifModel   = new Notificacion($pdo);
$reservaModel = new Reserva($pdo);
$prestamoModel = new Prestamo($pdo);

$today = date('Y-m-d');
$dueLoans = $prestamoModel->getDueLoans($today);

foreach ($dueLoans as $loan) {
    // Obtener siguiente en cola
    $next = $reservaModel->popNext($loan['libro_id']);
    if ($next) {
        $notifModel->create(
            $next['usuario_id'],
            "El libro “{$loan['titulo']}” está disponible para ti. ¡Pásate a recogerlo!"
        );
    }
}

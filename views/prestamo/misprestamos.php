<?php
global $prestamoModel, $currentUser;
$prestamos = $prestamoModel->getByUsuario($currentUser['id']);
?>

<h2>Mis Préstamos</h2>
<ul>
<?php foreach ($prestamos as $p): ?>
    <li><?= htmlspecialchars($p['titulo']) ?> (<?= $p['fecha_prestamo'] ?>)</li>
<?php endforeach; ?>
</ul>

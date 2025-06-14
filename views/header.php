<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Virtual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
<header class="bg-blue-900 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">📚 Biblioteca Virtual</h1>
        <nav class="space-x-4">
            <a href="index.php?action=list" class="hover:underline">Inicio</a>

            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['rol'] === 'bibliotecario'): ?>
                    <a href="index.php?action=mantenimiento" class="hover:underline">Mantenimiento</a>
                    <a href="index.php?action=prestamos" class="hover:underline">Préstamos</a>
                <?php else: ?>
                    <a href="index.php?action=misprestamos" class="hover:underline">Mis Préstamos</a>
                    <a href="index.php?action=misreservas" class="hover:underline">Mis Reservas</a>
                <?php endif; ?>
                <a href="index.php?action=verNotifs" class="hover:underline">Notificaciones</a>
                <span class="ml-4">👤 <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                <a href="index.php?action=logout" class="ml-2 bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-white">Salir</a>
            <?php else: ?>
                <a href="index.php?action=login" class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-white">Ingresar</a>
            <?php endif; ?>
        </nav>
    </div>

    <?php if (!empty($mostrarBusqueda)): ?>
        <div class="bg-blue-800">
            <form method="get" action="index.php" class="max-w-4xl mx-auto px-4 py-3 flex gap-2">
                <input type="hidden" name="action" value="list">
                <input type="text" name="q" placeholder="Buscar libros por título o autor"
                       class="w-full px-4 py-2 rounded bg-white text-gray-800 focus:outline-none"
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded text-white">
                    Buscar
                </button>
            </form>
        </div>
    <?php endif; ?>
</header>

<main class="max-w-7xl mx-auto px-4 py-6">

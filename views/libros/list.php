<?php
  global $currentUser, $reservaModel;
?>
<div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded mt-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-blue-800">📚 Lista de Libros</h1>
    <?php if ($currentUser && $currentUser['rol'] === 'bibliotecario'): ?>
      <a href="index.php?action=crear"
         class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
        ➕ Crear Nuevo Libro
      </a>
    <?php endif; ?>
  </div>

  <div class="overflow-auto">
    <table class="min-w-full table-auto border-collapse">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-3 border">ID</th>
          <th class="p-3 border">Título</th>
          <th class="p-3 border">Autor</th>
          <th class="p-3 border">ISBN</th>
          <th class="p-3 border">Año</th>
          <th class="p-3 border">Estado</th>
          <th class="p-3 border">Acciones</th>
        </tr>
      </thead>
      <tbody class="text-gray-800 text-sm">
        <?php if (!empty($libros)): foreach ($libros as $lib): ?>
          <tr class="hover:bg-gray-50 border-t">
            <td class="p-2 border text-center"><?= $lib['id'] ?></td>
            <td class="p-2 border"><?= htmlspecialchars($lib['titulo']) ?></td>
            <td class="p-2 border"><?= htmlspecialchars($lib['autor']) ?></td>
            <td class="p-2 border"><?= htmlspecialchars($lib['isbn']) ?></td>
            <td class="p-2 border text-center"><?= $lib['ano_publicacion'] ?></td>
            <td class="p-2 border text-center">
              <?php
                if ($lib['disponible'] == 1) echo '<span class="text-green-600 font-semibold">Disponible</span>';
                elseif ($lib['disponible'] == 2) echo '<span class="text-yellow-500 font-semibold">Prestado</span>';
                else echo '<span class="text-gray-500 italic">Baja</span>';
              ?>
            </td>
            <td class="p-2 border text-center space-x-2">
              <?php if ($currentUser && $currentUser['rol'] === 'bibliotecario'): ?>
                <a href="index.php?action=editar&id=<?= $lib['id'] ?>"
                   class="text-blue-600 hover:underline">✏️</a>
                <a href="index.php?action=eliminar&id=<?= $lib['id'] ?>"
                   onclick="return confirm('¿Eliminar este libro?')"
                   class="text-red-600 hover:underline">🗑️</a>
              <?php endif; ?>

              <?php if ($currentUser): ?>
                <?php if ($lib['disponible'] == 1): ?>
                  <a href="index.php?action=obtener&id=<?= $lib['id'] ?>"
                     class="text-green-600 hover:underline font-medium">Obtener</a>
                <?php elseif ($lib['disponible'] == 2): ?>
                  <a href="index.php?action=reservar&id=<?= $lib['id'] ?>"
                     class="text-indigo-600 hover:underline font-medium">Reservar</a>
                <?php endif; ?>

                <?php
                  $queue = $reservaModel->getQueue($lib['id']);
                  foreach ($queue as $pos => $r) {
                    if ($r['usuario_id'] === $currentUser['id']) {
                      echo '<div class="text-sm text-gray-500 italic">En cola (posición: ' . ($pos+1) . ')</div>';
                      break;
                    }
                  }
                ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr>
            <td colspan="7" class="p-4 text-center text-gray-600 italic">
              No hay libros registrados.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

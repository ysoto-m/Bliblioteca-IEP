<?php global $currentUser; ?>
<div class="max-w-4xl mx-auto p-6 bg-white shadow mt-4 rounded">
  <h2 class="text-2xl mb-4">Préstamos Activos</h2>

  <table class="w-full table-auto border-collapse">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2 border">ID</th>
        <th class="p-2 border">Libro</th>
        <th class="p-2 border">Usuario</th>
        <th class="p-2 border">Fecha Préstamo</th>
        <th class="p-2 border">Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($prestamos)): foreach ($prestamos as $p): ?>
        <tr class="hover:bg-gray-100">
          <td class="p-2 border"><?= $p['id'] ?></td>
          <td class="p-2 border"><?= htmlspecialchars($p['titulo']) ?></td>
          <td class="p-2 border"><?= htmlspecialchars($p['usuario']) ?></td>
          <td class="p-2 border"><?= $p['fecha_prestamo'] ?></td>
          <td class="p-2 border">
            <a href="index.php?action=devolver&id=<?= $p['id'] ?>"
               class="text-blue-600 hover:underline">Devolver</a>
          </td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="5" class="p-4 text-center">No hay préstamos activos.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

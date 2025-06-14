<?php global $prestamos; ?>
<div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-8">
  <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 text-blue-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v3a2 2 0 002 2m6 0v5a2 2 0 11-4 0v-5" />
    </svg>
    Mis Préstamos
  </h2>

  <?php if (empty($prestamos)): ?>
    <p class="text-gray-500">No tienes préstamos activos por ahora.</p>
  <?php else: ?>
    <table class="w-full table-auto border-collapse">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-3 border-b text-left">📘 Título</th>
          <th class="p-3 border-b text-left">📅 Fecha Préstamo</th>
          <th class="p-3 border-b text-left">📆 Vence</th>
          <th class="p-3 border-b text-left">✅ Devuelto</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($prestamos as $p): ?>
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-3 border-b"><?= htmlspecialchars($p['titulo']) ?></td>
            <td class="p-3 border-b"><?= $p['fecha_prestamo'] ?></td>
            <td class="p-3 border-b"><?= $p['due_date'] ?></td>
            <td class="p-3 border-b">
              <span class="inline-block px-2 py-1 rounded text-sm font-semibold
                <?= $p['devuelto'] ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                <?= $p['devuelto'] ? 'Sí' : 'No' ?>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

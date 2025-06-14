<?php global $reservas; ?>
<div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-8">
  <h2 class="text-2xl font-bold mb-4 flex items-center gap-2 text-pink-600">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 00-8 0v4H5a2 2 0 00-2 2v7h18v-7a2 2 0 00-2-2h-3V7z" />
    </svg>
    Mis Reservas
  </h2>

  <?php if (empty($reservas)): ?>
    <p class="text-gray-500">No tienes reservas activas por ahora.</p>
  <?php else: ?>
    <table class="w-full table-auto border-collapse">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-3 border-b text-left">📖 Título</th>
          <th class="p-3 border-b text-left">🗓 Fecha Reserva</th>
          <th class="p-3 border-b text-left">⚙️ Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservas as $r): ?>
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-3 border-b"><?= htmlspecialchars($r['titulo']) ?></td>
            <td class="p-3 border-b"><?= $r['fecha_reserva'] ?></td>
            <td class="p-3 border-b">
              <a href="index.php?action=cancelarReserva&id=<?= $r['id'] ?>"
                 onclick="return confirm('¿Estás seguro de cancelar esta reserva?')"
                 class="inline-block text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition">
                 Cancelar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

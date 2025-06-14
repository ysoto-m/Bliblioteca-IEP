<?php
  // views/notifs.php
  global $notifs;
?>
<div class="max-w-4xl mx-auto p-6 bg-white shadow mt-6 rounded">
  <h2 class="text-3xl font-bold mb-6 flex items-center gap-2">
    🔔 Notificaciones
  </h2>

  <?php if (empty($notifs)): ?>
    <div class="bg-gray-50 border border-gray-300 text-gray-600 p-4 rounded">
      No tienes notificaciones por ahora.
    </div>
  <?php else: ?>
    <ul class="space-y-3">
      <?php foreach ($notifs as $n): ?>
        <li class="p-4 rounded border flex justify-between items-start gap-4
                   <?= $n['leido'] ? 'bg-gray-50 border-gray-200' : 'bg-yellow-50 border-yellow-300' ?>">
          <div>
            <p class="text-sm"><?= htmlspecialchars($n['mensaje']) ?></p>
            <p class="text-xs text-gray-500 mt-1"><?= $n['creado_en'] ?></p>
          </div>

          <?php if (!$n['leido']): ?>
            <a href="index.php?action=marcarLeido&id=<?= $n['id'] ?>"
               class="text-sm text-blue-600 hover:underline mt-1">Marcar como leído</a>
          <?php else: ?>
            <span class="text-xs text-green-600 mt-1">✔ Leído</span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>


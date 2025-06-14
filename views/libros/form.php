<div class="max-w-4xl mx-auto mt-10 bg-white p-6 shadow rounded-lg">
  <?php 
    $isEdit = ($_GET['action'] ?? '') === 'editar';
    $actionUrl = $isEdit
      ? "index.php?action=editar&id=" . intval($_GET['id'])
      : "index.php?action=crear";
  ?>

  <h2 class="text-2xl font-bold mb-6 text-blue-700 flex items-center gap-2">
    <?= $isEdit ? '✏️ Editar libro' : '➕ Agregar nuevo libro' ?>
  </h2>

  <?php if (!empty($_GET['msg'])): ?>
    <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 mb-5 rounded">
      <?= $_GET['msg'] === 'actualizado' ? '✅ Libro actualizado correctamente.' : '✅ Libro agregado con éxito.' ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <ul class="bg-red-100 border border-red-500 text-red-800 px-4 py-3 mb-5 rounded space-y-1">
      <?php foreach ($errors as $e): ?>
        <li>⚠️ <?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post" action="<?= $actionUrl ?>" class="space-y-5">

    <div>
      <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
      <input type="text" id="titulo" name="titulo"
             value="<?= htmlspecialchars($data['titulo'] ?? '') ?>"
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
    </div>

    <div>
      <label for="autor" class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
      <input type="text" id="autor" name="autor"
             value="<?= htmlspecialchars($data['autor'] ?? '') ?>"
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
    </div>

    <div>
      <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
      <input type="text" id="isbn" name="isbn"
             value="<?= htmlspecialchars($data['isbn'] ?? '') ?>"
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
    </div>

    <div>
      <label for="ano_publicacion" class="block text-sm font-medium text-gray-700 mb-1">Año de Publicación</label>
      <input type="number" id="ano_publicacion" name="ano_publicacion"
             min="1000" max="<?= date('Y') ?>"
             value="<?= htmlspecialchars($data['ano_publicacion'] ?? '') ?>"
             class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
    </div>

    <div class="flex items-center space-x-2">
      <input type="checkbox" id="disponible" name="disponible" value="1"
             <?= !empty($data['disponible']) ? 'checked' : '' ?>
             class="h-4 w-4 text-blue-600 border-gray-300 rounded">
      <label for="disponible" class="text-gray-700">Disponible</label>
    </div>

    <div class="flex space-x-4">
      <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded shadow transition">
        <?= $isEdit ? 'Actualizar' : 'Guardar' ?>
      </button>
      <a href="index.php?action=mantenimiento"
         class="px-5 py-2 border rounded text-gray-700 hover:bg-gray-100 transition">Cancelar</a>
    </div>
  </form>
</div>

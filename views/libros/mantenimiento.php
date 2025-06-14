<h2 class="text-3xl font-bold mb-6 text-blue-700">🛠 Panel de Mantenimiento de Libros</h2>

<?php if (!empty($_GET['msg'])): ?>
  <div class="mb-6 p-4 rounded bg-green-100 border border-green-500 text-green-800 shadow">
    <?php
      switch ($_GET['msg']) {
        case 'creado': echo '📗 Libro agregado exitosamente.'; break;
        case 'actualizado': echo '📘 Libro actualizado correctamente.'; break;
        case 'eliminado': echo '📕 Libro eliminado con éxito.'; break;
      }
    ?>
  </div>
<?php endif; ?>

<div class="grid md:grid-cols-2 gap-10">

  <!-- 📚 Lista de libros -->
  <div>
    <h3 class="text-xl font-semibold mb-4 text-gray-800">📚 Libros registrados</h3>
    <div class="overflow-auto border rounded shadow-sm">
      <table class="min-w-full bg-white divide-y divide-gray-200">
        <thead class="bg-gray-100 text-sm font-semibold text-gray-700">
          <tr>
            <th class="px-4 py-2">Título</th>
            <th class="px-4 py-2">Autor</th>
            <th class="px-4 py-2">Estado</th>
            <th class="px-4 py-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="text-sm text-gray-800">
          <?php foreach ($libros as $libro): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="px-4 py-2"><?= htmlspecialchars($libro['titulo']) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($libro['autor']) ?></td>
              <td class="px-4 py-2">
                <?php
                  $estado = 'Disponible';
                  if (!$libro['disponible']) {
                    $estado = $this->model->verificarBaja($libro['id']) ? 'Dado de baja' : 'Prestado';
                  }
                  echo $estado;
                ?>
              </td>
              <td class="px-4 py-2 text-center space-x-2">
                <a href="index.php?action=editar&id=<?= $libro['id'] ?>"
                   class="text-blue-600 hover:underline">✏️</a>
                <button onclick="abrirModal(<?= $libro['id'] ?>, '<?= htmlspecialchars(addslashes($libro['titulo'])) ?>')"
                        class="text-red-600 hover:underline">🗑️</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ➕ Formulario agregar libro -->
  <div>
    <h3 class="text-xl font-semibold mb-4 text-gray-800">➕ Agregar nuevo libro</h3>
    <form method="post" action="index.php?action=crear"
          class="space-y-5 bg-white p-6 border shadow rounded">
      <div>
        <label class="block mb-1 font-medium text-gray-700">Título</label>
        <input type="text" name="titulo" required
               class="w-full border rounded px-3 py-2 focus:ring-blue-500"
               value="<?= htmlspecialchars($data['titulo']) ?>">
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Autor</label>
        <input type="text" name="autor" required
               class="w-full border rounded px-3 py-2"
               value="<?= htmlspecialchars($data['autor']) ?>">
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">ISBN</label>
        <input type="text" name="isbn" required
               class="w-full border rounded px-3 py-2"
               value="<?= htmlspecialchars($data['isbn']) ?>">
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Año de publicación</label>
        <input type="number" name="ano_publicacion" min="1000" max="<?= date('Y') ?>" required
               class="w-full border rounded px-3 py-2"
               value="<?= htmlspecialchars($data['ano_publicacion']) ?>">
      </div>
      <div>
        <label class="inline-flex items-center">
          <input type="checkbox" name="disponible" class="mr-2"
                 <?= !empty($data['disponible']) ? 'checked' : '' ?>>
          <span class="text-gray-700">Disponible para préstamo</span>
        </label>
      </div>
      <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
        Guardar libro
      </button>
    </form>
  </div>
</div>

<!-- Modal de confirmación -->
<div id="modalEliminar" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white p-6 rounded shadow-lg w-full max-w-md relative animate-fadeIn">
    <h2 class="text-xl font-bold mb-4">❌ ¿Eliminar libro?</h2>
    <p class="mb-6 text-gray-700" id="modalTexto"></p>
    <div class="flex justify-end space-x-3">
      <button onclick="cerrarModal()" class="px-4 py-2 border rounded hover:bg-gray-100">Cancelar</button>
      <a id="btnConfirmarEliminar" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Sí, eliminar</a>
    </div>
    <button onclick="cerrarModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
  </div>
</div>

<script>
  function abrirModal(id, titulo) {
    document.getElementById('modalTexto').textContent =
      `¿Estás seguro de que deseas eliminar el libro «${titulo}»?`;
    document.getElementById('btnConfirmarEliminar').href =
      `index.php?action=eliminar&id=${id}`;
    document.getElementById('modalEliminar').classList.remove('hidden');
    document.getElementById('modalEliminar').classList.add('flex');
  }

  function cerrarModal() {
    document.getElementById('modalEliminar').classList.remove('flex');
    document.getElementById('modalEliminar').classList.add('hidden');
  }

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModal();
  });

  document.getElementById('modalEliminar').addEventListener('click', e => {
    if (e.target.id === 'modalEliminar') cerrarModal();
  });
</script>

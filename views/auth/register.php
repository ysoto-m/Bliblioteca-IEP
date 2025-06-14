<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrarse — Biblioteca</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="w-full max-w-md p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-semibold mb-4 text-center">Crear cuenta</h2>

    <?php if (!empty($errors)): ?>
      <ul class="mb-4 p-3 bg-red-50 border border-red-500 text-red-700 rounded space-y-1">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form method="post" action="index.php?action=register" class="space-y-4">
      <div>
        <label class="block mb-1">Nombre</label>
        <input
          type="text"
          name="nombre"
          value="<?= htmlspecialchars($data['nombre'] ?? '') ?>"
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      <div>
        <label class="block mb-1">Email</label>
        <input
          type="email"
          name="email"
          value="<?= htmlspecialchars($data['email'] ?? '') ?>"
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      <div>
        <label class="block mb-1">Contraseña</label>
        <input
          type="password"
          name="password"
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      <div>
        <label class="block mb-1">Repetir contraseña</label>
        <input
          type="password"
          name="password2"
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      <button
        type="submit"
        class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded"
      >
        Registrarme
      </button>
    </form>

    <p class="mt-4 text-center text-sm">
      ¿Ya tienes cuenta?
      <a
        href="index.php?action=login"
        class="text-blue-600 hover:underline"
      >Inicia sesión aquí</a>.
    </p>
  </div>

</body>
</html>

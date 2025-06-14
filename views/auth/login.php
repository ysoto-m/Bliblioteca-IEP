<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión — Biblioteca</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="w-full max-w-md p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-semibold mb-4 text-center">Iniciar sesión</h2>

    <?php if (! empty($error)): ?>
      <div class="mb-4 p-3 bg-red-50 border border-red-500 text-red-700 rounded">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php if (! empty($_GET['msg']) && $_GET['msg'] === 'registered'): ?>
      <div class="mb-4 p-3 bg-green-50 border border-green-500 text-green-700 rounded">
        Cuenta creada con éxito. Por favor, inicia sesión.
      </div>
    <?php endif; ?>

    <form method="post" action="index.php?action=login" class="space-y-4">
      <div>
        <label class="block mb-1">Email</label>
        <input
          type="email"
          name="email"
          value="<?= htmlspecialchars($data['email'] ?? '') ?>"
          required
          class="w-full border rounded px-3 py-2"
        />
      </div>
      <div>
        <label class="block mb-1">Contraseña</label>
        <input
          type="password"
          name="password"
          required
          class="w-full border rounded px-3 py-2"
        />
      </div>
      <button
        type="submit"
        class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
      >
        Entrar
      </button>
    </form>

    <div class="mt-4 flex justify-between text-sm">
      <a
        href="index.php?action=register"
        class="text-green-600 hover:underline"
      >Regístrate</a>

      <!-- Enlace a la recuperación de contraseña -->
      <a
        href="index.php?action=forgot"
        class="text-red-600 hover:underline"
      >¿Olvidaste tu contraseña?</a>
    </div>
  </div>

</body>
</html>

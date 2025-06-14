<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="w-full max-w-md p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-semibold mb-4 text-center">Nueva contraseña</h2>
    <?php if ($error): ?><div class="mb-4 text-red-600"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <?php if ($success): ?><div class="mb-4 text-green-600"><?= $success ?></div><?php endif; ?>
    <?php if (!$success): ?>
    <form method="post" action="index.php?action=reset&email=<?=urlencode($email)?>&token=<?=urlencode($token)?>" class="space-y-4">
      <div><label>Contraseña</label><input type="password" name="password" required class="w-full border px-3 py-2" /></div>
      <div><label>Repetir contraseña</label><input type="password" name="password2" required class="w-full border px-3 py-2" /></div>
      <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">Actualizar</button>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
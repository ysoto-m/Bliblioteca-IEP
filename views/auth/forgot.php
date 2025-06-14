<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Olvidé mi contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="w-full max-w-md p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-semibold mb-4 text-center">Recuperar contraseña</h2>
    <?php if ($error): ?><div class="mb-4 text-red-600"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post" action="index.php?action=forgot" class="space-y-4">
      <div><label>Email</label><input type="email" name="email" required class="w-full border px-3 py-2" /></div>
      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Enviar enlace</button>
    </form>
  </div>
</body>
</html>
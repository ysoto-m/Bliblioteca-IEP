<?php
// config.php — Conexión a MySQL con PDO y soporte para .env

// Cargar variables de entorno desde .env si existe
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

$host    = $_ENV['DB_HOST']     ?? 'localhost';
$db      = $_ENV['DB_DATABASE'] ?? 'biblioteca';
$user    = $_ENV['DB_USERNAME'] ?? 'root';
$pass    = $_ENV['DB_PASSWORD'] ?? '';
$charset = $_ENV['DB_CHARSET']  ?? 'utf8mb4';
$env     = $_ENV['APP_ENV']     ?? 'production';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    if ($env === 'development') {
        echo "Error de conexión a la base de datos: " . htmlspecialchars($e->getMessage());
    } else {
        echo "Error de conexión. Intenta más tarde.";
    }
    exit;
}

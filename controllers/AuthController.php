<?php
// controllers/AuthController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PasswordReset.php';

class AuthController
{
    private PDO $pdo;
    private User $userModel;
    private PasswordReset $prModel;
    private array $mailConfig;

    public function __construct(PDO $pdo)
    {
        $this->pdo        = $pdo;
        $this->userModel  = new User($pdo);
        $this->prModel    = new PasswordReset($pdo);
        $this->mailConfig = require __DIR__ . '/../config/mail.php';
    }

    public function login(): void
    {
        $error = '';
        $data  = ['email' => '', 'password' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['email']    = trim($_POST['email'] ?? '');
            $data['password'] = $_POST['password'] ?? '';

            if ($data['email'] !== '' && $data['password'] !== '') {
                $foundUser = $this->userModel->findByEmail($data['email']);

                if ($foundUser && password_verify($data['password'], $foundUser['contraseña'])) {

                    $_SESSION['user'] = [
                        'id'   => $foundUser['id'],
                        'name' => $foundUser['nombre'],
                        'rol'  => $foundUser['rol'],
                    ];
                    header('Location: index.php?action=list');
                    exit;
                }

                $error = 'Email o contraseña incorrectos.';
            } else {
                $error = 'Email y contraseña son obligatorios.';
            }
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    public function register(): void
    {
        $errors = [];
        $data   = ['nombre' => '', 'email' => '', 'password' => '', 'password2' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['nombre']    = trim($_POST['nombre'] ?? '');
            $data['email']     = trim($_POST['email'] ?? '');
            $data['password']  = $_POST['password'] ?? '';
            $data['password2'] = $_POST['password2'] ?? '';

            if ($data['nombre'] === '') {
                $errors[] = 'El nombre es obligatorio.';
            }
            if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido.';
            } elseif ($this->userModel->findByEmail($data['email'])) {
                $errors[] = 'Este email ya está registrado.';
            }
            if (strlen($data['password']) < 6) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
            }
            if ($data['password'] !== $data['password2']) {
                $errors[] = 'Las contraseñas no coinciden.';
            }

            if (empty($errors)) {
                $this->userModel->create(
                    $data['nombre'],
                    $data['email'],
                    $data['password']
                );
                header('Location: index.php?action=login&msg=registered');
                exit;
            }
        }

        include __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Formulario olvide pass
     */
    public function forgot(): void
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            // Validar email y existencia
            if (filter_var($email, FILTER_VALIDATE_EMAIL)
                && $this->userModel->findByEmail($email))
            {
                // Generar token aleatorio
                $token = bin2hex(random_bytes(16));
                // Guardar hash del token
                $this->prModel->create($email, $token);

                // Construir enlace de reset
                $appUrl = rtrim(getenv('APP_URL'), '/');
                $link = "{$appUrl}/index.php?action=reset&email="
                    . urlencode($email) . "&token=" . urlencode($token);

                // Enviar correo 
                $headers = "From: {$this->mailConfig['from_name']} <{$this->mailConfig['from_email']}>";
                mail(
                    $email,
                    'Restablecer contraseña',
                    "Para restablecer tu contraseña, haz click aquí:\n\n{$link}\n\nEste enlace expira en 1 hora.",
                    $headers
                );

                include __DIR__ . '/../views/auth/forgot_sent.php';
                return;
            }

            $error = 'Email no válido o no registrado.';
        }

        include __DIR__ . '/../views/auth/forgot.php';
    }

    /**
     * Formulario de reset de contraseña y actualización
     */
    public function reset(): void
    {
        $error   = '';
        $success = '';
        $email   = $_GET['email'] ?? '';
        $token   = $_GET['token'] ?? '';

        // Verificar token y caducidad (1 hora)
        if (! $this->prModel->verify($email, $token)) {
            echo 'Enlace inválido o expirado.';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pass1 = $_POST['password']  ?? '';
            $pass2 = $_POST['password2'] ?? '';

            if (strlen($pass1) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } elseif ($pass1 !== $pass2) {
                $error = 'Las contraseñas no coinciden.';
            } else {
                // Actualizar contraseña del usuario
                $stmt = $this->pdo->prepare(
                    'UPDATE usuario SET contraseña = ? WHERE email = ?'
                );
                $stmt->execute([
                    password_hash($pass1, PASSWORD_DEFAULT),
                    $email
                ]);
                $this->prModel->clear($email);

                $success = 'Contraseña actualizada. <a href="index.php?action=login">Entrar</a>';
            }
        }

        include __DIR__ . '/../views/auth/reset.php';
    }
}

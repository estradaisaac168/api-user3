<?php

namespace App\Middleware;

class AuthWebMiddleware
{
    public static function handle(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['errors']['auth'] = 'Debes iniciar sesión';
            header('Location: /login');
            exit;
        }
    }
}

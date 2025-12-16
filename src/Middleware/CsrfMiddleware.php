<?php

namespace App\Middleware;

use App\Core\Response;

class CsrfMiddleware
{
    public static function handle(): void
    {
        // Solo proteger métodos que modifican estado
        if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return;
        }

        // Token enviado (POST o headers para API)
        $token = $_POST['_token']
            ?? $_SERVER['HTTP_X_CSRF_TOKEN']
            ?? null;

        // Token de sesión
        $sessionToken = $_SESSION['_csrf_token'] ?? null;

        // Validación
        if (
            empty($token) ||
            empty($sessionToken) ||
            !hash_equals($sessionToken, $token)
        ) {
            self::handleFailure();
        }

        // Opcional: regenerar token después de un request válido
        unset($_SESSION['_csrf_token']);
    }

    private static function handleFailure(): void
    {
        http_response_code(419);

        // Detectar si espera JSON
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $isJson = str_contains($accept, 'application/json');

        if ($isJson) {
            Response::json([
                'status'  => 'error',
                'message' => 'CSRF token mismatch'
            ], 419);
        }

        // HTML (formularios)
        $_SESSION['errors']['csrf'] = 'Sesión expirada. Por favor intenta nuevamente.';
        $_SESSION['old'] = $_POST;

        $redirect = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $redirect");
        exit;
    }
}


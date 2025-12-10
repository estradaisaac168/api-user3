<?php

namespace App\Middleware;

use App\Controllers\BaseController;
use App\Helpers\ResponseHelper;
use App\Services\JWTService;

class AuthMiddleware
{
    public function handle()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$auth) {
            ResponseHelper::error('Token no proporcionado', 401);
        }

        // Esperamos: "Bearer <token>"
        if (!preg_match('/Bearer\s(\S+)/', $auth,  $matches)) {
            ResponseHelper::error('Formato de token inválido', 401);
        }

        $token = $matches[1];

        try {
            $decoded = JWTService::validateToken($token);
            // Puedes exponer datos del usuario (claims) en una variable global
            // Ej: $_REQUEST['auth_user'] = $decoded;
            // O usar una clase App container - aquí uso $_REQUEST por simplicidad:
            $_REQUEST['auth_user'] = $decoded;
        } catch (\Exception $e) {
            ResponseHelper::error('Token inválido o expirado', 401, ['detail' => $e->getMessage()]);
        }
    }
}

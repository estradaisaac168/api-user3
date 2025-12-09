<?php

namespace App\Middleware;

use App\Controllers\BaseController;
use App\Services\JWTService;

class AuthMiddleware
{
    public function handle()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$auth) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Token no proporcionado']);
            exit;
        }




        // Esperamos: "Bearer <token>"
        if (!preg_match('/Bearer\s(\S+)/', $auth,  $matches)) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Formato de token inválido']);
            exit;
        }

        $token = $matches[1];

        try {
            $decoded = JWTService::validateToken($token);
            // Puedes exponer datos del usuario (claims) en una variable global
            // Ej: $_REQUEST['auth_user'] = $decoded;
            // O usar una clase App container - aquí uso $_REQUEST por simplicidad:
            $_REQUEST['auth_user'] = $decoded;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Token inválido o expirado', 'detail' => $e->getMessage()]);
            exit;
        }
    }
}

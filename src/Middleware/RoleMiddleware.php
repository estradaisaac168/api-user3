<?php

namespace App\Middleware;

class RoleMiddleware
{
    public static function requireRole($roles = [])
    {
        $auth = $_REQUEST['auth_user'] ?? null;

        if (!$auth) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            exit;
        }

        if (!in_array($auth->role_id, $roles)) {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            exit;
        }
    }
}

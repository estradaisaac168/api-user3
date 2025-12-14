<?php

namespace App\Middleware;

use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Response;

class RoleMiddleware
{
    public static function requireRole($roles = [])
    {
        $auth = $_REQUEST['auth_user'] ?? null;

        if (!$auth) {
            Response::error('No autenticado', 401);
        }

        if (!in_array($auth->role_id, $roles)) {
            Response::error('Acceso denegado', 403);
        }
    }
}

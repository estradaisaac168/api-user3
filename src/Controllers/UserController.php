<?php

namespace App\Controllers;

use App\Middleware\RoleMiddleware;
use App\Models\User;
use Respect\Validation\Validator as v;
use App\Interfaces\ICrudController;

class UserController extends BaseController implements ICrudController
{
    /**
     * GET /users
     */
    public function index()
    {
        RoleMiddleware::requireRole([2]);

        $users = User::all();
        return $this->success($users, "Lista de usuarios");
    }

    /**
     * GET /users/{id}
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error("Usuario no encontrado", 404);
        }

        return $this->success($user, "Usuario encontrado");
    }

    /**
     * POST /users
     */
    public function store()
    {
        $input = $this->jsonInput();

        // Validación
        $this->validateFromApi([
            'name'  => v::stringType()->length(3, 50)->notEmpty(),
            'email' => v::email()->notEmpty(),
            'password' => v::stringType()->length(6, 100)->notEmpty()
        ], $input);

        // Crear usuario
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => password_hash($input['password'], PASSWORD_BCRYPT)
        ]);

        return $this->success($user, "Usuario creado", 201);
    }

    /**
     * PUT /users/{id}
     */
    public function update($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error("Usuario no encontrado", 404);
        }

        $input = $this->jsonInput();

        // Validar parcialmente
        $this->validateFromHTML([
            'name'  => v::optional(v::stringType()->length(3, 50)),
            'email' => v::optional(v::email()),
        ], $input);

        // Update seguro
        if (isset($input['name'])) $user->name = $input['name'];
        if (isset($input['email'])) $user->email = $input['email'];
        if (isset($input['password'])) {
            $user->password = password_hash($input['password'], PASSWORD_BCRYPT);
        }

        $user->save();

        return $this->success($user, "Usuario actualizado");
    }

    /**
     * DELETE /users/{id}
     */
    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error("Usuario no encontrado", 404);
        }

        $user->delete();

        return $this->success([], "Usuario eliminado");
    }
}

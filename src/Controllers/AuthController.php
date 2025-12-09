<?php

namespace App\Controllers;

use Models\User;
use App\Services\JWTService;
use Respect\Validation\Validator as v;

class AuthController extends BaseController
{
  public function register()
  {
    $input = $this->jsonInput();

    $this->validate([
      'username' => v::stringType()->length(3, 10)->notEmpty(),
      'email' => v::email()->notEmpty(),
      'password' => v::stringType()->length(6, 100)->notEmpty(),
    ], $input);

    // Check unique email
    if (User::where('email', $input['email'])->exists()) {
      return $this->error("El email ya está en uso", 409);
    }

    $user = User::create([
      'username' => $input['username'],
      'email' => $input['email'],
      'password' => password_hash($input['password'], PASSWORD_BCRYPT),
      'role_id' => 2, // rol por defecto
      'status' => 'active'
    ]);

    // No devuelvas password en la respuesta
    unset($user->password);

    return $this->success($user, "Usuario creado", 201);
  }

  public function login()
  {
    $input = $this->jsonInput();

    $this->validate([
      'email' => v::email()->notEmpty(),
      'password' => v::stringType()->notEmpty()
    ], $input);

    $user = User::where('email', $input['email'])->first();

    if (!$user || !password_verify($input['password'], $user->password)) {
      return $this->error("Credenciales incorrectas", 401);
    }

    // payload mínimo (puedes añadir roles, id, etc.)
    $payload = [
      'sub' => $user->id,
      'email' => $user->email,
      'username' => $user->name,
      'role_id' => $user->role_id,
    ];

    $token = JWTService::generateToken($payload);

    return $this->success([
      'token' => $token,
      'token_type' => 'Bearer',
      'expires_in' => (int)($_ENV['JWT_EXPIRES_IN'] ?? 3600)
    ], "Autenticado");
  }
}

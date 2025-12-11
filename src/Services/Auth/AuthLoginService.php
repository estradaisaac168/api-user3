<?php

namespace App\Services\Auth;


use Models\User;
use App\Repositories\User\UserRepository;
use App\Services\JWTService;

class AuthLoginService{
    public function __construct(private UserRepository $repository)
  {
  }

  public function loginUser($input):array
  {
    $user = User::where('email', $input['email'])->first();

    if (!$user) {
      throw new \Exception('No existe un usuario con ese email', 404);
    }

    if (!$user->is_verified) {
      throw new \Exception("El email no ha sido verificado", 401);
    }

    if (!password_verify($input['password'], $user->password)) {
      throw new \Exception('Contraseña incorrecta', 401);
    }

    // payload mínimo
    $payload = [
      'sub' => $user->id,
      'email' => $user->email,
      'username' => $user->username,
      'role_id' => $user->role_id,
    ];

    $token = JWTService::generateToken($payload);

    $token = [
      'token' => $token,
      'token_type' => 'Bearer',
      'expires_in' => (int) ($_ENV['JWT_EXPIRES_IN'] ?? 3600)
    ];

    $user = [
      'email' => $user->email,
      'password' => $user->password
    ];

    return ["token" => $token, "user" => $user];

    // return [
    //   'token' => $token,
    //   'token_type' => 'Bearer',
    //   'expires_in' => (int) ($_ENV['JWT_EXPIRES_IN'] ?? 3600)
    // ];
  }
}
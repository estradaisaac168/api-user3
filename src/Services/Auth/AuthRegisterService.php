<?php

namespace App\Services\Auth;

use Illuminate\Database\Capsule\Manager as Capsule;
use Models\User;
use App\Repositories\User\UserRepository;
use App\Services\JWTService;

class AuthRegisterService
{

  public function __construct(private UserRepository $repository)
  {
  }


  public function registerUser(array $data): User
  {

    // Validación: email único
    if ($this->repository->existEmail($data["email"])) {
      throw new \Exception("El email ya está en uso", 400);
    }

    //No existe email, crear usuario
    $temporaryToken = bin2hex(random_bytes(32));

    $data["password"] = password_hash($data['password'], PASSWORD_BCRYPT);
    $data['role_id'] = 2;
    $data['verification_token'] = $temporaryToken;
    $data['verification_expires'] = date('Y-m-d H:i:s', strtotime('+1 day'));

    $user = Capsule::connection()->transaction(function () use ($data) {
      return $this->repository->create($data);
    });

    if (!sendEmail($user, $temporaryToken)) {
      throw new \Exception("No se pudo enviar el correo de verificación");
    }

    return $user;

    // return Capsule::connection()->transaction(function () use ($data) {

    //   $user = $this->repository->create($data);

    //   if (!$user) {
    //     throw new \Exception('No se pudo registrar el usuario');
    //   }

    //   // Crear usuario
    //   // $user = User::create([
    //   //   'username' => $data['username'],
    //   //   'email' => $data['email'],
    //   //   'role_id' => 2, // Rol por defecto
    //   //   'verification_token' => $temporaryToken,
    //   //   'verification_expires' => date('Y-m-d H:i:s', strtotime('+1 day'))
    //   // ]);


    //   if (!sendEmail($user, $temporaryToken)) {
    //     throw new \Exception("No se pudo enviar el correo de verificación");
    //   }

    //   return $user;
    // });
  }
}

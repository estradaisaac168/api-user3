<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepository;
use Models\User;

class EmailVerificationService
{

  public function __construct(private UserRepository $repository){}

  public function verifyEmail(string $token): ?User
  {
    //Verificacion del token
    // $user = User::where("verification_token", $token)->first();

    $user = $this->repository->verifyToken($token);

        //Usuario existe con ese token?
    if (!$user) {
      throw new \Exception("Token inválido", 400);
    }

    if ($user->is_verified) {
      throw new \Exception("El email ya ha sido verificado", 400);
    }

    //Token vigente?
    // if (strtotime($user->verification_expires) < time()) {
    //   throw new \Exception("Token expirado", 400);
    // }

    //Si todo salio bien
    $user->is_verified = 1;
    $user->verification_token = null;
    $user->verification_expires = null;
    // $user->save();
    $this->repository->updateVerification($user);

    return $user;
  }
}

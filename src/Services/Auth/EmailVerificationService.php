<?php

namespace App\Services\Auth;

use Models\User;

class EmailVerificationService
{
  public function verifyEmail(string $token): bool
  {
    //Verificacion del token
    $user = User::where("verification_token", $token)->first();

    if ($user->is_verified) {
      throw new \Exception("El email ya ha sido verificado", 400);
    }

    //Usuario existe con ese token?
    if (!$user) {
      throw new \Exception("Token inválido", 400);
    }

    //Token vigente?
    if (strtotime($user->verification_expires) < time()) {
      throw new \Exception("Token expirado", 400);
    }

    //Si todo salio bien
    $user->is_verified = 1;
    $user->verification_token = null;
    $user->verification_expires = null;
    $user->save();

    return true;
  }
}

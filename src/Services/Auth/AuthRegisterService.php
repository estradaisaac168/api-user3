<?php

namespace App\Services\Auth;

use Illuminate\Database\Capsule\Manager as Capsule;
use Models\User;
use App\Helpers\Mail;

class AuthRegisterService
{

  public function registerUser($data): User
  {
    return Capsule::connection()->transaction(function () use ($data) {

      $temporaryToken = bin2hex(random_bytes(32));

      // Validación: email único
      if (User::where('email', $data['email'])->exists()) {
        throw new \Exception("El email ya está en uso");
      }

      // Crear usuario
      $user = User::create([
        'username' => $data['username'],
        'email'    => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        'role_id'  => 2, // Rol por defecto
        'verification_token' => $temporaryToken,
        'verification_expires' => date('Y-m-d H:i:s', strtotime('+1 day'))
      ]);


      if (!sendEmail($user, $temporaryToken)) {
        throw new \Exception("No se pudo enviar el correo de verificación");
      }

      // Enviar email
      // $mail = new Mail();
      // $subject = "Verificación de email";
      // $verificationLink = "http://localhost:8000/auth/verify?token=$temporaryToken";

      // $body = "<h1>Verifica tu correo</h1>
      //            <p>Haz clic en el siguiente enlace para activar tu cuenta:</p>
      //            <a href='$verificationLink'>$verificationLink</a>";

      // if (!$mail->send($user->email, $subject, $body)) {
      //   throw new \Exception("No se pudo enviar el correo de verificación");
      // }

      return $user;
    });
  }
}

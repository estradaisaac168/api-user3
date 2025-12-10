<?php

use Models\User;
use App\Helpers\Mail;


function sendEmail(User $user, string $temporaryToken): bool
{
  $mail = new Mail();

  $subject = "Verificación de email";
  $verificationLink = "http://localhost:8000/auth/verify?token=" . urlencode($temporaryToken);

  $body = "
        <h1>Verifica tu correo</h1>
        <p>Haz clic en el siguiente enlace para activar tu cuenta:</p>
        <a href='$verificationLink'>$verificationLink</a>
    ";

  return $mail->send($user->email, $subject, $body);
}

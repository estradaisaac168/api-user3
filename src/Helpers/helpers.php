<?php

use App\Models\User;
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


if (!function_exists('e')) {
  function e(string $value): string
  {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  }
}



if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return '<input type="hidden" name="_token" value="' .
               htmlspecialchars($_SESSION['_csrf_token'], ENT_QUOTES, 'UTF-8') .
               '">';
    }
}


// unset($_SESSION['_csrf_token']);


function old(string $key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}


function error(string $key)
{
    return $_SESSION['errors'][$key] ?? null;
}



if (!function_exists('flash')) {
    function flash(string $key, $default = null)
    {
        if (!isset($_SESSION[$key])) {
            return $default;
        }

        $value = $_SESSION[$key];
        unset($_SESSION[$key]);

        return $value;
    }
}



function redirect(string $url)
{
    header("Location: $url");
    exit;
}

function redirectWith(string $url, array $flash = [])
{
    foreach ($flash as $key => $value) {
        $_SESSION[$key] = $value;
    }

    redirect($url);
}




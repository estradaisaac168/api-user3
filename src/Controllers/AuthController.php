<?php

namespace App\Controllers;

use Models\User;
use App\Services\JWTService;
use Respect\Validation\Rules\Time;
use Respect\Validation\Validator as v;
use App\Helpers\Mail;

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

    //Generar token temporal para validar el email del usuario
    $temporaryToken = bin2hex(random_bytes(32));

    $user = User::create([
      'username' => $input['username'],
      'email' => $input['email'],
      'password' => password_hash($input['password'], PASSWORD_BCRYPT),
      'is_verified' => 0,
      'verification_token' => $temporaryToken,
      'verification_expires' => date('Y-m-d H:i:s', time() + 3600), //1 hora
      'role_id' => 2, // rol por defecto
      'status' => 'active'
    ]);

    // No devuelvas password en la respuesta
    // unset($user->password);

    // return $this->success($user, "Usuario creado", 201);

    if ($user) {
      $email = new Mail();
      $subject = "Verificacion de email";
      $verificationLink = "http://localhost:8000/auth/verify?token=$temporaryToken";
      $body = "<h1>Verifica tu correo</h1>
              <p>Haz clic en el siguiente enlace para activar tu cuenta:</p>
              <a href='$verificationLink'>$verificationLink</a>";

      if ($email->send($user->email, $subject, $body)) {
        return $this->success($user, "Usuario creado, revisa tu correo electronico para confirmar tu cuenta", 201);
      } else {
        return $this->error("No se pudo enviar el correo de verificación", 500);
      }
    }

  }

  public function verifyEmail()
  {
    $token = $_GET["token"] ?? NULL;

    if (!$token) {
      return $this->error("Token no proporcionado", 400);
    }

    //Verificacion del token
    $user = User::where("verification_token", $token)->first();

    //Usuario existe con ese token?
    if (!$user) {
      return $this->error("Token invalido", 400);
    }

    //Token vigente?
    if (strtotime($user->verification_expires) < Time()) {
      return $this->error("Token expirado", 400);
    }

    //Si todo salio bien
    $user->is_verified = 1;
    $user->verification_token = null;
    $user->verification_expires = null;
    $user->save();

    //Si fuera pagina html
    // echo "<h1>Email verificado exitosamente</h1>";
    // echo "<p>Puedes cerrar esta ventana o ir a Iniciar sesión</p>";
    // exit;

    return $this->success([], "Cuenta confirmada", 201);

  }

  public function login()
  {
    $input = $this->jsonInput();

    $this->validate([
      'email' => v::email()->notEmpty(),
      'password' => v::stringType()->notEmpty()
    ], $input);

    $user = User::where('email', $input['email'])->first();

    if (!$user) {
      $this->error('No existe un usuario con ese email', 404);
    } else {
      //si el usuario ya verifico su email
      if (!$user->is_verified) {
        return $this->error("Debes verificar tu email para iniciar sesion", 403);
      }

      if (!password_verify($input['password'], $user->password)) {
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
        'expires_in' => (int) ($_ENV['JWT_EXPIRES_IN'] ?? 3600)
      ], "Autenticado");
    }

  }
}

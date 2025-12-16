<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Container;
use App\Controllers\BaseController;
use App\Middleware\CsrfMiddleware;
use Respect\Validation\Validator as v;
use App\Services\Auth\AuthLoginService;
use App\Repositories\Auth\AuthRepository;
use App\Services\Auth\AuthRegisterService;
use App\Services\Auth\EmailVerificationService;

class AuthController extends BaseController
{
  public function register()
  {
    try {

      $input = $this->jsonInput();

      $this->validateFromApi([
        'username' => v::stringType()->length(3, 10)->notEmpty()->setTemplate('El nombre de usuario debe tener entre 3 y 10 caracteres'),
        'email' => v::email()->notEmpty()->setTemplate('El email no es válido'),
        'password' => v::stringType()->length(6, 100)->notEmpty()->setTemplate('La contraseña debe tener al menos 6 caracteres'),
      ], $input);

      // $authRegisterService = new AuthRegisterService(new AuthRepository());
      $authRegisterService = Container::resolve(AuthRegisterService::class);
      $authRegisterService->registerUser($input);

      return $this->success([], "Usuario creado. Revisa tu email para verificar tu cuenta", 201);
    } catch (\Exception $e) {
      $status = $e->getCode() > 0 ? $e->getCode() : 400;
      return $this->error("Error al registrar el usuario: ", $status, [$e->getMessage()]);
    }
  }

  public function verifyEmail()
  {
    try {
      $token = $_GET["token"] ?? NULL;

      if (!$token) {
        return $this->error("Datos inválidos", 400, ["Token no proporcionado"]);
      }

      $emailVerificationService = Container::resolve(EmailVerificationService::class);
      $emailVerificationService->verifyEmail($token);

      // return $this->success([], "Cuenta confirmada", 200);
      View::render('auth/verification_success', [
        'title' => 'Cuenta verificada',
      ]);
    } catch (\Exception $e) {
      return $this->error("Error al verificar email", $e->getCode() ?: 500, [$e->getMessage()]);
    }
  }

  public function login()
  {
    try {
      $input = $this->jsonInput();

      $this->validateFromApi([
        'email' => v::email()->notEmpty()->setTemplate('El email no es válido'),
        'password' => v::stringType()->notEmpty()->setTemplate('La contraseña es obligatoria'),
      ], $input);

      $authLoginService = Container::resolve(AuthLoginService::class);
      $tokenData = $authLoginService->loginUser($input);

      return $this->success($tokenData, "Autenticado");
    } catch (\Exception $e) {
      return $this->error("Error en login", $e->getCode() ?: 500, [$e->getMessage()]);
    }
  }
}

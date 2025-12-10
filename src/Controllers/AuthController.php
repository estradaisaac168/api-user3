<?php

namespace App\Controllers;

use Models\User;
use App\Services\JWTService;
use App\Services\AuthService;
use App\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Respect\Validation\Validator as v;
use App\Services\Auth\AuthLoginService;
use App\Services\Auth\AuthRegisterService;
use App\Services\Auth\EmailVerificationService;

class AuthController extends BaseController
{
  public function register()
  {
    try {
      $input = $this->jsonInput();

      $this->validate([
        'username' => v::stringType()->length(3, 10)->notEmpty()->setTemplate('El nombre de usuario debe tener entre 3 y 10 caracteres'),
        'email' => v::email()->notEmpty()->setTemplate('El email no es válido'),
        'password' => v::stringType()->length(6, 100)->notEmpty()->setTemplate('La contraseña debe tener al menos 6 caracteres'),
      ], $input);

      $authRegisterService = new AuthRegisterService();
      $authRegisterService->registerUser($input);

      return $this->success([], "Usuario creado. Revisa tu email para verificar tu cuenta", 201);
    } catch (\Exception $e) {
      return $this->error("Error al registrar el usuario: " . $e->getMessage(), 500);
    }
  }

  public function verifyEmail()
  {
    try {
      $token = $_GET["token"] ?? NULL;

      if (!$token) {
        return $this->error("Datos inválidos", 400, $error[$token] = "Token no proporcionado");
      }

      $emailVerificationService = new EmailVerificationService();
      $emailVerificationService->verifyEmail($token);

      return $this->success([], "Cuenta confirmada", 200);
    } catch (\Exception $e) {
      return $this->error($e->getMessage(), $e->getCode() ?: 500);
    }
  }

  public function login()
  {
    try {
      $input = $this->jsonInput();

      $this->validate([
        'email' => v::email()->notEmpty()->setTemplate('El email no es válido'),
        'password' => v::stringType()->notEmpty()->setTemplate('La contraseña es obligatoria'),
      ], $input);

      $authLoginService = new AuthLoginService();
      $tokenData = $authLoginService->loginUser($input);

      return $this->success($tokenData, "Autenticado");
    } catch (\Exception $e) {
      return $this->error("Error en los datos de entrada", 400, [$e->getMessage()]);
    }
  }
}

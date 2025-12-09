<?php

namespace App\Controllers;

use Respect\Validation\Validator as v;
use App\Interfaces\ICrudController;

abstract class BaseController
{
  // public function __construct()
  // {
  //   $this->cors();
  // }


  // abstract public function index();
  // abstract public function show($id);
  // abstract public function store();
  // abstract public function update($id);
  // abstract public function delete($id);


  // protected function cors()
  // {
  //   header("Access-Control-Allow-Origin: *");
  //   header("Access-Control-Allow-Headers: Content-Type, Authorization");
  //   header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
  // }

  protected function success($data = [], $message = "OK", $status = 200)
  {
    http_response_code($status);
    echo json_encode([
      'status' => 'success',
      'message' => $message,
      'data' => $data
    ]);
    exit;
  }

  protected function error($message = "Error", $status = 400, $errors = [])
  {
    http_response_code($status);
    echo json_encode([
      'status' => 'error',
      'message' => $message,
      'errors' => $errors
    ]);
    exit;
  }

  protected function validate($rules, $data)
  {
    $errors = [];

    foreach ($rules as $field => $validator) {
      try {
        $validator->assert($data[$field] ?? null);
      } catch (\Respect\Validation\Exceptions\ValidationException $e) {
        $errors[$field] = $e->getMessages();
      }
    }

    if (!empty($errors)) {
      $this->error("Datos inválidos", 422, $errors);
    }
  }

  protected function jsonInput()
  {
    return json_decode(file_get_contents("php://input"), true) ?? [];
  }
}

<?php

namespace App\Controllers;

use App\Core\Response;
use App\Helpers\ResponseHelper;
use App\Interfaces\ICrudController;
use Illuminate\Support\ValidatedInput;
use Respect\Validation\Validator as v;

abstract class BaseController
{
  // public function index(){}
  // public function show($id){}
  // public function store(){}
  // public function update($id){}
  // public function delete($id){}

  protected function success($data = [], $message = "OK", $status = 200)
  {
    Response::success($data, $message, $status);
  }

  protected function error($message = "Error", $status = 400, $errors = [])
  {
    Response::error($message, $status, $errors);
  }

  protected function validateFromApi($rules, $data)
  {
    // $errors = [];

    // foreach ($rules as $field => $validator) {
    //   try {
    //     $validator->assert($data[$field] ?? null);
    //   } catch (\Respect\Validation\Exceptions\ValidationException $e) {
    //     $errors[$field] = $e->getMessage();
    //   }
    // }

    $errors = $this->validateInputs($rules);

    if (!empty($errors)) {
      $this->error("Datos inválidos", 422, $errors);
    }
  }

  protected function validateFromHTML(
    array $rules,
    array $data,
    string $redirectTo = "",
  ): void {

    $errors = $this->validateInputs($rules);

    if(!empty($errors)) {
      $_SESSION["error"] = $errors;
      $_SESSION["old"] = $data;

      header("Location: $redirectTo");
      exit;
    }
  }

  private function validateInputs(array $rules) : array {
    $errors = [];

    foreach ($rules as $field => $validator) {
      try {
        $validator->assert($data[$field] ?? null);
      } catch (\Respect\Validation\Exceptions\ValidationException $e) {
        $errors[$field] = $e->getMessage();
      }
    }

    return $errors;
  }

  protected function jsonInput()
  {
    return json_decode(file_get_contents("php://input"), true) ?? [];
  }
}

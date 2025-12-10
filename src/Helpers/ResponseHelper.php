<?php

namespace App\Helpers;

class ResponseHelper
{
  public static function success($data = [], $message = "OK", $status = 200)
  {
    http_response_code($status);
    echo json_encode([
      'status'  => 'success',
      'message' => $message,
      'data'    => $data
    ]);
    exit;
  }

  public static function error($message = "Error", $status = 400, $errors = [])
  {
    http_response_code($status);
    echo json_encode([
      'status'  => 'error',
      'message' => $message,
      'errors'  => $errors
    ]);
    exit;
  }
}

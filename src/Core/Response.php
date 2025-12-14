<?php

namespace App\Core;

class Response
{
  public static function json($data, int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: application/json');

    echo json_encode($data);
    exit;
  }

  public static function success($data = [], string $message = "OK", int $status = 200): void
  {
    self::json([
      'status'  => 'success',
      'message' => $message,
      'data'    => $data
    ], $status);
  }

  public static function error(string $message = "Error", int $status = 400, array $errors = []): void
  {
    self::json([
      'status'  => 'error',
      'message' => $message,
      'errors'  => $errors
    ], $status);
  }

  public static function view(string $view, array $data = []): void
  {
    http_response_code(200);
    header('Content-Type: text/html; charset=UTF-8');
    View::render($view, $data);
    exit;
  }
}

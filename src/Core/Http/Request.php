<?php


namespace App\Core\Http;

class Request
{

  public function all(): array
  {
    return $_POST;
  }

  public function input(
    string $key,
    $default = null
  ) {
    return $_POST[$key] ?? $default;
  }


  public function only(
    array $keys
  ): array {
    return array_intersect_key($_POST, array_flip($keys));
  }

  public function has(string $key): bool
  {
    return isset($_POST[$key]);
  }
}

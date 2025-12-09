<?php

// Configuración global básica
header('Content-Type: application/json');

// Mostrar errores durante desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Captura excepciones no manejadas
set_exception_handler(function ($exception) {
  http_response_code(500);
  echo json_encode([
    'status' => 'error',
    'message' => 'Internal server error',
    'details' => $exception->getMessage(),
  ]);
  exit;
});

// Captura errores fatales
register_shutdown_function(function () {
  $error = error_get_last();

  if ($error !== null) {
    http_response_code(500);
    echo json_encode([
      'status' => 'error',
      'message' => 'Fatal error detected',
      'details' => $error['message'],
    ]);
    exit;
  }
});


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Cargar DB/Eloquent
require_once __DIR__ . '/database.php';

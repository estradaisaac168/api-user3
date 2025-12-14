<?php

use App\Core\Response;
use App\Core\ErrorHandler;

// ====== LOGS ======
$logDir = __DIR__ . '/../../logs';
require_once __DIR__ . '/../../src/Core/ErrorHandler.php';


if (!is_dir($logDir)) {
  mkdir($logDir, 0777, true);
}

ini_set('log_errors', 1);
ini_set('error_log', $logDir . '/error.log');

// ====== DISPLAY ERRORS (dependiendo de entorno) ======
$environment = $_ENV['APP_ENV'] ?? 'production';

ini_set('display_errors', $environment === 'local' ? 1 : 0);

// ====== HANDLERS PERSONALIZADOS ======

set_exception_handler([ErrorHandler::class, 'handleException']);
set_error_handler([ErrorHandler::class, 'handleError']);

error_reporting(E_ALL);

// ====== FATAL ERRORS ======
register_shutdown_function(function () {
  $error = error_get_last();

  if ($error !== null) {
    error_log("[FATAL] {$error['message']}");
    Response::error("Internal server error", 500);
  }
});

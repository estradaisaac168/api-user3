<?php

// ====== HEADERS Y CORS ======

use App\Helpers\ResponseHelper;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Preflight para navegadores
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ====== DOTENV ======
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// ====== LOGS ======
$logDir = __DIR__ . '/../../logs';

if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

ini_set('log_errors', 1);
ini_set('error_log', $logDir . '/error.log');

// ====== DISPLAY ERRORS (dependiendo de entorno) ======
$environment = $_ENV['APP_ENV'] ?? 'production';

ini_set('display_errors', $environment === 'local' ? 1 : 0);

// ====== HANDLERS PERSONALIZADOS ======
require_once __DIR__ . '/ErrorHandler.php';

set_exception_handler([App\Core\ErrorHandler::class, 'handleException']);
set_error_handler([App\Core\ErrorHandler::class, 'handleError']);

error_reporting(E_ALL);

// ====== FATAL ERRORS ======
register_shutdown_function(function () {
    $error = error_get_last();

    if ($error !== null) {
        error_log("[FATAL] {$error['message']}");
        ResponseHelper::error("Internal server error", 500);
    }
});

// ====== BASE DE DATOS ======
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../Helpers/helpers.php';

<?php

// ====== HEADERS Y CORS ======

use Dotenv\Dotenv;
use App\Core\Response;
use App\Core\ErrorHandler;
use App\Helpers\ResponseHelper;

// header('Content-Type: application/json');
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Preflight para navegadores
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(200);
//     exit;
// }

// ====== DOTENV ======
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

require_once __DIR__ . '/../../config/logging/log.php';

// ====== BASE DE DATOS ======
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../Helpers/helpers.php';

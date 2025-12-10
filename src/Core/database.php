<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\QueryException;
use App\Helpers\ResponseHelper;


try {
  // Eloquent
  $capsule = new Capsule;
  $capsule->addConnection([
    "driver"   => $_ENV['DB_DRIVER'] ?? 'mysql',
    "host"     => $_ENV['DB_HOST'] ?? '127.0.0.1',
    "database" => $_ENV['DB_DATABASE'] ?? 'education_db',
    "username" => $_ENV['DB_USERNAME'] ?? 'root',
    "password" => $_ENV['DB_PASSWORD'] ?? '',
    "charset"  => "utf8",
    "collation" => "utf8_unicode_ci",
    "prefix"   => ""
  ]);
  $capsule->setAsGlobal();
  $capsule->bootEloquent();
} catch (QueryException $e) {
  // Error de SQL, constraints, sintaxis, etc.
  return ResponseHelper::error("Error en la consulta SQL",500,[$e->getMessage()]);
} catch (\PDOException $e) {
  // Error bajo nivel clásico de PDO
  return ResponseHelper::error(
    "Error de conexión o ejecución en la base de datos",500,[$e->getMessage()]);
} catch (\Exception $e) {
  // Cualquier otro error no contemplado
  return ResponseHelper::error("Error inesperado",500,[$e->getMessage()]);
}

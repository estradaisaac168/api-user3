<?php

use Illuminate\Database\Capsule\Manager as Capsule;

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

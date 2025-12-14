<?php

use App\Controllers\HomeController;


$router->get('/', function () {
  (new HomeController())->index();
});

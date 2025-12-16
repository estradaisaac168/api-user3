<?php

use App\Controllers\CourseController;
use App\Controllers\HomeController;
use App\Middleware\AuthWebMiddleware;
use App\Middleware\CsrfMiddleware;


$router->get('/', function () {
  (new HomeController())->index();
});


$router->get('/courses/create', function () { 
  (new CourseController())->create();
});

$router->post('/courses', function(){
      AuthWebMiddleware::handle();
    CsrfMiddleware::handle();
  (new CourseController())->store();
});

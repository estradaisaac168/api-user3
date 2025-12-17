<?php

use App\Controllers\CourseController;
use App\Controllers\HomeController;
use App\Middleware\AuthWebMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Core\Http\Request;

$request = new Request();

$router->get('/', function () {
  (new HomeController())->index();
});


$router->get('/courses/(\d+)', function ($id) {
  (new CourseController())->show((int) $id);
});

$router->get('/courses/create', function () {
  (new CourseController())->create();
});


$router->post('/courses', function () use ($request) {
  AuthWebMiddleware::handle();
  CsrfMiddleware::handle();

  (new CourseController())->store($request);
});

$router->get('/courses/(\d+)/edit', function ($id) {
    AuthWebMiddleware::handle();

    (new CourseController())->edit((int) $id);
});

$router->post('/courses/(\d+)', function ($id) use ($request) {
  AuthWebMiddleware::handle();
  CsrfMiddleware::handle();

  (new CourseController())->update((int)$id, $request);
});


$router->post('/courses/(\d+)/delete', function ($id) {
  (new CourseController())->destroy((int) $id);
});

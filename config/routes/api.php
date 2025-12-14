<?php

use App\Core\Response;
use App\Helpers\ResponseHelper;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\UserController;


// Rutas públicas
$router->post('/auth/register', function () {
  (new AuthController())->register();
});

$router->post('/auth/login', function () {
  (new AuthController())->login();
});

//Veridicacion del email by token
$router->get('/auth/verify', function () {
  (new AuthController())->verifyEmail();
});



// Protege rutas /users con middleware
$router->before('GET|POST|PUT|DELETE', '/users.*', function () {
  (new AuthMiddleware())->handle();
});

// $router->get('/', function () {
//     ResponseHelper::success([], "API funcionando", 200);
// });




$router->mount('/users', function () use ($router) {

  // GET /users
  $router->get('/', function () {
    (new UserController())->index();
  });

  // GET /users/:id
  $router->get('/(\d+)', function ($id) {
    (new UserController())->show($id);
  });

  // POST /users
  $router->post('/', function () {
    (new UserController())->store();
  });

  // PUT /users/:id
  $router->put('/(\d+)', function ($id) {
    (new UserController())->update($id);
  });

  // DELETE /users/:id
  $router->delete('/(\d+)', function ($id) {
    (new UserController())->delete($id);
  });
});


$router->set404(function () {
  Response::error("Ruta no encontrada", 404);
});

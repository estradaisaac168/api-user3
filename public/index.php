<?php


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Core/bootstrap.php';


use Bramus\Router\Router;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

$router = new Router();

// Rutas públicas
$router->post('/auth/register', function () {
    (new AuthController())->register();
});

$router->post('/auth/login', function () {
    (new AuthController())->login();
});

// Protege rutas /users con middleware
$router->before('GET|POST|PUT|DELETE', '/users.*', function () {
    (new AuthMiddleware())->handle();
});

$router->get('/', function () {
    echo json_encode(["message" => "API funcionando"]);
});

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

// ---------------------------------------------------------
// Error 404
$router->set404(function () {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Ruta no encontrada'
    ]);
});

// Ejecutar router
$router->run();

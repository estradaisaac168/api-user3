<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Interfaces\ICrudController;
use App\Middleware\RoleMiddleware;
use App\Middleware\CsrfMiddleware;
use Respect\Validation\Validator as v;
use App\Models\Course;
use App\Core\View;
use App\Core\Http\Request;

class CourseController extends BaseController implements ICrudController
{
    public function index(): void
    {
        //
    }
    public function show($id): void {}

    public function create(): void
    {

        $old     = flash('old', []);
        $errors  = flash('errors', []);
        $success = flash('success');

        View::render(
            'courses/create',
            compact('old', 'errors', 'success')
        );
    }

    public function store(Request $request): void
    {

        $input = $_POST;

        $this->validateFromHTML(
            [
                'name' => v::notEmpty()->setTemplate('El nombre del curso es obligatorio'),
            ],
            $input,
            '/course/create'
        );

        Course::create([
            'name' => $input['name']
        ]);

        redirectWith('/course/create', [
            'success' => 'Curso creado con éxito'
        ]);
    }

    public function edit($id): void {}

    public function update($id, Request $request): void {}

    public function destroy($id): void {}
}

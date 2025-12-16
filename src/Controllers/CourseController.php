<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Interfaces\ICrudController;
use App\Middleware\RoleMiddleware;
use App\Middleware\CsrfMiddleware;
use Respect\Validation\Validator as v;
use App\Models\Course;
use App\Core\View;

class CourseController extends BaseController implements ICrudController
{
    public function index()
    {
        RoleMiddleware::requireRole([1]);

        $course = Course::all();

        if($course->count() <= 0){
            return $this->error("No hay cursos para listar");
        }
        
        return $this->success($course, "Lista de cursos");
    }
    public function show($id)
    {
    }

    public function create():void{

       $old     = flash('old', []);
        $errors  = flash('errors', []);
        $success = flash('success');

        View::render(
            'courses/create',
            compact('old','errors','success')
        );

    }

    public function store(): void
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


    public function update($id)
    {
    }
    public function delete($id)
    {
    }
}
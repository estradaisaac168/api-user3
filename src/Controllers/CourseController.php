<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Interfaces\ICrudController;
use App\Middleware\RoleMiddleware;
use App\Models\Course;

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
    public function store()
    {
    }
    public function update($id)
    {
    }
    public function delete($id)
    {
    }
}
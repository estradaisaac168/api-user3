<?php

namespace App\Interfaces;

interface ICrudController
{
    public function index();
    public function show($id);
    public function store();
    public function update($id);
    public function delete($id);
}

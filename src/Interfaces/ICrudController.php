<?php

namespace App\Interfaces;

use App\Core\Http\Request;

interface ICrudController
{
    // public function index();
    // public function show($id);
    // public function store(Request $resquest);
    // public function update($id);
    // public function delete($id);

    public function index(): void;

    public function show(int|string $id): void;

    public function store(Request $request): void;

    public function update(int|string $id, Request $request): void;

    public function destroy(int|string $id): void;
}

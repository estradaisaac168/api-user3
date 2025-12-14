<?php

namespace App\Controllers;

use App\Core\View;

class HomeController
{
  public function index()
  {
    View::render('home/index', [
      'title' => 'Página de inicio',
    ]);
  }
}

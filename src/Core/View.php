<?php

namespace App\Core;

class View
{

  protected static string $layout = 'main';

  public static function render(
    string $view,
    array $data = [],
    ?string $layout = null
  ): void {

    $layout = $layout ?? self::$layout;

    $viewFile = self::viewPath($view);
    $layoutFile = self::layoutPath($layout);

    if (!file_exists($viewFile)) {
      throw new \Exception("La vista {$view} no existe.", 500);
    }

    if (!file_exists($layoutFile)) {
      throw new \Exception("El layout {$layout} no existe.", 500);
    }

    // Extraer datos para la vista
    extract($data, EXTR_SKIP);

    // Capturar el contenido de la vista
    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    require $layoutFile;
  }

  protected static function viewPath(string $view): string
  {
    return __DIR__ . "/../Views/" . $view . ".php";
  }

  protected static function layoutPath(string $layout): string
  {
    return __DIR__ . "/../Views/layouts/" . $layout . ".php";
  }

  public static function sanizate(
    string $value
  ): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  }
}

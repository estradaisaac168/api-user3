<?php

namespace App\Core;

use App\Helpers\ResponseHelper;

class ErrorHandler
{
    public static function handleException($exception)
    {
        error_log("[EXCEPTION] " . $exception->getMessage());
        Response::error("Internal server error", 500);
    }

    public static function handleError($severity, $message, $file, $line)
    {
        error_log("[ERROR] $message in $file on line $line");
        Response::error("Internal server error", 500);
    }
}

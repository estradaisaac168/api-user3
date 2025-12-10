<?php

namespace App\Core;

use App\Helpers\ResponseHelper;

class ErrorHandler
{
    public static function handleException($exception)
    {
        error_log("[EXCEPTION] " . $exception->getMessage());
        ResponseHelper::error("Internal server error", 500);
    }

    public static function handleError($severity, $message, $file, $line)
    {
        error_log("[ERROR] $message in $file on line $line");
        ResponseHelper::error("Internal server error", 500);
    }
}

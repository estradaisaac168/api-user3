<?php

class ErrorHandler
{
    public static function handleException($exception)
    {
        error_log("[EXCEPTION] " . $exception->getMessage());

        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error'
        ]);
    }

    public static function handleError($severity, $message, $file, $line)
    {
        $log = "[ERROR] $message in $file on line $line";
        error_log($log);

        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error'
        ]);
    }
}

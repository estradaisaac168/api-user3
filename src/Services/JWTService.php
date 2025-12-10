<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTService
{
    protected static function secret()
    {
        return $_ENV['JWT_SECRET'] ?? null;
    }

    public static function generateToken(array $payload = [], ?int $expiresIn = null)
    {
        $now = time();
        $exp = $now + ($expiresIn ?? (int)($_ENV['JWT_EXPIRES_IN'] ?? 3600));

        $token = array_merge([
            'iss' => $_ENV['JWT_ISSUER'] ?? null,
            'aud' => $_ENV['JWT_AUDIENCE'] ?? null,
            'iat' => $now,
            'nbf' => $now,
            'exp' => $exp
        ], $payload);

        return JWT::encode($token, self::secret(), 'HS256');
    }

    public static function validateToken(string $jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key(self::secret(), 'HS256'));
            // devuelve objeto stdClass con claims
            return $decoded;
        } catch (Exception $e) {
            throw $e; // deja que el middleware lo capture
        }
    }
}

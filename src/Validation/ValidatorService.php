<?php

namespace Validation;

use Respect\Validation\Exceptions\NestedValidationException;

class ValidatorService
{
    public static function validate(array $data, $rules)
    {
        try {
            $rules->assert($data);
            return []; // Sin errores
        } catch (NestedValidationException $e) {
            return $e->getMessages(); // Devuelve los mensajes de error
        }
    }
}

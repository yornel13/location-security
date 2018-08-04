<?php
namespace App\Validation;

use App\Lib\Response;

class VisitValidation
{
    public static function validate($data, $update = false)
    {
        $response = new Response();

        $key = 'visitor_id';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'guard_id';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'persons';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
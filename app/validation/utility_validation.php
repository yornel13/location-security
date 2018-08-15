<?php
namespace App\Validation;

use App\Lib\Response;

class UtilityValidation
{
    public static function validate($data)
    {
        $response = new Response();

        $key = 'name';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'value';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
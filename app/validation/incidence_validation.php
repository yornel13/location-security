<?php
namespace App\Validation;

use App\Lib\Response;

class IncidenceValidation
{
    public static function validate($data)
    {
        $response = new Response();

        $key = 'name';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 4) {
                $response->errors[$key][] = 'debe contener como minimo 4 caracteres';
            }
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
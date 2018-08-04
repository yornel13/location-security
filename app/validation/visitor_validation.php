<?php
namespace App\Validation;

use App\Lib\Response;

class VisitorValidation
{
    public static function validate($data, $update = false)
    {
        $response = new Response();

        $key = 'dni';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 6) {
                $response->errors[$key][] = 'debe contener como minimo 6 caracteres';
            }
        }

        $key = 'name';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 4) {
                $response->errors[$key][] = 'debe contener como minimo 4 caracteres';
            }
        }

        $key = 'lastname';
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
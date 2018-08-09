<?php
namespace App\Validation;

use App\Lib\Response;

class AlertValidation
{
    public static function validate($data, $update = false)
    {
        $response = new Response();

        $key = 'guard_id';
        if (!isset($data[$key])) {
            $response->errors[][$key] = 'Este campo es obligatorio';
        }

        $key = 'cause';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 4) {
                $response->errors[$key][] = 'debe contener como minimo 4 caracteres';
            }
        }

        $key = 'latitude';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'longitude';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
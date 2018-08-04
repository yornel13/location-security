<?php
namespace App\Validation;

use App\Lib\Response;

class VisitorVehicleValidation
{
    public static function validate($data, $update = false)
    {
        $response = new Response();

        $key = 'plate';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 3) {
                $response->errors[$key][] = 'debe contener como minimo 3 caracteres';
            }
        }

        $key = 'model';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 3) {
                $response->errors[$key][] = 'debe contener como minimo 3 caracteres';
            }
        }

        $key = 'type';
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
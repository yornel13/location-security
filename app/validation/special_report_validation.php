<?php
namespace App\Validation;

use App\Lib\Response;

class SpecialReportValidation
{
    public static function validate($data)
    {
        $response = new Response();

        $key = 'incidence_id';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'watch_id';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'title';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 4) {
                $response->errors[$key][] = 'debe contener como minimo 4 caracteres';
            }
        }

        $key = 'observation';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 10) {
                $response->errors[$key][] = 'debe contener como minimo 10 caracteres';
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
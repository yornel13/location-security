<?php
namespace App\Validation;

use App\Lib\Response;

class WatchValidation
{
    public static function validate($data, $update = false)
    {
        $response = new Response();

        if (!$update) {
            $key = 'guard_id';
            if (!isset($data[$key])) {
                $response->errors[$key][] = 'Este campo es obligatorio';
            }
        }

        $key = 'latitude';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'longitude';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
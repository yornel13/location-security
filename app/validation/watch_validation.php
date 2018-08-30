<?php
namespace App\Validation;

use App\Lib\Response;

class WatchValidation
{
    public static function validateStart($data)
    {
        $response = new Response();

        $key = 'guard_id';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'tablet_id';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'stand_id';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
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

    public static function validateEnd($data)
    {
        $response = new Response();

        $key = 'f_latitude';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'f_longitude';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
<?php
namespace App\Validation;

use App\Lib\Response;

class TabletValidation
{
    public static function validate($data)
    {
        $response = new Response();

        $key = 'imei';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
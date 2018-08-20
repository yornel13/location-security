<?php
namespace App\Validation;

use App\Lib\Response;

class ChannelValidation
{
    public static function validate($data)
    {
        $response = new Response();

        $key = 'creator_id';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'creator_type';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'creator_name';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
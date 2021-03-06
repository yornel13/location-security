<?php
namespace App\Validation;

use App\Lib\Response;

class MessengerValidation
{
    public static function validate($data)
    {
        $response = new Response();

        $key = 'text';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'sender_id';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'sender_type';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'sender_name';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $keyA = 'chat_id';
        $keyG = 'channel_id';
        if (empty($data[$keyA]) && empty($data[$keyG])) {
            $response->errors[$keyA][] = 'El mensaje debe estar asociado a un chat o canal';
            $response->errors[$keyG][] = 'El mensaje debe estar asociado a un chat o canal';
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
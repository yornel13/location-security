<?php
namespace App\Validation;

use App\Lib\Response;

class ReplyValidation
{
    public static function validate($data)
    {
        $response = new Response();

        $key = 'report_id';
        if (!isset($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $key = 'text';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        }

        $keyA = 'admin_id';
        $keyG = 'guard_id';
        if (empty($data[$keyA]) && empty($data[$keyG])) {
            $response->errors[$keyA][] = 'La respuesta debe estar asociada a un guardia o administrador';
            $response->errors[$keyG][] = 'La respuesta debe estar asociada a un guardia o administrador';
        }

        $key = 'user_name';
        if (empty($data[$key])) {
            $response->errors[$key][] = 'Este campo es obligatorio';
        } else {
            $value = $data[$key];

            if (strlen($value) < 1) {
                $response->errors[$key][] = 'debe contener como minimo 1 caracter';
            }
        }

        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
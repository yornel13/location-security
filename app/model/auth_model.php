<?php
namespace App\Model;


use App\Lib\Auth;
use App\Lib\Response;
use Exception;

class AuthModel
{
    private $db;
    private $tableG = 'guard';
    private $tableA = 'admin';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function guard($dni, $password)
    {
        $guard = $this->db->from($this->tableG)
            ->where('dni', $dni)
            ->fetch();

        if (is_object($guard)) {
            if ($guard->password != md5($password)) {
                return $this->response->SetResponse(false, 'Clave incorrecta');
            }

            if ($guard->stand_id == null) {
                return $this->response->SetResponse(false, 'No puedes iniciar session si no estas asociado a una zona');
            }
            $token = Auth::SignIn([
                'id' => $guard->id,
                'dni' => $guard->dni,
                'name' => $guard->name,
                'lastname' => $guard->lastname,
                'email' => $guard->email,
                'photo' => $guard->photo,
                'isAdmin' => false
            ]);
            $guard->token = $token;
            $this->response->result = $guard;
            return $this->response->SetResponse(true);

        } else {
            return $this->response->SetResponse(false, 'Cedula no registrada');
        }
    }

    public function admin($dni, $password)
    {
        $admin = $this->db->from($this->tableA)
            ->where('dni', $dni)
            ->fetch();

        if (is_object($admin)) {
            if ($admin->password !=  md5($password)) {
                return $this->response->SetResponse(false, 'Clave incorrecta');
            }

            $token = Auth::SignIn([
                'id' => $admin->id,
                'dni' => $admin->dni,
                'name' => $admin->name,
                'lastname' => $admin->lastname,
                'photo' => $admin->photo,
                'isAdmin' => true
            ]);
            $this->response->result = $token;
            return $this->response->SetResponse(true);
        } else {
            return $this->response->SetResponse(false, 'Cedula no registrada');
        }
    }

    public function sign_out($token) {
        if (is_null($token)) {
            return $this->response->SetResponse(false, 'debes enviar el token en el header');
        }
        try {
            $auth = $this->verify($token);
            if ($auth->isAdmin) {
                $this->db
                    ->deleteFrom('web_token')
                    ->where('session', $token)
                    ->execute();
            }
            return $this->response->SetResponse(true);

        } catch (Exception $e) {
            return $this->response->SetResponse(true);
        }
    }

    public function verify($token)
    {
        return Auth::GetData($token);
    }
}
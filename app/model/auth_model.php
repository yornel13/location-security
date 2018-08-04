<?php
namespace App\Model;


use App\Lib\Auth;
use App\Lib\Response;

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
            ->where('password', md5($password))
            ->fetch();

        if (is_object($guard)) {
            $token = Auth::SignIn([
                'id' => $guard->id,
                'dni' => $guard->dni,
                'name' => $guard->name,
                'lastname' => $guard->lastname,
                'isAdmin' => false
            ]);
            $this->response->result = $token;
            return $this->response->SetResponse(true);

        } else {
            return $this->response->SetResponse(false, 'Credenciales no validas');
        }
    }

    public function admin($email, $password)
    {
        $admin = $this->db->from($this->tableA)
            ->where('email', $email)
            ->where('password', md5($password))
            ->fetch();

        if (is_object($admin)) {
            $token = Auth::SignIn([
                'id' => $admin->id,
                'dni' => $admin->dni,
                'name' => $admin->name,
                'lastname' => $admin->lastname,
                'isAdmin' => true
            ]);
            $this->response->result = $token;
            return $this->response->SetResponse(true);

        } else {
            return $this->response->SetResponse(false, 'Credenciales no validas');
        }
    }
}
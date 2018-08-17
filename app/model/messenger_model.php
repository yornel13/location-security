<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class MessengerModel
{
    private $db;
    private $register = 'chat_user';
    private $table = 'message';
    private $response;
    private $WAY1 = 'ADMIN-TO-GUARD';
    private $WAY2 = 'GUARD-TO-ADMIN';
    private $WAY3 = 'GUARD-TO-GUARD';
    private $WAY4 = 'ADMIN-TO-ADMIN';

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function register($data)
    {
        $object = $this->db
            ->from($this->register)
            ->where('imei', $data['imei'])
            ->fetch();

        if (!empty($object)) {
            $this->db
                ->update($this->register, $data, $object->id)
                ->execute();
            $data['id'] = $object->id;
        } else {
            $query = $this->db
                ->insertInto($this->register, $data)
                ->execute();
            $data['id'] = $query;
        }

        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function send($data)
    {
        if ($data['way'] === $this->WAY1) {
            return $this->adminToGuard($data);
        } else if ($data['way'] === $this->WAY3) {
            return $this->guardToGuard($data);
        }
        return $this->response->SetResponse(false, 'No se describio la forma de envio');
    }

    public function adminToGuard($data)
    {
        $message = [
            'way' => $data['way'],
            'from_admin_id' => $data['sender_id'],
            'to_guard_id' => $data['receiver_id'],
            'text' => $data['message'],
            'state' => 1,
        ];

        try {
            $query = $this->db
                ->insertInto($this->table, $message)
                ->execute();
            $message['id'] = $query;
            $this->response->result = $message;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "foreign key")) {
                return $this->response->SetResponse(false, 'El sender_id o receiver_id es erroneo.');
            }
            return $this->response->SetResponse(false, 'Error de guardado.');
        }

        $registration = $this->db
            ->from($this->register)
            ->where('guard_id', $data['receiver_id'])
            ->fetch();

        if (is_object($registration)) {
            $firebase = new FirebaseNotification();
            $result = $firebase->send($message, $registration->registration_id);
            if ($result !== false && json_decode($result)->success == 1) {
                return $this->response->SetResponse(true);
            } else {
                return $this->response->SetResponse(true, "Error de envio de notificacion");
            }
        } else {
            return $this->response->SetResponse(true, 'El distinatario no tiene dispositivo registrado.');
        }
    }

    public function guardToGuard($data)
    {
        $message = [
            'way' => $data['way'],
            'from_guard_id' => $data['sender_id'],
            'to_guard_id' => $data['receiver_id'],
            'text' => $data['message'],
            'state' => 1,
        ];

        try {
            $query = $this->db
                ->insertInto($this->table, $message)
                ->execute();
            $message['id'] = $query;
            $this->response->result = $message;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "foreign key")) {
                return $this->response->SetResponse(false, 'El sender_id o receiver_id es erroneo.');
            }
            return $this->response->SetResponse(false, 'Error de guardado.');
        }

        $registration = $this->db
            ->from($this->register)
            ->where('guard_id', $data['receiver_id'])
            ->fetch();

        if (is_object($registration)) {
            $firebase = new FirebaseNotification();
            $result = $firebase->send($message, $registration->registration_id);
            if ($result !== false && json_decode($result)->success == 1) {
                return $this->response->SetResponse(true);
            } else {
                return $this->response->SetResponse(true, "Error de envio de notificacion");
            }
        } else {
            return $this->response->SetResponse(true, 'El distinatario no tiene dispositivo registrado.');
        }
    }
}
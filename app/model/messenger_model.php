<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class MessengerModel
{
    private $GUARD = 'GUARD';
    private $ADMIN = 'ADMIN';

    private $db;
    private $table_chat = 'chat';
    private $table_chat_line = 'chat_line';
    private $table_channel = 'channel';
    private $table_registered_channel = 'registered_channel';
    private $table_web_token = 'web_token';
    private $table_tablet_token = 'tablet_token';
    private $response;
    private $WAY1 = 'ADMIN-TO-GUARD';
    private $WAY2 = 'GUARD-TO-ADMIN';
    private $WAY3 = 'GUARD-TO-GUARD';
    private $WAY4 = 'ADMIN-TO-ADMIN';
    private $WAY5 = 'GUARD-TO-CHANNEL';
    private $WAY6 = 'ADMIN-TO-CHANNEL';

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function registerTablet($data)
    {
        $object = $this->db
            ->from($this->table_tablet_token)
            ->where('imei', $data['imei'])
            ->fetch();

        if (!empty($object)) {
            $this->db
                ->update($this->table_tablet_token, $data, $object->id)
                ->execute();
            $data['id'] = $object->id;
        } else {
            $query = $this->db
                ->insertInto($this->table_tablet_token, $data)
                ->execute();
            $data['id'] = $query;
        }

        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function registerWeb($data)
    {
        $object = $this->db
            ->from($this->table_web_token)
            ->where('admin_id', $data['admin_id'])
            ->fetch();

        if (!empty($object)) {
            $this->db
                ->update($this->table_web_token, $data, $object->id)
                ->execute();
            $data['id'] = $object->id;
        } else {
            $query = $this->db
                ->insertInto($this->table_web_token, $data)
                ->execute();
            $data['id'] = $query;
        }

        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function createChat($data)
    {
        $chat = $this->db
            ->from($this->table_chat)
            ->where('user_1_id', $data['user_1_id'])
            ->where('user_1_type', $data['user_1_type'])
            ->where('user_2_id', $data['user_2_id'])
            ->where('user_2_type', $data['user_2_type'])
            ->fetch();

        if (is_object($chat)) {
            return $this->response->SetResponse(true, 'Ya existe una conversación entre ambos.');
        }

        $chat = $this->db
            ->from($this->table_chat)
            ->where('user_2_id', $data['user_1_id'])
            ->where('user_2_type', $data['user_1_type'])
            ->where('user_1_id', $data['user_2_id'])
            ->where('user_1_type', $data['user_2_type'])
            ->fetch();

        if (is_object($chat)) {
            return $this->response->SetResponse(true, 'Ya existe una conversación entre ambos.');
        }

        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_at'] = $timestamp;
        $data['update_at'] = $timestamp;
        $data['state'] = 1;
        $query = $this->db
            ->insertInto($this->table_chat, $data)
            ->execute();
        $data['id'] = $query;
        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function send($data)
    {
        if (!empty($data['chat_id'])) {
            $chat = $this->db
                ->from($this->table_chat, $data['chat_id'])
                ->fetch();
            if (is_object($chat)) {
                $timestamp = time()-(5*60*60);
                $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
                $message = [
                    'chat_id' => $data['chat_id'],
                    'text' => $data['text'],
                    'create_at' => $timestamp,
                    'sender_id' => $data['sender_id'],
                    'sender_type' => $data['sender_type'],
                    'sender_name' => $data['sender_name'],
                    'state' => 1,
                ];
                $query = $this->db
                    ->insertInto($this->table_chat_line, $message)
                    ->execute();
                $message['id'] = $query;
                $this->response->result = $message;

                $id = null;
                $type = null;
                if ($chat->user_1_id === $data['sender_id']
                    && $chat->user_1_type === $data['sender_type']) {
                    $id = $chat->user_2_id;
                    $type =  $chat->user_2_type;
                }
                if ($chat->user_2_id === $data['sender_id']
                    && $chat->user_2_type === $data['sender_type']) {
                    $id = $chat->user_1_id;
                    $type =  $chat->user_1_type;
                }
                if ($type == $this->GUARD) {
                    $registration = $this->db
                        ->from($this->table_tablet_token)
                        ->where('guard_id', $id)
                        ->fetch();

                    if (is_object($registration)) {
                        $message['receiver_id'] = $id;
                        $message['receiver_type'] = $type;
                        $firebase = new FirebaseNotification();
                        $send_result = $firebase->send($message, $registration->registration_id);
                        if ($send_result !== false && json_decode($send_result)->success == 1) {
                            return $this->response->SetResponse(true);
                        } else {
                            return $this->response->SetResponse(true, "Error de envio de notificacion");
                        }
                    } else {
                        return $this->response->SetResponse(true, 'El distinatario no tiene dispositivo registrado.');
                    }
                } else {
                    $registration = $this->db
                        ->from($this->table_web_token)
                        ->where('admin_id', $id)
                        ->fetch();

                    if (is_object($registration)) {
                        $message['receiver_id'] = $id;
                        $message['receiver_type'] = $type;
                        $firebase = new FirebaseNotification();
                        $send_result = $firebase->send($message, $registration->registration_id);
                        if ($send_result !== false && json_decode($send_result)->success == 1) {
                            return $this->response->SetResponse(true);
                        } else {
                            return $this->response->SetResponse(true, "Error de envio de notificacion");
                        }
                    } else {
                        return $this->response->SetResponse(true, 'El distinatario no tiene dispositivo registrado.');
                    }
                }
            }
        } else {
            return $this->response->SetResponse(false, 'Aun no se implementan los grupos');
        }
    }

    public function getGuardChats($guard_id)
    {
        $params = [
            ':user_id'   => $guard_id,
            ':user_type' => $this->GUARD
        ];
        $data = $this->db
            ->from($this->table_chat)
            ->where('state = 1 and ((user_1_id = :user_id and user_1_type = :user_type) OR 
                (user_2_id = :user_id and user_2_type = :user_type))', $params)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getAdminChats($admin_id)
    {
        $params = [
            ':user_id'   => $admin_id,
            ':user_type' => $this->ADMIN
        ];
        $data = $this->db
            ->from($this->table_chat)
            ->where('state = 1 and ((user_1_id = :user_id and user_1_type = :user_type) OR 
                (user_2_id = :user_id and user_2_type = :user_type))', $params)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getMessages($chat_id)
    {
        $data = $this->db
            ->from($this->table_chat_line)
            ->where('chat_id', $chat_id)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

}
<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class BoundsGroupModel
{
    private $db;
    private $table = 'bounds_group';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function register($data)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_at'] = $timestamp;
        $data['status'] = 1;

        $object = $this->db
            ->from($this->table)
            ->where('name', $data['name'])
            ->fetch();

        if (!empty($object)) {
            $key = 'name';
            $this->response->errors[$key][] = 'Ya un grupo con este nombre';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function update($data, $id)
    {
        $object = $this->db
            ->from($this->table)
            ->where('name', $data['name'])
            ->fetch();

        if (!empty($object) && $object->id !== $id) {
            $key = 'name';
            $this->response->errors[$key][] = 'Ya existe un grupo con este nombre';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El grupo no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
    }

    public function addToGroup($data, $id)
    {
        if (is_array($data)) {
            foreach ($data as $valor) {
                try {
                    $this->db
                        ->update('bounds', [ 'bounds_group_id' => $id ], $valor['id'])
                        ->execute();
                } catch (Exception $e) {}
            }
            return $this->response->SetResponse(true);
        } else {
            return $this->response->SetResponse(false, 'la data debe ser un array de ids');
        }
    }

    public function get($id)
    {
        return $this->db
            ->from($this->table, $id)
            ->fetch();
    }

    public function getByName($name)
    {
        return $this->db
            ->from($this->table)
            ->where('name', $name)
            ->fetch();
    }

    public function getAll()
    {
        $data = $this->db
            ->from($this->table)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function delete($id)
    {
        try {
            $this->db
                ->update('bounds')
                ->set([ 'bounds_group_id' => null])
                ->where('bounds_group_id', $id)
                ->execute();
            $query = $this->db
                ->deleteFrom($this->table, $id)
                ->execute();
            if ($query === 0) {
                return $this->response
                    ->SetResponse(false, 'El grupo no exite');
            }


        } catch (Exception $e) {
            if (strpos($e->getMessage(), "FOREIGN KEY")) {
                $set = null;
                $set['status'] = 0;
                $this->db
                    ->update($this->table, $set, $id)
                    ->execute();
                $this->response->result = $this->get($id);
                return $this->response->SetResponse(true, 'DISABLED');
            } else {
                return $this->response->SetResponse(false);
            }
        }
        return $this->response->SetResponse(true);
    }
}
<?php
namespace App\Model;


use App\Lib\Response;

class IncidenceModel
{
    private $db;
    private $table = 'incidence';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function register($data)
    {
        $object = $this->db
            ->from($this->table)
            ->where('name', $data['name'])
            ->fetch();

        if (!empty($object)) {
            $key = 'name';
            $this->response->errors[$key][] = 'Ya existe una incidencia con este nombre';
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
            $this->response->errors[$key][] = 'Ya existe una incidencia con este nombre';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'La incidencia no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
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
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function delete($id)
    {
        $query = $this->db
            ->deleteFrom($this->table, $id)
            ->execute();
        if ($query === 0) {
            return $this->response
                ->SetResponse(false, 'La incidencia no existe no exite');
        }
        return $this->response->SetResponse(true);
    }
}
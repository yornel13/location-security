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

        $this->db
            ->insertInto($this->table, $data)
            ->execute();

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

        $this->db
            ->update($this->table, $data, $id)
            ->execute();

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

        $total = $this->db
            ->from($this->table)
            ->select('COUNT(*) Total')
            ->fetch()
            ->Total;

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    public function delete($id)
    {
        $this->db
            ->deleteFrom($this->table, $id)
            ->execute();

        return $this->response->SetResponse(true);
    }
}
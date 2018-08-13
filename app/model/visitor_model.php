<?php
namespace App\Model;


use App\Lib\Response;

class VisitorModel
{
    private $db;
    private $table = 'visitor';
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
        $data['create_date'] = $timestamp;
        $data['update_date'] = $timestamp;
        $data['active'] = 1;

        $guard = $this->db
            ->from($this->table)
            ->where('dni', $data['dni'])
            ->fetch();

        if (!empty($guard)) {
            $key = 'dni';
            $this->response->errors[$key][] = 'La cedula se encuentra asociada a otro visitante';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        $this->checkCompany($data['company']);
        return $this->response->SetResponse(true);
    }

    public function update($data, $id)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_date'] = $timestamp;
        $data['active'] = 1;

        $guard = $this->db
            ->from($this->table)
            ->where('dni', $data['dni'])
            ->fetch();

        if (!empty($guard) && $guard->id !== $id) {
            $key = 'dni';
            $this->response->errors[$key][] = 'La cedula se encuentra asociada a otro visitante';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El visitante no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
    }

    public function checkCompany($name)
    {
        if ($name !== null) {
            $data = $this->db
                ->from('company')
                ->where('name', $name)
                ->fetch();
            if (!is_object($data)) {
                $values = array('name' => $name);
                $this->db
                    ->insertInto('company')
                    ->values($values)
                    ->execute();
            }
        }
    }

    public function get($id)
    {
        return $this->db
            ->from($this->table, $id)
            ->fetch();
    }

    public function getByDni($dni)
    {
        return $this->db
            ->from($this->table)
            ->where('dni', $dni)
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
                ->SetResponse(false, 'El visitante no exite');
        }
        return $this->response->SetResponse(true);
    }
}
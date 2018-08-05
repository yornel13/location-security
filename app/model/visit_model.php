<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class VisitModel
{
    private $db;
    private $table = 'control_visit';
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
        $data['status'] = 1;

        try {
            $query = $this->db
                ->insertInto($this->table, $data)
                ->execute();
            $data['id'] = $query;
            $this->response->result = $data;
            return $this->response->SetResponse(true);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "foreign key")) {
                return $this->response->SetResponse(false, 'Uno o mas de los id no existe');
            }
            return $this->response->SetResponse(false);
        }
    }

    public function finish($id)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $dataF['finish_date'] = $timestamp;
        $dataF['status'] = 0;

        $query = $this->db
            ->update($this->table, $dataF, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'La visita no exite');
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

    public function getAllActive()
    {
        $data = $this->db
            ->from($this->table)
            ->where('status', 1)
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
                ->SetResponse(false, 'La visita no exite');
        }
        return $this->response->SetResponse(true);
    }
}
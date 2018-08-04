<?php
namespace App\Model;


use App\Lib\Response;

class SpecialReportModel
{
    private $db;
    private $table = 'special_report';
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
        $data['status'] = 1;

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;

        return $this->response->SetResponse(true);
    }

    public function update($id)
    {
        $data = null;
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_date'] = $timestamp;
        $data['status'] = 2;

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
        $this->db
            ->deleteFrom($this->table, $id)
            ->execute();

        return $this->response->SetResponse(true);
    }
}
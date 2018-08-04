<?php
namespace App\Model;


use App\Lib\Response;

class VisitorVehicleModel
{
    private $db;
    private $table = 'visitor_vehicle';
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
            ->where('plate', $data['plate'])
            ->fetch();

        if (!empty($guard)) {
            $key = 'plate';
            $this->response->errors[$key][] = 'La placa se encuentra asociada a otro vehiculo';
            return $this->response->SetResponse(false);
        }

        $this->db
            ->insertInto($this->table, $data)
            ->execute();

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
            ->where('plate', $data['plate'])
            ->fetch();

        if (!empty($guard) && $guard->id !== $id) {
            $key = 'plate';
            $this->response->errors[$key][] = 'La placa se encuentra asociada a otro vehiculo';
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

    public function getByPlate($plate)
    {
        return $this->db
            ->from($this->table)
            ->where('plate', $plate)
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
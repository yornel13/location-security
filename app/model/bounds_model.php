<?php
namespace App\Model;


use App\Lib\Response;

class BoundsModel
{
    private $db;
    private $table = 'bounds';
    private $table_vehicle_bounds = 'vehicle_bound';
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
            $this->response->errors[$key][] = 'Ya existe un cerco con este nombre';
            return $this->response->SetResponse(false);
        }

        if (is_array($data['points'])) {
            $data['points'] = json_encode($data['points']);
        }
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_at'] = $timestamp;
        $data['update_at'] = $timestamp;
        $data['status'] = 1;

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
            $this->response->errors[$key][] = 'Ya existe un cerco con este nombre';
            return $this->response->SetResponse(false);
        }
        if (is_array($data['points'])) {
            $data['points'] = json_encode($data['points']);
        }
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_at'] = $timestamp;

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El cerco no exite');
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

    public function addToBounds($id, $data)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);

        foreach ($data as $valor) {
            $valor['bounds_id'] = $id;
            $valor['create_at'] = $timestamp;

            $registered = $this->db
                ->from($this->table_vehicle_bounds)
                ->where('imei', $valor['imei'])
                ->fetch();

            if (is_object($registered)) {
                $this->deleteVehicleBounds($registered->id);
            }
            $this->db
                ->insertInto($this->table_vehicle_bounds, $valor)
                ->execute();
        }
        return $this->response->SetResponse(true);
    }

    public function getVehiclesBound($bounds_id)
    {
        $data = $this->db
            ->from($this->table_vehicle_bounds)
            ->where("bounds_id", $bounds_id)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getVehiclesByBounds($vehicle_bounds_id)
    {
        $data = $this->db
            ->from($this->table_vehicle_bounds, $vehicle_bounds_id)
            ->fetch();

        return $data;
    }

    public function getVehiclesBoundByImei($imei)
    {
        $data = $this->db
            ->from($this->table_vehicle_bounds)
            ->where("imei", $imei)
            ->select("bounds.name as bounds_name")
            ->select("bounds.points as bounds_points")
            ->fetch();

        return $data;
    }

    public function delete($id)
    {
        $query = $this->db
            ->deleteFrom($this->table, $id)
            ->execute();
        if ($query === 0) {
            return $this->response
                ->SetResponse(false, 'El cerco no exite');
        }
        return $this->response->SetResponse(true);
    }

    public function deleteVehicleBounds($vehicle_bounds_id)
    {
        $query = $this->db
            ->deleteFrom($this->table_vehicle_bounds, $vehicle_bounds_id)
            ->execute();
        if ($query === 0) {
            return $this->response
                ->SetResponse(false, 'Esta asociación no existe');
        }
        return $this->response->SetResponse(true);
    }
}
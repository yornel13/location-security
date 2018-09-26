<?php
namespace App\Model;


use App\Lib\Response;
use DateTime;
use Exception;

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

    public function save($data)
    {
        $vehicle = $this->db
            ->from($this->table)
            ->where('plate', $data['plate'])
            ->where('active', 1)
            ->fetch();

        if (is_object($vehicle)) {
            $this->response->result = (array) $vehicle;
            return $this->response->SetResponse(true, 'La placa se encuentra asociada a otro vehiculo');
        }

        $data['create_date'] = (new DateTime($data['create_date']))->format('Y-m-d H:i:s');
        $data['update_date'] = (new DateTime($data['update_date']))->format('Y-m-d H:i:s');
        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        return $this->response->SetResponse(true);
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
            ->where('active', 1)
            ->fetch();

        if (!empty($guard)) {
            $key = 'plate';
            $this->response->errors[$key][] = 'La placa se encuentra asociada a otro vehiculo';
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

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El vehiculo no exite');
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

        $visits = (new VisitModel($this->db))->getAllGroup()['data'];
        foreach ($visits as $visit) {
            foreach ($data as $vehicle) {
                if ($visit->vehicle_id == $vehicle->id) {
                    $lastVisit = [
                        'vehicle_id' => $visit->vehicle_id,
                        'visitor_id' => $visit->visitor_id,
                        'visited_id' => $visit->visited_id,
                        'guard_id' => $visit->guard_id,
                        'visit_id' => $visit->id
                    ];
                    $vehicle->last_visit = $lastVisit;
                }
            }
        }

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getAllActive()
    {
        $data = $this->db
            ->from($this->table)
            ->where('active', 1)
            ->orderBy('id DESC')
            ->fetchAll();

        $visits = (new VisitModel($this->db))->getAllGroup()['data'];
        foreach ($visits as $visit) {
            foreach ($data as $vehicle) {
                if ($visit->vehicle_id == $vehicle->id) {
                    $lastVisit = [
                        'vehicle_id' => $visit->vehicle_id,
                        'visitor_id' => $visit->visitor_id,
                        'visited_id' => $visit->visited_id,
                        'guard_id' => $visit->guard_id,
                        'visit_id' => $visit->id
                    ];
                    $vehicle->last_visit = $lastVisit;
                }
            }
        }

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function delete($id)
    {
        try {
            $query = $this->db
                ->deleteFrom($this->table, $id)
                ->execute();

            if ($query === 0) {
                return $this->response
                    ->SetResponse(false, 'El vehiculo no exite');
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "FOREIGN KEY")) {
                $timestamp = time() - (5 * 60 * 60);
                $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
                $set = null;
                $set['update_date'] = $timestamp;
                $set['active'] = 0;
                $this->db
                    ->update($this->table, $set, $id)
                    ->execute();
            } else {
                return $this->response->SetResponse(false);
            }
        }
        return $this->response->SetResponse(true);
    }
}
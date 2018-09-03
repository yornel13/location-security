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
            $this->response->result = $this->get($query);
            return $this->response->SetResponse(true);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "foreign key")) {
                if (strpos($e->getMessage(), "guard_id")) {
                    $this->response->errors['guard_id'][] = 'ID no encontrado';
                }
                if (strpos($e->getMessage(), "visitor_id")) {
                    $this->response->errors['visitor_id'][] = 'ID no encontrado';
                }
                if (strpos($e->getMessage(), "vehicle_id")) {
                    $this->response->errors['vehicle_id'][] = 'ID no encontrado';
                }
                if (strpos($e->getMessage(), "visited_id")) {
                    $this->response->errors['visited_id'][] = 'ID no encontrado';
                }
                return $this->response->SetResponse(false, 'Uno o mas de los id no existe');
            }
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function finish($id, $data)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['finish_date'] = $timestamp;
        $data['status'] = 0;

        $query = $this->db
            ->update($this->table, $data, $id)
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
        $data = $this->db
            ->from($this->table, $id)
            ->fetch();
        if (is_object($data)) {
            if ($data->vehicle_id != null) {
                $data->vehicle = $this->db
                    ->from('visitor_vehicle', $data->vehicle_id)
                    ->fetch();
            }
            if ($data->visited_id != null) {
                $data->visited = $this->db
                    ->from('clerk_visited', $data->visited_id)
                    ->fetch();
            }
            if ($data->visitor_id != null) {
                $data->visitor = $this->db
                    ->from('visitor', $data->visitor_id)
                    ->fetch();
            }
            if ($data->guard_id != null) {
                $data->guard = $this->db
                    ->from('guard', $data->guard_id)
                    ->fetch();
                $data->guard->password = null;
            }
        }
        return $data;
    }

    public function getAll()
    {
        $list = $this->db
            ->from($this->table)
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($list),
            'data' => $list
        ];
    }

    public function getAllGroup()
    {
        $list = $this->db
            ->from($this->table)
            ->groupBy('vehicle_id DESC')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($list),
            'data' => $list
        ];
    }

    public function getAllActive()
    {
        $list = $this->db
            ->from($this->table)
            ->where('status', 1)
            ->orderBy('id DESC')
            ->fetchAll();
        foreach ($list as $data)
        {
            if ($data->vehicle_id != null) {
                $data->vehicle = $this->db
                    ->from('visitor_vehicle', $data->vehicle_id)
                    ->fetch();
            }
            if ($data->visited_id != null) {
                $data->visited = $this->db
                    ->from('clerk_visited', $data->visited_id)
                    ->fetch();
            }
            if ($data->visitor_id != null) {
                $data->visitor = $this->db
                    ->from('visitor', $data->visitor_id)
                    ->fetch();
            }
            if ($data->guard_id != null) {
                $data->guard = $this->db
                    ->from('guard', $data->guard_id)
                    ->fetch();
                $data->guard->password = null;
            }
        }
        return [
            'total' => count($list),
            'data' => $list
        ];
    }

    public function getByDate($year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false)
    {
        $timestamp = time()-(5*60*60);
        if (is_bool($year) && !$year) {
            $year = gmdate("Y", $timestamp);
        }
        if (is_bool($month) && !$month) {
            $month = gmdate("m", $timestamp);
        }
        if (is_bool($day) && !$day) {
            $day = gmdate("d", $timestamp);
        }
        if (is_bool($t_year) && !$t_year) {
            $t_year = gmdate("Y", $timestamp);
        }
        if (is_bool($t_month) && !$t_month) {
            $t_month = gmdate("m", $timestamp);
        }
        if (is_bool($t_day) && !$t_day) {
            $t_day = gmdate("d", $timestamp);
        }
        $data = $this->db
            ->from($this->table)
            ->where('control_visit.create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('control_visit.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByDateAndProperty($propertyName, $propertyValue, $status, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false)
    {
        $timestamp = time()-(5*60*60);
        if (is_bool($year) && !$year) {
            $year = gmdate("Y", $timestamp);
        }
        if (is_bool($month) && !$month) {
            $month = gmdate("m", $timestamp);
        }
        if (is_bool($day) && !$day) {
            $day = gmdate("d", $timestamp);
        }
        if (is_bool($t_year) && !$t_year) {
            $t_year = gmdate("Y", $timestamp);
        }
        if (is_bool($t_month) && !$t_month) {
            $t_month = gmdate("m", $timestamp);
        }
        if (is_bool($t_day) && !$t_day) {
            $t_day = gmdate("d", $timestamp);
        }
        if ($status === 'all') {
            $status = array(0,1);
        }
        $data = $this->db
            ->from($this->table)
            ->where('control_visit.create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('control_visit.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
            ->where($propertyName, $propertyValue)
            ->where('status', $status)
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByGuardInDate($id, $status, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('guard_id', $id, $status, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByVehicleInDate($id, $status, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('vehicle_id', $id, $status, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByVisitorInDate($id, $status, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('visitor_id', $id, $status, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByClerkInDate($id, $status, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('visited_id', $id, $status, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByStatusInDate($status, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('control_visit.id > ?', 0, $status, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByGuard($id, $status)
    {
        if ($status === 'all') {
            $status = array(0,1);
        }
        $data = $this->db
            ->from($this->table)
            ->where('guard_id', $id)
            ->where('status', $status)
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByVehicle($id, $status)
    {
        if ($status === 'all') {
            $status = array(0,1);
        }
        $data = $this->db
            ->from($this->table)
            ->where('vehicle_id', $id)
            ->where('status', $status)
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByVisitor($id, $status)
    {
        if ($status === 'all') {
            $status = array(0,1);
        }
        $data = $this->db
            ->from($this->table)
            ->where('visitor_id', $id)
            ->where('status', $status)
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByClerk($id, $status)
    {
        if ($status === 'all') {
            $status = array(0,1);
        }
        $data = $this->db
            ->from($this->table)
            ->where('visited_id', $id)
            ->where('status', $status)
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByStatus($status)
    {
        if ($status === 'all') {
            $status = array(0,1);
        }
        $data = $this->db
            ->from($this->table)
            ->where('status', $status)
            ->select('visitor.dni AS visitor_dni')
            ->select('visitor.name AS visitor_name')
            ->select('visitor.lastname AS visitor_lastname')
            ->leftJoin('visitor_vehicle ON visitor_vehicle.id = vehicle_id')
            ->select('visitor_vehicle.plate')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
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
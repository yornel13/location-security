<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class WatchModel
{
    private $db;
    private $table = 'watch';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function start($data)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_date'] = $timestamp;
        $data['status'] = 1;

        $watch = $this->getWatchActiveByGuard($data['guard_id']);
        if ($watch != null) {
            $watch->resumed = true;
            $this->response->result = $watch;
            return $this->response->SetResponse(true);
        }

        try {
            $query = $this->db
                ->insertInto($this->table, $data)
                ->execute();
            $data['id'] = $query;
            $data['resumed'] = false;
            $this->alertStart($data['guard_id'], $data['latitude'], $data['longitude']);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "foreign key")) {
                return $this->response->SetResponse(false, 'Asociacion erronea');
            }
            return $this->response->SetResponse(false, $e->getMessage());
        }

        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function end($data, $id)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_date'] = $timestamp;
        $data['status'] = 0;

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'La guardia no exite');
        } else {
            $watch = $this->get($id);
            $this->response->result = $watch;
            $this->alertEnd($watch->guard_id, $watch->f_latitude, $watch->f_longitude);
        }
        return $this->response->SetResponse(true);
    }

    public function get($id)
    {
        $watch = $this->db
            ->from($this->table, $id)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->fetch();

        return $watch;
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
            ->where('watch.create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('watch.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->orderBy('watch.id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByDateAndProperty($propertyName, $propertyValue, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false)
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
            ->where('watch.create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('watch.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
            ->where($propertyName, $propertyValue)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->orderBy('watch.id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByGuardInDate($id, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('guard_id', $id, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getWatchActiveByGuard($id)
    {
        return $this->db
            ->from($this->table)
            ->where('watch.status', 1)
            ->where('guard_id', $id)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->fetch();
    }

    public function getAll()
    {
        $data = $this->db
            ->from($this->table)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->orderBy('watch.id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByGuard($guard_id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('guard_id', $guard_id)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->orderBy('watch.id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getAllActiveByGuard($guard_id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('guard_id', $guard_id)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->where('watch.status', 1)
            ->orderBy('watch.id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getAllActive()
    {
        $watches = $this->db
            ->from($this->table)
            ->where('status', 1)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('stand.name as stand_name')
            ->select('stand.address as stand_address')
            ->select('tablet.imei as tablet_imei')
            ->orderBy('watch.id DESC')
            ->fetchAll();

        return [
            'data' => $watches,
            'total' => count($watches)
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
                    ->SetResponse(false, 'La guardia no exite');
            }
            return $this->response->SetResponse(true);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "special_report_fk1")) {
                return $this->response
                    ->SetResponse(false, 'No se puede borrar esta guardia');
            }
        }
    }

    public function alertStart($guard_id, $latitude, $longitude) {
        $guardService = new GuardModel($this->db);
        $guard = $guardService->get($guard_id);
        $name = $guard->name." ".$guard->lastname;
        $alert = [
            "guard_id" => $guard_id,
            "cause" => AlertModel::GENERAL,
            "message" => $name." ha iniciado su guardia",
            "latitude" => $latitude,
            "longitude" => $longitude,
        ];
        $alertService = new AlertModel($this->db);
        $alertService->registerGeneral($alert);
    }

    public function alertEnd($guard_id, $latitude, $longitude) {
        $guardService = new GuardModel($this->db);
        $guard = $guardService->get($guard_id);
        $name = $guard->name." ".$guard->lastname;
        $alert = [
            "guard_id" => $guard_id,
            "cause" => AlertModel::GENERAL,
            "message" => $name." ha finalizado su guardia",
            "latitude" => $latitude,
            "longitude" => $longitude,
        ];
        $alertService = new AlertModel($this->db);
        $alertService->registerGeneral($alert);
    }
}
<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class WatchModel
{
    private $db;
    private $table = 'watch';
    private $table_session = 'tablet_token';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function start($data, $token)
    {
        $guard_service = new GuardModel($this->db);
        $guard = $guard_service->get($data['guard_id']);
        $tablet_service = new TabletModel($this->db);
        $tablet = $tablet_service->getTabletByImei($data['tablet_imei']);
        if ($guard->stand_id == null) {
            return $this->response->SetResponse(false, 'Debes estar asociado a este puesto para poder iniciar.');
        }

        if (is_object($tablet) && ((int) $tablet->status) == 1) {
            if ((int) $guard->stand_id != (int) $tablet->stand_id) {
                return $this->response->SetResponse(false, 'El dispositivo no pertenece a tu puesto');
            }
        } else {
            return $this->response->SetResponse(false, 'El dispositivo no esta asociado a un puesto');
        }

        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_date'] = $timestamp;
        $data['stand_id'] = $tablet->stand_id;
        $data['stand_name'] = $tablet->stand_name;
        $data['stand_address'] = $tablet->stand_address;
        $data['status'] = 1;

        $watch = $this->getWatchActiveByGuard($data['guard_id']);

        try {
            if (is_object($watch)) {
                $watch = (array) $watch;
                $watch['resumed'] = true;
            } else {
                $this->db
                    ->insertInto($this->table, $data)
                    ->execute();
                $watch = $this->getWatchActiveByGuard($data['guard_id']);
                $watch = (array) $watch;
                $watch['resumed'] = false;
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "foreign key")) {
                return $this->response->SetResponse(false, 'Asociacion erronea');
            }
            return $this->response->SetResponse(false, $e->getMessage());
        }
        $this->db
            ->deleteFrom($this->table_session)
            ->where('guard_id', $data['guard_id'])
            ->execute();
        $session = [
            'imei' => $data['tablet_imei'],
            'session' => $token,
            'guard_id' => $data['guard_id'],
            'init_at' => $timestamp,
        ];
        $this->db
            ->insertInto($this->table_session, $session)
            ->execute();

        $this->generate_record_start((object) $watch, $data['latitude'], $data['longitude'], $timestamp);
        $this->alertStart($data['guard_id'], $data['latitude'], $data['longitude'], $watch['resumed'], $data['tablet_imei']);

        $this->response->result = $watch;
        return $this->response->SetResponse(true);
    }

    public function generate_record_start($watch, $latitude, $longitude, $timestamp)
    {
        $position = array();
        $position['latitude'] = $latitude;
        $position['longitude'] = $longitude;
        $position['generated_time'] = $timestamp;
        $position['message_time'] = $timestamp;
        $position['watch_id'] = $watch->id;
        $position['imei'] = $watch->tablet_imei;
        $name = $watch->guard_name." ".$watch->guard_lastname;
        if ($watch->resumed) {
            $position['message'] = 'RESUMED_WATCH';
            $position['alert_message'] = $name." ha retomado su guardia";
        } else {
            $position['message'] = 'INIT_WATCH';
            $position['alert_message'] = $name." ha iniciado su guardia";
        }
        $position['is_exception'] = true;
        $tabletService = new TabletModel($this->db);
        $tabletService->register($position);
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
            $this->generate_record_end((object) $watch, $data['f_latitude'], $data['f_longitude'], $timestamp);
            $this->alertEnd($watch->guard_id, $watch->f_latitude, $watch->f_longitude, $watch->tablet_imei);
        }
        return $this->response->SetResponse(true);
    }

    public function generate_record_end($watch, $latitude, $longitude, $timestamp)
    {
        $position = array();
        $position['latitude'] = $latitude;
        $position['longitude'] = $longitude;
        $position['generated_time'] = $timestamp;
        $position['message_time'] = $timestamp;
        $position['watch_id'] = $watch->id;
        $position['imei'] = $watch->tablet_imei;
        $name = $watch->guard_name." ".$watch->guard_lastname;
        $position['message'] = 'FINISHED_WATCH';
        $position['alert_message'] = $name." ha finalizado su guardia";
        $position['is_exception'] = true;
        $tabletService = new TabletModel($this->db);
        $tabletService->register($position);
    }

    public function get($id)
    {
        $watch = $this->db
            ->from($this->table, $id)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
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
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
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
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
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

    public function getByStandInDate($id, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('watch.stand_id', $id, $year, $month, $day, $t_year, $t_month, $t_day);
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
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
            ->fetch();
    }

    public function getAll()
    {
        $data = $this->db
            ->from($this->table)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
            ->orderBy('id DESC')
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
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
            ->orderBy('watch.id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByStand($stand_id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('stand_id', $stand_id)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
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
            ->where('watch.status', 1)
            ->select('guard.dni as guard_dni')
            ->select('guard.name as guard_name')
            ->select('guard.lastname as guard_lastname')
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
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
            ->select('guard.email as guard_email')
            ->select('guard.photo as guard_photo')
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

    public function alertStart($guard_id, $latitude, $longitude, $resumed, $imei) {
        $guardService = new GuardModel($this->db);
        $guard = $guardService->get($guard_id);
        $name = $guard->name." ".$guard->lastname;
        if (!$resumed) {
            $text = " ha iniciado su guardia";
        } else {
            $text = " ha retomado su guardia";
        }
        $alert = [
            "guard_id" => $guard_id,
            "cause" => AlertModel::GENERAL,
            "type" => AlertModel::INIT_WATCH,
            "imei" => $imei,
            "message" => $name.$text,
            "latitude" => $latitude,
            "longitude" => $longitude,
        ];
        $alertService = new AlertModel($this->db);
        return $alertService->registerGeneral($alert);
    }

    public function alertEnd($guard_id, $latitude, $longitude, $imei) {
        $guardService = new GuardModel($this->db);
        $guard = $guardService->get($guard_id);
        $name = $guard->name." ".$guard->lastname;
        $alert = [
            "guard_id" => $guard_id,
            "cause" => AlertModel::GENERAL,
            "type" => AlertModel::FINISH_WATCH,
            "imei" => $imei,
            "message" => $name." ha finalizado su guardia",
            "latitude" => $latitude,
            "longitude" => $longitude,
        ];
        $alertService = new AlertModel($this->db);
        $alertService->registerGeneral($alert);
    }
}
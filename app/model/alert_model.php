<?php
namespace App\Model;


use App\Lib\Response;
use DateTime;

class AlertModel
{
    const OUT_BOUNDS = "OUT_BOUNDS";
    const IN_BOUNDS = "IN_BOUNDS";
    const IGNITION_ON = "IGNITION_ON";
    const IGNITION_OFF = "IGNITION_OFF";
    const SPEED_MAX = "SPEED_MAX";
    const GENERAL = "GENERAL";
    const INIT_WATCH = "INIT_WATCH";
    const FINISH_WATCH = "FINISH_WATCH";
    const INCIDENCE = "INCIDENCE";
    const INCIDENCE_LEVEL_1 = "INCIDENCE_LEVEL_1";
    const INCIDENCE_LEVEL_2 = "INCIDENCE_LEVEL_2";
    const DROP = "DROP";
    const SOS1 = "SOS1";

    private $db;
    private $table = 'alert';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function save($data)
    {
        $data['create_date'] = (new DateTime($data['create_date']))->format('Y-m-d H:i:s');
        $data['update_date'] = (new DateTime($data['update_date']))->format('Y-m-d H:i:s');
        $data['status'] = (int) $data['status'];

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        $this->notify($data);
        return $this->response->SetResponse(true);
    }

    public function registerGeneral($data)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_date'] = $timestamp;
        $data['update_date'] = $timestamp;
        $data['status'] = 0;

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        $this->notify($data);
        return $this->response->SetResponse(true);
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
        if ($data['cause'] == AlertModel::SOS1 || $data['cause'] == AlertModel::DROP) {
            $this->generate_record((object) $data);
        }
        $this->notify($data);
        return $this->response->SetResponse(true);
    }

    public function generate_record($alert)
    {
        $watchService = new WatchModel($this->db);
        $watch = $watchService->getWatchActiveByGuard($alert->guard_id);
        if (!is_object($watch)) {
            return;
        }
        $position = array();
        $position['latitude'] = $alert->latitude;
        $position['longitude'] = $alert->longitude;
        $position['generated_time'] = $alert->create_date;
        $position['message_time'] = $alert->create_date;
        $position['watch_id'] = $watch->id;
        $position['imei'] = $watch->tablet_imei;
        $position['message'] = $alert->cause;
        $position['alert_message'] = $alert->message;
        $position['is_exception'] = true;
        $tabletService = new TabletModel($this->db);
        $tabletService->register($position);
    }

    private function notify($alert) {
        $notificationService = new MessengerModel($this->db);
        return $notificationService->send_alert_notification($alert);
    }

    public function update($id)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data = null;
        $data['update_date'] = $timestamp;
        $data['status'] = 0;

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'La alerta no exite');
        } else {
            $this->response->result = $this->get($id);
        }

        return $this->response->SetResponse(true);
    }

    public function getByCauseInDate($cause, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false)
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
        if ($cause === 'all') {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
                ->where('cause', $cause)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        }

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByCauseAndGuardInDate($cause, $guard_id, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false)
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
        if ($cause === 'all') {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
                ->where('guard_id', $guard_id)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
                ->where('cause', $cause)
                ->where('guard_id', $guard_id)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        }

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function get($id)
    {
        $alert = $this->db
            ->from($this->table, $id)
            ->fetch();
        if (is_object($alert)) {
            if ($alert->guard_id != null) {
                $guardService = new GuardModel($this->db);
                $alert->guard = $guardService->get($alert->guard_id);
            }
        }
        return $alert;
    }

    public function getAll()
    {
        $data = $this->db
            ->from($this->table)
            ->orderBy('id DESC')
            ->select("guard.dni as guard_dni")
            ->select("guard.name as guard_name")
            ->select("guard.lastname as guard_lastname")
            ->orderBy('alert.create_date DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getAllActive()
    {
        $data = $this->db
            ->from($this->table)
            ->where('status', 1)
            ->orderBy('id DESC')
            ->select("guard.dni as guard_dni")
            ->select("guard.name as guard_name")
            ->select("guard.lastname as guard_lastname")
            ->orderBy('alert.create_date DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByCause($cause)
    {
        if ($cause === 'all') {
            $cause = null;
            $data = $this->db
                ->from($this->table)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('cause', $cause)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        }
        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByGuard($cause, $guard_id)
    {
        if ($cause === 'all') {
            $data = $this->db
                ->from($this->table)
                ->where('guard_id', $guard_id)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('cause', $cause)
                ->where('guard_id', $guard_id)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->select("guard.lastname as guard_lastname")
                ->orderBy('alert.create_date DESC')
                ->fetchAll();
        }

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
                ->SetResponse(false, 'La alerta no exite');
        }
        return $this->response->SetResponse(true);
    }

    public function getOutSideBoundsByImeiInDate($imei, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {

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
            ->where('type', AlertModel::OUT_BOUNDS)
            ->where('imei', $imei)
            ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
            ->where('alert.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
            ->orderBy('id ASC')
            ->fetchAll();

        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        foreach ($data as $alert) {
            $in = $this->db
                ->from($this->table)
                ->where('imei', $alert->imei)
                ->where('type', AlertModel::IN_BOUNDS)
                ->where('id > ?', $alert->id)
                ->fetch();
            $vehicle = $this->db
                ->from('vehicle')
                ->where('imei', $alert->imei)
                ->fetch();
            $alert->alias = $vehicle->alias;
            if (is_object($in)) {
                $alert->in = $in;
                $alert->diff_sec = $this->diff($alert->create_date, $in->create_date);
                $alert->diff_text = $this->dateDiff($alert->create_date, $in->create_date);
            } else {
                $alert->diff_sec = $this->diff($alert->create_date, $timestamp);
                $alert->diff_text = $this->dateDiff($alert->create_date, $timestamp);
            }
        }

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getOutSideBoundsInDate($year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {

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
            ->where('type', AlertModel::OUT_BOUNDS)
            ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
            ->where('alert.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
            ->orderBy('id ASC')
            ->fetchAll();

        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        foreach ($data as $alert) {
            $in = $this->db
                ->from($this->table)
                ->where('imei', $alert->imei)
                ->where('type', AlertModel::IN_BOUNDS)
                ->where('id > ?', $alert->id)
                ->fetch();
            $vehicle = $this->db
                ->from('vehicle')
                ->where('imei', $alert->imei)
                ->fetch();
            $alert->alias = $vehicle->alias;
            if (is_object($in)) {
                $alert->in = $in;
                $alert->diff_sec = $this->diff($alert->create_date, $in->create_date);
                $alert->diff_text = $this->dateDiff($alert->create_date, $in->create_date);
            } else {
                $alert->diff_sec = $this->diff($alert->create_date, $timestamp);
                $alert->diff_text = $this->dateDiff($alert->create_date, $timestamp);
            }
        }

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getOutSideBoundsByImei($imei) {
        $data = $this->db
            ->from($this->table)
            ->where('type', AlertModel::OUT_BOUNDS)
            ->where('imei', $imei)
            ->orderBy('id ASC')
            ->fetchAll();

        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        foreach ($data as $alert) {
            $in = $this->db
                ->from($this->table)
                ->where('imei', $alert->imei)
                ->where('type', AlertModel::IN_BOUNDS)
                ->where('id > ?', $alert->id)
                ->fetch();
            $vehicle = $this->db
                ->from('vehicle')
                ->where('imei', $alert->imei)
                ->fetch();
            $alert->alias = $vehicle->alias;
            if (is_object($in)) {
                $alert->in = $in;
                $alert->diff_sec = $this->diff($alert->create_date, $in->create_date);
                $alert->diff_text = $this->dateDiff($alert->create_date, $in->create_date);
            } else {
                $alert->diff_sec = $this->diff($alert->create_date, $timestamp);
                $alert->diff_text = $this->dateDiff($alert->create_date, $timestamp);
            }
        }

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getOutSideBounds()
    {
        $data = $this->db
            ->from($this->table)
            ->where('type', AlertModel::OUT_BOUNDS)
            ->orderBy('id ASC')
            ->fetchAll();

        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        foreach ($data as $alert) {
            $in = $this->db
                ->from($this->table)
                ->where('imei', $alert->imei)
                ->where('type', AlertModel::IN_BOUNDS)
                ->where('id > ?', $alert->id)
                ->fetch();
            $vehicle = $this->db
                ->from('vehicle')
                ->where('imei', $alert->imei)
                ->fetch();
            $alert->alias = $vehicle->alias;
            if (is_object($in)) {
                $alert->in = $in;
                $alert->diff_sec = $this->diff($alert->create_date, $in->create_date);
                $alert->diff_text = $this->dateDiff($alert->create_date, $in->create_date);
            } else {
                $alert->diff_sec = $this->diff($alert->create_date, $timestamp);
                $alert->diff_text = $this->dateDiff($alert->create_date, $timestamp);
            }
        }

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    function diff($qw , $saw)
    {
        $datetime1 = strtotime($qw);
        $datetime2 = strtotime($saw);
        $interval = $datetime2 - $datetime1;
        return $interval;
    }

    function dateDiff($date, $date2)
    {
        $datetime1 = date_create($date);
        $datetime2 = date_create($date2);
        $interval = date_diff($datetime1, $datetime2);
        $min=$interval->format('%i');
        $sec=$interval->format('%s');
        $hour=$interval->format('%h');
        $mon=$interval->format('%m');
        $day=$interval->format('%d');
        $year=$interval->format('%y');
        if($interval->format('%i%h%d%m%y')=="00000")
        {
            //echo $interval->format('%i%h%d%m%y')."<br>";
            return $sec." Segundos";

        }

        else if($interval->format('%h%d%m%y')=="0000"){
            return $min." Minutos";
        }


        else if($interval->format('%d%m%y')=="000"){
            return $hour." Horas";
        }


        else if($interval->format('%m%y')=="00"){
            return $day." Dias";
        }

        else if($interval->format('%y')=="0"){
            return $mon." Meses";
        }

        else{
            return $year." Años";
        }

    }
}
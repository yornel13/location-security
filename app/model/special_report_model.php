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
        $data['resolved'] = 1;

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $report = $this->get($query);
        $this->response->result = $report;
        $this->alertIncidence($report);
        return $this->response->SetResponse(true);
    }

    public function accept($id)
    {
        $data = null;
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_date'] = $timestamp;
        $data['status'] = 2;

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El reporte especial no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
    }

    public function resolved($id)
    {
        $data = null;
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_date'] = $timestamp;
        $data['resolved'] = 0;
        $data['status'] = 2;

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El reporte especial no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
    }

    public function reOpen($id)
    {
        $data = null;
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_date'] = $timestamp;
        $data['resolved'] = 2;

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El reporte especial no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
    }

    public function get($id)
    {
        $query = $this->db
            ->from($this->table, $id)
            ->fetch();

        if (is_object($query)) {
            $incidenceModel = new IncidenceModel($this->db);
            $query->incidence = $incidenceModel->get($query->incidence_id);
            $watchModel = new WatchModel($this->db);
            $query->watch = $watchModel->get($query->watch_id);
        }
        return $query;
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
            ->where('create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('create_date <= ?', $t_year."-".$t_month."-".$t_day." 23:59:59")
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByDateAndProperty($propertyName, $propertyValue, $resolved, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false)
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
        if ($resolved === 'all') {
            $resolved = array(0,1,2);
        }
        if ($resolved === 'open') {
            $resolved = array(1,2);
        }
        $data = $this->db
            ->from($this->table)
            ->where('special_report.create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('special_report.create_date <= ?', $t_year . "-" . $t_month . "-" . $t_day . " 23:59:59")
            ->where($propertyName, $propertyValue)
            ->where('resolved', $resolved)
            ->select('watch.guard.dni AS guard_dni')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByGuardInDate($id, $resolved, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('watch.guard_id', $id, $resolved, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByWatchInDate($id, $resolved, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('watch_id', $id, $resolved, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByIncidenceInDate($id, $resolved, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('incidence_id', $id, $resolved, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByResolvedInDate($resolved, $year = false, $month = false, $day = false, $t_year = false, $t_month = false, $t_day = false) {
        return $this->getByDateAndProperty('special_report.id > ?', 0, $resolved, $year, $month, $day, $t_year, $t_month, $t_day);
    }

    public function getByIncidence($id, $resolved)
    {
        if ($resolved === 'all') {
            $resolved = array(0,1,2);
        }
        if ($resolved === 'open') {
            $resolved = array(1,2);
        }
        $data = $this->db
            ->from($this->table)
            ->where('incidence_id', $id)
            ->where('resolved', $resolved)
            ->select('watch.guard.dni AS guard_dni')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByWatch($id, $resolved)
    {
        if ($resolved === 'all') {
            $resolved = array(0,1,2);
        }
        if ($resolved === 'open') {
            $resolved = array(1,2);
        }
        $data = $this->db
            ->from($this->table)
            ->where('watch_id', $id)
            ->where('resolved', $resolved)
            ->select('watch.guard.dni AS guard_dni')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByGuard($id, $resolved)
    {
        if ($resolved === 'all') {
            $resolved = array(0,1,2);
        }
        if ($resolved === 'open') {
            $resolved = array(1,2);
        }
        $data = $this->db
            ->from($this->table)
            ->where('watch.guard_id', $id)
            ->where('resolved', $resolved)
            ->select('watch.guard.dni AS guard_dni')
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getByResolved($resolved)
    {
        if ($resolved === 'all') {
            $resolved = array(0,1,2);
        }
        if ($resolved === 'open') {
            $resolved = array(1,2);
        }
        $data = $this->db
            ->from($this->table)
            ->where('resolved', $resolved)
            ->select('watch.guard.dni AS guard_dni')
            ->orderBy('id DESC')
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
            ->select('watch.guard.dni AS guard_dni')
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
                ->SetResponse(false, 'El reporte especial no exite');
        }
        return $this->response->SetResponse(true);
    }

    public function alertIncidence($report) {
        $name = $report->watch->guard->name." ".$report->watch->guard->lastname;
        if ($report->incidence->level > 1) {
            $message = $name." ha creado un reporte de incidencia Importante";
        } else {
            $message = $name." ha creado un reporte de incidencia";
        }
        $alert = [
            "guard_id" => $report->watch->guard_id,
            "cause" => AlertModel::INCIDENCE,
            "message" => $message,
            "latitude" => $report->latitude,
            "longitude" => $report->longitude,
            "extra" => json_encode($report)
        ];
        $alertService = new AlertModel($this->db);
        if ($report->incidence->level > 1) {
            $alertService->register($alert);
        } else {
            $alertService->registerGeneral($alert);
        }
    }
}
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

        $this->response->result = $this->get($query);
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

    public function getByDate($year = false, $month = false, $day = false)
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
        $data = $this->db
            ->from($this->table)
            ->where('create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('create_date <= ?', $year."-".$month."-".$day." 23:59:59")
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByDateAndProperty($propertyName, $propertyValue, $year = false, $month = false, $day = false)
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
        $data = $this->db
            ->from($this->table)
            ->where('special_report.create_date >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('special_report.create_date <= ?', $year."-".$month."-".$day." 23:59:59")
            ->where($propertyName, $propertyValue)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByGuardInDate($id, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('watch.guard_id', $id, $year, $month, $day);
    }

    public function getByWatchInDate($id, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('watch_id', $id, $year, $month, $day);
    }

    public function getByIncidenceInDate($id, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('incidence_id', $id, $year, $month, $day);
    }

    public function getByResolvedInDate($resolved, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('resolved', $resolved, $year, $month, $day);
    }

    public function getAllOpenInDate($year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('resolved > ?', 0, $year, $month, $day);
    }

    public function getByIncidence($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('incidence_id', $id)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByWatch($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('watch_id', $id)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByGuard($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('watch.guard_id', $id)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByResolved($resolved)
    {
        $data = $this->db
            ->from($this->table)
            ->where('resolved', $resolved)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
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

    public function getAllOpen()
    {
        $data = $this->db
            ->from($this->table)
            ->where('resolved > ?', 0)
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
                ->SetResponse(false, 'El reporte especial no exite');
        }
        return $this->response->SetResponse(true);
    }
}
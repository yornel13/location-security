<?php
namespace App\Model;


use App\Lib\Response;

class AlertModel
{
    private $db;
    private $table = 'alert';
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

    public function getByCauseInDate($cause, $year = false, $month = false, $day = false)
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
        if ($cause === 'all') {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $year . "-" . $month . "-" . $day . " 23:59:59")
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->orderBy('id DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $year . "-" . $month . "-" . $day . " 23:59:59")
                ->where('cause', $cause)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->orderBy('id DESC')
                ->fetchAll();
        }

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByCauseAndGuardInDate($cause, $guard_id, $year = false, $month = false, $day = false)
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
        if ($cause === 'all') {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $year . "-" . $month . "-" . $day . " 23:59:59")
                ->where('guard_id', $guard_id)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->orderBy('id DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('alert.create_date >= ?', $year . "-" . $month . "-" . $day . " 00:00:00")
                ->where('alert.create_date <= ?', $year . "-" . $month . "-" . $day . " 23:59:59")
                ->where('cause', $cause)
                ->where('guard_id', $guard_id)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->orderBy('id DESC')
                ->fetchAll();
        }

        return [
            'data' => $data,
            'total' => count($data)
        ];
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
            ->select("guard.dni as guard_dni")
            ->select("guard.name as guard_name")
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
            ->select("guard.dni as guard_dni")
            ->select("guard.name as guard_name")
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
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
                ->orderBy('id DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('cause', $cause)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->orderBy('id DESC')
                ->fetchAll();
        }
        return [
            'data' => $data,
            'total' => count($data)
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
                ->orderBy('id DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('cause', $cause)
                ->where('guard_id', $guard_id)
                ->select("guard.dni as guard_dni")
                ->select("guard.name as guard_name")
                ->orderBy('id DESC')
                ->fetchAll();
        }

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
                ->SetResponse(false, 'La alerta no exite');
        }
        return $this->response->SetResponse(true);
    }
}
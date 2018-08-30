<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class TabletModel
{
    private $db;
    private $table = 'tablet';
    private $table_position = 'tablet_position';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function registerTablet($data)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_at'] = $timestamp;
        $data['status'] = 1;

        $tablet = $this->db
            ->from($this->table)
            ->where('imei', $data['imei'])
            ->fetch();

        if (!empty($tablet)) {
            $key = 'imei';
            $this->response->errors[$key][] = 'El imei se encuentra ya registrado';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function active($id, $active)
    {
        try {
            $data = [
                'status' => $active
            ];
            $query = $this->db
                ->update($this->table, $data, $id)
                ->execute();

            if ($query === 0) {
                return $this->response->SetResponse(false, 'La tablet no existe o ya tiene este estado');
            } else {
                $this->response->result = $this->getTablet($id);
            }

            return $this->response->SetResponse(true);
        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function getTablet($id)
    {
        return $this->db
            ->from($this->table, $id)
            ->fetch();
    }

    public function getTabletsByStatus($status)
    {
        if ($status == 'all') {
            $data = $this->db
                ->from($this->table)
                ->orderBy('id DESC')
                ->fetchAll();
        } else {
            $data = $this->db
                ->from($this->table)
                ->where('status', $status)
                ->orderBy('id DESC')
                ->fetchAll();
        }

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    public function getTabletsByStand($standId)
    {
        $data = $this->db
            ->from($this->table)
            ->where('stand_id', $standId)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'total' => count($data),
            'data' => $data
        ];
    }

    /*
     * Tablet Position
     */
    public function register($data)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['generated_time'] = $timestamp;

        $query = $this->db
            ->insertInto($this->table_position, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function getPosition($id)
    {
        return $this->db
            ->from($this->table_position, $id)
            ->fetch();
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
            ->where('generated_time >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('generated_time <= ?', $year."-".$month."-".$day." 23:59:59")
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
            ->from($this->table_position)
            ->where('generated_time >= ?', $year."-".$month."-".$day." 00:00:00")
            ->where('generated_time <= ?', $year."-".$month."-".$day." 23:59:59")
            ->where($propertyName, $propertyValue)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByWatchInDate($watchId, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('watch_id', $watchId, $year, $month, $day);
    }

    public function getByGuardInDate($guardId, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('watch.guard_id', $guardId, $year, $month, $day);
    }

    public function getByImeiInDate($imei, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('imei', $imei, $year, $month, $day);
    }

    public function getByMessageInDate($message, $year = false, $month = false, $day = false) {
        return $this->getByDateAndProperty('message', $message, $year, $month, $day);
    }

    public function getByWatch($watchId)
    {
        $data = $this->db
            ->from($this->table_position)
            ->where('watch_id', $watchId)
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
            ->from($this->table_position)
            ->where('watch.guard_id', $id)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByImei($imei)
    {
        $data = $this->db
            ->from($this->table_position)
            ->where('imei', $imei)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getByMessage($message)
    {
        $data = $this->db
            ->from($this->table_position)
            ->where('message', $message)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getAllPositions()
    {
        $data = $this->db
            ->from($this->table_position)
            ->orderBy('id DESC')
            ->fetchAll();

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    public function getLast()
    {
        $data = $this->db
            ->from($this->table_position)
            ->where('generated_time in (SELECT MAX(generated_time) FROM tablet_position GROUP BY imei)')
            ->disableSmartJoin()
            ->leftJoin('watch.guard AS guard')
            ->select('guard.id AS guard_id')
            ->select('guard.dni AS guard_dni')
            ->select('guard.name AS guard_name')
            ->select('guard.lastname AS guard_lastname')
            ->orderBy('generated_time DESC')
            ->fetchAll();
        return [
            'data' => $data,
            'total' => count($data)
        ];
    }
}
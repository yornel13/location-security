<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class WatchModel
{
    private $db;
    private $table = 'watch';
    private $tableF = 'finish_watch';
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
        } catch (Exception $e) {
            if (strpos($e->getMessage(), "foreign key")) {
                return $this->response->SetResponse(false, 'El guardia no existe');
            }
            return $this->response->SetResponse(false);
        }

        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function finish($data, $id)
    {
        $this->finishWatch($data, $id);
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);

        $watch = null;
        $watch['update_date'] = $timestamp;
        $watch['status'] = 0;

        $query = $this->db
            ->update($this->table, $watch, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'La guardia no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
    }

    public function finishWatch($data, $id)
    {
        $data['watch_id'] = $id;
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['create_date'] = $timestamp;

        $query = $this->db
            ->insertInto($this->tableF, $data)
            ->execute();

        $data['id'] = $query;

        return $data;
    }

    public function get($id)
    {
        $watch = $this->db
            ->from($this->table, $id)
            ->fetch();
        if ($watch != null) {
            $finishWatch = $this->db
                ->from($this->tableF)
                ->where('watch_id', $watch->id)
                ->fetch();
            if ($finishWatch != null) {
                $watch->finish = $finishWatch;
            }
            $guardModel = new GuardModel($this->db);
            $watch->guard = $guardModel->get($watch->guard_id);
        }
        return $watch;
    }

    public function getWatchActiveByGuard($id)
    {
        return $this->db
            ->from($this->table)
            ->where('status', 1)
            ->where('guard_id', $id)
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

    public function getAllActive()
    {
        $watches = $this->db
            ->from($this->table)
            ->where('status', 1)
            ->orderBy('id DESC')
            ->fetchAll();

        $guards = $this->db
            ->from('guard')
            ->orderBy('id DESC')
            ->fetchAll();

        foreach ($watches as $watch) {
            foreach ($guards as $guard) {
                if ($watch->guard_id == $guard->id) {
                    $watch->guard = $guard;
                }
            }
        }

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
}
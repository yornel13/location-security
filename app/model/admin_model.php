<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class AdminModel
{
    private $db;
    private $table = 'admin';
    private $response;

    public function __CONSTRUCT($db)
    {
        $this->db = $db;
        $this->response = new Response();
    }

    public function register($data)
    {
        try {
            $data['password'] = md5($data['password']);
            $timestamp = time()-(5*60*60);
            $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
            $data['create_date'] = $timestamp;
            $data['update_date'] = $timestamp;
            $data['active'] = 1;

            $guard = $this->db
                ->from($this->table)
                ->where('dni', $data['dni'])
                ->where('active', 1)
                ->fetch();

            if (!empty($guard)) {
                $key = 'dni';
                $this->response->errors[$key][] = 'La cedula se encuentra asociada a otro administrador';
                return $this->response->SetResponse(false);
            }

            $query = $this->db
                ->insertInto($this->table, $data)
                ->execute();

            $data['id'] = $query;
            $this->response->result = $data;

            return $this->response->SetResponse(true);
        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function update($data, $id)
    {
        try {
            if (isset($data['password'])) {
                $data['password'] = md5($data['password']);
            }
            $timestamp = time()-(5*60*60);
            $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
            $data['update_date'] = $timestamp;
            $data['active'] = 1;

            $guard = $this->db
                ->from($this->table)
                ->where('dni', $data['dni'])
                ->fetch();

            if (!empty($guard) && $guard->id !== $id) {
                $key = 'dni';
                $this->response->errors[$key][] = 'La cedula se encuentra asociada a otro administrador';
                return $this->response->SetResponse(false);
            }

            $query = $this->db
                ->update($this->table, $data, $id)
                ->execute();

            if ($query === 0) {
                return $this->response->SetResponse(false, 'El administrador no exite');
            } else {
                $this->response->result = $this->get($id);
            }

            return $this->response->SetResponse(true);

        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function savePhoto($data, $id)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data = [
            'photo'         => $data['photo'],
            'update_date'   => $timestamp
        ];
        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El administrador no exite');
        } else {
            $this->response->result = $this->get($id);
        }
        return $this->response->SetResponse(true);
    }

    public function active($id, $active)
    {
        try {
            $timestamp = time()-(5*60*60);
            $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
            $data = [
                'update_date' => $timestamp,
                'active'      => $active
            ];
            $query = $this->db
                ->update($this->table, $data, $id)
                ->execute();

            if ($query === 0) {
                return $this->response->SetResponse(false, 'El administrador no exite');
            } else {
                $this->response->result = $this->get($id);
            }

            return $this->response->SetResponse(true);
        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            return $this->db
                ->from($this->table, $id)
                ->fetch();

        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function getByDni($dni)
    {
        try {
            return $this->db
                ->from($this->table)
                ->where('dni', $dni)
                ->fetch();

        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }

    }

    public function getAll()
    {
        try {
            $data = $this->db
                ->from($this->table)
                ->orderBy('id DESC')
                ->fetchAll();

            return [
                'data' => $data,
                'total' => count($data)
            ];
        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function getByActive($active)
    {
        try {
            $data = $this->db
                ->from($this->table)
                ->where('active', $active)
                ->orderBy('id DESC')
                ->fetchAll();

            return [
                'data' => $data,
                'total' => count($data)
            ];
        } catch (Exception $e) {
            return $this->response->SetResponse(false, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $query = $this->db
                ->deleteFrom($this->table, $id)
                ->execute();
            if ($query === 0) {
                return $this->response
                    ->SetResponse(false, 'El administrador no exite');
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
                $this->response->result = $this->get($id);
                return $this->response->SetResponse(true, 'DISABLED');
            } else {
                return $this->response->SetResponse(false);
            }
        }
        return $this->response->SetResponse(true);
    }
}
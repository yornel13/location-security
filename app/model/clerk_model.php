<?php
namespace App\Model;


use App\Lib\Response;
use Exception;

class ClerkModel
{
    private $db;
    private $table = 'clerk_visited';
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
        $data['active'] = 1;

        $guard = $this->db
            ->from($this->table)
            ->where('dni', $data['dni'])
            ->where('active', 1)
            ->fetch();

        if (!empty($guard)) {
            $key = 'dni';
            $this->response->errors[$key][] = 'La cedula se encuentra asociada a otro funcionario';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        return $this->response->SetResponse(true);
    }

    public function update($data, $id)
    {
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
            $this->response->errors[$key][] = 'La cedula se encuentra asociada a otro funcionario';
            return $this->response->SetResponse(false);
        }

        $query = $this->db
            ->update($this->table, $data, $id)
            ->execute();

        if ($query === 0) {
            return $this->response->SetResponse(false, 'El funcionario no existe');
        } else {
            $this->response->result = $this->get($id);
        }

        return $this->response->SetResponse(true);
    }

    public function get($id)
    {
        return $this->db
            ->from($this->table, $id)
            ->fetch();
    }

    public function getByDni($dni)
    {
        return $this->db
            ->from($this->table)
            ->where('dni', $dni)
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
        try {
            $data = $this->db
                ->from($this->table)
                ->where('active', 1)
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
                    ->SetResponse(false, 'El funcionario no exite');
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
            } else {
                return $this->response->SetResponse(false);
            }
        }
        return $this->response->SetResponse(true);
    }
}
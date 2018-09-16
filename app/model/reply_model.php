<?php
namespace App\Model;


use App\Lib\Response;

class SpecialReportReplyModel
{
    private $db;
    private $table = 'special_report_reply';
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
        $data['state'] = 1;

        $query = $this->db
            ->insertInto($this->table, $data)
            ->execute();

        $data['id'] = $query;
        $this->response->result = $data;
        $this->sendNotify($data);
        return $this->response->SetResponse(true);
    }

    public function sendNotify($data) {
        if (is_null($data['admin_id'])) {
            $messenger = new MessengerModel($this->db);
            $messenger->send_message_report_to_admins($data);
        } else {
            $report = $this->db
                ->from('special_report', $data['report_id'])
                ->select('watch.guard_id as guard_id')
                ->fetch();
            $registration = $this->db
                ->from('tablet_token')
                ->where('guard_id',$report->guard_id)
                ->fetch();
            if (is_object($registration) && isset($registration->registration_id)) {
                $messenger = new MessengerModel($this->db);
                return $messenger->send_message_report_to_guard($data, $registration->registration_id);
            } else {
                return 'this user guard don`t have active session';
            }
        }
    }

    public function update($data, $id)
    {
        $timestamp = time()-(5*60*60);
        $timestamp = gmdate('Y-m-d H:i:s', $timestamp);
        $data['update_date'] = $timestamp;
        $data['active'] = 1;

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
        return $this->db
            ->from($this->table, $id)
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

    public function getByReport($id)
    {
        $data = $this->db
            ->from($this->table)
            ->where('report_id', $id)
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
                ->SetResponse(false, 'El comentario no exite');
        }
        return $this->response->SetResponse(true);
    }

    function getAllGuardUnreadComments($guard_id) {
        $unreadReports = [];

        $replies = $this->db
            ->from($this->table)
            ->where('special_report_reply.guard_id', null)
            ->where('state', 1)
            ->leftJoin('special_report ON special_report.id = report_id')
            ->where('special_report.watch.guard_id', $guard_id)
            //->select('special_report.watch.guard_id AS report_guard_id')
            ->orderBy('id DESC')
            ->fetchAll();

        if (is_array($replies)) {
            $arr = array();
            foreach($replies as $reply) {
                $arr[$reply->report_id][] = $reply;
            }
            foreach($arr as $unreadRep) {
                $count = 0;
                $report_id = null;
                foreach($unreadRep as $reply) {
                    $count++;
                    $report_id = $reply->report_id;
                }
                $unreadReports[] = [
                    'report' => [ 'id' => $report_id ],
                    'unread' => $count
                ];
            }
        }
        return [
            'unread' => count($replies),
            'data' => $unreadReports
        ];
    }

    function getAllAdminUnreadComments() {
        $unreadReports = [];

        $replies = $this->db
            ->from($this->table)
            ->where('state', 1)
            ->where('admin_id', null)
            ->orderBy('id DESC')
            ->fetchAll();

        if (is_array($replies)) {
            $arr = array();
            foreach($replies as $reply) {
                $arr[$reply->report_id][] = $reply;
            }
            foreach($arr as $unreadRep) {
                $count = 0;
                $report_id = null;
                foreach($unreadRep as $reply) {
                    $count++;
                    $report_id = $reply->report_id;
                }
                $unreadReports[] = [
                    'report' => [ 'id' => $report_id ],
                    'unread' => $count
                ];
            }
        }
        return [
            'unread' => count($replies),
            'data' => $unreadReports
        ];
    }

    function putAllReadAdmin($report_id) {
        $data = null;
        $data['state'] = 0;
        $this->db
            ->update($this->table)
            ->set($data)
            ->where('report_id', $report_id)
            ->where('admin_id', null)
            ->execute();

        return $this->response->SetResponse(true);
    }

    function putAllReadGuard($report_id) {
        $data = null;
        $data['state'] = 0;
        $this->db
            ->update($this->table)
            ->set($data)
            ->where('report_id', $report_id)
            ->where('guard_id', null)
            ->execute();

        return $this->response->SetResponse(true);
    }
}
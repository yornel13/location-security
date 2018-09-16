<?php

use App\Middleware\AuthMiddleware;
use App\Validation\ReplyValidation;

$app->group('/binnacle-reply', function () {
    $this->post('', function ($req, $res, $args) {
        $r = ReplyValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->register($req->getParsedBody()))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->delete($args['id']))
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->get($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->getAll())
            );
    });
    $this->get('/guard/{id}/comment/unread', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->getAllGuardUnreadComments($args['id']))
            );
    });
    $this->get('/admin/comment/unread', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->getAllAdminUnreadComments())
            );
    });
    $this->put('/admin/report/{report_id}/read', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->putAllReadAdmin($args['report_id']))
            );
    });
    $this->put('/guard/report/{report_id}/read', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->putAllReadGuard($args['report_id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;













<?php

use App\Middleware\AuthMiddleware;
use App\Validation\ReplyValidation;

$app->group('/special/report/reply/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->get($args['id']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->getAll())
            );
    });
    $this->post('register', function ($req, $res, $args) {
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
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;













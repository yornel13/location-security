<?php

use App\Middleware\AuthMiddleware;
use App\Validation\WatchValidation;

$app->group('/watch/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->get($args['id']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getAll())
            );
    });
    $this->get('get_active', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getAllActive())
            );
    });
    $this->post('register', function ($req, $res, $args) {
        $r = WatchValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->register($req->getParsedBody()))
            );
    });
    $this->post('finish/{id}', function ($req, $res, $args) {
        $r = WatchValidation::validate($req->getParsedBody(), true);

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->finish($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











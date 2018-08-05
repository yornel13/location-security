<?php

use App\Middleware\AuthMiddleware;
use App\Validation\WatchValidation;

$app->group('/watch', function () {
    $this->post('', function ($req, $res, $args) {
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
    $this->put('/{id}', function ($req, $res, $args) {
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
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getAll())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->get($args['id']))
            );
    });
    $this->get('/active/1', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getAllActive())
            );
    });
})/*->add(new AuthMiddleware($app))*/;











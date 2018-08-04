<?php

use App\Middleware\AuthMiddleware;
use App\Validation\GuardValidation;

$app->group('/guard/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->get($args['id']))
            );
    });
    $this->get('get/dni/{dni}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->getByDni($args['dni']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->getAll())
            );
    });
    $this->post('register', function ($req, $res, $args) {
        $r = GuardValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->register($req->getParsedBody()))
            );
    });
    $this->post('update/{id}', function ($req, $res, $args) {
        $r = GuardValidation::validate($req->getParsedBody(), true);

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->update($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











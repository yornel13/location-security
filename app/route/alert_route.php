<?php

use App\Middleware\AuthMiddleware;
use App\Validation\AlertValidation;

$app->group('/alert/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->get($args['id']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->getAll())
            );
    });
    $this->get('get_active', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->getAllActive())
            );
    });
    $this->post('register', function ($req, $res, $args) {
        $r = AlertValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->register($req->getParsedBody()))
            );
    });
    $this->post('update/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->update($args['id']))
            );
    });
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;













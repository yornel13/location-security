<?php

use App\Middleware\AuthMiddleware;
use App\Validation\VisitValidation;

$app->group('/visit/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->get($args['id']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->getAll())
            );
    });
    $this->get('get_active', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->getAllActive())
            );
    });
    $this->post('register', function ($req, $res, $args) {
        $r = VisitValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->register($req->getParsedBody()))
            );
    });
    $this->post('finish/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->finish($args['id']))
            );
    });
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











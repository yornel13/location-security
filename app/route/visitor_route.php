<?php

use App\Middleware\AuthMiddleware;
use App\Validation\VisitorValidation;

$app->group('/visitor/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->get($args['id']))
            );
    });
    $this->get('get/dni/{dni}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->getByDni($args['dni']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->getAll())
            );
    });
    $this->post('register', function ($req, $res, $args) {
        $r = VisitorValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->register($req->getParsedBody()))
            );
    });
    $this->post('update/{id}', function ($req, $res, $args) {
        $r = VisitorValidation::validate($req->getParsedBody(), true);

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->update($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











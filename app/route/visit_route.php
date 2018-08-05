<?php

use App\Middleware\AuthMiddleware;
use App\Validation\VisitValidation;

$app->group('/visit', function () {
    $this->post('', function ($req, $res, $args) {
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
    $this->put('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->finish($args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->getAll())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->get($args['id']))
            );
    });
    $this->get('/active/1', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->getAllActive())
            );
    });
})/*->add(new AuthMiddleware($app))*/;











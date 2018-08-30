<?php

use App\Middleware\AuthMiddleware;
use App\Validation\IncidenceValidation;
use App\Validation\StandValidation;

$app->group('/stand', function () {
    $this->post('', function ($req, $res, $args) {
        $r = StandValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->stand->register($req->getParsedBody()))
            );
    });
    $this->put('/{id}', function ($req, $res, $args) {
        $r = StandValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->stand->update($req->getParsedBody(), $args['id']))
            );
    });
    $this->post('/{id}/tablet/add', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->stand->addToStand($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->stand->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->stand->getAll())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->stand->get($args['id']))
            );
    });
    $this->get('/name/{name}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->stand->getByName($args['name']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











<?php

use App\Middleware\AuthMiddleware;
use App\Validation\VisitorVehicleValidation;

$app->group('/visitor-vehicle', function () {
    $this->post('', function ($req, $res, $args) {
        $r = VisitorVehicleValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->register($req->getParsedBody()))
            );
    });
    $this->post('/sync', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->save($req->getParsedBody()))
            );
    });
    $this->put('/{id}', function ($req, $res, $args) {
        $r = VisitorVehicleValidation::validate($req->getParsedBody(), true);

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->update($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->getAll())
            );
    });
    $this->get('/active/1', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->getAllActive())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->get($args['id']))
            );
    });
    $this->get('/plate/{plate}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->getByPlate($args['plate']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











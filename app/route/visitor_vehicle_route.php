<?php

use App\Middleware\AuthMiddleware;
use App\Validation\VisitorVehicleValidation;

$app->group('/visitor/vehicle/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->get($args['id']))
            );
    });
    $this->get('get/plate/{plate}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->getByPlate($args['plate']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->getAll())
            );
    });
    $this->post('register', function ($req, $res, $args) {
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
    $this->post('update/{id}', function ($req, $res, $args) {
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
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitorVehicle->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











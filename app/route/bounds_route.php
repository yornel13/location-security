<?php

use App\Middleware\AuthMiddleware;
use App\Validation\IncidenceValidation;

$app->group('/bounds', function () {
    $this->post('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->register($req->getParsedBody()))
            );
    });
    $this->put('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->update($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->getAll())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->get($args['id']))
            );
    });
    $this->get('/name/{name}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->getByName($args['name']))
            );
    });
    $this->get('/group/{group_id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->getByGroup($args['group_id']))
            );
    });
    $this->put('/{id}/group/remove', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->removeGroup($args['id']))
            );
    });

    /* vehicle */
    $this->post('/{id}/vehicle', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->addToBounds($args['id'], $req->getParsedBody()))
            );
    });
    $this->get('/{id}/vehicle', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->getVehiclesBound($args['id']))
            );
    });
    $this->delete('/vehicle/{vehicle_id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->deleteVehicleBounds($args['vehicle_id']))
            );
    });

    /* tablet */
    $this->post('/{id}/tablet', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->addTabletToBounds($args['id'], $req->getParsedBody()))
            );
    });
    $this->get('/{id}/tablet', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->getTabletsBound($args['id']))
            );
    });
    $this->delete('/tablet/{tablet_id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->bounds->deleteTabletBounds($args['tablet_id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











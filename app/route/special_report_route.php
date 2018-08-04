<?php

use App\Middleware\AuthMiddleware;
use App\Validation\SpecialReportValidation;

$app->group('/special/report/', function () {
    $this->get('get/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->get($args['id']))
            );
    });
    $this->get('get/{id}/replies', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->getByReport($args['id']))
            );
    });
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getAll())
            );
    });
    $this->get('get_active', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getAllActive())
            );
    });
    $this->post('register', function ($req, $res, $args) {
        $r = SpecialReportValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->register($req->getParsedBody()))
            );
    });
    $this->post('update/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->update($args['id']))
            );
    });
    $this->delete('delete/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->delete($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











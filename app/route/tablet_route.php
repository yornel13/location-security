<?php

use App\Middleware\AuthMiddleware;
use App\Validation\GuardValidation;
use App\Validation\TabletValidation;

$app->group('/tablet', function () {
    $this->post('', function ($req, $res, $args) {
        $r = TabletValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->tablet->register($req->getParsedBody()))
            );
    });
    $this->get('/all', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->tablet->getAll())
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->tablet->getLast())
            );
    });
    $this->get('id/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->tablet->get($args['id']))
            );
    });
    $this->get('/date/today', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->tablet->getByDate())
            );
    });
    $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->tablet->getByDate($args['year'],$args['month'],$args['day']))
            );
    });
    $this->group('/watch/{id}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByWatch($args['id']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByWatchInDate($args['id']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByWatchInDate( $args['id'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
    $this->group('/guard/{id}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByGuard($args['id']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByGuardInDate($args['id']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByGuardInDate($args['id'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
    $this->group('/imei/{imei}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByImei($args['imei']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByImeiInDate($args['imei']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByImeiInDate($args['imei'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
    $this->group('/message/{message}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByMessage($args['message']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByMessageInDate($args['message']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->tablet->getByMessageInDate($args['message'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
});










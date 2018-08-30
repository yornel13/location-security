<?php

use App\Middleware\AuthMiddleware;
use App\Validation\WatchValidation;

$app->group('/watch', function () {
    $this->post('/start', function ($req, $res, $args) {
        $r = WatchValidation::validateStart($req->getParsedBody());
        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->start($req->getParsedBody()))
            );
    });
    $this->put('/{id}/end', function ($req, $res, $args) {
        $r = WatchValidation::validateEnd($req->getParsedBody(), true);
        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->end($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getAll())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->get($args['id']))
            );
    });
    $this->get('/{id}/history', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->tablet->getByWatch($args['id']))
            );
    });
    $this->get('/active/1', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getAllActive())
            );
    });
    $this->get('/date/today', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getByDate())
            );
    });
    $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->watch->getByDate($args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
            );
    });
    $this->group('/guard/{id}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->watch->getByGuard($args['id']))
                );
        });
        $this->get('/active/1', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->watch->getAllActiveByGuard($args['id']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->watch->getByGuardInDate($args['id']))
                );
        });
        $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->watch->getByGuardInDate($args['id'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                );
        });
    });
})/*->add(new AuthMiddleware($app))*/;











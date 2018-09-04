<?php

use App\Middleware\AuthMiddleware;
use App\Validation\AlertValidation;

$app->group('/alert', function () {
    $this->post('', function ($req, $res, $args) {
        $r = AlertValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->register($req->getParsedBody()))
            );
    });
    $this->put('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->update($args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->getAll())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->get($args['id']))
            );
    });
    $this->get('/active/1', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->alert->getAllActive())
            );
    });
    $this->group('/cause/{cause}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->alert->getByCause($args['cause']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->alert->getByCauseInDate($args['cause']))
                );
        });
        $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->alert->getByCauseInDate($args['cause'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                );
        });
        $this->group('/guard/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->alert->getByGuard($args['cause'], $args['id']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->alert->getByCauseAndGuardInDate($args['cause'], $args['id']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->alert->getByCauseAndGuardInDate($args['cause'], $args['id'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                    );
            });
        });
    });
    $this->group('/out/bounds', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->alert->getOutSideBounds())
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->alert->getOutSideBoundsInDate())
                );
        });
        $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->alert->getOutSideBoundsInDate($args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                );
        });
        $this->group('/imei/{imei}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->alert->getOutSideBoundsByImei($args['imei']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->alert->getOutSideBoundsByImeiInDate($args['imei']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->alert->getOutSideBoundsByImeiInDate($args['imei'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                    );
            });
        });
    });
})/*->add(new AuthMiddleware($app))*/;













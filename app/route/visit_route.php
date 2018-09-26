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
    $this->post('/sync', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->save($req->getParsedBody()))
            );
    });
    $this->put('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->finish($args['id'], $req->getParsedBody()))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->delete($args['id']))
            );
    });
    $this->get('/last/group', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->getAllGroup())
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
    $this->group('/status/{status}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByStatus($args['status']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByStatusInDate($args['status']))
                );
        });
        $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByStatusInDate($args['status'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                );
        });
        $this->group('/guard/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByGuard($args['id'], $args['status']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByGuardInDate($args['id'], $args['status']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByGuardInDate($args['id'], $args['status'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                    );
            });
        });
        $this->group('/vehicle/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByVehicle($args['id'], $args['status']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByVehicleInDate($args['id'], $args['status']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByVehicleInDate($args['id'], $args['status'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                    );
            });
        });
        $this->group('/visitor/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByVisitor($args['id'], $args['status']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByVisitorInDate($args['id'], $args['status']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByVisitorInDate($args['id'], $args['status'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                    );
            });
        });
        $this->group('/clerk/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByClerk($args['id'], $args['status']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByClerkInDate($args['id'], $args['status']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}/to/{t_year}/{t_month}/{t_day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->visit->getByClerkInDate($args['id'], $args['status'], $args['year'],$args['month'],$args['day'], $args['t_year'],$args['t_month'],$args['t_day']))
                    );
            });
        });
    });
})/*->add(new AuthMiddleware($app))*/;











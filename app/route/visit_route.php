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
    $this->get('/date/today', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->getByDate())
            );
    });
    $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visit->getByDate($args['year'],$args['month'],$args['day']))
            );
    });
    $this->group('/guard/{id}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByGuard($args['id']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByGuardInDate($args['id']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByGuardInDate($args['id'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
    $this->group('/vehicle/{id}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByVehicle($args['id']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByVehicleInDate($args['id']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByVehicleInDate($args['id'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
    $this->group('/visitor/{id}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByVisitor($args['id']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByVisitorInDate($args['id']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByVisitorInDate($args['id'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
    $this->group('/clerk/{id}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByClerk($args['id']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByClerkInDate($args['id']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->visit->getByClerkInDate($args['id'], $args['year'],$args['month'],$args['day']))
                );
        });
    });
})/*->add(new AuthMiddleware($app))*/;











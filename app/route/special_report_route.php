<?php

use App\Middleware\AuthMiddleware;
use App\Validation\SpecialReportValidation;

$app->group('/binnacle', function () {
    $this->post('', function ($req, $res, $args) {
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
    $this->put('/accept/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->accept($args['id']))
            );
    });
    $this->put('/resolved/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->resolved($args['id']))
            );
    });
    $this->put('/open/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->reOpen($args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->delete($args['id']))
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->get($args['id']))
            );
    });
    $this->get('/active/1', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getAllActive())
            );
    });
    $this->group('/resolved/{resolved}', function () {
        $this->get('', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->specialReport->getByResolved($args['resolved']))
                );
        });
        $this->get('/date', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->specialReport->getByResolvedInDate($args['resolved']))
                );
        });
        $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
            return $res->withHeader('Content-type', 'application/json')
                ->write(
                    json_encode($this->model->specialReport->getByResolvedInDate($args['resolved'], $args['year'],$args['month'],$args['day']))
                );
        });
        $this->group('/guard/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByGuard($args['id'], $args['resolved']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByGuardInDate($args['id'], $args['resolved']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByGuardInDate($args['id'], $args['resolved'], $args['year'],$args['month'],$args['day']))
                    );
            });
        });
        $this->group('/watch/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByWatch($args['id'], $args['resolved']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByWatchInDate($args['id'], $args['resolved']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByWatchInDate($args['id'], $args['resolved'], $args['year'],$args['month'],$args['day']))
                    );
            });
        });
        $this->group('/incidence/{id}', function () {
            $this->get('', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByIncidence($args['id'], $args['resolved']))
                    );
            });
            $this->get('/date', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByIncidenceInDate($args['id'], $args['resolved']))
                    );
            });
            $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
                return $res->withHeader('Content-type', 'application/json')
                    ->write(
                        json_encode($this->model->specialReport->getByIncidenceInDate($args['id'], $args['resolved'], $args['year'],$args['month'],$args['day']))
                    );
            });
        });
    });
    $this->get('/{id}/replies', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->getByReport($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











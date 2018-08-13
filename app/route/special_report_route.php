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
    $this->put('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->update($args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getAll())
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
    $this->get('/incidence/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getByIncidenceId($args['id']))
            );
    });
    $this->get('/watch/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getByWatchId($args['id']))
            );
    });
    $this->get('/guard/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getByGuardId($args['id']))
            );
    });
    $this->get('/date/today', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getByDate())
            );
    });
    $this->get('/date/{year}/{month}/{day}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->get())
            );
    });
    $this->get('/guard/{id}/date/today', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getByDateAndGuard($args['id']))
            );
    });
    $this->get('/guard/{id}/date/{year}/{month}/{day}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->specialReport->getByDateAndGuard($args['id'], $args['year'],$args['month'],$args['day']))
            );
    });
    $this->get('/{id}/replies', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->reply->getByReport($args['id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











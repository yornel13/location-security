<?php

use App\Middleware\AuthMiddleware;
use App\Validation\VisitorValidation;

$app->group('/visitor', function () {
    $this->post('', function ($req, $res, $args) {
        $r = VisitorValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->register($req->getParsedBody()))
            );
    });
    $this->post('/sync', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->save($req->getParsedBody()))
            );
    });
    $this->put('/{id}', function ($req, $res, $args) {
        $r = VisitorValidation::validate($req->getParsedBody(), true);

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->update($req->getParsedBody(), $args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->getAll())
            );
    });
    $this->get('/active/1', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->getAllActive())
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->get($args['id']))
            );
    });
    $this->get('/dni/{dni}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->visitor->getByDni($args['dni']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











<?php

use App\Middleware\AuthMiddleware;
use App\Validation\GuardValidation;

$app->group('/guard', function () {
    $this->post('', function ($req, $res, $args) {
        $r = GuardValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->register($req->getParsedBody()))
            );
    });
    $this->put('/{id}', function ($req, $res, $args) {
        $r = GuardValidation::validate($req->getParsedBody(), true);

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->update($req->getParsedBody(), $args['id']))
            );
    });
    $this->put('/{id}/photo', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->savePhoto($req->getParsedBody(), $args['id']))
            );
    });
    $this->put('/{id}/active/{active}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->active($args['id'], $args['active']))
            );
    });
    $this->put('/{id}/stand/remove', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->removeStand($args['id']))
            );
    });
    $this->delete('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->delete($args['id']))
            );
    });
    $this->get('', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->getAll())
            );
    });
    $this->get('/active/{active}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->getByActive($args['active']))
            );
    });
    $this->get('/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->get($args['id']))
            );
    });
    $this->get('/dni/{dni}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->getByDni($args['dni']))
            );
    });
    $this->get('/stand/{stand_id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->guard->getByStand($args['stand_id']))
            );
    });
})/*->add(new AuthMiddleware($app))*/;











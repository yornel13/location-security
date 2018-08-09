<?php

use App\Middleware\AuthMiddleware;

$app->group('/auth', function () {
    $this->post('/guard', function ($req, $res, $args) {

        $parameters= $req->getParsedBody();

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->auth->guard($parameters['dni'], $parameters['password']))
            );
    });
    $this->post('/admin', function ($req, $res, $args) {

        $parameters = $req->getParsedBody();

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->auth->admin($parameters['dni'], $parameters['password']))
            );
    });
    $this->get('/verify', function ($req, $res, $args) {
        $token = $req->getHeaderLine('APP-TOKEN');
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->auth->verify($token))
            );
    })/*->add(new AuthMiddleware($this))*/;
});











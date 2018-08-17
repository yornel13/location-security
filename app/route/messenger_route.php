<?php

use App\Validation\MessengerValidation;

$app->group('/messenger', function () {
    $this->post('/send', function ($req, $res, $args) {

        $r = MessengerValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->send($req->getParsedBody()))
            );
    });
    $this->post('/register', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->register($req->getParsedBody()))
            );
    });
});











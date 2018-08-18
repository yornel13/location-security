<?php

use App\Validation\ChatValidation;
use App\Validation\MessengerValidation;

$app->group('/messenger', function () {
    $this->post('/register/tablet', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->registerTablet($req->getParsedBody()))
            );
    });
    $this->post('/register/web', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->registerWeb($req->getParsedBody()))
            );
    });
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
    $this->post('/chat', function ($req, $res, $args) {

        $r = ChatValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->createChat($req->getParsedBody()))
            );
    });
    $this->get('/conversations/guard/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->getGuardChats($args['id']))
            );
    });
    $this->get('/conversations/admin/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->getAdminChats($args['id']))
            );
    });
    $this->get('/conversations/chat/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->getMessages($args['id']))
            );
    });
});











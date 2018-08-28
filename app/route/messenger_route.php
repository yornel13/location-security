<?php

use App\Validation\ChannelValidation;
use App\Validation\chatvalidation;
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
    $this->post('/channel', function ($req, $res, $args) {

        $r = ChannelValidation::validate($req->getParsedBody());

        if (!$r->response) {
            return $res->withHeader('Content-type', 'application/json')
                ->withStatus(422)
                ->write(json_encode($r));
        }

        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->createChannel($req->getParsedBody()))
            );
    });
    $this->post('/channel/{id}/add', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->addToChannel($args['id'], $req->getParsedBody()))
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
    $this->get('/conversations/channel/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->getChannelMessages($args['id']))
            );
    });
    $this->get('/channel/guard/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->getChannelsGuard($args['id']))
            );
    });
    $this->get('/channel/admin/{id}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->getChannelsAdmin($args['id']))
            );
    });
    $this->get('/channel/{id}/members', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->getChannelMembers($args['id']))
            );
    });
    $this->get('/test/alert', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->messenger->send_alert_notification('hola amigo'))
            );
    });
});











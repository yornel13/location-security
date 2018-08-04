<?php

$app->group('/vehicle/', function () {
    $this->get('get', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->vehicle->getAll())
            );
    });
    $this->get('get/{imei}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->vehicle->get($args['imei']))
            );
    });
    $this->get('history/{imei}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->vehicle->dailyHistory($args['imei']))
            );
    });
    $this->get('history/{imei}/{year}/{month}/{day}', function ($req, $res, $args) {
        return $res->withHeader('Content-type', 'application/json')
            ->write(
                json_encode($this->model->vehicle->dailyHistory($args['imei'],$args['year'],$args['month'],$args['day']))
            );
    });
});


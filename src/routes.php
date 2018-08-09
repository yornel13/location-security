<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
});
$app->add(function ($request, $response, $next) {
    $response = $next($request, $response);
    if ($response->getStatusCode() === 200) {
        $body = $response->getBody();
        $bodyDecode = json_decode($body);
        if (isset($bodyDecode->response)) {
            $bodyString = $bodyDecode->response;
            if (!$bodyString) {
                $response = $response->withStatus(422);
            }
        } else if (is_bool($bodyDecode) && !$bodyDecode) {
            $response = $response->withStatus(404);
        }
    }
    return $response;
});
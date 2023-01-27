<?php

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;


$server = new Server('', 9501, SWOOLE_BASE);

$server->set([
    'worker_num' => 4,
]);

$server->on('Request', function (Request $request, Response $response) {
    $sleep = $request->get['sleep'] ?: 0;
    go(function () use ($sleep, $response) {
        sleep($sleep);
        $response->end($sleep);
    });
});

$server->start();
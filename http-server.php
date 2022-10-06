<?php


use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$server = new Server('127.0.0.1', 9501);

$server->on('Request', function (Request $request, Response $response) {
    $response->header('content-type', 'text/html;charset:utf-8');
    $id = rand(1, 60000);
    $response->end("<h4>{$id}</h4>");
});

$server->start();
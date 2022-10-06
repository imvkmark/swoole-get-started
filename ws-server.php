<?php


use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\Websocket\Server;

$server = new Server('127.0.0.1', 9502);

$server->on('Open', function (Server $ws, Request $request) {
    $info = $ws->connection_info($request->fd);
    var_dump($info);
    echo "client:{$request->fd} open." . PHP_EOL;
});

$server->on('Message', function (Server $ws, Frame $frame) {
    echo "message: {$frame->data}" . PHP_EOL;
});
$server->on('Close', function (Server $ws, $fd) {
    echo "client: {$fd} closed." . PHP_EOL;
});

$server->start();
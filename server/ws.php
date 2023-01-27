<?php

use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\Websocket\Server;


/* 这里的连接采用 postman 进行连接，方便查看
 * ---------------------------------------- */

$server = new Server('', 9502);

$server->on('Open', function (Server $ws, Request $request) {
    $info = $ws->connection_info($request->fd);
    var_dump($info);
    echo "client:{$request->fd} open." . PHP_EOL;
});

$server->on('Message', function (Server $ws, Frame $frame) {
    echo "message: {$frame->data}" . PHP_EOL;

    $hello = 'Hello World';
    if ($frame->data === 'back') {
        $ws->push($frame->fd, json_encode([
            'type' => 'text',
            'text' => $hello,
        ]));

    }
    if ($frame->data === 'pack') {
        // todo 发送的消息无法被解析， 发送完成就断连接
        $sendData = "HTTP/1.1 101 Switching Protocols\r\n";
        $sendData .= "Upgrade: websocket\r\nConnection: Upgrade\r\nSec-WebSocket-Accept: IFpdKwYy9wdo4gTldFLHFh3xQE0=\r\n";
        $sendData .= "Sec-WebSocket-Version: 13\r\nServer: swoole-http-server\r\n\r\n";
        $sendData .= Server::pack($hello . PHP_EOL);
        $ws->push($frame->fd, $sendData);
    }
});

$server->on('Close', function (Server $ws, $fd) {
    echo "client: {$fd} closed." . PHP_EOL;
});

$server->start();
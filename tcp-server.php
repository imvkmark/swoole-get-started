<?php


use Swoole\Server;

$server = new Server('127.0.0.1', 9501);

$server->on('Connect', function ($server, $fd) {
    echo "Tcp Client: Connected {$fd}" . PHP_EOL;
});

$server->on('Receive', function (Server $server, $fd, $reactor_id, $data) {
    $data = trim($data);
    $server->send($fd, "Server : {$data}, fd: {$fd}, reactor_id : {$reactor_id}" . PHP_EOL);
});

$server->on('Close', function ($server, $fd) {
    echo 'Client : Close ' . $fd . PHP_EOL;
});

$server->start();
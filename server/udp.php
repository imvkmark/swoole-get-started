<?php


use Swoole\Server;

$server = new Server('', 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

$server->on('Packet', function (Server $server, $data, $clientInfo) {
    var_dump($clientInfo);
    $data = trim($data);
    $server->sendto($clientInfo['address'], $clientInfo['port'], "server:{$data}" . PHP_EOL);
});

$server->start();
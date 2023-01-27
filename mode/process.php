<?php

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;


$server = new Server('', 9501, SWOOLE_PROCESS);

$server->set([
    'worker_num'      => 4,
    'task_worker_num' => 2,
]);

$server->on('Request', function (Request $request, Response $response) {
    $sleep = $request->get['sleep'] ?: 0;
    go(function () use ($sleep, $response) {
        sleep($sleep);
        $response->end($sleep);
    });
});
$server->on('Task', function (Server $serv, $task_id, $reactor_id, $data) {
    echo "New AsyncTask[id={$task_id}]" . PHP_EOL;
    $serv->finish("{$data} -> OK");
});

$server->start();

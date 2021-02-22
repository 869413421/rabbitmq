<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
$channel = $connection->channel();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

list($queueName) = $channel->queue_declare('', false, false, false, false);

$routeKeys = array_slice($argv, 1);

foreach ($routeKeys as $routeKey)
{
    //绑定多个路由KEY
    $channel->queue_bind($queueName, 'direct_logs', $routeKey);
}

echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

$callback = function ($msg)
{
    echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, PHP_EOL;
};

$channel->basic_consume($queueName, '', false, true, false, false, $callback);

while (count($channel->callbacks))
{
    $channel->wait();
}

$channel->close();
$connection->close();
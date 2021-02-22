<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
$channel = $connection->channel();

$channel->exchange_declare('logs', 'fanout', false, false, false);

list($queue_name) = $channel->queue_declare('', false, false, true, false);
//将队列绑定到交换机，接受消息
$channel->queue_bind($queue_name, 'logs');

$callBack = function ($msg)
{
    echo '消费:' . $msg->body . PHP_EOL;
    echo '完成消费';
};

//消费队列
$channel->basic_consume($queue_name, '', false, true, false, false, $callBack);

while (count($channel->callbacks))
{
    $channel->wait();
}

$channel->close();
$connection->close();
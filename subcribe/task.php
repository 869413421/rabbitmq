<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
$channel = $connection->channel();

//声明一个交换机
$channel->exchange_declare('logs', 'fanout', false, false, false);

$data = implode(',', array_slice($argv, 1));
if (empty($data))
{
    $data = "info:hello world!";
}

$msg = new AMQPMessage(
    $data
);

while (true)
{
    //消息发送到交换机上，而不是直接发布到队列上
    $channel->basic_publish($msg, 'logs');

    echo '生产消息' . PHP_EOL;
    sleep(1);
}


$channel->close();
$connection->close();
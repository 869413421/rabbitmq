<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
$channel = $connection->channel();

/**
 * queue,指定监听的队列
 * passive,
 * durable,是否持久化
 * exclusive,是否排他，不允许其他链接处理本队列
 * auto_delete,自动删除
 */
$channel->queue_declare('task_queue', false, true, false, false);

$data = implode(',', array_slice($argv, 1));
if (empty($data))
{
    $data = "hello world!";
}

$msg = new AMQPMessage(
    $data,
    [
        //将消息标记为持久化
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ]
);

while (true)
{
    $channel->basic_publish($msg, '', 'task_queue');

    echo '生产消息' . PHP_EOL;
    sleep(1);
}


$channel->close();
$connection->close();
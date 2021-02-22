<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

$callBack = function ($msg)
{
    echo '消费:' . $msg->body . PHP_EOL;
    sleep(2);
    echo '完成消费';
    //向生产者确认消息已经被消费
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

//一次只指派一个消息，公平分配
$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue', '', false, false, false, false, $callBack);

while (count($channel->callbacks))
{
    $channel->wait();
}

$channel->close();
$connection->close();
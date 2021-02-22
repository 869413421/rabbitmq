<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
$channel = $connection->channel();

//生成一个direct交换机
$exchanges = $channel->exchange_declare('direct_logs', 'direct', false, false, false);

$routeKey = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';
$data = implode(' ', array_slice($argv, 2));
if (empty($data))
{
    $data = 'hello word!';
}

$msg = new AMQPMessage($data);
//发送到自定义的routeKey
$channel->basic_publish($msg, 'direct_logs', $routeKey);
echo '[x]send', $routeKey, ':', $data, PHP_EOL;
$channel->close();
$connection->close();
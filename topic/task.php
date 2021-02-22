<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


$connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
$channel = $connection->channel();
$channel->exchange_declare('topic_logs', 'topic', false, false, false);

$routeKey = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'anonymous.info';

$data = implode(' ', array_slice($argv, 2));
if (empty($data)) $data = 'hello word!';

$msg = new AMQPMessage($data);

// 携带路由键发送到topic_logs交换机
$channel->basic_publish($msg, 'topic_logs', $routeKey);

echo " [x] Sent ", $routeKey, ':', $data, PHP_EOL;

$channel->close();
$connection->close();
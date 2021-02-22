<?php

namespace Simple;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    private $connection;

    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
        $this->channel = $this->connection->channel();
        echo '开始生产' . PHP_EOL;
    }

    public function publish($content)
    {
        $this->channel->queue_declare('hello');
        $msg = new AMQPMessage($content);
        $this->channel->basic_publish($msg, '', 'hello');
        echo '生产成功' . PHP_EOL;
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}


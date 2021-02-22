<?php

namespace Simple;


use PhpAmqpLib\Connection\AMQPStreamConnection;

class Consumer
{
    private $connection;

    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('47.94.155.227', 39200, 'admin', 'admin', 'test');
        $this->channel = $this->connection->channel();
        echo '消费者启动' . PHP_EOL;
    }

    public function consumer()
    {
        $callBack = function ($msg)
        {
            echo '消费消息:' . $msg->body . PHP_EOL;
        };

        $this->channel->queue_declare('hello');
        $this->channel->basic_consume('hello', '', false, true, false, false, $callBack);
        while (count($this->channel->callbacks))
        {
            $this->channel->wait();
        }
    }
}
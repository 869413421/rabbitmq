<?php
require_once __DIR__ . '/vendor/autoload.php';

use Simple\Producer;

$consumer = new Producer();
$i = 0;
while (true)
{
    $i++;
    $consumer->publish('hello word!' . $i);
}

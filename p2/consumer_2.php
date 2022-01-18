<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('mq2', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    echo $msg->body . " [mq2]\n";
};

$channel->basic_consume('mq2', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

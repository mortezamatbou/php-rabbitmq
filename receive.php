<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('Q1', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body . " " . date('Y-m-d H:i:s') .  "\n";
};

$channel->basic_consume('Q1', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

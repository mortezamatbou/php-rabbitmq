<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('A1R', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [1] Received ', $msg->body . " " . date('Y-m-d H:i:s') .  "\n";
};

$channel->basic_consume('A1R', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

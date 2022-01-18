<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange_name = 'logs';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchange_name, 'fanout', FALSE, TRUE);
$queue = $channel->queue_declare('', FALSE, TRUE, TRUE);

$channel->queue_bind($queue[0], $exchange_name);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    $decoded = json_decode($msg->body, TRUE);
    echo 'name: ' . $decoded['name'] . "\n";
};

$channel->basic_consume($queue[0], '', false, TRUE, TRUE, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

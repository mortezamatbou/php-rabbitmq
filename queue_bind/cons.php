<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$queue = 'mq_mq';
$exchange = 'ex_ex';

$channel->exchange_declare($exchange, 'direct');
$channel->queue_declare($queue, false, false, false, false);
$channel->queue_bind($queue, $exchange);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    echo "OOooOOppsSSss";
};

$channel->basic_consume($queue, '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange_name = 'ex_direct_log';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchange_name, 'direct', FALSE, TRUE);
$queue = $channel->queue_declare('', FALSE, TRUE, TRUE);
$channel->queue_bind($queue[0], $exchange_name, 'warning');

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    echo 'WARNING -> ' . $msg->body . "\n";
};

$channel->basic_consume($queue[0], '', FALSE, TRUE, TRUE, FALSE, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

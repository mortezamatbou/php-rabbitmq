<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('mq1', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
    if ($msg->body < 3 || $msg->body > 8) {
        // return to the queue
        $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
        echo "**Failed** {$msg->body}\n";
    } else {
        // send ack , remove from queue
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        echo "Success[] {$msg->body}\n";
    }
};

$channel->basic_consume('mq1', 'tag3', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

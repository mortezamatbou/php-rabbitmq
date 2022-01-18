<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('simple', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$failed = 0;
$success = 0;

$callback = function ($msg) {
    global $success;
    global $failed;
    echo 'Received ', $msg->body . " " . date('Y-m-d H:i:s') . "\n";

    if ($msg->body >= 3 && $msg->body <= 8) {
        echo "3 <= [{$msg->body}] <= 8 [success]\n";
        $success++;
        $msg->ack();
    } else {
        echo ($msg->body < 3 ? "[{$msg->body}] < 3\n" : "[{$msg->body}] > 8") . " [failed]\n";
        $failed++;
        $msg->nack(FALSE);
    }

    print_r($msg->get_properties());
    echo "\n";

    echo "Success: {$success}, Failed: {$failed}\n***********************************************\n";

};

$channel->basic_consume('simple', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}
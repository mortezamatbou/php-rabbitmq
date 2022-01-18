<?php

require '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange_name = 'ex_direct_log';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchange_name, 'direct', FALSE, TRUE);
while (TRUE) {
    $input = fopen("php://stdin", "r");
    $user_input = trim(fgets($input));

    if (strtolower($user_input) == 'bye') {
        break;
    }

    $input = strip_tags($user_input);
    $queue_target = trim(substr($input, 0, strpos($input, " ")));
    $queue_message = trim(substr($input, strpos($input, " "), strlen($input)));
    $route_key = '';

    switch ($queue_target) {
        case 'info':
            $route_key = 'info';
            break;
        case 'warning':
            $route_key = 'warning';
            break;
        case 'error':
            $route_key = 'error';
            break;
        default:
            $route_key = '';
            break;
    }

    if (!$route_key) {
        echo "[ERROR] Invalid type\n";
        continue;
    }

    if (!$queue_message) {
        echo "[ERROR] Message cannot be empty.\n";
        continue;
    }

    $properties = ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, 'content_type' => 'text/plain'];
    $msg = new AMQPMessage($queue_message, $properties);
    $channel->basic_publish($msg, $exchange_name, $route_key);
    echo "[SUCCESS] {$route_key} message sent. \n";
}

echo 'Connection closed!';

$channel->close();
$connection->close();
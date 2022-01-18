<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

while (TRUE) {
    $input = fopen("php://stdin", "r");
    $user_input = trim(fgets($input));

    if (strtolower($user_input) == 'bye') {
        break;
    }

    if (!$user_input || !is_numeric($user_input)) {
        $user_input = rand(0, 10);
    }

    $properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, 'headers' => ['name' => 'Morteza']];

    $msg = new AMQPMessage($user_input, $properties);
    $channel->basic_publish($msg, '', 'simple');

}

echo 'Connection closed!';

$channel->close();
$connection->close();

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

    if ($user_input == 1) {
        $routing_key = 'mq_mq';
        $msg = new AMQPMessage(rand(0, 10));
        $channel->basic_publish($msg, 'ex_ex', $routing_key);
    } else {
        echo "Bad input\n";
    }
}

echo 'Connection closed!';

$channel->close();
$connection->close();

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
        $routing_key = 'mq1';
        $msg = new AMQPMessage(rand(0, 10));
        $channel->basic_publish($msg, 'ex1', $routing_key);
//        for ($i = 0; $i < 30; $i++) {
//        }
    } else if ($user_input == 2) {
        $msg = new AMQPMessage($user_input);
        $routing_key = 'mq2';
        $channel->basic_publish($msg, '', $routing_key);
    } else {
        echo "Bad input\n";
        continue;
    }
}

echo 'Connection closed!';

$channel->close();
$connection->close();

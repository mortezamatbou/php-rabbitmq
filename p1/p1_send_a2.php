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

    $msg = new AMQPMessage($user_input . ' [2]');
    $channel->basic_publish($msg, '', 'A1R');

}

echo 'Connection closed!';

$channel->close();
$connection->close();

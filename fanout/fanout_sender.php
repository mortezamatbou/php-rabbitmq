<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange_name = 'logs';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchange_name, 'fanout', FALSE, TRUE);
$queue = $channel->queue_declare('', FALSE, TRUE, TRUE);

$channel->queue_bind($queue[0], $exchange_name);

$properties = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
$msg = new AMQPMessage('{"name": "Morteza"}', $properties);
$channel->basic_publish($msg, $exchange_name, '');

$channel->close();
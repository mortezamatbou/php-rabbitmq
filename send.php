<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('Q1', false, false, false, false);
$msg = new AMQPMessage('WOW!');
$channel->basic_publish($msg, '', 'Q1');
//echo json_encode(['name' => 'morteza', 'age' => 29]);

$channel->close();
$connection->close();

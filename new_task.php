<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();

										/*save queue if rabbitmq server die,false is not save*/
$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage('Hello World!');

$channel->basic_publish($msg, '', 'hello');

echo ' [x] Sent ', 'Hello World!', "\n";

?>
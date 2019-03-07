<?php 

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

$msg = new \PhpAmqpLib\Message\AMQPMessage(
		json_encode(['name' => 'dat dat','addredd' => 'sai gon'], JSON_UNESCAPED_SLASHES),
        array('delivery_mode' => 2) # make message persistent
    );

$channel->close();
$connection->close();

 ?>
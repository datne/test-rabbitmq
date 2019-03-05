<?php 

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

//create queue with queue-name => 'dat-ne'
$channel->queue_declare('dat-ne', false, false, false, false);

echo "Chờ nhận message. To exit press CTRL+C\n";
$callback = function ($msg) {
	echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('dat-ne', '', false, true, false, false, $callback);
var_dump($channel->basic_consume('dat-ne', '', false, true, false, false, $callback));
while (count($channel->callbacks)) {
	$channel->wait();
}

?>
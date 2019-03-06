<?php 

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->exchange_declare('logs', 'fanout', false, false, false);
// list($queue_name, ,) = $channel->queue_declare("");
$job_id=0;
while ($job_id < 16)
{
	$jobArray = array(
		'id' => $job_id++,
		'task' => 'sleep',
		'sleep_period' => rand(0, 3)
	);

	$msg = new \PhpAmqpLib\Message\AMQPMessage(
		json_encode($jobArray, JSON_UNESCAPED_SLASHES),
        array('delivery_mode' => 2) # make message persistent
    );
	$channel->basic_publish($msg, 'logs');
	print 'info: Job created' . PHP_EOL;
	sleep(1);
}


$channel->close();
$connection->close();

?>
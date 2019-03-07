<?php 
require_once(__DIR__ . '/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();

//create queue with queue-name => 'dat-ne'
// the third param is True (always keep queue when rabbitmq server die)
$channel->queue_declare('dat-ne', false,true, false, false);
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
	$channel->basic_publish($msg, '', 'dat-ne');
	print 'Job created' . PHP_EOL;
	sleep(1);
}

?>
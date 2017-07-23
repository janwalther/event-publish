<?php
namespace janwalther\event_publish;

use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EventPublisher
{
    /** @var AbstractConnection */
    private $connection;

    public function __construct(AbstractConnection $connection)
    {
        $this->connection = $connection;
    }

    public function __destruct()
    {
        $this->connection->channel()->close();
        $this->connection->close();
    }

    public function publish(QueueableEvent $event) {
        $channel = $this->connection->channel();

        $channel->queue_declare(
            get_class($event),
            #queue - Queue names may be up to 255 bytes of UTF-8 characters
            false,
            #passive - can use this to check whether an exchange exists without modifying the server state
            true,
            #durable, make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            false,
            #exclusive - used by only one connection and the queue will be deleted when that connection closes
            false               #auto delete - queue is deleted when last consumer unsubscribes
        );

        $msg = new AMQPMessage(
            serialize($event),
            ['delivery_mode' => 2] # make message persistent, so it is not lost if server crashes or quits
        );

        $channel->basic_publish(
            $msg,               #message
            '',                 #exchange
            get_class($event)     #routing key (queue)
        );
    }
}

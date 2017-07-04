<?php
namespace janwalther\event_publisher;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Webmozart\Assert\Assert;

class RabbitMqEventQueue implements EventListener
{
    /** @var EventPublisher */
    private $eventPublisher;

    public function __construct(EventPublisher $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    public function handle(DomainMessage $domainMessage)
    {
        /** @var QueueableEvent $event */
        $event = $domainMessage->getPayload();

        Assert::implementsInterface($event, QueueableEvent::class);

        $this->eventPublisher->publish(get_class($event), $event->serialize());
    }
}

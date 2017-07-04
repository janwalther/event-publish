<?php
namespace janwalther\event_publish;

use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;
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

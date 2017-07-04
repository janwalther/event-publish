<?php
namespace janwalther\event_publisher;

use Broadway\Serializer\Serializable;
use BroadwaySerialization\Serialization\AutoSerializable;

abstract class QueueableEvent implements Serializable
{
    use AutoSerializable;
}

<?php
namespace janwalther\event_publish;

use Broadway\Serializer\Serializable;
use BroadwaySerialization\Serialization\AutoSerializable;

abstract class QueueableEvent implements Serializable
{
    use AutoSerializable;
}

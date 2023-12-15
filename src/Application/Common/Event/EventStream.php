<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Event;

final class EventStream
{
    private array $recordedEvents = [];

    public function record(Event $event) : void
    {
        $this->recordedEvents[] = $event;
    }

    /** @return Event[] */
    public function all() : array
    {
        return $this->recordedEvents;
    }

    public function purge() : void
    {
        $this->recordedEvents = [];
    }
}
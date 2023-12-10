<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Event;

final class InMemoryEventDispatcher implements EventDispatcher
{
    /** @var Event[] */
    public array $events = [];

    public function dispatch(Event $event): void
    {
        $this->events[] = $event;
    }
}
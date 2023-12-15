<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Event;

final readonly class EventBus
{
    public function __construct(private EventStream $stream, private EventDispatcher $dispatcher)
    {
    }

    public function publish() : void
    {
        foreach ($this->stream->all() as $event) {
            $this->dispatcher->dispatch($event);
        }

        $this->stream->purge();
    }
}
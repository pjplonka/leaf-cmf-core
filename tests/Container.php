<?php declare(strict_types=1);

namespace Tests;

use Leaf\Core\Application\Common\Command\CommandBus;
use Leaf\Core\Application\Common\Command\DefaultCommandBus;
use Leaf\Core\Application\Common\ConfigurationProvider;
use Leaf\Core\Application\Common\Event\EventBus;
use Leaf\Core\Application\Common\Event\EventStream;
use Leaf\Core\Application\Common\Event\InMemoryEventDispatcher;
use Leaf\Core\Application\Common\FieldsDtoValidator;
use Leaf\Core\Application\CreateElement\CreateElementHandler;
use Leaf\Core\Application\UpdateElement\UpdateElementHandler;
use Leaf\Core\Core\Element\Field\FieldFactory;
use Leaf\Core\Infrastructure\Domain\InMemoryElements;
use Symfony\Component\Validator\Validation;
use Tests\Doubles\ConfigurationProviderStub;

/**
 * This package does not require any DI
 * Container class is representation of dummy container
 * to make tests easier to handle, read and maintenance.
 */
final readonly class Container
{
    public EventStream $stream;
    public CommandBus $bus;
    public InMemoryElements $elements;
    public InMemoryEventDispatcher $dispatcher;

    public function __construct(ConfigurationProvider $configurationProvider = null)
    {
        $configurationProvider = $configurationProvider ?? new ConfigurationProviderStub();

        // Event Bus
        $stream = new EventStream();
        $dispatcher = new InMemoryEventDispatcher();
        $eventBus = new EventBus($stream, $dispatcher);

        // Elements (repository)
        $elements = new InMemoryElements();

        // Create Elements Handler
        $createElementHandler = new CreateElementHandler(
            $configurationProvider,
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            $elements,
            $stream
        );

        // Update Element Handler
        $updateElementHandler = new UpdateElementHandler(
            $configurationProvider,
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            $elements,
            $stream
        );

        $bus = new DefaultCommandBus(
            $eventBus,
            $createElementHandler,
            $updateElementHandler
        );

        $this->stream = $stream;
        $this->bus = $bus;
        $this->elements = $elements;
        $this->dispatcher = $dispatcher;
    }
}
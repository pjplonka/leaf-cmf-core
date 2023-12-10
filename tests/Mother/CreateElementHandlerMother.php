<?php declare(strict_types=1);

namespace Tests\Mother;

use Leaf\Core\Application\Common\Event\EventDispatcher;
use Leaf\Core\Application\Common\Event\InMemoryEventDispatcher;
use Leaf\Core\Application\Common\FieldsDtoValidator;
use Leaf\Core\Application\CreateElement\CreateElementHandler;
use Leaf\Core\Core\Element\Elements;
use Leaf\Core\Core\Element\Field\FieldFactory;
use Leaf\Core\Infrastructure\Domain\InMemoryElements;
use Symfony\Component\Validator\Validation;
use Tests\Doubles\ConfigurationProviderStub;
use Tests\Doubles\ThrowingConfigurationProvider;

final class CreateElementHandlerMother
{
    public static function basic(): CreateElementHandler
    {
        return new CreateElementHandler(
            new ConfigurationProviderStub(),
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            new InMemoryElements(),
            new InMemoryEventDispatcher()
        );
    }

    public static function withThrowingConfigurationProvider(): CreateElementHandler
    {
        return new CreateElementHandler(
            new ThrowingConfigurationProvider(),
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            new InMemoryElements(),
            new InMemoryEventDispatcher()
        );
    }

    public static function withCustomElements(Elements $elements): CreateElementHandler
    {
        return new CreateElementHandler(
            new ConfigurationProviderStub(),
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            $elements,
            new InMemoryEventDispatcher()
        );
    }

    public static function withCustomEventDispatcher(EventDispatcher $dispatcher): CreateElementHandler
    {
        return new CreateElementHandler(
            new ConfigurationProviderStub(),
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            new InMemoryElements(),
            $dispatcher
        );
    }
}
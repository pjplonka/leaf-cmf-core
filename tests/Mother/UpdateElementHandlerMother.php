<?php declare(strict_types=1);

namespace Tests\Mother;

use Leaf\Core\Application\Common\Event\EventDispatcher;
use Leaf\Core\Application\Common\Event\InMemoryEventDispatcher;
use Leaf\Core\Application\Common\FieldsDtoValidator;
use Leaf\Core\Application\UpdateElement\UpdateElementHandler;
use Leaf\Core\Core\Element\Elements;
use Leaf\Core\Core\Element\Field\FieldFactory;
use Symfony\Component\Validator\Validation;
use Tests\Doubles\ConfigurationProviderStub;
use Tests\Doubles\ThrowingConfigurationProvider;

final class UpdateElementHandlerMother
{
    public static function basic(Elements $elements): UpdateElementHandler
    {
        return new UpdateElementHandler(
            new ConfigurationProviderStub(),
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            $elements,
            new InMemoryEventDispatcher()
        );
    }

    public static function withThrowingConfigurationProvider(Elements $elements): UpdateElementHandler
    {
        return new UpdateElementHandler(
            new ThrowingConfigurationProvider(),
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            $elements,
            new InMemoryEventDispatcher()
        );
    }

    public static function withCustomEventDispatcher(Elements $elements, EventDispatcher $dispatcher): UpdateElementHandler
    {
        return new UpdateElementHandler(
            new ConfigurationProviderStub(),
            new FieldsDtoValidator(Validation::createValidator()),
            new FieldFactory(),
            $elements,
            $dispatcher
        );
    }
}
<?php declare(strict_types=1);

namespace Leaf\Core\Application\CreateElement;

use Leaf\Core\Application\Common\ConfigurationProvider;
use Leaf\Core\Application\Common\Event\EventDispatcher;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Application\Common\FieldDTO;
use Leaf\Core\Application\Common\FieldsDtoValidator;
use Leaf\Core\Application\Common\Result\Result;
use Leaf\Core\Application\Common\Result\Success;
use Leaf\Core\Application\Common\Result\ValidationFailed;
use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Elements;
use Leaf\Core\Core\Element\Field\FieldFactory;

final readonly class CreateElementHandler
{
    public function __construct(
        private ConfigurationProvider $configurationProvider,
        private FieldsDtoValidator    $validator,
        private FieldFactory          $factory,
        private Elements              $elements,
        private EventDispatcher       $dispatcher
    )
    {
    }

    /** @throws ConfigurationNotFoundException */
    public function __invoke(CreateElementCommand $command): Result
    {
        $configuration = $this->configurationProvider->find($command->name);

        $violations = $this->validator->validate($configuration, ...$command->fields);

        if (0 !== $violations->count()) {
            return new ValidationFailed($violations);
        }

        $fields = array_map(
        fn(FieldDTO $fieldDTO) => $this->factory->create(
            $configuration->getTypeFor($fieldDTO->name),
            $fieldDTO->name,
            $fieldDTO->value
        ), $command->fields);

        $element = new Element($command->uuid, $command->name, ...$fields);

        $this->elements->save($element);

        $this->dispatcher->dispatch(new ElementCreated($element));

        return new Success();
    }
}

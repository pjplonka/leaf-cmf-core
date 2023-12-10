<?php declare(strict_types=1);

namespace Leaf\Core\Application\UpdateElement;

use Leaf\Core\Application\Common\ConfigurationProvider;
use Leaf\Core\Application\Common\Event\EventDispatcher;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Application\Common\FieldsDtoValidator;
use Leaf\Core\Application\Common\Result\ElementNotFound;
use Leaf\Core\Application\Common\Result\Result;
use Leaf\Core\Application\Common\Result\Success;
use Leaf\Core\Application\Common\Result\ValidationFailed;
use Leaf\Core\Core\Element\Elements;
use Leaf\Core\Core\Element\Field\FieldFactory;
use Leaf\Core\Core\Exception\FieldAlreadyExistException;
use Leaf\Core\Core\Exception\FieldNotFoundException;

final readonly class UpdateElementHandler
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

    /** @throws ConfigurationNotFoundException|FieldAlreadyExistException|FieldNotFoundException */
    public function __invoke(UpdateElementCommand $command): Result
    {
        $element = $this->elements->find($command->uuid);

        if (!$element) {
            return new ElementNotFound();
        }

        $configuration = $this->configurationProvider->find($element->group);

        $violations = $this->validator->validate($configuration, ...$command->fields);

        if (0 !== $violations->count()) {
            return new ValidationFailed($violations);
        }

        foreach ($command->fields as $fieldDTO) {
            $field = $this->factory->create(
                $configuration->getTypeFor($fieldDTO->name),
                $fieldDTO->name,
                $fieldDTO->value
            );

            $element->addWithReplacement($field);
        }

        $this->elements->save($element);

        $this->dispatcher->dispatch(new ElementUpdated($element));

        return new Success();
    }
}

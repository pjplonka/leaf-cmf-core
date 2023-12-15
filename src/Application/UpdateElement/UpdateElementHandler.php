<?php declare(strict_types=1);

namespace Leaf\Core\Application\UpdateElement;

use Leaf\Core\Application\Common\Command\CommandHandler;
use Leaf\Core\Application\Common\ConfigurationProvider;
use Leaf\Core\Application\Common\Event\EventStream;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Application\Common\Exception\ElementNotFoundException;
use Leaf\Core\Application\Common\Exception\ValidationFailedException;
use Leaf\Core\Application\Common\FieldsDtoValidator;
use Leaf\Core\Core\Element\Elements;
use Leaf\Core\Core\Element\Field\FieldFactory;
use Leaf\Core\Core\Exception\FieldAlreadyExistException;
use Leaf\Core\Core\Exception\FieldNotFoundException;

final readonly class UpdateElementHandler implements CommandHandler
{
    public function __construct(
        private ConfigurationProvider $configurationProvider,
        private FieldsDtoValidator    $validator,
        private FieldFactory          $factory,
        private Elements              $elements,
        private EventStream           $stream
    )
    {
    }

    public function handles(): string
    {
        return UpdateElementCommand::class;
    }

    /** @throws ConfigurationNotFoundException|FieldAlreadyExistException|FieldNotFoundException|ElementNotFoundException|ValidationFailedException */
    public function __invoke(UpdateElementCommand $command): void
    {
        $element = $this->elements->find($command->uuid);

        if (!$element) {
            throw new ElementNotFoundException();
        }

        $configuration = $this->configurationProvider->find($element->group);

        $this->validator->validate($configuration, ...$command->fields);

        foreach ($command->fields as $fieldDTO) {
            $field = $this->factory->create(
                $configuration->getTypeFor($fieldDTO->name),
                $fieldDTO->name,
                $fieldDTO->value
            );

            $element->addWithReplacement($field);
        }

        $this->elements->save($element);

        $this->stream->record(new ElementUpdated($element));
    }
}

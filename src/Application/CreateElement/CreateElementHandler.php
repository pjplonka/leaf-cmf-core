<?php declare(strict_types=1);

namespace Leaf\Core\Application\CreateElement;

use Leaf\Core\Application\Common\Command\CommandHandler;
use Leaf\Core\Application\Common\ConfigurationProvider;
use Leaf\Core\Application\Common\Event\EventStream;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Application\Common\Exception\ValidationFailedException;
use Leaf\Core\Application\Common\FieldDTO;
use Leaf\Core\Application\Common\FieldsDtoValidator;
use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Elements;
use Leaf\Core\Core\Element\Field\FieldFactory;

final readonly class CreateElementHandler implements CommandHandler
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
        return CreateElementCommand::class;
    }

    /** @throws ConfigurationNotFoundException|ValidationFailedException */
    public function __invoke(CreateElementCommand $command): void
    {
        $configuration = $this->configurationProvider->find($command->name);

        $this->validator->validate($configuration, ...$command->fields);

        $fields = array_map(
            fn(FieldDTO $fieldDTO) => $this->factory->create(
                $configuration->getTypeFor($fieldDTO->name),
                $fieldDTO->name,
                $fieldDTO->value
            ), $command->fields);

        $element = new Element($command->uuid, $command->name, ...$fields);

        $this->elements->save($element);

        $this->stream->record(new ElementCreated($element));
    }
}

<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element;

use Leaf\Core\Core\Element\Field\Field;
use Leaf\Core\Core\Exception\FieldAlreadyExistException;
use Leaf\Core\Core\Exception\FieldNotFoundException;
use Symfony\Component\Uid\Uuid;

final class Element
{
    /** @var Field[] */
    private array $fields;

    public function __construct(public readonly Uuid $uuid, public readonly string $group, Field ...$fields)
    {
        $this->fields = $fields;
    }

    /** @return Field[] */
    public function getFields(): array
    {
        return array_map(fn (Field $field) => clone $field, $this->fields);
    }

    /** @throws FieldNotFoundException|FieldAlreadyExistException */
    public function addWithReplacement(Field $field): void
    {
        if ($this->fieldExist($field->getName())) {
            $this->removeField($field->getName());
        }

        $this->addField($field);
    }

    /** @throws FieldAlreadyExistException */
    private function addField(Field $field): void
    {
        if ($this->fieldExist($field->getName())) {
            throw FieldAlreadyExistException::create($field->getName());
        }

        $this->fields[] = $field;
    }

    private function fieldExist(string $name): bool
    {
        foreach ($this->fields as $field) {
            if ($field->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /** @throws FieldNotFoundException */
    private function removeField(string $name): void
    {
        if (!$this->fieldExist($name)) {
            throw FieldNotFoundException::create($name);
        }

        foreach ($this->fields as $key => $existingField) {
            if ($existingField->getName() === $name) {
                unset($this->fields[$key]);
                $this->fields = array_values($this->fields);
            }
        }
    }
}

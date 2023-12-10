<?php declare(strict_types=1);

namespace Leaf\Core\Core\Configuration;

use UnexpectedValueException;

final readonly class Configuration
{
    /** @var Field[] */
    private array $fields;

    public function __construct(public string $name, Field ...$fields)
    {
        $this->fields = $fields;
    }

    public function getConstraints(): Constraints
    {
        $constraints = new Constraints;
        foreach ($this->fields as $field) {
            $constraints->add($field->name, ...$field->constraints);
        }

        return $constraints;
    }

    public function getTypeFor(string $name): string
    {
        foreach ($this->fields as $field) {
            if ($field->name === $name) {
                return $field->type;
            }
        }

        throw new UnexpectedValueException(sprintf('Configuration does not contain field with name `%s`', $name));
    }
}
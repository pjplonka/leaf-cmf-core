<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element\Field;

readonly class StringField extends Field
{
    public function __construct(private string $name, private string $value)
    {
    }

    public static function getType(): string
    {
        return 'string';
    }

    public static function getConstraints(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
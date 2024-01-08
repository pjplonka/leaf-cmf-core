<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element\Field;

use DateTimeImmutable;

readonly class DateField extends Field
{
    public function __construct(private string $name, private DateTimeImmutable $value)
    {
    }

    public static function getType(): string
    {
        return 'date';
    }

    public static function getConstraints(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }
}

<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element\Field;

use Carbon\CarbonImmutable;

readonly class DateTimeField extends Field
{
    public function __construct(private string $name, private CarbonImmutable $value)
    {
    }

    public static function getType(): string
    {
        return 'datetime';
    }

    public static function getConstraints(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): CarbonImmutable
    {
        return $this->value;
    }
}

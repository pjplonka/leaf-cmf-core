<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element\Field;

use Carbon\CarbonImmutable;
use UnexpectedValueException;

class FieldFactory
{
    public function create(string $type, string $name, mixed $value): Field
    {
        return match ($type) {
            StringField::getType() => new StringField($name, $value),
            DateField::getType() => new DateField($name, new CarbonImmutable($value)),
            DateTimeField::getType() => new DateTimeField($name, new CarbonImmutable($value)),
            default => throw new UnexpectedValueException(sprintf('Can not create field by type `%s`.', $type))
        };
    }
}
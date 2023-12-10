<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element\Field;

use Symfony\Component\Validator\Constraint;

readonly abstract class Field
{
    /** @return Constraint[] */
    abstract public static function getConstraints(): array;
    abstract public static function getType(): string;
    abstract public function getName(): string;
    abstract public function getValue(): mixed;
}

<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element\Field;

use Symfony\Component\Validator\Constraints as Assert;

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
        return [
            new Assert\Type('string')
        ];
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
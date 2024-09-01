<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element\Field;

use Symfony\Component\Uid\Uuid;

readonly class ParentField extends Field
{
    public function __construct(private string $name, private Uuid $value)
    {
    }

    public static function getType(): string
    {
        return 'parent';
    }

    public static function getConstraints(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): Uuid
    {
        return $this->value;
    }
}

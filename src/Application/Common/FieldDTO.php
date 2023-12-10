<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common;

final readonly class FieldDTO
{
    public function __construct(public string $name, public mixed $value)
    {
    }
}

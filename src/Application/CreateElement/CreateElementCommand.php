<?php declare(strict_types=1);

namespace Leaf\Core\Application\CreateElement;

use Leaf\Core\Application\Common\FieldDTO;
use Ramsey\Uuid\UuidInterface;

final readonly class CreateElementCommand
{
    /** @var FieldDTO[]  */
    public array $fields;

    public function __construct(public string $name, public UuidInterface $uuid, FieldDTO ...$fields)
    {
        $this->fields = $fields;
    }
}

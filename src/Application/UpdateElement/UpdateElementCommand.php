<?php declare(strict_types=1);

namespace Leaf\Core\Application\UpdateElement;

use Leaf\Core\Application\Common\FieldDTO;
use Ramsey\Uuid\UuidInterface;

final readonly class UpdateElementCommand
{
    /** @var FieldDTO[]  */
    public array $fields;

    public function __construct(public UuidInterface $uuid, FieldDTO ...$fields)
    {
        $this->fields = $fields;
    }
}

<?php declare(strict_types=1);

namespace Leaf\Core\Application\CreateElement;

use Leaf\Core\Application\Common\Command\Command;
use Leaf\Core\Application\Common\FieldDTO;
use Symfony\Component\Uid\Uuid;

final readonly class CreateElementCommand implements Command
{
    /** @var FieldDTO[]  */
    public array $fields;

    public function __construct(public string $name, public Uuid $uuid, FieldDTO ...$fields)
    {
        $this->fields = $fields;
    }
}

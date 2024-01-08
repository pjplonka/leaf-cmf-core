<?php declare(strict_types=1);

namespace Leaf\Core\Application\UpdateElement;

use Leaf\Core\Application\Common\Command\Command;
use Leaf\Core\Application\Common\FieldDTO;
use Symfony\Component\Uid\Uuid;

final readonly class UpdateElementCommand implements Command
{
    /** @var FieldDTO[]  */
    public array $fields;

    public function __construct(public Uuid $uuid, FieldDTO ...$fields)
    {
        $this->fields = $fields;
    }
}

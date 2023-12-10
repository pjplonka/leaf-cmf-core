<?php declare(strict_types=1);

namespace Leaf\Core\Core\Configuration;

use Symfony\Component\Validator\Constraint;

final readonly class Field
{
    /** @var array Constraint[] */
    public array $constraints;

    public function __construct(public string $name, public string $type, Constraint ...$constraints)
    {
        $this->constraints = $constraints;
    }
}
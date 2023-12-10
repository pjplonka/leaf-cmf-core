<?php declare(strict_types=1);

namespace Leaf\Core\Core\Configuration;

use Symfony\Component\Validator\Constraint;

/**
 * Contains constraints grouped by field name
 * Example:
 * [
 *   "color" => [
 *       new NotBlank(),
 *       new Type('string')
 *   ],
 *   "created_at" => [
 *      new NotBlank(),
 *    ]
 * ]
 */
final class Constraints
{
    private array $constraints;

    public function add(string $name, Constraint ...$constraint): void
    {
        $this->constraints[$name] = $constraint;
    }

    public function get(): array
    {
        return $this->constraints;
    }
}
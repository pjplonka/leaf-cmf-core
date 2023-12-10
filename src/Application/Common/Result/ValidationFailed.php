<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Result;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final readonly class ValidationFailed extends Result
{
    public function __construct(public ConstraintViolationListInterface $violations)
    {
    }

    /**
     * Returns violations in simple array form:
     * [
     *   "name" => [
     *     "This field is missing.",
     *   ],
     *   "color" => [
     *     "The value you selected is not a valid choice.",
     *    ],
     * ]
     */
    public function simplify(): array
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[str_replace(['[', ']'], '', $violation->getPropertyPath())][] = $violation->getMessage();
        }

        return $errors;
    }
}
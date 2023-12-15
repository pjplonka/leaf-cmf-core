<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common;

use Leaf\Core\Application\Common\Exception\ValidationFailedException;
use Leaf\Core\Core\Configuration\Configuration;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly final class FieldsDtoValidator
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /** @throws ValidationFailedException */
    public function validate(Configuration $configuration, FieldDTO ...$fields): void
    {
        $violations = $this->validator->validate(
            $this->transformCommandFieldsToArrayForValidation(...$fields),
            new Collection(['fields' => $configuration->getConstraints()->get()])
        );

        if (0 !== $violations->count()) {
            throw new ValidationFailedException($violations);
        }
    }

    private function transformCommandFieldsToArrayForValidation(FieldDTO ...$fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            $data[$field->name] = $field->value;
        }

        return $data;
    }
}
<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common;

use Leaf\Core\Core\Configuration\Configuration;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly final class FieldsDtoValidator
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function validate(Configuration $configuration, FieldDTO ...$fields): ConstraintViolationListInterface
    {
        return $this->validator->validate(
            $this->transformCommandFieldsToArrayForValidation(...$fields),
            new Collection(['fields' => $configuration->getConstraints()->get()])
        );
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
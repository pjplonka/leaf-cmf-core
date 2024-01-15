<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Serializer;

use DateTimeInterface;
use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Field\DateTimeField;

/**
 * Serialize element to array where fields are key => value pairs:
 * [
 *   'uuid' => '42085c1e-4d0b-4050-9c9a-041e5d05218d',
 *   'group' => 'products',
 *   'fields' => [
 *     'name' => 'Box',
 *     'delivered_at' => '2023-01-04'
 *   ],
 * ]
 */
class SimpleSerializer implements ElementSerializer
{
    public function serialize(Element $element): array
    {
        $fields = [];
        foreach ($element->getFields() as $field) {
            if ($field instanceof DateTimeField) {
                $fields[$field->getName()] = $field->getValue()->format(DateTimeInterface::ATOM);

                continue;
            }

            $fields[$field->getName()] = $field->getValue();
        }

        return [
            'uuid' => $element->uuid->toRfc4122(),
            'group' => $element->group,
            'fields' => $fields
        ];
    }
}
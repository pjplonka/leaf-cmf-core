<?php declare(strict_types=1);

namespace Leaf\Core\Infrastructure\Domain;

use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Elements;
use Ramsey\Uuid\UuidInterface;

final class InMemoryElements implements Elements
{
    /** @var Element[] */
    public array $elements = [];

    public function __construct(Element ...$elements)
    {
        foreach ($elements as $element) {
            $this->save($element);
        }
    }

    public function save(Element $element): void
    {
        $this->elements[$element->uuid->toString()] = $element;
    }

    public function find(UuidInterface $uuid): ?Element
    {
        return $this->elements[$uuid->toString()] ?? null;
    }
}
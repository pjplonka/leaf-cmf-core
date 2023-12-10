<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element;

use Ramsey\Uuid\UuidInterface;

interface Elements
{
    public function save(Element $element): void;

    public function find(UuidInterface $uuid): ?Element;
}
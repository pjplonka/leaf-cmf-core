<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Serializer;

use Leaf\Core\Core\Element\Element;

interface ElementSerializer
{
    public function serialize(Element $element): array;
}
<?php declare(strict_types=1);

namespace Leaf\Core\Application\UpdateElement;

use Leaf\Core\Application\Common\Event\Event;
use Leaf\Core\Core\Element\Element;

final readonly class ElementUpdated implements Event
{
    public function __construct(public Element $element)
    {
    }
}
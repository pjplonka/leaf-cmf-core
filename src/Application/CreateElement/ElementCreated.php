<?php declare(strict_types=1);

namespace Leaf\Core\Application\CreateElement;

use Leaf\Core\Application\Common\Event\Event;
use Leaf\Core\Core\Element\Element;

final readonly class ElementCreated implements Event
{
    public function __construct(public Element $element)
    {
    }
}
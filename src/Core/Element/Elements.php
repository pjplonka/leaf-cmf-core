<?php declare(strict_types=1);

namespace Leaf\Core\Core\Element;

use Symfony\Component\Uid\Uuid;

interface Elements
{
    public function save(Element $element): void;

    public function find(Uuid $uuid): ?Element;
}
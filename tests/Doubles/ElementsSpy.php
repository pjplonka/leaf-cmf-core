<?php declare(strict_types=1);

namespace Tests\Doubles;

use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Elements;
use Symfony\Component\Uid\Uuid;

final class ElementsSpy implements Elements
{
    public ?Element $storedElement = null;
    public ?Element $foundElement = null;
    public int $findCounter = 0;
    public int $saveCounter = 0;

    public static function create(): self
    {
        return new self();
    }

    public static function createWithFoundElement(Element $foundElement): self
    {
        $self = new self();
        $self->foundElement = $foundElement;

        return $self;
    }

    public function save(Element $element): void
    {
        $this->saveCounter++;

        $this->storedElement = $element;
    }

    public function find(Uuid $uuid): ?Element
    {
        $this->findCounter++;

        return $this->foundElement;
    }
}
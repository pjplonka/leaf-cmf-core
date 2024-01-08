<?php declare(strict_types=1);

namespace Tests\Core;

use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Field\StringField;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ElementTest extends TestCase
{
    /** @test */
    public function fields_are_encapsulated(): void
    {
        $element = new Element(Uuid::v4(), 'products', new StringField('name', 'Snowboard'));

        $this->assertNotSame(spl_object_id($element->getFields()[0]), spl_object_id($element->getFields()[0]));
    }

    /** @test */
    public function field_can_be_added(): void
    {
        $element = new Element(Uuid::v4(), 'products', new StringField('name', 'Snowboard'));

        $element->addWithReplacement(new StringField('color', 'red'));

        $this->assertCount(2, $element->getFields());
        $this->assertSame('name', $element->getFields()[0]->getName());
        $this->assertSame('Snowboard', $element->getFields()[0]->getValue());
        $this->assertSame('color', $element->getFields()[1]->getName());
        $this->assertSame('red', $element->getFields()[1]->getValue());
    }

    /** @test */
    public function field_will_be_replaced_if_exist_when_adding(): void
    {
        $element = new Element(Uuid::v4(), 'products', new StringField('name', 'Snowboard'));

        $element->addWithReplacement(new StringField('name', 'Shoes'));

        $this->assertCount(1, $element->getFields());
        $this->assertSame('name', $element->getFields()[0]->getName());
        $this->assertSame('Shoes', $element->getFields()[0]->getValue());
    }
}
<?php declare(strict_types=1);

namespace Tests\Application\Handler;

use DateTimeImmutable;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Application\Common\Exception\ElementNotFoundException;
use Leaf\Core\Application\Common\Exception\ValidationFailedException;
use Leaf\Core\Application\Common\FieldDTO;
use Leaf\Core\Application\UpdateElement\ElementUpdated;
use Leaf\Core\Application\UpdateElement\UpdateElementCommand;
use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Field\DateField;
use Leaf\Core\Core\Element\Field\ParentField;
use Leaf\Core\Core\Element\Field\StringField;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Tests\Mother\ContainerMother;

final class UpdateElementHandlerTest extends TestCase
{
    /** @test */
    public function element_can_not_be_found(): void
    {
        $container = ContainerMother::basic();

        $command = new UpdateElementCommand(Uuid::v4(), new FieldDTO('color', 'red'));

        $this->expectException(ElementNotFoundException::class);

        $container->bus->handle($command);
    }

    /** @test */
    public function configuration_can_not_be_found(): void
    {
        $container = ContainerMother::withThrowingConfigurationProvider();

        $uuid = Uuid::v4();

        $container->elements->save(new Element($uuid, 'products'));

        $command = new UpdateElementCommand($uuid, new FieldDTO('color', 'red'));

        $this->expectException(ConfigurationNotFoundException::class);

        $container->bus->handle($command);
    }

    /** @test */
    public function validation_failed(): void
    {
        $container = ContainerMother::basic();

        $uuid = Uuid::v4();

        $container->elements->save(new Element($uuid, 'products'));

        $command = new UpdateElementCommand($uuid, new FieldDTO('color', [1, 2]));

        $this->expectException(ValidationFailedException::class);

        $container->bus->handle($command);
    }

    /** @test */
    public function element_fields_can_be_updated(): void
    {
        $container = ContainerMother::basic();

        $uuid = Uuid::v4();

        $container->elements->save(
            new Element(
                $uuid,
                'products',
                new StringField('name', 'Annie'),
                new ParentField('categories', Uuid::fromString('ee953595-dc72-456f-bc7f-7ea275a01537')))
        );

        $command = new UpdateElementCommand(
            $uuid,
            new FieldDTO('name', 'John'),
            new FieldDTO('color', 'red'),
            new FieldDTO('created_at', '10.10.2020'),
            new FieldDTO('categories', '9b65c74b-54ec-4baa-86ac-a4a8c7f7426f'),
        );

        $container->bus->handle($command);

        $elements = $container->elements;

        // Elements
        $element = reset($elements->elements);
        $this->assertCount(1, $elements->elements);
        $this->assertSame($uuid, $element->uuid);
        $this->assertSame('products', $element->group);
        $this->assertCount(4, $element->getFields());

        // Fields - Check if fields were created properly
        $fields = $element->getFields();
        $this->assertInstanceOf(StringField::class, $fields[0]);
        $this->assertSame('name', $fields[0]->getName());
        $this->assertSame('John', $fields[0]->getValue());
        $this->assertInstanceOf(StringField::class, $fields[1]);
        $this->assertSame('color', $fields[1]->getName());
        $this->assertSame('red', $fields[1]->getValue());
        $this->assertInstanceOf(DateField::class, $fields[2]);
        $this->assertSame('created_at', $fields[2]->getName());
        $this->assertInstanceOf(DateTimeImmutable::class, $fields[2]->getValue());
        $this->assertSame('10.10.2020', $fields[2]->getValue()->format('d.m.Y'));
        $this->assertSame('categories', $fields[3]->getName());
        $this->assertInstanceOf(Uuid::class, $fields[3]->getValue());
        $this->assertSame('9b65c74b-54ec-4baa-86ac-a4a8c7f7426f', (string)$fields[3]->getValue());
    }

    /** @test */
    public function event_is_dispatched(): void
    {
        $container = ContainerMother::basic();

        $uuid = Uuid::v4();

        $container->elements->save(new Element($uuid, 'products'));

        $command = new UpdateElementCommand(
            $uuid,
            new FieldDTO('name', 'John'),
            new FieldDTO('color', 'red'),
            new FieldDTO('created_at', '10.10.2020')
        );

        $container->bus->handle($command);

        $dispatcher = $container->dispatcher;

        $this->assertCount(1, $dispatcher->events);
        $this->assertInstanceOf(ElementUpdated::class, $dispatcher->events[0]);
        $this->assertSame($uuid, $dispatcher->events[0]->element->uuid);
    }
}

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

        $command = new UpdateElementCommand($uuid, new FieldDTO('color', 'red'));

        $this->expectException(ValidationFailedException::class);

        $container->bus->handle($command);
    }

    /** @test */
    public function updated_element_is_stored(): void
    {
        $container = ContainerMother::basic();

        $uuid = Uuid::v4();

        $container->elements->save(new Element($uuid, 'products', new StringField('name', 'Annie')));

        $command = new UpdateElementCommand(
            $uuid,
            new FieldDTO('name', 'John'),
            new FieldDTO('color', 'red'),
            new FieldDTO('created_at', '10.10.2020')
        );

        $container->bus->handle($command);

        $elements = $container->elements;

        // Elements
        $element = reset($elements->elements);
        $this->assertCount(1, $elements->elements);
        $this->assertSame($uuid, $element->uuid);
        $this->assertSame('products', $element->group);
        $this->assertCount(3, $element->getFields());

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

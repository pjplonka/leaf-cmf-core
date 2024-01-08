<?php declare(strict_types=1);

namespace Tests\Application;

use DateTimeImmutable;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Application\Common\Exception\ValidationFailedException;
use Leaf\Core\Application\Common\FieldDTO;
use Leaf\Core\Application\CreateElement\CreateElementCommand;
use Leaf\Core\Application\CreateElement\ElementCreated;
use Leaf\Core\Core\Element\Field\DateField;
use Leaf\Core\Core\Element\Field\StringField;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Tests\Mother\ContainerMother;

final class CreateElementHandlerTest extends TestCase
{
    /** @test */
    public function configuration_can_not_be_found(): void
    {
        $container = ContainerMother::withThrowingConfigurationProvider();

        $command = new CreateElementCommand('products', Uuid::v4(), new FieldDTO('color', 'red'));

        $this->expectException(ConfigurationNotFoundException::class);

        $container->bus->handle($command);
    }

    /** @test */
    public function validation_failed(): void
    {
        $container = ContainerMother::basic();

        $command = new CreateElementCommand('products', Uuid::v4(), new FieldDTO('color', 'red'));

        $this->expectException(ValidationFailedException::class);

        $container->bus->handle($command);
    }

    /** @test */
    public function element_is_stored(): void
    {
        $container = ContainerMother::basic();

        $command = new CreateElementCommand(
            $groupName = 'products',
            $uuid = Uuid::v4(),
            new FieldDTO('name', 'John'),
            new FieldDTO('color', 'red'),
            new FieldDTO('created_at', '10.10.2020')
        );

        $container->bus->handle($command);

        $elements = $container->elements;
        $this->assertCount(1, $elements->elements);

        // Elements
        $element = $elements->find($uuid);
        $this->assertSame($uuid, $element->uuid);
        $this->assertSame($groupName, $element->group);
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

        $groupName = 'products';
        $command = new CreateElementCommand($groupName,
            $uuid = Uuid::v4(),
            new FieldDTO('name', 'john'),
            new FieldDTO('color', 'red'),
            new FieldDTO('created_at', '10.10.2020')
        );

        $container->bus->handle($command);
        
        $dispatcher = $container->dispatcher;
        
        $this->assertCount(1, $dispatcher->events);
        $this->assertInstanceOf(ElementCreated::class, $dispatcher->events[0]);
        $this->assertSame($uuid, $dispatcher->events[0]->element->uuid);
    }
}

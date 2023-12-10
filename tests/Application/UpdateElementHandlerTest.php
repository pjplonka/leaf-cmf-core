<?php declare(strict_types=1);

namespace Tests\Application;

use Carbon\CarbonImmutable;
use Leaf\Core\Application\Common\Event\InMemoryEventDispatcher;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Application\Common\FieldDTO;
use Leaf\Core\Application\Common\Result\ElementNotFound;
use Leaf\Core\Application\Common\Result\Success;
use Leaf\Core\Application\Common\Result\ValidationFailed;
use Leaf\Core\Application\UpdateElement\ElementUpdated;
use Leaf\Core\Application\UpdateElement\UpdateElementCommand;
use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Field\DateField;
use Leaf\Core\Core\Element\Field\StringField;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\Doubles\ElementsSpy;
use Tests\Mother\UpdateElementHandlerMother;

final class UpdateElementHandlerTest extends TestCase
{
    /** @test */
    public function element_can_not_be_found(): void
    {
        $command = new UpdateElementCommand(Uuid::uuid4(), new FieldDTO('color', 'red'));

        $handler = UpdateElementHandlerMother::basic(ElementsSpy::create());

        $result = $handler($command);

        $this->assertInstanceOf(ElementNotFound::class, $result);
    }

    /** @test */
    public function configuration_can_not_be_found(): void
    {
        $uuid = Uuid::uuid4();
        $elements = ElementsSpy::createWithFoundElement(new Element($uuid, 'products'));

        $command = new UpdateElementCommand($uuid, new FieldDTO('color', 'red'));

        $handler = UpdateElementHandlerMother::withThrowingConfigurationProvider($elements);

        $this->expectException(ConfigurationNotFoundException::class);

        $handler($command);
    }

    /** @test */
    public function validation_failed2(): void
    {
        $uuid = Uuid::uuid4();
        $elements = ElementsSpy::createWithFoundElement(new Element($uuid, 'products'));

        $command = new UpdateElementCommand($uuid, new FieldDTO('color', 'red'));

        $handler = UpdateElementHandlerMother::basic($elements);

        $result = $handler($command);

        $this->assertInstanceOf(ValidationFailed::class, $result);
        $this->assertSame([
            'name' => ['This field is missing.'],
            'created_at' => ['This field is missing.'],
        ], $result->simplify());

    }

    /** @test */
    public function updated_element_is_stored(): void
    {
        $uuid = Uuid::uuid4();
        $elements = ElementsSpy::createWithFoundElement(
            new Element($uuid, 'products', new StringField('name', 'Annie'))
        );

        $command = new UpdateElementCommand(
            $uuid,
            new FieldDTO('name', 'John'),
            new FieldDTO('color', 'red'),
            new FieldDTO('created_at', '10.10.2020')
        );

        $handler = UpdateElementHandlerMother::basic($elements);

        $result = $handler($command);

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame(1, $elements->saveCounter);
        $this->assertSame(1, $elements->findCounter);

        // Elements
        $element = $elements->storedElement;
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
        $this->assertInstanceOf(CarbonImmutable::class, $fields[2]->getValue());
        $this->assertSame('10.10.2020', $fields[2]->getValue()->format('d.m.Y'));
    }

    /** @test */
    public function event_is_dispatched(): void
    {
        $uuid = Uuid::uuid4();
        $elements = ElementsSpy::createWithFoundElement(new Element($uuid, 'products'));

        $command = new UpdateElementCommand(
            $uuid,
            new FieldDTO('name', 'John'),
            new FieldDTO('color', 'red'),
            new FieldDTO('created_at', '10.10.2020')
        );

        $dispatcher = new InMemoryEventDispatcher();
        $handler = UpdateElementHandlerMother::withCustomEventDispatcher($elements, $dispatcher);

        $result = $handler($command);

        $this->assertInstanceOf(Success::class, $result);
        $this->assertCount(1, $dispatcher->events);
        $this->assertInstanceOf(ElementUpdated::class, $dispatcher->events[0]);
        $this->assertSame($uuid, $dispatcher->events[0]->element->uuid);
    }
}

<?php declare(strict_types=1);

namespace Tests\Application\Serializer;

use DateTimeImmutable;
use Leaf\Core\Application\Common\Serializer\SimpleSerializer;
use Leaf\Core\Core\Element\Element;
use Leaf\Core\Core\Element\Field\DateTimeField;
use Leaf\Core\Core\Element\Field\StringField;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SimpleSerializerTest extends TestCase
{
    /** @test */
    public function serialize(): void
    {
        $element = new Element(
            $uuid = Uuid::v4(),
            'products',
            new StringField('name', 'Box'),
            new DateTimeField('delivered_at', new DateTimeImmutable('2023-01-02'))
        );

        $serializer = new SimpleSerializer();

        $this->assertSame([
            'uuid' => $uuid->toRfc4122(),
            'group' => 'products',
            'fields' => [
                'name' => 'Box',
                'delivered_at' => '2023-01-02T00:00:00+00:00',
            ]
        ], $serializer->serialize($element));
    }
}
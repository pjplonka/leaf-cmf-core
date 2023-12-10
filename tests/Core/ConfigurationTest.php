<?php declare(strict_types=1);

namespace Tests\Core;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Tests\Mother\ConfigurationMother;
use UnexpectedValueException;

final class ConfigurationTest extends TestCase
{
    /** @test */
    public function has_a_name(): void
    {
        $this->assertSame('products', ConfigurationMother::create()->name);
    }

    /** @test */
    public function returns_fields_constraints(): void
    {
        $configuration = ConfigurationMother::create();

        $constraints = $configuration->getConstraints()->get();

        $this->assertCount(2, $constraints);
        $this->assertCount(1, $constraints['name']);
        $this->assertInstanceOf(NotBlank::class, $constraints['name'][0]);
        $this->assertCount(2, $constraints['color']);
        $this->assertInstanceOf(Type::class, $constraints['color'][0]);
        $this->assertInstanceOf(Choice::class, $constraints['color'][1]);
    }

    /** @test */
    public function returns_type_for_existing_name(): void
    {
        $this->assertSame('string', ConfigurationMother::create()->getTypeFor('color'));
    }

    /** @test */
    public function returns_type_for_not_existing_name(): void
    {
        $this->expectException(UnexpectedValueException::class);

        ConfigurationMother::create()->getTypeFor('size');
    }
}

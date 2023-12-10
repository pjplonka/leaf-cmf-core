<?php declare(strict_types=1);

namespace Tests\Mother;

use Leaf\Core\Core\Configuration\Configuration;
use Leaf\Core\Core\Configuration\Field;
use Leaf\Core\Core\Element\Field\StringField;
use Symfony\Component\Validator\Constraints as Assert;

final class ConfigurationMother
{
    public static function create(): Configuration
    {
        return new Configuration(
            'products',
            new Field('name', StringField::getType(), new Assert\NotBlank()),
            new Field('color', StringField::getType(), new Assert\Type('string'), new Assert\Choice(['red', 'blue'])),
        );
    }
}
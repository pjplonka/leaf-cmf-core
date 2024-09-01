<?php declare(strict_types=1);

namespace Tests\Doubles;

use Leaf\Core\Application\Common\ConfigurationProvider as ConfigurationProviderInterface;
use Leaf\Core\Core\Configuration\Configuration;
use Leaf\Core\Core\Configuration\Field;
use Leaf\Core\Core\Element\Field\DateField;
use Leaf\Core\Core\Element\Field\ParentField;
use Leaf\Core\Core\Element\Field\StringField;

final class ConfigurationProviderStub implements ConfigurationProviderInterface
{
    /**
     * Configuration contains all available fields
     */
    public function find(string $identifier): Configuration
    {
        return new Configuration(
            'products',
            ...[
                new Field('name', StringField::getType(), ...StringField::getConstraints()),
                new Field('color', StringField::getType(), ...StringField::getConstraints()),
                new Field('created_at', DateField::getType(), ...DateField::getConstraints()),
                new Field('categories', ParentField::getType(), ...ParentField::getConstraints()),
            ]
        );
    }
}
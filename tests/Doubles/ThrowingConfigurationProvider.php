<?php declare(strict_types=1);

namespace Tests\Doubles;

use Leaf\Core\Application\Common\ConfigurationProvider as ConfigurationProviderInterface;
use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Core\Configuration\Configuration;

final class ThrowingConfigurationProvider implements ConfigurationProviderInterface
{
    public function find(string $identifier): Configuration
    {
        throw ConfigurationNotFoundException::create($identifier);
    }
}
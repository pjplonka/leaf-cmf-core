<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common;

use Leaf\Core\Application\Common\Exception\ConfigurationNotFoundException;
use Leaf\Core\Core\Configuration\Configuration;

interface ConfigurationProvider
{
    /** @throws ConfigurationNotFoundException */
    public function find(string $identifier): Configuration;
}
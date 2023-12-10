<?php declare(strict_types=1);

namespace Leaf\Core\Application\Common\Exception;

use Exception;

final class ConfigurationNotFoundException extends Exception
{
    public static function create(string $name): self
    {
        return new self(sprintf('Configuration with name `%s` not found.', $name));
    }
}